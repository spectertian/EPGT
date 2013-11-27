<?php
/**
 *  @todo  : 从欢网导入节目数据，每次导入当天或指定日期的
 *  @author: lifucang
 */
class tvGetProgramsTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_OPTIONAL, 'date'),
      new sfCommandOption('yesterday', null, sfCommandOption::PARAMETER_OPTIONAL, 'yesterday'),
      new sfCommandOption('channel', null, sfCommandOption::PARAMETER_OPTIONAL, 'channel'),     
    ));

    $this->namespace        = 'tv';
    $this->name             = 'GetPrograms';
    $this->briefDescription = '';
    $this->patterns         = array();  //敏感词数组
    $this->status           = '自动审核';  //敏感词状态
    $this->detailedDescription = <<<EOF
The [tv:GetPrograms|INFO] task does things.
Call it with:

  [php symfony tv:GetPrograms|INFO]
EOF;
  //symfony tv:GetPrograms --date=2013-01-04 --channel=GuiZhouTV  抓取指定日期数据
  //或者
  //symfony tv:GetPrograms --yesterday=1  获取昨天的数据
  }

  protected function execute($arguments = array(), $options = array())
  {
        $this->connectMaster($options);
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('GetPrograms');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        //$channels = Doctrine::getTable('Channel')->getAllChannelByTv();
        $channels=$mongo->getRepository('SpService')->getServicesByTag();
        $url = sfConfig::get('app_epghuan_url');
        $apikey = sfConfig::get('app_epghuan_apikey');
        $secretkey = sfConfig::get('app_epghuan_secretkey');
        $wiki_num=0;  //记录wiki导入数
        $program_num=0; //记录保存program数 
        if($options['yesterday']==1){
            $date = date("Y-m-d",strtotime("-1 day"));
        }else{
            if(isset($options['date'])){
                $date = $options['date'];
            }else{
                $date = date("Y-m-d");
            }
        }
        $program_repository = $mongo->getRepository('program');
        $this->getSensitiveWords();  //获取敏感词
        foreach($channels as $channel){
              if(!$channel->getChannelCode()) continue;
              if(isset($options['channel'])){
                  if($channel->getChannelId()!=$options['channel']) continue; //如果不是广东卫视，继续，只传递一个频道数据过去
              }
              $channel_code=$channel->getChannelCode();
              $channel_codea=$channel->getChannelCodea();
              if($channel_codea){
                  $channel_codeGet=$channel_codea;
              }else{
                  $channel_codeGet=$channel_code;
              }
              //增加节目
              //$json_post='{"action":"GetProgramsByChannelGd","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"channel_code":"'.$channel->getCode().'","start_time":"'.$start_time.'","end_time":"'.$end_time.'"}}';
              $json_post='{"action":"GetProgramsByChannelGd","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"channel_code":"'.$channel_codeGet.'","date":"'.$date.'"}}';
              $getinfo = Common::post_json($url,$json_post);
              if($getinfo){
                  $result = json_decode($getinfo,true);
                  if(!$result) continue;  //减少notice提示，用处不大
                  $programs=$result['program'];
                  if(!$programs) continue;  //减少notice提示，用处不大
                  //先删除当天的节目
                  $program_repository->removeDayPrograms($channel_code, $date);
                  foreach($programs as $program_arr){
                       $time=date('H:i',strtotime($program_arr['start_time']));
                       $program = new Program();
                       $program->setChannelCode($channel_code);
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
                       if($program_arr['updated_at']!=null)
                           $program->setUpdatedAt(new DateTime($program_arr['updated_at']));                 
                       $program->save();
                       $program_num++;
                       //开始判断wiki表里是否存在该wiki，如果不存在，从接口导入该wiki
                       if($program_arr['wiki_id']){
                           $wiki = $mongo->getRepository("Wiki")->findOneById(new MongoId($program_arr['wiki_id']));
                           if(!$wiki){
                                $this->importWiki($url,$program_arr['wiki_id'],$options,$apikey,$secretkey);
                                $wiki_num++;
                           }
                       }
                  }
              }
              //echo $channel->getChannelCode(),'******************';
        }  
        echo date("Y-m-d H:i:s"),'------',"Program:".$program_num."------Wiki:".$wiki_num; 
        echo "------finished!\r\n";       
        $content="date:$date---Program:".$program_num."---Wiki:".$wiki_num;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();                 
                        
  }
  //获取敏感词
  /*
  private function getSensitiveWords(){
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Setting');
        $query = array('query' => array( "key" => 'sensitiveWords' ));
        $rs = $repository->findOne($query);
        if($rs){
            $words=$rs->getValue();
            $this->patterns=Common::getSensitiveWords($words);
        }
  }
  */
    private function getSensitiveWords(){
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('words');
        $words_res = $repository->find();
        $arr=array();
        foreach($words_res as $rs){
            $arr[] = $rs->getWord();
        }
        $words=implode(',',$arr);
        $this->patterns=Common::getSensitiveWords($words);
        //查询敏感词状态
        $setting_repository = $mongo->getRepository('Setting');
        $rs = $setting_repository->findOne(array('query' => array( "key" => 'words' )));
        if($rs){
            $this->status=$rs->getValue();
        }
    }
  //导入wiki
  private function importWiki($url,$wiki_id,$options,$apikey,$secretkey){
        //$this->connectMaster($options);
        //$mongo = $this->getMondongo();
        //$url='http://www.5i.test.cedock.net/json';
        $json_post='{"action":"GetWikiInfoGd","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"wiki_id":"'.$wiki_id.'"}}';
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true);
        $wikiinfo=$result['wiki'];
        if($wikiinfo){
            //$wiki=new Wiki();
            if($this->status=='人工审核'){
                $wikititle_wiki = $wikiinfo['title'];
                $content_wiki = $wikiinfo['content'];
                $verify = 0;
            }else{
                $verify = 1;
                $wikititle = preg_replace($this->patterns, "*", $wikiinfo['title']);
                $content = preg_replace($this->patterns, "*", $wikiinfo['content']);
                //敏感词日志记录
                if($this->status=='半自动审核'){
                    $wikititlea = '';
                    $contenta = '';
                    $wikititle_wiki = $wikiinfo['title'];
                    $content_wiki = $wikiinfo['content'];
                }else{
                    //自动审核
                    $wikititlea = $wikititle;
                    $contenta = $content;
                    $wikititle_wiki = $wikititle;
                    $content_wiki = $content;
                }
                if($wikititle!=$wikiinfo['title']){
                    if($this->status=='半自动审核'){
                        $verify = 0;
                    }
                    $words=new WordsLog();
                    $words->setWord($wikiinfo['title']);
                    $words->setReword($wikititlea);
                    $words->setFrom('wiki');
                    $words->setFromId($wikiinfo['id']);
                    $words->setStatus($verify);
                    $words->save();
                }
                if($content!=$wikiinfo['content']){
                    if($this->status=='半自动审核'){
                        $verify = 0;
                    }
                    $words=new WordsLog();
                    $words->setWord($wikiinfo['content']);
                    $words->setReword($contenta);
                    $words->setFrom('wiki');
                    $words->setFromId($wikiinfo['id']);
                    $words->setStatus($verify);
                    $words->save();
                }
            }

            $wiki=new WikiGet();
            $wiki->setId(new mongoId($wikiinfo['id']));
            $wiki->setTitle($wikititle_wiki);
            $wiki->setSlug($wikiinfo['slug']);
            $wiki->setTvsouId($wikiinfo['tvsou_id']);
            $wiki->setModel($wikiinfo['model']);
            $wiki->setContent($content_wiki);
            $wiki->setHtmlCache($wikiinfo['html_cache']);
            $wiki->setCover($wikiinfo['cover']);
            $wiki->setScreenshots($wikiinfo['screens']);
            $wiki->setTags($wikiinfo['tags']);
            $wiki->setSource($wikiinfo['source']);
            $wiki->setLikeNum($wikiinfo['like_num']);
            $wiki->setDislikeNum($wikiinfo['dislike_num']);  
            //$wiki->setHasVideo($wikiinfo['has_video']);  
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
            if($wikiinfo['cover']){
                $queueOk = $this->attachmentCopySqs($wikiinfo['cover']);
                if(!$queueOk){
                    exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --file_key=".$wikiinfo['cover']);
                }
            }
                
            $screens = $wikiinfo['screens'];   
            foreach($screens as $screen)
            {
                $queueOk = $this->attachmentCopySqs($screen);
                if(!$queueOk){
                    exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --file_key=".$screen);
                }
            }
            //继续写入wiki_meta信息
            $json='{"action":"GetWikiMetasGd","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"wiki_id":"'.$wiki_id.'"}}';
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
    private function attachmentCopySqs($file_key) {
        $httpsqs = HttpsqsService::get();  
        $arr = array(
                   "title" => $file_key,
                   "action" => "attachment_copy",
                   "parms" => array("file_key" => $file_key)
                   );
        return $httpsqs->put("epg_queue",json_encode($arr));
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
