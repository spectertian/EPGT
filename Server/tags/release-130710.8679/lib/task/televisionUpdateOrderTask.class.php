<?php

class televisionUpdateOrderTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('wiki_id', null, sfCommandOption::PARAMETER_OPTIONAL, 'The connection name'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'televisionUpdateOrder';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:wikiToXunSearch|INFO] task does things.
Call it with:

  [php symfony tv:televisionUpdateOrder|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $mongo = $this->getMondongo();
    $video_repo = $mongo->getRepository("video");

	$videos = $video_repo->find(array(
		"query" => array("model" => 'television'),
		//"sort" => array("created_at" => 1), 
		//"limit" => 100
		));
	echo count($videos);echo "\n";
	echo '个栏目需要重新抓取以便于排序！！';echo "\n";
	if (!$videos) break;
	foreach ($videos as $key=>$video) 
	{
		$nummm=$key+1;
		echo "现在开始执行第".$nummm."个";
		echo "\n";
		
		$wiki_id = $video->getWikiId();

		$video_playlist_id = $video->getVideoPlaylistId();
		$title = $video->getTitle();
		$url = $video->getUrl();
		$created_at = $video->getCreatedAt();
		echo $title;echo "\n";
        $wiki_repository = $mongo->getRepository('Wiki');
        $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));

        $date  = date('Ymd');
        $mark = 0;

		$config=array();
		if(strpos($url, 'qiyi')){
			$referer='qiyi';
			$config = $this->qiyiAnalysis($url);
			//@@@@@@@@@@@@@@@@@@@@@
			$video->delete();
			//@@@@@@@@@@@@@@@@@@@
			if (array_key_exists('title', $config))
			{
				$qiyi_title = $config['title'];
				preg_match("/.*?(\d{8}).*?/",$qiyi_title, $matchs);print_r($matchs);
				if(isset($matchs[1]))
					$mark = $matchs[1];
			}
		}elseif(strpos($url, 'sina')){
			$referer='sina';
			$config = $this->sinaAnalysis($url);
		}elseif(strpos($url, 'youku')){
			$referer='youku';
			$config = $this->youkuAnalysis($url);
		}elseif(strpos($url, 'sohu')){
			$referer='sohu';
			$config = $this->sohuAnalysis($url);
		}else{
			$referer='other';$video->delete();
		}   

		$result=$this->saveTeleplayVideo($wiki_id,$title, $url, $referer, $config, $video_playlist_id, $time = 0, $mark,$created_at); 

        
	}		
  }

    protected function saveTeleplayVideo($wiki_id,$title, $url, $referer, $config, $video_playlist_id, $time = 0, $mark = 0,$created_at) {
        $video = new Video();
		$video->setAutoCreate(false);
		
        $video->setWikiId($wiki_id);
        $video->setModel('television');
        $video->setTitle($title);
        $video->setUrl($url);
        $video->setConfig($config);
        $video->setReferer($referer);
        $video->setPublish(true);
        $video->setVideoPlaylistId($video_playlist_id);
        if ($time) $video->setTime($time);
        if ($mark > 0) {
            $video->setMark($mark);

        }
        if ($mark == 0) 
            $video->setMark(0);  
		$video->setCreatedAt($created_at);
        $video->save();    

    } 



    /**
     * 分析奇艺播放数据
     * @param <type> $url
     * @return <type>
     * @author luren
     */
    protected function qiyiAnalysis($url) {
        $result = array();
        $html = @file_get_contents($url, false, Common::createStreamContext());
	
        $html = explode('</script>', $html);
        
        if (isset($html[0])) {
            preg_match("/\"?pid\"? ?: ?\"(.*?)\"/",  $html[0], $ret);
            if (isset($ret[1])) $result['pid'] = $ret[1];
            preg_match("/\"?ptype\"? ?: ?\"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['ptype'] = $ret[1];
            preg_match("/\"?videoId\"? ?: ?\"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['videoId'] = $ret[1];
            preg_match("/\"?albumId\"? ?: ?\"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['albumId'] = $ret[1];
            preg_match("/\"?tvId\"? ?: ?\"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['tvId'] = $ret[1];
            preg_match("/\"?title\"? ?: ?\"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['title'] = $ret[1];
        }

        return $result;
    }    
    
    /**
     * 分析新浪播放数据
     * @param <type> $url
     * @return <type>
     * @author luren
     */
    protected function sinaAnalysis($url) {
        $result = array();
        $html = file_get_contents($url, false, Common::createStreamContext());
        $html = explode('</head>', $html);
        $html = array_shift($html);
        preg_match("|\Wvid:\'(.*?)\',|",$html, $ret);
        if (isset($ret[1]))  $result['vid'] = $ret[1];
        preg_match("|ipad_vid:\'(.*?)\',|",$html, $ret);
        if (isset($ret[1]))  $result['ipad_vid'] = $ret[1];
        return $result;
    }    
    
    /**
     * 分析搜狐播放数据
     * @param <type> $url
     * @return <type>
     */
    protected function sohuAnalysis($url) {
        $result = array();
        $html = file_get_contents($url, false, Common::createStreamContext());
        $html = explode('<body>', $html);
        $html = iconv('GB18030', 'UTF-8//IGNORE', $html[0]);
        preg_match("|\Wvid=\"(.*?)\";|",$html, $ret);
        if (isset($ret[1]))  $result['vid'] = $ret[1];
        return $result;
    }
    
    /**
     * 分析优酷播放数据
     * @param <type> $url
     * @return <type>
     */
    protected function youkuAnalysis($url) {
        $result = array();
        $html = file_get_contents($url, false, Common::createStreamContext());
        $html = explode('</title>', $html);

        preg_match("|<title>(.+)|",$html[0], $ret);
        if (isset($ret[1])) {
            $title = explode(' - ', $ret[1]);
            $result['title'] = reset($title);
        }

        preg_match("|id_(.+)\.html|",$url, $ret);
        if (isset($ret[1]))  $result['id'] = $ret[1];

        return $result;
    }



}