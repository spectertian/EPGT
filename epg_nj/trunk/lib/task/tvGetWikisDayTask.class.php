<?php
/**
 *  @todo  : 从huan.tv获取wiki,和symfony tv:GetWikis不同的是需要按--queryat=updated_at获取wiki
 *  @author: lifucang
 */
class tvGetWikisDayTask extends sfMondongoTask
{
  protected function configure()
  {
    // add your own arguments here
    /*
    $this->addArguments(array(
       new sfCommandArgument('startTime', sfCommandArgument::REQUIRED, 'startTime'),
       new sfCommandArgument('endTime', sfCommandArgument::REQUIRED, 'endTime'),
    ));
    */
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('startTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'startTime'),
      new sfCommandOption('endTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'endTime'),
      new sfCommandOption('queryat', null, sfCommandOption::PARAMETER_OPTIONAL, 'queryat'),
      new sfCommandOption('getpic', null, sfCommandOption::PARAMETER_OPTIONAL, 'getpic'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'getWikisDay';
    $this->briefDescription = '';
    $this->patterns         = array();  //敏感词数组
    $this->status           = '自动审核';  //敏感词状态
    $this->detailedDescription = <<<EOF
The [tv:getWikisDay|INFO] task does things.
Call it with:

  [php symfony tv:getWikisDay|INFO]
EOF;
    //symfony tv:getWikisDay --queryat=updated_at  按更新时间抓取
    //symfony tv:getWikisDay --startTime=2012-12-01 --endTime=2012-12-10  --getpic=false 抓取1号到10号创建的wiki
    //symfony tv:getWikisDay --queryat=updated_at --startTime=2013-05-23 --endTime=2013-05-24
    //--getpic=false  是不重新抓取图片
  }

  protected function execute($arguments = array(), $options = array())
  {
        //$this->connectMaster($options);
        $startTime=$options['startTime'];
        $endTime=$options['endTime'];
        $queryat=$options['queryat'];
        $getPic=$options['getpic']?$options['getpic']:true;
        
        $mongo = $this->getMondongo();
        $url=sfConfig::get('app_epghuan_url');
        $apikey = sfConfig::get('app_epghuan_apikey');
        $secretkey = sfConfig::get('app_epghuan_secretkey');
        if(isset($options['queryat'])){
            $startTime=$options['startTime']?$options['startTime']:date("Y-m-d 00:00:00");
            $endTime=$options['endTime']?$options['endTime']:date("Y-m-d 23:59:59");
            $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'","queryat":"'.$queryat.'"}}';
        }else{
            if (isset($options['startTime'])) {
                $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'"}}';
            }else{
                $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"}}';
            } 
        }
        $this->getSensitiveWords();  //获取敏感词
        
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $wikis=$result['wiki']?$result['wiki']:array();       
        $i=0;
        $k=0;
        $count=0;
        echo "count:",count($wikis),"\n";
        sleep(1);
        foreach($wikis as $wikiinfo){
               $wiki_exists = $mongo->getRepository("Wiki")->findOneById(new MongoId($wikiinfo['id']));
               if(!$wiki_exists){
                    echo iconv('utf-8','gbk',$wikiinfo['title']),"add\n";
                    $this->importWiki($url,$wikiinfo,$options,$getPic,$apikey,$secretkey);   
                    $i++;
               }else{
                    if($queryat=='updated_at'){
                        echo iconv('utf-8','gbk',$wikiinfo['title']),"update\n";
                        $this->updateWiki($url,$wikiinfo,$wiki_exists,$getPic);   
                        $k++;     
                    }
               }  
               $count++;     
        }    
        echo date("Y-m-d H:i:s"),'------',"Count:$count;WikiAdd:".$i.";WikiUpate:".$k; 
        echo "------finished!\r\n";  
  }
  //获取敏感词
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
  private function importWiki($url,$wikiinfo,$options,$getPic,$apikey,$secretkey){
        //$this->connectMaster($options);
        //$mongo = $this->getMondongo();
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
            if($wikiinfo['cover']&&$getPic)
                exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy  --need_examine=no --file_key=".$wikiinfo['cover']);
            $screens = $wikiinfo['screens'];   
            if($screens&&$getPic){
                foreach($screens as $screen)
                {
                    //exec("php /usr/local/php5.3.8/bin/symfony tv:AttachmentsCopy --file_key=".$screen);
                    exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy  --need_examine=no --file_key=".$screen);
                }   
            }
            //继续写入wiki_meta信息
            $json='{"action":"GetWikiMetasGd","device":{"dnum":"123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"wiki_id":"'.$wikiinfo['id'].'"}}';
            $info = Common::post_json($url,$json);
    		$resulta = json_decode($info,true);
            $metas=$resulta['wikimetas']?$resulta['wikimetas']:array();
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
  }  

  //更新wiki
  private function updateWiki($url,$wikiinfo,$wiki,$getPic){
        if($wikiinfo){
            //$wiki=new Wiki();
            //$wiki->setId(new mongoId($wikiinfo['id']));
            if($this->status=='人工审核'){
                $wiki_title=preg_replace($this->patterns, "*", $wikiinfo['title']);
                $wiki_content=preg_replace($this->patterns, "*", $wikiinfo['content']);
            }else{
                $wiki_title=$wikiinfo['title'];
                $wiki_content=$wikiinfo['content'];
            }
            $wiki->setTitle($wiki_title);
            //$wiki->setSlug($wikiinfo['slug']);
            $wiki->setTvsouId($wikiinfo['tvsou_id']);
            $wiki->setModel($wikiinfo['model']);
            $wiki->setContent($wiki_content);
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
            $wiki->save();
            //导入图片
            if($wikiinfo['cover']&&$getPic)
                exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy  --need_examine=no --file_key=".$wikiinfo['cover']);
            $screens = $wikiinfo['screens']; 
            if($screens&&$getPic){
                foreach($screens as $screen)
                {
                    //exec("php /usr/local/php5.3.8/bin/symfony tv:AttachmentsCopy --file_key=".$screen);
                    exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy  --need_examine=no --file_key=".$screen);
                }  
            }  
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
