<?php

class tvpptvVideoTask extends sfMondongoTask
{
	protected $total;
	protected $success;

	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
			new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
			new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
			new sfCommandOption('charset', null, sfCommandOption::PARAMETER_REQUIRED, 'The out charset', 'utf-8'),
		));

		$this->namespace        = 'tv';
		$this->name             = 'pptvVideo';
		$this->briefDescription = '';
		$this->detailedDescription = '';

		$this->total = 0;
		$this->success = 0;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$mongo = $this->getMondongo();
		$wiki_repos = $mongo->getRepository('Wiki');
		$content = '';

		$xmlcontent = file_get_contents("http://epg.api.pptv.com/cover_image_recommand_nav-ex.api?auth=2&mode=1&platform=ipad");
		$xmlobj = simplexml_load_string($xmlcontent);
		foreach($xmlobj->nav as $a => $xmlnav) {
			$content .= $this->iconvString($xmlnav->name, $options['charset']);

			$xmlcontent = file_get_contents("http://epg.api.pptv.com/cover_image_recommand_list-ex.api?auth=2&nav_id=".$xmlnav->navid);
			$xmlobj = simplexml_load_string($xmlcontent);
			foreach($xmlobj->c as $a => $xmlc) {
				$this->total++;
				$wiki = $wiki_repos->findOne(array('query' => array('title' => Wiki::slugify($xmlc->title))));
				if($wiki) {
					$this->success ++;
					$content .= $this->iconvString("+++++".$xmlc->title, $options['charset']);
					$this->parseMediaInfo($wiki_repos,$wiki,$xmlc->vid);
				}else{
					$content .= $this->iconvString("-----".$xmlc->title, $options['charset']);
				}
			}
		}
		$content .= $this->iconvString("finished,total is ".$this->total." ,success is ".$this->success." .\n", $options['charset']);
		$logname = "./log/task_tv_pptvVideo_".date("Y-m-d-H-i-s").".txt";
		file_put_contents($logname, $content);
	}

	/**
	  * 解析媒体详情
	  * @param int vid
	  * @return void
	  */
	protected function parseMediaInfo($wiki_repos, $wiki, $vid)
	{
		$xmlcontent = file_get_contents("http://epg.api.pptv.com/detail.ashx?platform=stb&c=28&s=1&vid=".$vid."&auth=1");
		$xmlobj = simplexml_load_string($xmlcontent);
		if(count($xmlobj->video_list->video) > 1) {			
			$VideoPlaylist = $this->saveVideoPlayList($xmlobj->title,$wiki);
			foreach($xmlobj->video_list->video as $a => $xmlv) {
				$attrs = $xmlv->attributes();
				$this->saveVideo("teleplay", $xmlobj->title, $xmlv, array("pptvid" => $attrs['id']), $wiki, $VideoPlaylist->getId(), $attrs['title']);
			}
		} elseif(count($xmlobj->video_list->video) == 1){
			$xmlv = $xmlobj->video_list->video[0];
			$attrs = $xmlv->attributes();
			$this->saveVideo("film", $xmlobj->title, $xmlv, array("pptvid" => $attrs['id']), $wiki);
		} else {

		}
	}

	/**
     * 视频保存
     * @param <type> $title
     * @param <type> $config
     * @param <type> $referer
     * @param Wiki $wiki
     * @param <type> $time
     * @param <type> $mark
     * @return void
     */
	protected function saveVideo($model, $title, $url, $config, $wiki, $videoPlaylistId = false, $mark = 0) 
	{   
		$query = array('referer'=>'pptv',	'config.pptvid' =>$config['pptvid']);
		$mongo = $this->getMondongo();
		$video_repos = $mongo->getRepository('video');
		$res = $video_repos->find(array('query'=>$query));
		if($res)
		{
			if($mark!=0)//电视剧
				echo "《".$title."》    第".$mark."集 已经存在了，不创建\n";
			else        //电影
				echo "《".$title."》      已经存在了，不创建\n";
		}
		else
		{
			if($mark!=0)//电视剧
				echo "《".$title."》    第".$mark."集 不存在，需要创建\n";
			else    {    //电影
				echo "《".$title."》      不存在，需要创建\n";
			}
	        $video = new Video();
	        $video->setModel($model);
	        $video->setTitle($title);
	        $video->setUrl($url);
	        $video->setConfig($config);
	        $video->setReferer('tps');
	        $video->setPublish(true);
	        if ($videoPlaylistId)  $video->setVideoPlaylistId($videoPlaylistId);
	        if ($mark > 0) $video->setMark($mark);
	        if ($wiki instanceof Wiki) {
	            $video->setWikiId((string) $wiki->getId());           
	            $wiki->setHasVideo(true);
	            $wiki->save();
	        } else{
	            $video->setWikiId($wiki);
	        }
	        $video->save();
		}		

    }
    /**
     * 存储一份临时电视剧视频列表
     * @param <type> $title
     * @param Wiki $wiki
     * @param <type> $url
     */
    protected function saveVideoPlayList($title, $wiki) {
    	$query=array(
				'referer'=>'tps',
				'title'  =>$title,
			);
		$mongo = $this->getMondongo();
		$video_playlist_repos = $mongo->getRepository('VideoPlaylist');
		$res = $video_playlist_repos->find(array(
			'query'=>$query
			));
		if($res) return $res[0];
		else
		{
	        $VideoPlaylist = new VideoPlaylist();
	        $VideoPlaylist->setTitle($title);
	        $VideoPlaylist->setReferer('tps');
	
	        if ($wiki instanceof Wiki) {
	            $VideoPlaylist->setWikiId((string) $wiki->getId());
	        } else {
	            $VideoPlaylist->setWikiId($wiki);
	        }
	
	        $VideoPlaylist->save();
	        return $VideoPlaylist;
		}
    }

	protected function iconvString($string, $out_charset) {
		if(strtolower($out_charset) != "utf-8") {
			$string = iconv("utf-8", $out_charset, $string);
		}
		echo $string."\n";
		return $string."\n";
	}
}


?>