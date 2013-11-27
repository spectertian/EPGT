<?php
/**
 * 供后台维基模块抓取单个视频使用
 * @author wn
 *
 */
class onevideocrawlerTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('url', null, sfCommandOption::PARAMETER_OPTIONAL, 'what url???????', ''),
            new sfCommandOption('wiki_id', null, sfCommandOption::PARAMETER_OPTIONAL, 'what wiki_id???????', ''),
            new sfCommandOption('dongman', null, sfCommandOption::PARAMETER_OPTIONAL, 'what wiki_id???????', ''),
            // add your own options here
        ));

        $this->namespace    = 'tv';
        $this->name         = 'onevideocrawler';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:onevideocrawler|INFO] task does things.
Call it with:
    [php symfony tv:onevideocrawler|INFO]
EOF;
    }

	protected function execute($arguments = array(), $options = array())
	{
		global $argv;
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');

		if (isset($options['wiki_id'])) 
		{
			$wiki_id = $options['wiki_id'];
			$url = $options['url'];
			$dongman = $options['dongman'];
			$wiki = $wiki_repository->findOneById(new MongoId($wiki_id));
			//exec("php ".$cmd." --wiki_id=".$wiki->getId());echo "\n";
		                        $result = 0;
                        switch($wiki->getModel()) {
                                case 'film':
                                        if (false !== strpos($url, 'qiyi')) {
                                                if ($config = $this->qiyiAnalysis($url,'film',$dongman)) {
                                                        $this->saveFilmVideo($wiki, 'qiyi', $url, $config);
                                                        $result = 1;
                                                }                    
                                        } elseif (false !== strpos($url, 'sina')){
                                                if ($config = $this->sinaAnalysis($url)) {
                                                        $this->saveFilmVideo($wiki, 'sina', $url, $config);
                                                        $result = 1;
                                                }
                                        } elseif (false !== strpos($url, 'youku')){
                                                if ($config = $this->youkuAnalysis($url)) {
                                                        $this->saveFilmVideo($wiki, 'youku', $url, $config);
                                                        $result = 1;
                                                }                                         
                                        } elseif (false !== strpos($url, 'sohu')){
                                                if ($config = $this->sohuAnalysis($url)) {
                                                        $this->saveFilmVideo($wiki, 'sohu', $url, $config);
                                                        $result = 1;
                                                } 
                                        }                                        
                                        break;
                                case 'teleplay':
                                        if (false !== strpos($url, 'qiyi')) {
                                                if ($this->crawlerQiyiTeleplay($url, $wiki)) $result = 1;                    
                                        } elseif (false !== strpos($url, 'sina')){
                                                if ($this->crawlerSinaTeleplay($url, $wiki)) $result = 1;
                                        } elseif (false !== strpos($url, 'youku')){
                                                if ($this->crawlerYoukuTeleplay($url, $wiki)) $result = 1;        
                                        } elseif (false !== strpos($url, 'sohu')){
                                                if ($this->crawlerSohuTeleplay($url, $wiki)) $result = 1;    
                                        }                                                 
                                        break;
                                default: 
                                        $result = 0;
                        }			
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
            $html = file_get_contents($url, false, Common::createStreamContext());
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
            if (isset($ret[1]))    $result['vid'] = $ret[1];
            preg_match("|ipad_vid:\'(.*?)\',|",$html, $ret);
            if (isset($ret[1]))    $result['ipad_vid'] = $ret[1];
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
        if (isset($ret[1]))    $result['vid'] = $ret[1];
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
        if (isset($ret[1]))    $result['id'] = $ret[1];

        return $result;
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
        $VideoPlaylist = new VideoPlaylist();
        $VideoPlaylist->setTitle($title);
        $VideoPlaylist->setUrl($url);
        $VideoPlaylist->setReferer($referer);
        $VideoPlaylist->setWikiId($wiki_id);
        $VideoPlaylist->save();
        return $VideoPlaylist;
    }        
        
    /**
     * 电影视频保存
     * @param <type> $config
     * @param <type> $referer
     * @param Wiki $wiki
     * @return void
     * @author luren
     */
    protected function saveFilmVideo($wiki, $referer, $url, $config) {
        $video = new Video();
        $video->setTitle($wiki->getTitle());                
        $video->setWikiId((string)$wiki->getId());
        $video->setModel($wiki->getModel());                
        $video->setUrl($url);
        $video->setConfig($config);
        $video->setReferer($referer);
        $video->setPublish(true);
        $video->save();         
        $wiki->setHasVideo(true);
        $wiki->save();
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
    protected function saveTeleplayVideo($wiki, $title, $url, $referer, $config, $videoPlaylistId, $time = 0, $mark = 0) {
        $video = new Video();
        $video->setWikiId((string) $wiki->getId());
        $video->setModel($wiki->getModel());
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
            $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => (int) $mark)));
            if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
        }
        $video->save();     
        $wiki->setHasVideo(true); 
        $wiki->save();
    } 
     
    /**
     * 爬取奇艺电视剧视频
     * @param string $url
     * @param Wiki $wiki
     * @return type
     * @author luren 
     * @author2 tianzhongsheng-ex@huan.tv diff by 2013-07-30 15:43:00
     */
    protected function crawlerQiyiTeleplay($url, Wiki $wiki)
	{
        $html = file_get_contents($url,false, Common::createStreamContext());
        $http = "http://www.iqiyi.com";
        $pattern = "/\/common\/topicinc\/(.*)_(.*)\/playlist_(.*).inc/i";
        preg_match_all($pattern,$html,$matches);
        unset($html);
        $list = $matches[0];
        if(count($list)>0)
        {
        	//删除原来的奇艺视频
            $mongo = $this->getMondongo();
            $PlayListRepository = $mongo->getRepository('VideoPlaylist');
            $PlayListRepository->deleteVideos((string) $wiki->getId(), 'qiyi');
            $VideoPlayList = $this->saveVideoPlayList($wiki->getTitle(), (string) $wiki->getId(), $url, 'qiyi');
        	foreach($list as $k => $v)
        	{
        		//重新采集视频并保存
        		$childurl = $http.$v;
        		$childHtml = file_get_contents($childurl,false, Common::createStreamContext());
    
        		$pattern = '/href=\"(.*?)\" .*?\n<img.*? title="(.*?)" alt=.*?(\d+).*? class=.+?>.*?\n<span.*?\"s2\">([\d|:]+)<\/span> <\/a>/';
        		preg_match_all($pattern, $childHtml, $matches);
        		unset($childHtml);
        		array_shift($matches);
				$counts = count($matches[0]);
				if($counts<1)continue;
        		for($i=0; $i < $counts;$i++ )
        		{
        			$tvurl = $matches[0][$i];
        			$title = $matches[1][$i]; 
        			$mark = $matches[2][$i]; 
        			$time = $matches[3][$i]; 
        			$config = $this->qiyiAnalysis($tvurl,'teleplay');
					$this->saveTeleplayVideo($wiki, $title, $tvurl, 'qiyi', $config, (string)$VideoPlayList->getId(), $time, $mark);
        		}
        		
        		
        	}
        	return true;
        }   
        return false;
    }         
    
    /**
     * 爬取新浪电视剧视频
     * @param type $url
     * @param Wiki $wiki
     * @return type 
     * @author luren
     */
    protected function crawlerSinaTeleplay($url, Wiki $wiki) {
        $html = file_get_contents($url, false, Common::createStreamContext());
        $htmlArray = explode('<div class="list_demand" id="T_1">', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode("<!-- 分集点播 end-->", $htmlArray[1]);
        $html = array_shift($htmlArray);
        $list = explode('</li>', $html);
        if ($list) {
            //删除原来的奇艺视频
            $mongo = $this->getMondongo();
            $PlayListRepository = $mongo->getRepository('VideoPlaylist');
            $PlayListRepository->deleteVideos((string) $wiki->getId(), 'sina');
            
            //重新采集视频并保存
            $VideoPlayList = $this->saveVideoPlayList($wiki->getTitle(), (string) $wiki->getId(), $url, 'sina');                        
            foreach ($list as $item) {
                $item = preg_replace('/\s+/s', '', $item);
                preg_match('|</div><ahref="(.*)"target.*?rel="(\d+)">|', $item, $tvmatches);
                if ($tvmatches) {
                    $tvurl = isset($tvmatches[1]) ? 'http://video.sina.com.cn'.$tvmatches[1] : '';
                    $tvtitle = isset($tvmatches[2]) ? $wiki->getTitle() .'第'. $tvmatches[2] .'集' : '';
                    $mark = isset($tvmatches[2]) ? $tvmatches[2] : false;
                    $config = $this->sinaAnalysis($tvurl);
                    $this->saveTeleplayVideo($wiki, $tvtitle, $tvurl, 'sina', $config, (string)$VideoPlayList->getId(), 0, $mark);
                }
            }    
            
            return true;
        }
            
        return false;
    }        
    
    /**
     * 爬取搜狐电视剧视频
     * @param type $url
     * @param Wiki $wiki
     * @return type 
     * @author luren
     */
    protected function crawlerSohuTeleplay($url, Wiki $wiki) {
        $html = file_get_contents($url,false, Common::createStreamContext());
        $htmlArray = explode('<div id="similarLists"', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode("<!--for(;nowpage<=count;nowpage++)", $htmlArray[1]);
        $html = array_shift($htmlArray);
        $html = iconv('GB18030', 'UTF-8//IGNORE', $html);
        $list = explode('</li>', $html);
        
        if ($list) {
            $mongo = $this->getMondongo();
            $PlayListRepository = $mongo->getRepository('VideoPlaylist');
            $PlayListRepository->deleteVideos((string) $wiki->getId(), 'sohu');
            
            //重新采集视频并保存
            $VideoPlayList = $this->saveVideoPlayList($wiki->getTitle(), (string) $wiki->getId(), $url, 'sohu');    
            $mark = 1;
            foreach ($list as $tvitem) {
                preg_match('|<span><a target=_blank href= \'(.*)\' >(.*)</a>|', $tvitem, $tvmatches);
                if ($tvmatches) {
                    $tvurl = isset($tvmatches[1]) ? $tvmatches[1] : '';
                    $tvtitle = isset($tvmatches[2]) ? trim($tvmatches[2]) : '';
                    $config = $this->sohuAnalysis($tvurl);
                    $this->saveTeleplayVideo($wiki, $tvtitle, $tvurl, 'sohu', $config, (string)$VideoPlayList->getId(), 0, $mark);                                        
                }
                $mark++;
            }                        

            return true;
        }
        
        return false;
    }        
    
    /**
     * 爬取优酷电视剧视频
     * @param type $url
     * @param Wiki $wiki
     * @return type 
     * @author luren
     */
    protected function crawlerYoukuTeleplay($url, Wiki $wiki) {
        preg_match('/id_(.+)\.html/', $url, $tv_url_match);
        if (isset($tv_url_match[1])) {         
            $mongo = $this->getMondongo();
            $PlayListRepository = $mongo->getRepository('VideoPlaylist');
            $PlayListRepository->deleteVideos((string) $wiki->getId(), 'youku');
            //重新采集视频并保存
            $VideoPlayList = $this->saveVideoPlayList($wiki->getTitle(), (string) $wiki->getId(), $url, 'youku');                                

            //循环采集电视分集
            for($i = 1; $i < 10; $i++) {
                $tv_list_url = sprintf('http://www.youku.com/show_eplist/showid_%s_page_%d.html', $tv_url_match[1], $i);
                $html = file_get_contents($tv_list_url,false, Common::createStreamContext());
                $htmlArray = explode('<div class="items">', $html);
                if (empty($htmlArray[1])) return true;
                $htmlArray = explode('<div class="qPager">', $htmlArray[1]);
                $html = array_shift($htmlArray);
                $list = explode('</ul>', $html);

                foreach ($list as $tvitem) {
                    $tvitem = preg_replace('/\s+/s', '', $tvitem);
                    preg_match('#<spanclass="num">([\d|:]+)</span>.*href="(.*)"t.*?>(.*)</a>#s', $tvitem, $tvmatches);
                    if (isset($tvmatches[1])) {
                        $time = isset($tvmatches[1]) ? $tvmatches[1] : '';
                        $tvurl = isset($tvmatches[2]) ? $tvmatches[2] : '';
                        $tvtitle = isset($tvmatches[3]) ? $tvmatches[3] : '';
                        preg_match('/id_(.+)\.html/', $tvurl, $tvmatch);
                        if (isset($tvmatch[1])) $config['id'] = $tvmatch[1];
                        preg_match('#^.*[^\d+](\d+)$#i', $tvtitle, $tvmark);
                        if (isset($tvmark[1])) $mark = $tvmark[1];
                        $this->saveTeleplayVideo($wiki, $tvtitle, $tvurl, 'youku', $config, (string)$VideoPlayList->getId(), $time, $mark);
                    }
                }
            }
        }        
        return false;             
    }        
}