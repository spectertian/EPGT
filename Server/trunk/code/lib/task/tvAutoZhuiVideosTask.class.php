<?php
/**
 * 
 * 处理自动追剧功能
 * @author majun
 * @date   2013-09-05
 *
 */
class tvAutoZhuiVideosTask extends sfMondongoTask
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
    $this->name             = 'autoZhuiVideos';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:autoZhuiVideos|INFO] task does things.
Call it with:

  [php symfony tv:autoZhuiVideos|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    
  	$mongo = $this->getMondongo();
  	$zrep = $mongo -> getRepository("videosZhui");
  	//只取状态为0 待抓取条目 1 正在抓取
  	$zvideos = $zrep -> find(array('query'=>array('state'=>array('$in' => array(0,1))))); 
  	//print_r($zvideos);
	if($zvideos){
    	foreach($zvideos as $zvideo){
            $sourse = $zvideo->getSource();
            if($sourse["qiyi"]["url"]){//暂时只处理奇艺
                $qiyi = $this->zhuiQiyi($sourse["qiyi"]["url"]);
                if(count($qiyi[0])>0){
                    $local = $sourse["qiyi"]["local"]?$sourse["qiyi"]["local"]:0;
                    //只有存在该剧集且总集数大于本地已有剧集开始抓取
                    $qiyiTotal = count($qiyi[2]);
                    if($local>=0 && $qiyiTotal>$local){
                        $time = date('Y-m-d H:i:s',time());
                        $wikiId = $zvideo->getWikiId();
                        $wikiTitle = $zvideo->getWikiName();
                        //循环抓取数据
                        foreach($qiyi[0] as $key => $url){
                            if(($key+1)<=$local) continue; //已抓取过的集数跳过
                            $videoTime = $qiyi[3][$key];
                            $mark = $qiyi[2][$key];
                            $title = $qiyi[1][$key];
                            $VideoPlayList = $this->saveVideoPlayList($wikiTitle, $wikiId, $url, 'qiyi');
                            if ($VideoPlayList){
                            	$videoPlaylistId = (string)$VideoPlayList->getId();
                            	$config = $this->qiyiAnalysis($url,"teleplay");
                            	$this->saveTeleplayVideo($wikiId,$title,$url,"qiyi",$config,$videoPlaylistId,$videoTime,$mark);
                            	echo $title."catched!\n";
                            }else {
                            	echo $title."已存在!\n";
                            }
                        }
                        
                        $zvideo -> setLocal($qiyiTotal);
                        $zvideo -> setSuccess(1);
                        $zvideo -> setUpdateTime($time);        //设置抓取的更新时间
                        $sourse["qiyi"]["update_time"] = $time; //设置奇艺源的最近一次最新抓取时间
                        $sourse["qiyi"]["local"] = $qiyiTotal;
                        $sourse["qiyi"]["success"] = 1;
                        $zvideo -> setState(2);
                    }
                }else{
                    $zvideo -> setSuccess(0);
                    $sourse["qiyi"]["success"] = 0;
                }
                //当本地剧集等于总集数时 状态设置为2
                if($local == $zvideo->getTotal()){
                    $zvideo -> setState(2);
                }
	            $zvideo -> setSource($sourse);
	            $zvideo -> save();
	            $wikiRep = $mongo->getRepository("wiki");
	            $wiki = $wikiRep->findOneById(new MongoId($wikiId));
	            $wiki->setHasVideo(true); 
        		$wiki->save();
            }
    	}
	}
	echo "finished!";
  }
  
  
  
  /**
     * 电视剧视频保存
     * @param Wiki $wiki
     * @param type $title
     * @param type $url
     * @param type $config
     * @param type $videoPlaylistId
     * @param type $time
     * @param type $mark 
     * @author luren
     */
    protected function saveTeleplayVideo($wikiId, $title, $url, $referer, $config, $videoPlaylistId, $time = 0, $mark = 0) 
    {
        $video = new Video();
        $video->setWikiId($wikiId);
        $video->setModel("teleplay");
        $video->setTitle($title);
        $video->setUrl($url);
        $video->setConfig($config);
        $video->setReferer($referer);
        $video->setPublish(true);
        $video->setVideoPlaylistId($videoPlaylistId);
        if ($time) $video->setTime($time);
        if ($mark > 0) {
            $video->setMark($mark);
            $mongo = $this->getMondongo();
            $wikiMetaRepos = $mongo->getRepository('wikiMeta');
            $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => $wikiId, 'mark' => (int) $mark)));
            if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
        }
        $video->save();     
    }
   
   /**
     * 保存一条 playlist 记录
     * @param type $title
     * @param type $wiki_id
     * @param type $url
     * @param type $referer
     * @return VideoPlaylist 
     * @author luren
     */
    protected function saveVideoPlayList($title, $wiki_id, $url, $referer) {
        $mongo = $this->getMondongo();
        $vplRep = $mongo->getRepository("videoPlaylist");
        $videoPlay = $vplRep->findOne(array("query"=>array("wiki_id"=>$wiki_id,"url"=>$url,"title"=>$title)));
		if ($videoPlay){
			return false;
		}else{
        	$VideoPlaylist = new VideoPlaylist();
	        $VideoPlaylist->setTitle($title);
	        $VideoPlaylist->setUrl($url);
	        $VideoPlaylist->setReferer($referer);
	        $VideoPlaylist->setWikiId($wiki_id);
	        $VideoPlaylist->save();
	        return $VideoPlaylist;
		}
    }
   
   /**
     * 分析奇艺播放数据
     * @param <type> $url
     * @return <type>
     * @author luren
     */
    protected function qiyiAnalysis($url,$model='',$dongman='') {
            $result = array();
            $html = $this->curl_file_get_contents($url);
            if($model == 'teleplay' || $dongman =='yes'){
                //$html = explode('</script>', $html);
            	$html = explode('</html>', $html);
                if (isset($html[0])) {  
                	preg_match("/pid=(.*?)\&/",    $html[0], $ret);
                	if (isset($ret[1])) $result['pid'] = $ret[1];
                	preg_match("/data-player-tvid=\"(.*?)\"/",    $html[0], $ret);
                	if (isset($ret[1])) $result['tvId'] = $ret[1];
                	preg_match("/data-player-albumid=\"(.*?)\"/",    $html[0], $ret);
                	if (isset($ret[1])) $result['albumId'] = $ret[1];
                	preg_match("/data-player-videoid=\"(.*?)\"/",    $html[0], $ret);
                	if (isset($ret[1])) $result['videoId'] = $ret[1];
                	$result['ptype'] = 2;
                	
                }
            }
            if($model == 'film' && $dongman !='yes')
            {
                
             $html = explode('<div class="videoPlay medium">', $html);
             /**
              if (isset($html[0])) {
				preg_match("/\"videoId\":\"(.*?)\"/",    $html[0], $ret);
			    if (isset($ret[1])) $result['videoId'] = $ret[1];
				preg_match("/\"albumId\":\"(.*?)\"/",    $html[0], $ret);
				if (isset($ret[1])) $result['albumId'] = $ret[1];
				preg_match("/\"tvId\":\"(.*?)\"/",    $html[0], $ret);
				if (isset($ret[1])) $result['tvId'] = $ret[1];
			   }
			   */
             if (isset($html[1])) {
             preg_match("/data-player-tvid=\"(.*?)\"/",    $html[1], $ret);
             if (isset($ret[1])) $result['tvId'] = $ret[1];
             preg_match("/data-player-albumid=\"(.*?)\"/",    $html[1], $ret);
             if (isset($ret[1])) $result['albumId'] = $ret[1];
             preg_match("/data-player-videoid=\"(.*?)\"/",    $html[1], $ret);
             if (isset($ret[1])) $result['videoId'] = $ret[1];
             }
             
            }
            return $result;
    }
  
  //奇艺电视剧采集程序
  protected function zhuiQiyi($url){
      $html = $this->curl_file_get_contents($url);
      $http = "http://www.iqiyi.com";
      $pattern = "/\/common\/topicinc\/(.*)_(.*)\/playlist_(.*).inc/i";
      preg_match_all($pattern,$html,$matches);
      unset($html);
      $list = $matches[0];
      $resules = array();
      if(count($list)>0)
      {
    	foreach($list as $k => $v)
       	{
       		//重新采集视频并保存
    		$childurl = $http.$v;
     		$childHtml = $this->curl_file_get_contents($childurl);
    
     		$pattern = '/href=\"(.*?)\" .*?\n<img.*? title="(.*?)" alt=.*?(\d+).*? class=.+?>.*?\n<span.*?\"s2\">([\d|:]+)<\/span> <\/a>/';
    		preg_match_all($pattern, $childHtml, $matches);
    		unset($childHtml);
    		array_shift($matches);
			$counts = count($matches[0]);
			if($counts<1)continue;
            foreach ($matches as $k => $v){
        		$resulets[$k] = (count($resulets[$k]) > 0)?array_merge($resulets[$k],$matches[$k]) : $matches[$k];
    		}
        }
        return $resulets;
      }else{
        return false;
      }
  }
  //curl 辅助函数
  public function curl_file_get_contents($durl){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $durl);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      //curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
      //curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $r = curl_exec($ch);
      curl_close($ch);
      return $r;
  }
 
}
