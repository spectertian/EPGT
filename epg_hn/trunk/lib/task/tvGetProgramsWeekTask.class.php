<?php
/**
 *  @todo  : 从欢网导入节目数据，每次导入当天及以后的
 *  @author: lifucang
 *  @modify: superwen 2013-4-7
 *  @remark: --days=1 --channel=GuiZhouTV 只抓取当天数据
 */
class tvGetProgramsWeekTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
          new sfCommandOption('days', null, sfCommandOption::PARAMETER_OPTIONAL, 'days'),
          new sfCommandOption('channel', null, sfCommandOption::PARAMETER_OPTIONAL, 'channel')    
        ));

        $this->namespace        = 'tv';
        $this->name             = 'GetProgramsWeek';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        if(isset($options['channel'])){
            $channels = $mongo->getRepository('SpService')->find(array("query" => array("channel_code" => $options['channel'])));
        }else{
            $channels = $mongo->getRepository('SpService')->find(array("query" => array("channel_code" => array('$exists' =>1))));
        }  
        $program_repository = $mongo->getRepository('program');
        $url = "http://www.epg.huan.tv/json";
        $wiki_num = 0;  //记录wiki导入数
        $program_num = 0; //记录保存program数
        
        if (isset($options['days'])) {
            $days = $options['days'];
        }else{
            $days = 3;
        }
        
        foreach($channels as $channel){
            if(!$channel->getChannelCode()) continue;
            echo $channel->getChannelCode()."\n";
            for($i = 0; $i < $days ; $i ++) {
                $today = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$i,date("Y")));
                $json_post = '{"action":"GetProgramsByChannelGd","developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"device":{"dnum":"123"},"user":{"userid":"123"},"param":{"channel_code":"'.$channel->getChannelCode().'","date":"'.$today.'"}}';
                $getinfo = Common::post_json($url,$json_post);
            
                if($getinfo){
                    $result = json_decode($getinfo,true);
                    if(!$result) continue; 
                    if(isset($result['program'])){
                        $programs = $result['program'];
                    }else {
                        continue;
                    }
                    
                    //先删除当天的节目
                    $program_repository->removeDayPrograms($channel->getChannelCode(), $today);
                    //插入新的节目
                    foreach($programs as $program_arr){
                        $time = date('H:i',strtotime($program_arr['start_time']));
                        $program = new Program();
                        $program->setChannelCode($channel->getChannelCode());
                        $program->setName($program_arr['name']);
                        $program->setStartTime(new DateTime($program_arr['start_time']));
                        $program->setEndTime(new DateTime($program_arr['end_time']));
                        $program->setTime($time);
                        $program->setDate($program_arr['date']);
                        if($program_arr['wiki_id']!=null)
                           $program->setWikiId($program_arr['wiki_id']);
                        if($program_arr['tvsou_id']!=null)    
                           $program->setTvsouId($program_arr['tvsou_id']);
                        $program->setTags($program_arr['tags']);
                        $program->setPublish($program_arr['publish']);
                        if($program_arr['sort']!=null)
                           $program->setSort($program_arr['sort']);                       
                        $program->save();
                        $program_num++;
                        //开始判断wiki表里是否存在该wiki，
                        //如果不存在，从接口导入该wiki
                        if($program_arr['wiki_id']){
                           $wiki = $mongo->getRepository("Wiki")->findOneById(new MongoId($program_arr['wiki_id']));
                           if(!$wiki){
                                $this->importWiki($url,$program_arr['wiki_id'],$options);
                                $wiki_num++;
                           }
                        }
                    }
                }    
            }
        }  
        echo date("Y-m-d H:i:s"),'------',"Program:".$program_num."------Wiki:".$wiki_num; 
        echo "------finished!\r\n"; 
    }
  
    //导入wiki
    private function importWiki($url,$wiki_id,$options)
    {
        //$this->connectMaster($options);
        //$mongo = $this->getMondongo();
        //$url='http://www.5i.test.cedock.net/json';
        $json_post='{"action":"GetWikiInfoGd","developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"device":{"dnum":"123"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wiki_id.'"}}';
        $getinfo = Common::post_json($url,$json_post);
        $result = json_decode($getinfo,true);
        $wikiinfo = $result['wiki'];
        if($wikiinfo){
            $wiki = new WikiGet();
            $wiki->setId(new mongoId($wikiinfo['id']));
            $wiki->setTitle($wikiinfo['title']);
            $wiki->setSlug($wikiinfo['slug']);
            $wiki->setTvsouId($wikiinfo['tvsou_id']);
            $wiki->setModel($wikiinfo['model']);
            $wiki->setContent($content_wiki);
            $wiki->setHtmlCache($wikiinfo['html_cache']);
            $wiki->setCover($wikiinfo['cover']);
            $wiki->setScreenshots($wikiinfo['screens']);
            $wiki->setTags($wikiinfo['tags']);
            $wiki->setLikeNum($wikiinfo['like_num']);
            $wiki->setDislikeNum($wikiinfo['dislike_num']);  
            $wiki->setCommentTags($wikiinfo['comment_tags']);  
                    
            if ($wikiinfo['model'] == 'actor') {
                $wiki->setEnglishName($wikiinfo['info']['english_name']);
                $wiki->setNickname($wikiinfo['info']['nickname']);              
                $wiki->setSex($wikiinfo['info']['sex']);
                $wiki->setBirthday($wikiinfo['info']['birthday']);
                $wiki->setBirthplace($wikiinfo['info']['birthplace']);
                $wiki->setOccupation($wikiinfo['info']['occupation']);
                $wiki->setNationality($wikiinfo['info']['nationality']);
                $wiki->setZodiac($wikiinfo['info']['zodiac']);
                $wiki->setBloodType($wikiinfo['info']['bloodType']);
                $wiki->setDebut($wikiinfo['info']['debut']);
                $wiki->setHeight($wikiinfo['info']['height']);
                $wiki->setWeight($wikiinfo['info']['weight']);
                $wiki->setRegion($wikiinfo['info']['region']);
                
            }elseif ($wikiinfo['model'] == 'film') {
                $wiki->setAlias($wikiinfo['info']['alias']);
                $wiki->setDirector($wikiinfo['info']['director']);
                $wiki->setStarring($wikiinfo['info']['starring']);
                $wiki->setReleased($wikiinfo['info']['released']);
                $wiki->setLanguage($wikiinfo['info']['language']);
                $wiki->setCountry($wikiinfo['info']['country']);
                $wiki->setWriter($wikiinfo['info']['writer']);
                $wiki->setDistributor($wikiinfo['info']['distributor']);
                $wiki->setRuntime($wikiinfo['info']['runtime']);
                $wiki->setProduced($wikiinfo['info']['produced']);
                
            }elseif ($wikiinfo['model'] == 'teleplay') {
                $wiki->setAlias($wikiinfo['info']['alias']);
                $wiki->setDirector($wikiinfo['info']['director']);
                $wiki->setStarring($wikiinfo['info']['starring']);
                $wiki->setReleased($wikiinfo['info']['released']);
                $wiki->setLanguage($wikiinfo['info']['language']);
                $wiki->setCountry($wikiinfo['info']['country']);
                $wiki->setWriter($wikiinfo['info']['writer']);
                $wiki->setDistributor($wikiinfo['info']['distributor']);
                $wiki->setRuntime($wikiinfo['info']['runtime']);
                $wiki->setProduced($wikiinfo['info']['produced']);
                $wiki->setEpisodes($wikiinfo['info']['episodes']);
                
            }elseif ($wikiinfo['model'] == 'television') {
                $wiki->setChannel($wikiinfo['info']['channel']);
                $wiki->setPlayTime($wikiinfo['info']['play_time']);
                $wiki->setHost($wikiinfo['info']['host']);
                $wiki->setGuests($wikiinfo['info']['guest']);
                $wiki->setProducer($wikiinfo['info']['producer']);
                $wiki->setAlias($wikiinfo['info']['alias']);
                $wiki->setRuntime($wikiinfo['info']['runtime']);
                $wiki->setCountry($wikiinfo['info']['country']);
                $wiki->setLanguage($wikiinfo['info']['language']); 
                
            }
            $wiki->setVerify($verify);
            $wiki->save();
            //导入图片
            if($wikiinfo['cover'])
                $this->uploadFileFromHuan($wikiinfo['cover']);
            $screens = $wikiinfo['screens'];   
            foreach($screens as $screen){
                $this->uploadFileFromHuan($screen);
            }
            //继续写入wiki_meta信息
            $json='{"action":"GetWikiMetasGd","developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"device":{"dnum":"123"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wiki_id.'"}}';
            $info = Common::post_json($url,$json);
            $resulta = json_decode($info,true);
            if($resulta){
                $metas=$resulta['wikimetas'];
                foreach($metas as $meta){
                    $wikimeta=new WikiMeta();
                    $wikimeta->setWikiId($wikiinfo['id']);
                    $wikimeta->setTitle($meta['title']);
                    $wikimeta->setContent($meta['content']);
                    $wikimeta->setHtmlCache($meta['html_cache']);
                    $wikimeta->setMark($meta['mark']);
                    $wikimeta->save();
                } 
            }
            //echo $wikiinfo['id'],'|',$wikiinfo['title'],'已导入';
        }else{
            //echo $wiki_id,'未找到';
        }
    }
    
    /**
     * 从欢网同步单个文件
     * @param array $options
     */
    private function uploadFileFromHuan($filename) {
        $storage = StorageService::get('photo');
        $content = $storage->get($filename);
        if(!$content) {
            //echo $filename."+++\n";
            if(!is_dir("./tmp/upload")){
                mkdir("./tmp/upload",0700);
            }
            $content = Common::get_url_content("http://image.epg.huan.tv/2011/10/10/".$filename, 5);
            file_put_contents("./tmp/upload/".$filename, $content);                     
            sleep(1);
            if(!is_file("./tmp/upload/".$filename)) {
                sleep(1);
            }            
            $storage->save($filename, "./tmp/upload/".$filename);
            @unlink("./tmp/upload/".$filename);
        }
    }
    
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }    
}
