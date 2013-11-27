<?php 
/**
 * 单例
 * 将百度中的视频导入到video  video_playlist中 
 * @author wn
 */

class BaiduvideoToMongo
{
	static $instance;
	private $mongo;
	private $output=true;
	private function __construct()
	{
	}
	
	private function __clone(){}
	
	static function getInstance()
	{
            if (empty(self::$instance)){
                    self::$instance = new BaiduvideoToMongo();
            }
            return self::$instance;
	}
	/*
	 * mongo
	 */
	public function setMongo($mongo)
	{
		$this->mongo = $mongo;
	}
	/*
	 * 传入对象 
	 * @param $obj  video_crawler
	 * @author wn
	 */ 	
	public function setObject($obj)
	{
		$this->getBaiduVideoList($obj);
	}
	/*
	 * 是否输出提示
	 * @author wn
	 */ 
	public function setOutput($value)
	{
		$this->output = $value;
	}	
    protected function getBaiduVideoList($vc)
    {
		$sites = array( 'baidu_qiyi' =>"iqiyi.com",
						'baidu_pptv' =>"pptv.com",
						'baidu_pps'  =>"pps.tv",
						'baidu_letv' =>"letv.com",
						'baidu_tencent'   =>"qq.com",   
						'baidu_sohu' =>"sohu.com",
						'baidu_tudou'=>"tudou.com",
						'baidu_youku'=>"youku.com",
						//'wasu' =>"wasu.cn",
						//'cntv' =>"cntv.cn"
					);
    	$model = $vc->getModel();
		$not_film = in_array($model, array('teleplay', 'television'))?true:false;//如果不是电影则 not_film为true
		$vc_url = $vc->getUrl();
		if($not_film)// 如果不是电影
		{
			if($model == 'teleplay')
			{
				$is_dongman = strpos($vc_url,'show_intro');
				preg_match("/.*id=(.*)&page.*/",$vc_url,$matches);
				if($matches[1] != '')
				{
					$id = intval($matches[1]);
					foreach($sites as $key => $site)
					{
						if($is_dongman === false)
							$url = "http://video.baidu.com/htvplaysingles/?id=$id&site=$site";
						else
							$url = "http://video.baidu.com/hcomicsingles/?id=$id&site=$site";
						//$content = @file_get_contents($url, false, Common::createStreamContext());
						//$content = json_decode($content);
						$ch = curl_init(); //初始化curl
						curl_setopt($ch, CURLOPT_URL, $url);//设置链接
						curl_setopt($ch, CURLOPT_HEADER, 0);//这里不要header，加块效率
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
						$response = curl_exec($ch);//接收返回信息
						if(curl_errno($ch)){//出错则显示错误信息
							print curl_error($ch);
						}
						curl_close($ch); //关闭curl链接
						$content = json_decode($response);									
						if($content->site == $site)//默认如果 只有一个pps源 不管你site是什么 都会返回pps
						{
							$VideoPlaylist = $this->saveVideoPlayList($vc,$key);//$key 就是来源 referer
							foreach($content->videos as $v)
							{
								$title           = $v->title;
								$vc_id           = $vc->getId();
								$mark            = $v->episode;
								$url             = $v->url;
								$wiki_id         = $VideoPlaylist->getWikiId();
								$videoPlaylistId = (string)$VideoPlaylist->getId();
								$referer         = $key;
								$this->saveVideo($model, $title, $url,  $wiki_id, $videoPlaylistId, $mark, $referer,$vc);
							}
							unset($content);
						}
					}
				}
			}
			if($model == 'television')
			{
				preg_match("/.*id=(.*)&page.*/",$vc_url,$matches);
				if($matches[1] != '')
				{
					$id = intval($matches[1]);
					foreach($sites as $key => $site)
					{
						echo $url = "http://video.baidu.com/htvshowsingles/?id=$id&site=$site";echo "\n";
						//$content = @file_get_contents($url, false, Common::createStreamContext());
						//$content = json_decode($content);
						$ch = curl_init(); //初始化curl
						curl_setopt($ch, CURLOPT_URL, $url);//设置链接
						curl_setopt($ch, CURLOPT_HEADER, 0);//这里不要header，加块效率
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//返回结果，而不是输出它
						$response = curl_exec($ch);//接收返回信息
						if(curl_errno($ch)){//出错则显示错误信息
							print curl_error($ch);
						}
						$info = curl_getinfo($ch);
						echo "\n";echo '获取'. $info['url'] . '耗时'. $info['total_time'] . '秒';echo "\n";
						curl_close($ch); //关闭curl链接
						$content = json_decode($response);							
						if($content->site == $site)//默认如果 只有一个pps源 不管你site是什么 都会返回pps
						{
							$VideoPlaylist = $this->saveVideoPlayList($vc,$key);//$key 就是来源 referer
							foreach($content->videos as $v)
							{
								$title           = $v->title;
								$vc_id           = $vc->getId();
								$mark            = $v->episode;
								$url             = $v->url;
								$wiki_id         = $VideoPlaylist->getWikiId();
								$videoPlaylistId = (string)$VideoPlaylist->getId();
								$referer         = $key;
								$this->saveVideo($model, $title, $url,  $wiki_id, $videoPlaylistId, $mark, $referer,$vc);
							}
							unset($content);
						}
					}
				}
				
			}
		}
		else//电影
		{
			$sites_key = array( 'baidu_qiyi'    =>"爱奇艺",
								'baidu_pptv'    =>"PPTV",
								'baidu_pps'     =>"PPS",
								'baidu_letv'    =>"乐视",
								'baidu_tencent' =>"腾讯",   
								'baidu_sohu'    =>"搜狐",
								'baidu_tudou'   =>"土豆",
								'baidu_youku'   =>"优酷",
								'baidu_sina'    =>"新浪"
								//'wasu'        =>"华数",
								//'cntv'        =>"cntv.cn",
					);	
			if($this->output)		
	        	printf("url: %s \n", $vc_url);
			preg_match("/.*id=(.*)&page.*/",$vc_url,$matches);
			if($matches[1] != '')
			{
				$id = intval($matches[1]);
				        	
				$url = "http://video.baidu.com/movie_intro/?dtype=playUrl&service=json&id=$id"; echo "\n";
				//$contents = @file_get_contents($url, false, Common::createStreamContext());
				//$contents = json_decode($contents);	   
				$ch = curl_init(); //初始化curl
				curl_setopt($ch, CURLOPT_URL, $url);//设置链接
				curl_setopt($ch, CURLOPT_HEADER, 0);//这里不要header，加块效率
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
				$response = curl_exec($ch);//接收返回信息
				if(curl_errno($ch)){//出错则显示错误信息
					print curl_error($ch);
				}
				curl_close($ch); //关闭curl链接
				$contents = json_decode($response);				     	
				foreach($contents as  $content)
				{
					if($key = array_search($content->name, $sites_key))
					{
						$vc_id   = $vc->getId();
						$title   = $vc->getTitle();
						$url     = $content->link;
						$wiki_id = $vc->getWikiId();
						$referer = $key;
						$this->saveVideo($model, $title, $url,  $wiki_id, $videoPlaylistId = false, $mark = 0, $referer ,$vc);
					}
					unset($content);
				}
			}
			unset($contents);
		}
    }
    protected function saveVideoPlayList($vc,$referer) {
    	$model = $vc->getModel();
    	$name_end = array('television'=>'栏目','teleplay'=>'电视剧');
    	$title = $vc->getTitle();
    	$query=array(
				'referer'=>$referer,
				'title'  =>$title,
			);
//		$mongo = sfContext::getInstance()->getMondongo();
		$video_playlist_repos = $this->mongo->getRepository('VideoPlaylist');
		$res = $video_playlist_repos->find(array(
			'query'=>$query
			));
		if($res) 
		{
			if($this->output)
				echo "$name_end[$model]<<".$title.">>【列表】已存在  ********不创建$referer\n"	;		
			return $res[0]; 
		}
		else
		{
			if($this->output)
	        	echo "$name_end[$model]<<".$title.">>【列表】不存在  +++++++++++需要创建$referer\n"	;
	        $VideoPlaylist = new VideoPlaylist();
	        $VideoPlaylist->setTitle($title);
	        $VideoPlaylist->setUrl($vc->getUrl());
	        $VideoPlaylist->setReferer($referer);
			$VideoPlaylist->setWikiId((string) $vc->getWikiId());
			$VideoPlaylist->setVcId((string) $vc->getId());
	
	        $VideoPlaylist->save();
	        return $VideoPlaylist;
		}   	
    }
    
    
    protected function saveVideo($model, $title, $url,  $wiki_id, $videoPlaylistId = false, $mark = 0, $referer,$vc) {
    	$name_end = array('television'=>'栏目','teleplay'=>'电视剧','film'=>'电影');
		$query=array(
				'referer'=> $referer,
				'title'  => $title,
			);
		if($videoPlaylistId)//如果是电视剧则过滤要添加mark 电影不加因为以前的电影video的qiyi没有mark字段
			$query['mark'] = (int)$mark;
		//$mongo = sfContext::getInstance()->getMondongo();print_r($query) ;exit;
		$video_repos = $this->mongo->getRepository('video');
		$res = $video_repos->find(array(
			'query'=>$query
			));
		$num = count($res);
    	if($res)
		{
			if($model=='television' || $model=='teleplay')//电视剧  栏目
			{
				if($this->output)
					echo "$name_end[$model]《".$title."》    第".$mark."集 已经存在了".$num."条，**********不创建$referer\n";
			}
			else        //电影
			{
				if($this->output)
					echo "$name_end[$model]《".$title."》      已经存在了".$num."条，*********不创建$referer\n";
			}
		}
		else
		{
			if($model=='television' || $model=='teleplay')//电视剧  栏目
			{
				if($this->output)
					echo "$name_end[$model]《".$title."》    第".$mark."集 不存在，++++++++需要创建$referer\n";
			}
			else    //电影
			{
				if($this->output)
					echo "$name_end[$model]《".$title."》      不存在，++++++++需要创建$referer\n";
			}
			$video = new video();
			$video->setModel($model);
			$video->setTitle($title);
			$video->setUrl($url);
			$video->setPublish(true);
			$video->setReferer($referer);	        
			$video->setWikiId($wiki_id);
	        $video->setMark((int)$mark);
	        $video->setVcId((string)$vc->getId());
	        if ($videoPlaylistId)  $video->setVideoPlaylistId($videoPlaylistId);
//	        if ($time) $video->setTime($time);
//	        if ($wiki instanceof Wiki) 
//	        {
//	            $video->setWikiId((string) $wiki->getId());
//	            if ($mark > 0) 
//	            {
//	                $mongo = $this->getMondongo();
//	                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
//	                $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => (int) $mark)));
//	                if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
//	            }
//	        } 
//	        else
//	        {
//	            $video->setWikiId($wiki);
//	        }
	        
	        $video->save();
						$vc->setState(1);
						$vc->save();	        
		}
    }	 	
}


?>