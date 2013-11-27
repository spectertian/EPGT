<?php

class tvGetWikisDayTask extends sfMondongoTask
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
        //$this->connectMaster($options);
        $mongo = $this->getMondongo();
        $url='http://www.epg.huan.tv/json';
        $json_post='{"action":"GetWikisDayGd","device":{"dnum":"123"},"user":{"userid":"123"}}';
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $wikis=$result['wiki']?$result['wiki']:array();       
        foreach($wikis as $wikiinfo){
               $wiki_exists = $mongo->getRepository("Wiki")->findOneById(new MongoId($wikiinfo['id']));
               if(!$wiki_exists){
                    $this->importWiki($url,$wikiinfo,$options);  
               }       
        }     
  }
  //导入wiki
  private function importWiki($url,$wikiinfo,$options){
        //$this->connectMaster($options);
        //$mongo = $this->getMondongo();
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
            $json='{"action":"GetWikiMetasGd","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"wiki_id":"'.$wikiinfo['id'].'"}}';
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
  
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }  
}
