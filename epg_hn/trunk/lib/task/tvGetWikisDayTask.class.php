<?php

class tvGetWikisDayTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('startTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'startTime'),
            new sfCommandOption('endTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'endTime'),
            new sfCommandOption('overlap', null, sfCommandOption::PARAMETER_OPTIONAL, 'overlap','false'),
            // add your own options here
        ));

    $this->namespace        = 'tv';
    $this->name             = 'getWikisDay';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:getWikisDay|INFO] task does things.
Call it with:

[php symfony tv:getWikisDay|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $startTime = $options['startTime'];
        $endTime = $options['endTime'];
        
        $mongo = $this->getMondongo();
        $url = 'http://www.epg.huan.tv/json';
        if (isset($options['startTime'])) {
            $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"user":{"userid":"123"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'"}}';
        }else{
            $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"user":{"userid":"123"}}';
        }
        $getinfo = Common::post_json($url,$json_post);
       
		$result = json_decode($getinfo,true); 
        
        $wikis = $result['wiki'] ? $result['wiki'] : array();       
        $i=0;
        foreach($wikis as $wikiinfo){
                if($options['overlap'] != "true") {
                    $wiki_exists = $mongo->getRepository("Wiki")->findOneById(new MongoId($wikiinfo['id']));
                }
                if(!$wiki_exists){
                    $this->importWiki($url,$wikiinfo,$options);                   
                    $i++;
                }       
        }    
        echo "Wiki:".$i; 
        echo '------finished!';
    }
    
    //导入wiki
    private function importWiki($url,$wikiinfo,$options)
    {
        //$this->connectMaster($options);
        //$mongo = $this->getMondongo();
        if($wikiinfo){
            $wiki=new Wiki();
            $wiki->setId(new mongoId($wikiinfo['id']));
            $wiki->setTitle($wikiinfo['title']);
            $wiki->setSlug($wikiinfo['slug']);
            $wiki->setTvsouId($wikiinfo['tvsou_id']);
            $wiki->setModel($wikiinfo['model']);
            $wiki->setContent($wikiinfo['content']);
            $wiki->setHtmlCache($wikiinfo['html_cache']);
            $wiki->setCover($wikiinfo['cover']);
            $wiki->setScreenshots($wikiinfo['screens']);
            $wiki->setTags($wikiinfo['tags']);
            $wiki->setSource($wikiinfo['source']);
            $wiki->setLikeNum($wikiinfo['like_num']);
            $wiki->setDislikeNum($wikiinfo['dislike_num']);  
            $wiki->setHasVideo($wikiinfo['has_video']);  
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
            if($wikiinfo['cover']) {
                $this->CopyFile($wikiinfo['cover']);
            }
            $screens = $wikiinfo['screens']; 
            if(count($screens) > 0) {
                foreach($screens as $screen){
                    $this->CopyFile($screen);
                }
            }            
            //继续写入wiki_meta信息
            $json = '{"action":"GetWikiMetasGd","device":{"dnum":"123"},"developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wikiinfo['id'].'"}}';
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

    function CopyFile($filename)
    {
        $storage = StorageService::get('photo');
        $content = $storage->get($filename);
        if(!$content) {
            echo $filename."+++\n";
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
}
