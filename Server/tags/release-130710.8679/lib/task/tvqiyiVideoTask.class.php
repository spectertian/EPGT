<?php
/**
 * 奇艺爬取视频任务
 * @author luren
 */
class tvqiyiVideoTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'qiyiVideo';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tvqiyiVideo|INFO] task does things.
Call it with:

  [php symfony tvqiyiVideo|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
       $channels = array(
                    'film',
                    'teleplay',
                   // 'dongman',
                   // 'jilu',
                   // 'zongyi',
                   // 'yinyue',
                   // 'travel',
                   // 'yule',
                );
       foreach ($channels as $channel) {
           $this->crawlerQiyi($channel);
       }
  }

    /**
     * 奇艺视频采集
     * @param <type> $channel
     * @param <type> $pages
     */
    protected function crawlerQiyi($channel) 
    {
        $mongo = $this->getMondongo();
        $wiki_repos = $mongo->getRepository('Wiki');

        for ($page = 1; $page <= 8; $page++) {
            $list = $this->crawlerQiyiListHtml($channel, $page);
            $models = array('film', 'teleplay', 'television');
            $model = in_array($channel, $models) ? $channel : 'television';

            if (!empty($list)) {
                foreach ($list as $item) {
                    preg_match('|<a  href="(.*)" class="title">(.*)</a>|', $item, $matches);
                    if ($matches) {
                        $title = isset($matches[2]) ? $matches[2] : '';
                        $item_url = isset($matches[1]) ? $matches[1] : '';
                        $wiki = $wiki_repos->findOne(array('query' => array('slug' => Wiki::slugify($title), 'model' => $model)));
                        if($wiki) {
	                        switch($channel) {
	                            case 'film' :
	                                    $config = $this->qiyiAnalysis($item_url);
	                                    $this->saveVideo($model, $title,$item_url, $config, $wiki);
	                                break;
	                            case 'teleplay' :
	                                $VideoPlaylist = $this->saveVideoPlayList($title, $wiki, $item_url);
	                                $tvList = $this->crawlerTeleplayListHtml($item_url);
	                                foreach ($tvList as $tvitem) {
	                                    preg_match('#<a href="(.*?)" .*?\n<img.*? title="(.*?)" alt.*?>\n<.*?>([\d|:]+).*?\n<.*?<a.*?>.*?(\d+).*?</a>#i', $tvitem, $tvmatches);
	                                    if ($tvmatches) {
	                                        $url = isset($tvmatches[1]) ? $tvmatches[1] : '';
	                                        $title = isset($tvmatches[2]) ? $tvmatches[2] : '';
	                                        $time = isset($tvmatches[3]) ? $tvmatches[3] : '';
	                                        $mark = isset($tvmatches[4]) ? $tvmatches[4] : false;
	                                        $config =  $this->qiyiAnalysis($url);
	                                        $this->saveVideo($model, $title, $url, $config, $wiki, (string)$VideoPlaylist->getId(), $time, $mark);
	                                    }
	                                }
	                                sleep(mt_rand(10, 100));
	                                break;
	                            case 'zongyi' :
	                                preg_match('#<span class="imgBg1C">([\d|\-]+).*</span>#i', $item, $matches);
	                                $mark = isset($matches[1]) ? str_replace('-', '', $matches[1]) : date('Ymd', time());
	                                $config = $this->qiyiAnalysis($item_url);
	                                $this->saveVideo($model, $title,$item_url, $config, $wiki, false, 0, $mark);
	                                break;
	                        }
                    	}
                    	else
                    	{
                    		echo "WIKI <<".Wiki::slugify($title).">> 没找到\n";
                    	}
                    }
                }
            }
            sleep(mt_rand(10, 100));
        }
    }

    /**
     * 爬取奇艺网视频列表页
     * @param <type> $channel
     * @param <type> $page
     * @return <array> 视频列表 html
     */
    private function crawlerQiyiListHtml($channel, $page) {
        $url = $list = '';
        $date = date("Y");
        switch ($channel) {
            case 'film' :  //电影
                $url = 'http://list.qiyi.com/www/1/-----------'.$date.'-5-1-'.$page.'-1---.html';
                break;
            case 'teleplay' : // 电视剧
                $url = 'http://list.qiyi.com/www/2/-----------'.$date.'-5-1-'.$page.'-1---.html';
                break;
            case 'dongman' : //动漫
                $url = 'http://list.qiyi.com/www/4/------------5-1-'.$page.'-1---.html';
                break;
            case 'jilu' : //纪录片
                $url = 'http://list.qiyi.com/www/3/------------5-1-'.$page.'----.html';
                break;
            case 'zongyi' : //综艺
                $url = 'http://list.qiyi.com/www/6/------------2-1-'.$page.'-1---.html';
                break;
            case 'yinyue' : //音乐
                $url = 'http://list.qiyi.com/www/5/------------5-1-'.$page.'----.html';
                break;
            case 'travel' : //旅游
                $url = 'http://list.qiyi.com/www/9/------------2-1-'.$page.'----.html';
                break;
            case 'yule' : //娱乐
                $url = 'http://list.qiyi.com/www/7/------------2-1-'.$page.'----.html';
                break;
            default :
                return array();
        }
        
        printf("1 url: %s \n", $url);
        $html = @file_get_contents($url, false, Common::createStreamContext());
        $htmlArray = explode('<div class="list0">', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode('</ul>', $htmlArray[1]);
        $html = array_shift($htmlArray);
        $list = explode('</li>', $html);
        return $list;
    }

     /**
     * 爬取电视剧分集列表页 HTML
     * @param <type> $url
     * @return <array> 分集列表 html
     */
    protected function crawlerTeleplayListHtml($url) 
    {
        printf("3 url: %s \n", $url);
        $html = @file_get_contents($url,false, Common::createStreamContext());
        $htmlArray = explode('<div id="j-album-1"', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode('<div id="j-desc-1"' , $htmlArray[1]);
        preg_match_all('|none;">(.*)</div>|', $htmlArray[0], $matches);
        $desc_list = $video_list = array();

        if ($matches) {
            $i = 1;
            foreach ($matches[1] as $match) {
                $url = 'http://www.qiyi.com'. $match;
                $listhtml = @file_get_contents($url,false, Common::createStreamContext());
                $listArray = explode('<li>', $listhtml);
                array_shift($listArray);
                foreach ($listArray as $list) {
                    $video_list[] = $list;
                }

                $desc_urls[] = str_replace(sprintf('_%d.inc', $i), sprintf('_desc_%d.inc', $i) , $url); //剧情列表 url
                $i++;
            }
        }
        
        return $video_list;
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
    protected function saveVideo($model, $title, $url, $config, $wiki, $videoPlaylistId = false, $time = 0, $mark = 0) 
    {
		@$query=array(
				'referer'=>'qiyi',
				'config.pid' =>$config['pid'],
				'title'  =>$title,
			);
		$mongo = $this->getMondongo();
		$video_repos = $mongo->getRepository('video');
		$res = @$video_repos->find(array(
			'query'=>$query
			));
    	if($res) {
			if($mark!=0)//电视剧
				echo "电视剧《".$title."》    第".$mark."集 已经存在了，**********不创建\n";
			else        //电影
				echo "电影《".$title."》      已经存在了，*********不创建\n";
		} else {
			if($mark!=0)//电视剧
				echo "电视剧《".$title."》    第".$mark."集 不存在，++++++++需要创建\n";
			else {    //电影
				echo "电影《".$title."》      不存在，++++++++需要创建\n";
			}
	        $video = new Video();
	        $video->setModel($model);
	        $video->setTitle($title);
	        $video->setUrl($url);
	        $video->setConfig($config);
	        $video->setReferer('qiyi');
	        $video->setPublish(true);
	        
	        if ($videoPlaylistId)  $video->setVideoPlaylistId($videoPlaylistId);
	        if ($time) $video->setTime($time);
	        if ($mark > 0) $video->setMark($mark);
	        if ($wiki instanceof Wiki) {
	            $video->setWikiId((string) $wiki->getId());
	            if ($mark > 0) {
	                $mongo = $this->getMondongo();
	                $wikiMetaRepos = $mongo->getRepository('wikiMeta');
	                $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => (int) $mark)));
	                if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
	            }
	        } else {
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
    protected function saveVideoPlayList($title, $wiki, $url) 
    {
    	$query=array(
				'referer'=>'qiyi',
				'title'  =>$title,
			);
		$mongo = $this->getMondongo();
		$video_playlist_repos = $mongo->getRepository('VideoPlaylist');
		$res = $video_playlist_repos->find(array(
			'query'=>$query
			));
		if($res) {
			echo "电视剧<<".$title.">>列表已存在  ********不创建\n"	;		
			return $res[0]; 
		} else {
	        echo "电视剧<<".$title.">>不存在  +++++++++++需要创建\n"	;
	        $VideoPlaylist = new VideoPlaylist();
	        $VideoPlaylist->setTitle($title);
	        $VideoPlaylist->setUrl($url);
	        $VideoPlaylist->setReferer('qiyi');
	
	        if ($wiki instanceof Wiki) {
	            $VideoPlaylist->setWikiId((string) $wiki->getId());
	        } else {
	            $VideoPlaylist->setWikiId($wiki);
	        }
	
	        $VideoPlaylist->save();
	        return $VideoPlaylist;
		}   	
    }
    
    /**
     * 分析播放数据
     * @param <type> $url
     * @return <type>
     */
    protected function qiyiAnalysis($url) 
    {
        $result = array();
        printf("2 url: %s \n", $url);
        $html = @file_get_contents($url, false, Common::createStreamContext());
        $html = explode('</script>', $html);

        if (isset($html[0])) {
            preg_match("/pid : \"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['pid'] = $ret[1];
            preg_match("/ptype : \"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['ptype'] = $ret[1];
            preg_match("/videoId : \"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['videoId'] = $ret[1];
            preg_match("/albumId : \"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['albumId'] = $ret[1];
            preg_match("/tvId : \"(.*?)\",/",  $html[0], $ret);
            if (isset($ret[1])) $result['tvId'] = $ret[1];
        }

        return $result;
    }
}

