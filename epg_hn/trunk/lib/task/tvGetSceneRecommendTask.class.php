<?php
/**
 * 从接口中获取节目推荐数据
 * Enter description here ...
 * @author majun
 *
 */
class tvGetSceneRecommendTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'GetSceneRecommend';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:GetSceneRecommend|INFO] task does things.
Call it with:

  [php symfony tv:GetSceneRecommend|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->connectMaster($options);
    $mongo = $this->getMondongo();
    $recRep = $mongo -> getRepository('recommend');
    $wikiRep = $mongo -> getRepository("wiki");
    $url = 'http://www.epg.huan.tv/json';
    //$url = 'http://www.5i.test.cedock.net/json';
    $json_post='{"action":"GetSceneRecommend","device":{"dnum":"123"},"developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"user":{"userid":"123"},"param":{"scene":"hncatv_index_hotplay"}}';
    
    $resules = Common::post_json($url,$json_post);
    $resules = json_decode($resules,true);
    
    if($resules["recommends"]){
	    foreach($resules["recommends"] as $resule){
	        if($resule["big_pic"]) {
	            $bpic = substr($resule["big_pic"], strrpos($resule["big_pic"], "/")+1);
	            $this -> uploadFileFromHuan($bpic);
            }
	        if($resule["smallpic"]) {
	            $spic = substr($resule["smallpic"], strrpos($resule["smallpic"], "/")+1);
	            $this -> uploadFileFromHuan($spic);
	        }
		    $recommend = $recRep -> findOneById(new mongoId($resule['id']));//查询该条推荐是否已经存在 存在则更新
		    if($recommend){
    		    $recommend -> setPic($bpic);
    		    $recommend -> setSmallpic($spic);
    		    $recommend -> setTitle($resule["title"]);
    		    $recommend -> setDesc($resule["desc"]);
    		    $recommend -> setUrl($resule["url"]);
    		    $recommend -> save();
		    }else{
    		    $recommend = new Recommend();
    		    $recommend -> setPic($bpic);
    		    $recommend -> setSmallpic($spic);
    		    $recommend -> setTitle($resule["title"]);
    		    $recommend -> setDesc($resule["desc"]);
    		    $recommend -> setUrl($resule["url"]);
    		    $recommend -> save();
		    }
		    
		    $wikiId = substr($result["url"], strrpos($result["url"], "="));
		    $wiki = $wikiRep -> findOneById(new mongoId($wikiId));
		    if(!$wiki) $this -> importWiki($url,$wikiId,$options);
		    echo $resule['title']." 已导入\r\n";
	    }
	    echo "------finished!\r\n"; 
    }else{
        echo "------none!\r\n"; 
    }
    
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
     * 连接 master 中的数据库   * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }
}
