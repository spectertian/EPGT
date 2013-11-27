<?php

class tvtpsVideoTask extends sfMondongoTask
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
    $this->name             = 'tpsVideo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:importData|INFO] task does things.
Call it with:

  [php symfony tv:importData|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $mongo = $this->getMondongo();
    $wiki_repos = $mongo->getRepository('Wiki');
    $count = 100;
    $client = new nusoap_client('http://124.40.120.29/tpsv_ch?wsdl',true);
    $parameters = array("xmlString" => '<?xml version="1.0" encoding="utf-8"?><request website="http://124.40.120.29/tpsv_ch"><parameter type="GetTPSVMediaType" language="zh-CN" ><client type="nxp" id="x" keytoken="x" keytype="x" /><user type="Normal" id="x" keytoken="x" keytype="x" /></parameter></request>');
    $array = $client->call('IPTV2',$parameters);
    $_tcl_cate = $array['IPTV2Result']['response']['server']['class'];
    $lenght = count($_tcl_cate);
    foreach($_tcl_cate as $key => $tcate)
    {	
      if(!isset($tcate['subclass'])) {
        //echo iconv("UTF-8","GB2312",$tcate['!title']."\n");    
        $contentnum = max(0,$tcate['!contentnum']);
        $totalpage = ceil($contentnum/$count);
        for($page = 1; $page <= $totalpage; $page ++)
        {
          $xmlstring = '<?xml version="1.0" encoding="utf-8"?><request website="http://124.40.120.29/tpsv_ch"><parameter type="GetTPSVListByCategory" language="zh-CN" ><client type="nxp" id="x" keytoken="x" keytype="x" /><user type="Normal" id="x" keytoken="x" keytype="x" /><class id="'.$tcate['!id'].'" subid="" page="'.$page.'" count="'.$count.'" /></parameter></request>';
          $title = ($totalpage > 1) ? $tcate['!title']."(".$page.")" : $tcate['!title'];
          $filestrings[] = array("xmlstring" => $xmlstring,'title' => $title);
        }
      }else {
        foreach($tcate['subclass'] as $skey => $scate) {
          //echo iconv("UTF-8","GB2312",$tcate['!title']."  -> ".$scate['!title']."\n");          
          $contentnum = max(0,$scate['!contentnum']);
          $totalpage = ceil($contentnum/$count);
          for($page = 1; $page <= $totalpage; $page ++)
          {
            $xmlstring = '<?xml version="1.0" encoding="utf-8"?><request website="http://124.40.120.29/tpsv_ch"><parameter type="GetTPSVListByCategory" language="zh-CN" ><client type="nxp" id="x" keytoken="x" keytype="x" /><user type="Normal" id="x" keytoken="x" keytype="x" /><class id="'.$tcate['!id'].'" subid="'.$scate['!subid'].'" page="'.$page.'" count="'.$count.'" /></parameter></request>';		
            $title = ($totalpage > 1) ? $tcate['!title']." ==> ".$scate['!title']."(".$page.")" : $tcate['!title']." ==> ".$scate['!title'];
            $filestrings[] = array("xmlstring" => $xmlstring,'title' => $title);
          }
        }
      }
    }
    unset($client);
    $iii = 0;
    $ooo = 0;
    $totalres = count($filestrings)*$count;
    foreach($filestrings as $filestring) {
      echo  "".$filestring['title']."\n";
      $client = new nusoap_client('http://124.40.120.29/tpsv_ch?wsdl',true);
      $array = $client->call('IPTV2',array("xmlString" => $filestring['xmlstring']));
      unset($client);
      $medias = $array['IPTV2Result']['response']['server']['media'];								
      if(is_array($medias) and !isset($medias[0]))
      {
        unset($medias1);
        $medias1[0] =  $medias;
        unset($medias);
        $medias = $medias1;
        unset($medias1);
      }
      foreach($medias as $media)
      {
        $iii ++;
        $wikis = $wiki_repos->find(array('query' => array('title' => Wiki::slugify($media['!title']))));
        echo Wiki::slugify($media['!title'])."找到了".count($wikis)."个";
		unset($type);
        if($wikis) 
        { 
			foreach($wikis as $k=>$v)
			{
        		$type[$k] = $v->getModel(); 	
			}
			$ooo ++;
			echo "      ".$media['!title']."+++++++++++\n";
          
			if(isset($media['down']['url'][0]))
			{
				$key = array_search('teleplay', $type);
				if($key!==false){
					echo $media['!title'].$wikis[$key]->getId()."是电视剧\n";
					$title = $media['!title'];
					$VideoPlaylist = $this->saveVideoPlayList($title, $wikis[$key]);
					echo $VideoPlaylist->getId();
					foreach($media['down']['url'] as $url)
					{
						$model = "teleplay";
						$downurl = $url['!url'];
						$url['!id'] = $url['!id'] ? $url['!id'] : 0;
						$config = array("tpsid" => $url['!id']);
						$this->saveVideo($model, $title, $downurl, $config, $wikis[$key], $VideoPlaylist->getId(), $url['!ci']);
					}
			}
          }
          else
          {
          	$key = array_search('film', $type);
			if($key!==false){
          	echo $media['!title'].$wikis[$key]->getId()."是电影\n";
            $model = "film";
            $title = $media['!title'];
            $url = $media['down']['url']['!url'];
            $media['down']['url']['!id'] = $media['down']['url']['!id'] ? $media['down']['url']['!id'] : 0;
            $config = array("tpsid" => $media['down']['url']['!id']);
            $this->saveVideo($model, $title, $url, $config, $wikis[$key]);
          }	
          }
        }
        else
        { 
            //如果没有wiki如何处理？
            echo "      ".$media['!title']."-----------\n";
        }
      }
    } 
    echo "共有".$iii."个电影，导入了".$ooo."个。"; 	
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
		$query=array(
				'referer'=>'tps',
				'config.tpsid' =>$config['tpsid'],
				'title'  =>$title,
			);
		$mongo = $this->getMondongo();
		$video_repos = $mongo->getRepository('video');
		$res = $video_repos->find(array(
			'query'=>$query
			));
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
}


?>