<?php

/**
 * video actions.
 *
 * @package    epg
 * @subpackage video
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class videoActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $this->pageTitle    = '视频列表';
        $this->q = $request->getParameter('q', '');
        $this->pager = new sfMondongoPager('Wiki', 20);
        $this->pager->setFindOptions(array(
                                    'query' => array(
                                            'has_video' => array('$gt' => 0),
                                            'title' => new MongoRegex("/^".trim($this->q).".*?/im")
                                        )
                                    )
                                );
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    /**
     * 临时视频数据管理
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeTemp(sfWebRequest $request) {
        $sites = array(
                    'vod' => 'yang.com',
                    'pptv'=> '2A08_003',
                    '1905'=> '1905yy00',
                );
        
        $models = array('film', 'teleplay', 'television');
        $site = $request->getParameter('site', 'vod');
        $this->site = $sites[$site];
        $this->model = $request->getParameter('model', 'film');
        $this->q = trim($request->getParameter('q', ''));
        if (key_exists($site, $sites)) {
            if ('teleplay' == $this->model) {
                $this->showCollectionNumber = "yes";
                $this->pageTitle = sprintf('[ %s ] 电视剧视频管理', $sites[$this->site]);
                $this->video = new sfMondongoPager('VideoPlaylist', 20);
                $this->video->setFindOptions(array('query' => array('referer' => $this->site, 'title' => new MongoRegex("/.*". $this->q .".*/i")), 'sort' => array('created_at' => -1)));
            } elseif('film' == $this->model) {
                $this->pageTitle = sprintf('[ %s ] 电影视频管理', $sites[$this->site]);
                $this->video = new sfMondongoPager('Video', 20);
                $this->video->setFindOptions(array('query' => array('referer' => $this->site, 'title' => new MongoRegex("/^". $this->q .".*?/im"), 'model' => "film", 'mark' => array('$exists' => false)), 'sort' => array('created_at' => -1)));
            }else{
                $this->show_mark = "yes";
                $this->pageTitle = sprintf('[ %s ] 栏目视频管理', $sites[$this->site]);
                $this->video = new sfMondongoPager('Video', 20);
                $this->video->setFindOptions(array('query' => array('referer' => $this->site, 'title' =>  new MongoRegex("/.*". $this->q .".*/i"), 'model' => "television"), 'sort' => array('created_at' => -1)));
            }
            
            if ($this->q){
                 $this->video->setPage($request->getParameter(1));
            } else {
                 $this->video->setPage($request->getParameter('page', 1));
            }
           
            $this->video->init();
        } else {
            $this->redirect('video/index');
        }
    }
    
    /**
     * 单个维基所有视频列表页
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeShow(sfWebRequest $request) {
        $wiki_id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $wikiRepos = $mongo->getRepository('wiki');
        $this->wiki = $wikiRepos->findOneById(new MongoId($wiki_id));
        if (!$this->wiki) $this->redirect404();
        $this->pageTitle  = sprintf('%s 视频列表', $this->wiki->getTitle());
        $this->pager = new sfMondongoPager('Video', 20);
        $this->pager->setFindOptions(array('query' => array('wiki_id' => $wiki_id), 'sort' => array('mark' => 1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    /**
     * 视频删除
     * @param sfWebRequest $request
     * @author luren
     */
    public function executeDelete(sfWebRequest $request) {
        $url=$request->getReferer();
        $ids = $request->getParameter('id');
        $ref = $request->getParameter('ref','none');
        $mongo = $this->getMondongo();
        if($ref == 'tps')
        {
        	$repository = $mongo->getRepository('Video');
        	if (!empty($ids))
        	{
                $video = $repository->findOneById(new MongoId($ids));
                $video->delete();
        	}
                $this->getUser()->setFlash('notice', '已删除所选择的视频!');
        }
        else
        {
	        if ($request->getParameter('model') == 'teleplay') 
	        {
	            $repository = $mongo->getRepository('VideoPlaylist');
	        } 
	        else 
	        {
	            $repository = $mongo->getRepository('Video');
	        }
	        
	        if (!empty($ids)) 
	        {
	            if (is_array($ids)) 
	            {
	                foreach ($ids as $id) 
	                {
	                    $video = $repository->findOneById(new MongoId($id));
	                    if(!empty ($video))  $video->delete(); 
	                }
	            }
	            elseif(is_string($ids)) 
	            {
	                $video = $repository->findOneById(new MongoId($ids));
	                $video->delete();
	            }
	            
	             $this->getUser()->setFlash('notice', '已删除所选择的视频!');
	        }
        }   
        $this->redirect($url);
    }

    /**
     * @param sfWebRequest $request
     * ajax 加载维基
     */
    public function executeLoadWiki(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $query = $request->getPostParameter('query');
            $mongo = $this->getMondongo();
            $wikiRep = $mongo->getRepository('Wiki');
            $this->wikis = $wikiRep->likeWikiName(trim($query));            
        } else {
            return $this->forward404();
        }
    }

    
     /**
     * ajax 视频修改保存
     * @param sfWebRequest $request
     * @return <type>
     * @author luren
     */
    public function executeAjaxsave(sfWebRequest $request){
        if ($request->isXmlHttpRequest()) {
            $model = $request->getParameter('model', 'film');
            $mongo = $this->getMondongo();
            $videoRepos = $mongo->getRepository('video');
            $wikiRepos = $mongo->getRepository('wiki');
            $id = $request->getParameter('id');  //视频 id
            $wiki_id = $request->getParameter('wiki_id');  //修改视频关联的 wiki_id
            $wiki = $wikiRepos->findOneById(new MongoId($wiki_id));
            
            //如果长度不是24，即非wiki  id
            if(strlen($wiki_id)!=24){
                return $this->renderText($wiki_id);
            }
            
            if ($wiki) {
                $has_video = ($wiki->getHasVideo()) ? $wiki->getHasVideo() : 0 ;
                if ('teleplay' == $model) {        // 电视剧视频
                    $videos = $videoRepos->getVideosByPlaylistId($id);        // 获取所有该维基视频
                    if ($videos) {
                        $wikiMetaRepos = $mongo->getRepository('wikiMeta');
                        foreach($videos as $video) {
                            $old_wiki =  $wikiRepos->findOneById(new MongoId($video->getWikiId()));
                            if ($old_wiki) {
                                $old_wiki->setHasVideo(false);  //原始维基视频数减一
                                $old_wiki->save();
                            }
                            $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => $video->getMark())));
                            // 电视剧关联分集剧情介绍 wikiMeta
                            if ($wikiMeta) $video->setWikiMataId((string) $wikiMeta->getId());
                            // 电视剧关联维基
                            $video->setWikiId((string) $wiki->getId());
                            $video->save();
                            $has_video++;
                        }
                    }
           
                    // 电视剧视频临时表关联维基 确定显示正确
                    $videoPlaylistRepos = $mongo->getRepository('VideoPlaylist');
                    $VideoPlay = $videoPlaylistRepos->findOneById(new MongoId($id));
                    $VideoPlay->setWikiId((string) $wiki->getId());
                    $VideoPlay->save();
                }elseif( $model == 'television'){
                    $video = $videoRepos->findOneById(new MongoId($id));
                    $old_wiki =  $wikiRepos->findOneById(new MongoId($video->getWikiId()));
                    if ($old_wiki) {
                        $old_wiki->setHasVideo(false);  //原始维基视频数减一
                        $old_wiki->save();
                    }
                    
                    $video->setWikiId((string) $wiki->getId());
                    $wikiMetaRepos = $mongo->getRepository('wikiMeta');
                    $wikiMeta = $wikiMetaRepos->findOne(array('query' => array('wiki_id' => (string) $wiki->getId(), 'mark' => $video->getMark())));
                    if ($wikiMeta) {
                        $video->setWikiMataId((string) $wikiMeta->getId());
                        $video->save();
                        $has_video+= 1;
                    }else{
                        return $this->renderText('没有该条栏目分期, 请添加期数应为'.$video->getMark().'的数据');
                    }
                    
                } else {  // 电影视频只需关联上维基
                    $video = $videoRepos->findOneById(new MongoId($id));
                    $old_wiki =  $wikiRepos->findOneById(new MongoId($video->getWikiId()));
                    if ($old_wiki) {
                        $old_wiki->setHasVideo(false);  //原始维基视频数减一
                        $old_wiki->save();
                    }

                    $video->setWikiId((string) $wiki->getId());
                    $video->save();
                    $has_video+= 1;
                }

                $wiki->setHasVideo($has_video);
                $wiki->save();

                return $this->renderText($wiki->getTitle(). '|' . $wiki->getDisplayName());
            } else {
                return $this->renderText('没有该条维基 ..');
            }
        } else {
            $this->redirect404();
        }
    }
    
    /**
     * 执行单个视频采集
     * @param sfWebRequest $request 
     * @author luren
     */
    public function executeCrawler(sfWebRequest $request) {
        $url = $request->getParameter('url');
        $wiki_id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));

        if (!$url) {
            return $this->renderPartial('crawler_video', array('wiki' => $wiki));
        } else {
            $result = 0;
            switch($wiki->getModel()) {
                case 'film':
                    if (false !== strpos($url, 'qiyi')) {
                        if ($config = $this->qiyiAnalysis($url)) {
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

            return $this->renderText($result);
        }
    }


    /**
     * 添加VideoPlayList
     * @param sfWebRequest $request 
     * @author lifucang
     */
    public function executeAddVideoPlayList(sfWebRequest $request) {
        $url = $request->getParameter('url');
        $wiki_id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));

        if (!$url) {
            return $this->renderPartial('videoplaylist', array('wiki' => $wiki));
        } else {
            if(strpos($url, 'qiyi')){
                $referer='qiyi';
            }elseif(strpos($url, 'sina')){
                $referer='sina';
            }elseif(strpos($url, 'youku')){
                $referer='youku';
            }elseif(strpos($url, 'sohu')){
                $referer='sohu';
            }else{
                $referer='other';
            }            
            $result = 0;
            switch($wiki->getModel()) {
                case 'film':
                    $config=array();  //未用该值
                    $this->saveFilmVideo($wiki, $referer, $url, $config); 
                    $result = 1;                
                    break;
                case 'teleplay':
                case 'television':
                    $this->saveVideoPlayList($wiki->getTitle(), (string)$wiki->getId(),$url, $referer);  
                    $result = 1;              
                    break;                   
                default: 
                    $result = 0;
            }

            return $this->renderText($result);
        }
    }


    /**
     * 添加分期视频
     * @param sfWebRequest $request 
     * @author lifucang
     */
    public function executeAddVideo(sfWebRequest $request) {
        $url = $request->getParameter('url');
        $title = $request->getParameter('title');
        $mark  = $request->getParameter('mark');
        $wiki_id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));
        $mark  = date('Ymd');
        if (!$url) {
            $video_repos = $mongo->getRepository('Video');
            $num=count($video_repos->find(array('query' => array('wiki_id' => (string)$wiki_id))));
            if($wiki->getModel()=='television'){
                $titlesub=$wiki->getTitle().'第'.$mark.'期';
            }else{
                $titlesub=$wiki->getTitle().'第'.(string)($num+1).'集';
            }
            return $this->renderPartial('add_video', array('wiki' => $wiki,'title'=>$titlesub));
        } else {
            $config=array();
            if(strpos($url, 'qiyi')){
                $referer='qiyi';
                $config = $this->qiyiAnalysis($url);
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
                $referer='other';
            }   
            //获取对应videoplay ID    
            $videoPlay = $mongo->getRepository('VideoPlaylist')->findone(array('query' => array('wiki_id' => (string)$wiki_id)));  
            if($videoPlay){
                $videoPlaylistId=$videoPlay->getId();
            }else{
                $videoPlaylistId='';
            }
            $result=$this->saveTeleplayVideo($wiki, $title, $url, $referer, $config, $videoPlaylistId); 
            return $this->renderText($result);
        }
    } 
    /**
     * 视频列表删除
     * @param sfWebRequest $request
     * @author lfc
     */
    public function executeDeleteVideoPlayList(sfWebRequest $request) {
        $url=$request->getReferer();
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('VideoPlayList');
        $videolist = $repository->findOneById(new MongoId($id));
        $videolist->delete();
        $this->getUser()->setFlash('notice', '已删除所选择的视频!');
        $this->redirect($url);
    }      
    /**
     * 视频删除
     * @param sfWebRequest $request
     * @author lfc
     */
    public function executeDeleteVideo(sfWebRequest $request) {
        $url=$request->getReferer();
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Video');
        $video = $repository->findOneById(new MongoId($id));
        $video->delete();
        $this->getUser()->setFlash('notice', '已删除所选择的视频!');
        $this->redirect($url);
    }        
    /**
     * 分析奇艺播放数据
     * @param <type> $url
     * @return <type>
     * @author luren
     */
    protected function qiyiAnalysis($url) {
        $result = array();
        $html = file_get_contents($url, false, Common::createStreamContext());
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
     */
    protected function crawlerQiyiTeleplay($url, Wiki $wiki) {
        $html = file_get_contents($url,false, Common::createStreamContext());
        $htmlArray = explode('<div id="j-album-1"', $html);
        if (!isset ($htmlArray[1])) return array();
        $htmlArray = explode('<div id="j-desc-1"' , $htmlArray[1]);
        preg_match_all('|none;">(.*)</div>|', $htmlArray[0], $matches);
        
        if ($matches) {
            //删除原来的奇艺视频
            $mongo = $this->getMondongo();
            $PlayListRepository = $mongo->getRepository('VideoPlaylist');
            $PlayListRepository->deleteVideos((string) $wiki->getId(), 'qiyi');
            
            //重新采集视频并保存
            $VideoPlayList = $this->saveVideoPlayList($wiki->getTitle(), (string) $wiki->getId(), $url, 'qiyi');
            foreach ($matches[1] as $match) {
                $url = 'http://www.qiyi.com'. $match;
                $listhtml = file_get_contents($url,false, Common::createStreamContext());
                $listArray = explode('<li>', $listhtml);
                array_shift($listArray);

                foreach ($listArray as $list) {
                    preg_match('#<a href="(.*?)" .*?\n<img.*? title="(.*?)" alt.*?>\n<.*?>([\d|:]+).*?\n<.*?<a.*?>.*?(\d+).*?</a>#i', $list, $tvmatches);
                    if ($tvmatches) {
                        $tvurl = isset($tvmatches[1]) ? $tvmatches[1] : '';
                        $title = isset($tvmatches[2]) ? $tvmatches[2] : '';
                        $time = isset($tvmatches[3]) ? $tvmatches[3] : '';
                        $mark = isset($tvmatches[4]) ? $tvmatches[4] : false;
                        $config =  $this->qiyiAnalysis($tvurl);
                        $this->saveTeleplayVideo($wiki, $title, $tvurl, 'qiyi', $config, (string)$VideoPlayList->getId(), $time, $mark);
                    }                    
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
                    $config =  $this->sohuAnalysis($tvurl);
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
