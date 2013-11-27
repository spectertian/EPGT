<?php
/**
 * 腾讯视频任务
 * @author luren
 */
class tencentVideoTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('type', null, sfCommandOption::PARAMETER_OPTIONAL, 'type'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'tencentVideo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tvsinaVideo|INFO] task does things.
Call it with:

  [php symfony tencentVideo|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
    	$type = $options['type'];
    	if(!in_array($type,array(1,2,3,4))) 
    	{
    		echo 'type值不对';
    		exit;
    	}
    	if($type==1)             $model='film';
    	if($type==2 || $type==3) $model='teleplay';
    	if($type==4)             $model='television';
    	
    	ini_set("max_execution_time",'90');

		$host = "3g.v.qq.com";  // 主机
		$script = "/xmredirect?typeid=$type&ver=1.0.0";  //脚本地址
		
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
		if (!$fp)
		    echo "$errstr ($errno)<br />\n";
		else {
			$header  = "GET $script HTTP/1.1\r\n";
			$header .= "Host: 3g.v.qq.com\r\n";
			$header .= "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0\r\n";
			$header .= "Referer: http://{$host}/\r\n";
			$header .= "Connection: Close\r\n\r\n";
			
			$content = '';
		
		    fwrite($fp, $header);
		    while (!feof($fp)) {
		        $content.= fgets($fp, 128);
		    }
		    fclose($fp);
			//echo $content;
		
			$true_url = '';
			preg_match("/Location: (.*)?/",$content, $matches);
			echo $true_url = $matches[1];
			$ch = curl_init(); //初始化curl
			curl_setopt($ch, CURLOPT_URL, $true_url);//设置链接
			curl_setopt($ch, CURLOPT_HEADER, 0);//这里不要header，加块效率
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
			$response = curl_exec($ch);//接收返回信息
			if(curl_errno($ch)){//出错则显示错误信息
				print curl_error($ch);
			}
			curl_close($ch); //关闭curl链接
		
			$tar_xml = simplexml_load_string($response);
			foreach ($tar_xml->items->item as $item)
			{
				$this->crawlerVideo($item,$model);
			}
		}
    }

    protected function crawlerVideo($item,$model) {
    	$title = (string)$item->title;
    	
        $mongo = $this->getMondongo();
        $wiki_repos = $mongo->getRepository('Wiki');

		$wiki = $wiki_repos->findOne(array('query' => array('slug' => Wiki::slugify($title), 'model' => $model)));
		if($wiki)
		{
			switch ($model) {
				case 'film' :
					$url = $item->playurl;
					$this->saveVideo($model, $title, $url,  $wiki);
					break;
				case 'teleplay' :
					$url_list = (string)$item->gatherurl;
					preg_match('/(.*)?\/(.*)?.html$/',$url_list,$matches);
					$zhuanji_id = $matches[2];//专辑id
					$zhuanji_id_first_word = $zhuanji_id{0};//专辑id首字母
					$VideoPlaylist = $this->saveVideoPlayList($title, $wiki, $url_list);
					foreach($item->playurls->playurl as $playurl)
					{
						$video_id = (int)$playurl->vid;//视频id
						echo $mark = (int)$playurl->id;//mark
						echo $url = "http://v.qq.com/cover/".$zhuanji_id_first_word."/".$zhuanji_id."/".$video_id.".html";echo "\n";
						$this->saveVideo($model, $title, $url,  $wiki, (string)$VideoPlaylist->getId(), $mark);
					}
					//sleep(mt_rand(3, 5));
					break;
				case 'television' :
					$url_list = (string)$item->column->url;
					$url = (string)$item->playurl;
					$mark = $this->getURLDate($url);
					$VideoPlaylist = $this->saveVideoPlayList($title, $wiki, $url_list);
					$qi_name = (string)$item->hotspot;
					$this->saveVideo($model, $qi_name, $url,  $wiki, (string)$VideoPlaylist->getId(), $mark);
					break;
			}
		}
		//sleep(mt_rand(10, 100));
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
    protected function saveVideo($model, $title, $url, $wiki, $videoPlaylistId = false,  $mark = 0) {
		@$query=array(
				'referer'=>'api_tencent',
				'title'  =>$title,
				'mark'=>$mark
			);
		$mongo = $this->getMondongo();
		$video_repos = $mongo->getRepository('video');
		$res = @$video_repos->find(array(
			'query'=>$query
			));
    	if($res)
		{
			if($mark!=0)//电视剧
				echo "电视剧《".$title."》    第".$mark."集 已经存在了，**********不创建\n";
			else        //电影
				echo "电影《".$title."》      已经存在了，*********不创建\n";
		}
		else
		{
			if($mark!=0)//电视剧
				echo "电视剧《".$title."》    第".$mark."集 不存在，++++++++需要创建\n";
			else    {    //电影
				echo "电影《".$title."》      不存在，++++++++需要创建\n";
			}
	        $video = new Video();
	        $video->setModel($model);
	        $video->setTitle($title);
	        $video->setUrl($url);
	        $video->setReferer('api_tencent');
	        $video->setPublish(true);
	        $video->setMark($mark);
	        
	        if ($videoPlaylistId)  $video->setVideoPlaylistId($videoPlaylistId);
	        //if ($time) $video->setTime($time);
	        if ($wiki instanceof Wiki) 
	        {
	            $video->setWikiId((string) $wiki->getId());
	            if ($mark > 0) 
	            {
	                $mongo = $this->getMondongo();
	                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
	                $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => (int) $mark)));
	                if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
	            }
	        } 
	        else
	        {
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
    protected function saveVideoPlayList($title, $wiki, $url) {
    	$query=array(
				'referer'=>'api_tencent',
				'title'  =>$title,
			);
		$mongo = $this->getMondongo();
		$video_playlist_repos = $mongo->getRepository('VideoPlaylist');
		$res = $video_playlist_repos->find(array(
			'query'=>$query
			));
		if($res) 
		{
			echo "电视剧<<".$title.">>列表已存在  ********不创建\n"	;		
			return $res[0]; 
		}
		else
		{
	        echo "电视剧<<".$title.">>列表不存在  +++++++++++需要创建\n"	;
	        $VideoPlaylist = new VideoPlaylist();
	        $VideoPlaylist->setTitle($title);
	        $VideoPlaylist->setUrl($url);
	        $VideoPlaylist->setReferer('api_tencent');
	
	        if ($wiki instanceof Wiki) {
	            $VideoPlaylist->setWikiId((string) $wiki->getId());
	        } else {
	            $VideoPlaylist->setWikiId($wiki);
	        }
	
	        $VideoPlaylist->save();
	        return $VideoPlaylist;
		}   	
    }
    
    public function getURLDate($url)
    {
        $result = '';
        $html = @file_get_contents($url, false, Common::createStreamContext());
        $html = explode('</head>', $html);

        if (isset($html[0])) {
            preg_match("/varietyDate:\"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) 
            {
            	$ret[1] = str_replace('-','',$ret[1]);
            	$result = $ret[1];
            }
        }

        return $result;    	
    }
    
}
