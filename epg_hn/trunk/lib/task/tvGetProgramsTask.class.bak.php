<?php

class tvGetProgramsTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'getPrograms';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:getPrograms|INFO] task does things.
Call it with:
[php symfony tv:getPrograms|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $this->connectMaster($options);
        $mongo = $this->getMondongo();
        $channels = Doctrine::getTable('Channel')->getAllChannelByTv();
        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d 23:59:59');
        $today = date('Y-m-d');
        $today_next = date('Y-m-d',mktime(0,0,0,date('m'),date('d')+1,date('Y')));
        
        $url = 'http://www.epg.huan.tv/json';
        foreach($channels as $channel){
            //先删除当天的节目
            $program_repository = $mongo->getRepository('program');
            $program_repository->removeDayPrograms($channel->getCode(), $today);
            //增加节目
            $json_post='{"action":"GetProgramsByChannel","device":{"dnum":"123"},"developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"user":{"userid":"123"},"param":{"channel_code":"'.$channel->getCode().'","start_time":"'.$start_time.'","end_time":"'.$end_time.'"}}';
            $getinfo = Common::post_json($url,$json_post);
            $result = json_decode($getinfo,true);
            $programs=$result['program'];
            foreach($programs as $program_arr){
                $start_timea=$program_arr['date'].' '.$program_arr['start_time'];
                $end_timea=$today.' '.$program_arr['end_time'];
                if(strtotime($end_timea)<strtotime($start_timea)){
                   $end_timea=$today_next.' '.$program_arr['end_time'];
                }
                $program = new Program();
                $program->setName($program_arr['name']);
                $program->setTags($program_arr['tags']);
                $program->setStartTime(new DateTime($start_timea));
                $program->setEndTime(new DateTime($end_timea));
                $program->setPublish(true);
                $program->setWikiId($program_arr['wiki_id']);
                $program->setTime($program_arr['start_time']);
                $program->setDate($program_arr['date']);
                $program->setChannelCode($channel->getCode());
                $program->save();
                //开始判断wiki表里是否存在该wiki，如果不存在，从接口导入该wiki
                if($program_arr['wiki_id']){
                    $wiki = $mongo->getRepository("Wiki")->findOneById(new MongoId($program_arr['wiki_id']));
                    if(!$wiki){
                        $this->importWiki($url,$program_arr['wiki_id'],$options);
                    }
                }
            }
        }    
    }
    
    //导入wiki
    private function importWiki($url,$wiki_id,$options){
        $json_post='{"action":"GetWikiInfoGd","device":{"dnum":"123"}",developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wiki_id.'"}}';
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true);
        $wikiinfo = $result['wiki'];
        if($wikiinfo){
            $wiki=new Wiki();
            $wiki->setId(new mongoId($wikiinfo['id']));
            $wiki->setTitle($wikiinfo['title']);
            $wiki->setSlug($wikiinfo['slug']);
            $wiki->setModel($wikiinfo['model']);
            $wiki->setDescription($wikiinfo['description']);
            $wiki->setCover($wikiinfo['cover']);
            $wiki->setScreens($wikiinfo['screens']);
            if ($wikiinfo['model'] == 'actor') {
                $wiki->setSex($wikiinfo['info']['sex']);
                $wiki->setBirthday($wikiinfo['info']['birthday']);
                $wiki->setBirthplace($wikiinfo['info']['birthplace']);
                $wiki->setOccupation($wikiinfo['info']['occupation']);
                $wiki->setZodiac($wikiinfo['info']['zodiac']);
                $wiki->setBloodType($wikiinfo['info']['bloodType']);
                $wiki->setNationality($wikiinfo['info']['nationality']);
                $wiki->setRegion($wikiinfo['info']['region']);
                $wiki->setHeight($wikiinfo['info']['height']);
                $wiki->setWeight($wikiinfo['info']['weight']);
                $wiki->setDebut($wikiinfo['info']['debut']);
            }else{
                $wiki->setDirector($wikiinfo['info']['director']);
                $wiki->setStarring($wikiinfo['info']['starring']);
                $wiki->setTags($wikiinfo['info']['tags']);
                $wiki->setCountry($wikiinfo['info']['country']);
                $wiki->setLanguage($wikiinfo['info']['language']);
                $wiki->setReleased($wikiinfo['info']['released']);
                $wiki->setLikeNum($wikiinfo['info']['like_num']);
                $wiki->setDislikeNum($wikiinfo['info']['dislike_num']);
                $wiki->setSource($wikiinfo['info']['source']);
            }
            $wiki->save();
            
            //继续写入wiki_meta信息
            $json = '{"action":"GetWikiMetasGd","device":{"dnum":"123"},"developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wiki_id.'"}}';
            $info = Common::post_json($url,$json);
    		$resulta = json_decode($info,true);
            $metas = $resulta['wikimetas'];
            foreach($metas as $meta){
                $wikimeta = new WikiMeta();
                $wikimeta->setWikiId($wikiinfo['id']);
                $wikimeta->setTitle($meta['title']);
                $wikimeta->setContent($meta['content']);
                $wikimeta->setHtmlCache($meta['html_cache']);
                $wikimeta->setMark($meta['mark']);
                $wikimeta->save();
            }
            //echo $wikiinfo['id'],'|',$wikiinfo['title'],'已导入';
        }else{
            //echo $wiki_id,'未找到';
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
