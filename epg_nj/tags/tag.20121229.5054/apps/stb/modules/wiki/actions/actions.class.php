<?php
/**
 * wiki actions.
 * @package    epg2.0
 * @subpackage user
 * @author     jianghongwei
 * @version    1.0
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
class wikiActions extends sfActions
{
    /**
    * Executes index action
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        //$this->forward('default', 'module');
    }
    /**
    * 根据 url 请求取得维基
    * @param sfWebRequest $request
    * @return <type>
    */
    protected function requestWiki(sfWebRequest $request) 
    {
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $this->slug  = trim($request->getParameter('slug'));

        if (preg_match("|[0-9a-f]{24}|", $this->slug) || $request->hasParameter("id")) {
            $id = $request->hasParameter("id") ? $request->getParameter('id') : $this->slug;
            $this->slug = $wiki_repository->getSlugById($id);

            if ($this->slug) {
                $this->redirect("wiki/show?slug=".$this->slug, 301);
            } else {
                $this->forward404('该条维基不存在！');
            }
        } else {
            $wiki = $wiki_repository->getWikiBySlug($this->slug);
            $this->forward404Unless($wiki, '该条维基不存在！');
            return $wiki;
        }
    }    
    /**
    * 单个维基显示页面
    * @param sfWebRequest $request
    * @author jianghongwei
    */
    public function executeShow(sfWebRequest $request)
    {
        $this->user_id = $this->getUser()->getAttribute('user_id');
      
        $mongo = $this->getMondongo();
        $this->wiki = $this->requestWiki($request);
         
        //根据wiki获取节目播出预告
        $program_repository = $mongo->getRepository('Program');
        //$this->count_programs = $program_repository->countDayPlayedProgramByWikiIdGd($this->wiki->getId()); //统计相关节目数量
        $this->played_programs = $program_repository->getDayPlayedProgramByWikiIdGd($this->wiki->getId(),3); //获取当天已播放节目
        $this->unplayed_programs = $program_repository->getDayUnPlayedProgramByWikiIdGd($this->wiki->getId(),3);  //获取当天未播放节目
        $this->count_programs = count($this->played_programs) + count($this->unplayed_programs);
        //$this->related_programs = $this->wiki->getWeekRelatedPrograms();
        //$this->previous = $request->getReferer();

        switch ($this->wiki->getModel()) {
            case 'teleplay':
                // 获取电视剧相关节目单
                $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                //$this->dramas_total = $wikiMetaRepos->count(array('wiki_id' => (string) $this->wiki->getId()));
                //$this->dramas = $wikiMetaRepos->getMetasByWikiId((string) $this->wiki->getId(), 0, 10);
                break;
            case 'film':
                //$wikiMetaRepos = $mongo->getRepository('WikiMeta');
                break;
            case 'television':
                $wikiMetaRepos = $mongo->getRepository('WikiMeta');
                $time = (int) str_replace('-', '', $request->getParameter('time'));
                if ($time) {
                    $query = array(
                            'query' => array(
                                'wiki_id' => (string) $this->wiki->getId(),
                                'mark' => $time
                            )
                          );
                    $this->wikiMeta = $wikiMetaRepos->getMetesByQurey($query);
                    //if (!$this->wikiMeta) {
                    //    return $this->setTemplate('television_main') ;
                    //} else {
                    //    return $this->setTemplate('television');
                    //}
                 } else {
                    //return $this->setTemplate('television_main') ;
                    //return $this->setTemplate('television') ;
                 }
                 break;
            case 'actor':
                 $this->film0graphy = $this->wiki->getFilmography($this->wiki->getTitle());
                 break;
        }
        if($this->count_programs==0){
            $interface='tcl';
            $hot_programs=array();
            if($interface=='tcl'){
                //直播信息从tcl获取
                $sp_repository = $mongo->getRepository('SpService');
                $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=30&uid=123";
                $contents=Common::get_url_content($url);
                if($contents){
                    $arr_contents=json_decode($contents);
                    $k=0;
                    foreach($arr_contents->recommend as $value){
                        if($k>=7) break;
                        $sp=$sp_repository->findOne(array('query'=>array('channel_id'=>$value->contid_id)));
                        $channelCode = $sp->getChannelCode(); 
                        $program=$program_repository->getLiveProgramByChannel($channelCode);
                        if($program){
                             $hot_programs[]= $program;
                             $k++;
                        }         
                    }
                }
            }
            if(!$hot_programs||count($hot_programs)==0){
                $channels=$mongo->getRepository('SpService')->getServicesByTag(null,'hot',-1);
                $k=0;
                foreach($channels as $channel){
                    if($k>=7) break;
                    $program=$program_repository->getLiveProgramByChannel($channel->getChannelCode());
                    if($program){
                         $hot_programs[]= $program;
                         $k++;
                    }
                }
            }
            $this->hot_programs = $hot_programs;
        }
        $this->setTemplate($this->wiki->getModel());
    } 
    /**
    * 用户对维基 加入收藏操作
    * @author jianghongwei
    */
    public function executeDo(sfWebRequest $request) 
    {
        //if ($request->isXmlHttpRequest()) {
            $wiki_id = $request->getParameter('wiki_id');
            $user_id = $request->getParameter('id');
            $mongo = $this->getMondongo();
            $singleChip_repository = $mongo->getRepository('SingleChip');
            $SingleChip = $singleChip_repository->isUserChipByWikiIdUserId($wiki_id,$user_id);
            if($SingleChip==true){
                return $this->renderText(2); 
            }else{
                $wiki_repository = $mongo->getRepository('Wiki');
                $wiki = $wiki_repository->findOneById(new MongoId($wiki_id));
                if ($wiki) {
                    $chip = new SingleChip();
                    $chip->setUserId($user_id);
                    $chip->setWikiId($wiki_id);
                    $chip->setIsPublic(true);
                    $chip->save();
                    $comment = new Comment();
                    $comment->saveComent($wiki_id, 'queue', 0,'',$user_id);
                    return $this->renderText(1);       
                }else{
                    return $this->renderText(0);
                }
            }
        //} else {
            //$this->forward404();
            //return $this->renderText(2);
        //}
    }
    public function executeOrderAdd(sfWebRequest $request) 
    {
        $user_id = $request->getParameter('user_id');
        $channel_code = $request->getParameter('channel_code');
        $name = $request->getParameter('program_name');
        $start_time = $request->getParameter('start_time');
        $channel_name = $request->getParameter('channel_name');
        
        $mongo = $this->getMondongo();
        $ProgramUser = new Programe_user();
        $ProgramUser->add($user_id,$channel_code,$name,$start_time,$channel_name);
        return $this->renderText(1);
    }
	/**
	 * 返回视频信息
	 * @param sfRequest $request A request object
	 */
	public function executeVideos(sfWebRequest $request)
	{
	    $wiki_id = $request->getParameter("wiki_id");
        $model = $request->getParameter("model",'teleplay');
        $referer_num = $request->getParameter("num",0);
        $arr_refer=array('yang.com','2A08_003','1905yy00');
        $referer=$arr_refer[$referer_num];
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $wiki=$wiki_repository->findOneById(new MongoID($wiki_id));
        return $this->renderPartial('videos', array('refer'=>$referer,'wiki'=>$wiki,'model'=>$model)); 
    }
    /**
     * tcl用户数据保存
     * @param sfWebRequest $request
     * @author lifucang
     */
    public function executeTclSave(sfWebRequest $request) 
    {
        $uid=$request->getParameter('uid');
        $wiki_id=$request->getParameter('wiki_id');
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=EventFeedback&feedback_type=watch_start&uid=$uid&cid=$wiki_id";
        $contents=file_get_contents($url);
        if($contents){
            $arr_contents=json_decode($contents);
            if($arr_contents[1]->message==null){
                return $this->renderText(1);
            }else{
                return $this->renderText(0);
            }
        }else{
            return $this->renderText(-1);
        }        
    }
    
    public function executePlay(sfWebRequest $request) 
    {
        $asset_id = $request->getParameter('asset_id');
        $sp_code  = $request->getParameter('sp_code');
        $userid   = $request->getParameter('user_id','8250102886999238');     
        switch($sp_code){
            case 'yang.com':
                $url = $this->getYangVideoUrl($userid,$asset_id,$request);
                break;
            case '2A08_003':
            case 'CP1N02A08_003':
                $url = $this->getPPTVVideoUrl($userid,$asset_id);
                break;
            case '1905yy00':
                $url = $this->getM1905VideoUrl($userid,$asset_id);
                break;
        }  
        if($url) {
            //return $this->renderText($url);exit;
            $this->redirect($url);
        }else {
            $backurl = $request->getReferer() ? $request->getReferer() : sfConfig::get("app_base_url");
        }
    }
    
    private function getYangVideoUrl($clientid,$contented,$request)
	{
        $clientid = $clientid?$clientid:'01006608470056014';
        $playtype = 0;
        $backurl = $request->getReferer() ? $request->getReferer() : sfConfig::get("app_base_url");
        if(!$contented) {
            return $this->renderText("参数错误！");            
        }        
        $submit_url = sfConfig::get("app_cpg_portal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=n&contented=".$contented."&backurl=".urlencode($backurl); 
                
        $curl = curl_init();          
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_USERPWD, sfConfig::get("app_cpg_portal_username").":".sfConfig::get("app_cpg_portal_password")); 
        curl_setopt($curl, CURLOPT_URL, $submit_url); 
        $data = curl_exec($curl);
        curl_close($curl); 
        $xmls = simplexml_load_string($data);        
        if(isset($xmls->url)) {
            return strval($xmls->url);
        }else{
            return '';
        }
	}
    private function getPPTVVideoUrl($userid,$asset_id)
    {
        $url = "http://172.31.155.22:9080/core/ContentLinksQuery.do?spcode=SP1N02A08_003&assetid=$asset_id&usercode=$userid&stbno=10000&movieassetid=$asset_id";
        $data=Common::get_url_content($url);
        if($data){
    		$result = json_decode($data,true);
            return $result['BackURL'];  
        }else{
            return '';
        }
    }
    private function getM1905VideoUrl($userid,$asset_id)
    {
        $url = "http://172.31.155.22:9080/core/ContentLinksQuery.do?spcode=SP1N02M04_030&assetid=$asset_id&usercode=$userid&stbno=10000&movieassetid=$asset_id";  
        $data=Common::get_url_content($url);
        if($data){
    		$result = json_decode($data,true);
            return $result['BackURL'];  
        }else{
            return '';
        }
    }
}
