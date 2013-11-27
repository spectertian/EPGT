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
        $slug = $request->getParameter('slug');
        $cardId = $request->getParameter("cardId","8250102372401749");  //智能卡号
        $stbId  = $request->getParameter("stbId","99586611250057372");  //机顶盒号
        
        $this->refer = $request->getParameter('refer','vod');
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');        
        $program_repository = $mongo->getRepository('Program');
        
        $this->wiki = $wiki_repository->getWikiBySlug($slug);
        $this->forward404Unless($this->wiki, '该条维基不存在！');
         
        //根据wiki获取节目播出预告
        $startTime=date("Y-m-d",strtotime('-3 day'));
        $endTime=date("Y-m-d",strtotime('+3 day'));
        $this->played_programs = $program_repository->getDayPlayedProgramByWikiIdGd($this->wiki->getId(),3,$startTime); //获取当天已播放节目
        $this->unplayed_programs = $program_repository->getDayUnPlayedProgramByWikiIdGd($this->wiki->getId(),3,$endTime);  //获取当天未播放节目
        $this->count_programs = count($this->played_programs) + count($this->unplayed_programs);
        
        //无播出预告及回看则显示热播节目
        if($this->count_programs==0){
            //直播来源
            $settingRepository = $mongo->getRepository('Setting');
            $rsa = $settingRepository->findOne(array('query' => array( "key" => 'liveWho' )));
            if($rsa){
                $interface_live=$rsa->getValue();
            }else{
                $interface_live = sfConfig::get('app_recommend_liveWho');
            }
            
            $memcache = tvCache::getInstance(); 
            $programList = $memcache->get("wiki_programs");
            if(!$programList){
                switch($interface_live){
                    case 'tcl':
                        $programList = $this->getTclLivePrograms($cardId, 7);
                        break;
                    /*
                    case 'tongzhou':
                        $programList = $this->getTongzhouLivePrograms($cardId, 7);
                        break;
                    case 'center':
                        $programList = $this->getCenterLivePrograms($stbId, 7);
                    */
                }
                if(!$programList||count($programList)==0){
                    $programList = $this->getLocationLivePrograms($cardId, 7);
                }
                $memcache->set("wiki_programs",$programList,60);  //1分钟
            }            
            $this->hot_programs = $programList;
        }
        $this->setTemplate($this->wiki->getModel());
    } 
    /**
     * 获取tcl的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTclLivePrograms($user_id,$count=20,$type='')
    {
        $programList = null;
        $mongo = $this->getMondongo();
        $sp_repository = $mongo->getRepository('SpService');
        $programs = $mongo->getRepository('program');
        $ccount = $count*2;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        //按标签推荐
        //$url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$ccount."&genre=".$type."&uid=".$user_id;
        $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=epg&count=".$ccount."&uid=".$user_id;
        $contents = Common::get_url_content($url);
        if(!$contents){
            return null;
        }
        $arr_contents = json_decode($contents);
        if(!$arr_contents) {
            return null;
        }
        $k=0;
        foreach($arr_contents->recommend as $value){
            $sp=$sp_repository->findOne(array('query'=>array('channel_id'=>$value->contid_id)));
            $channelCode = $sp->getChannelCode(); 
            $program=$programs->getLiveProgramByChannel($channelCode);
            if($program&&$program->getWiki()){
                 $programList[]= $program;
                 $k++;
            }    
            if($k>=$count) break;     
        }
        return $programList;
    }
    /**
     * 获取本地直播节目
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */ 
    protected function getLocationLivePrograms($user_id,$count=20)
    {
        $programList = null;
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository('program');
        
        $channels = $mongo->getRepository('SpService')->getServicesByTag(null,'hot',-1);
        $k = 0;
        
        foreach($channels as $channel){
            $program = $program_repo->getLiveProgramByChannel($channel->getChannelCode());
            if($program){
                $programList[]= $program;
                $k++;
            }
            if($k >= $count) break;
        }
        return $programList;
    }
    /**
    * 用户对维基 加入收藏操作
    * @author jianghongwei
    */
    public function executeDo(sfWebRequest $request) 
    {
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
    }
    
    /**
	 * 预约
	 * @param sfRequest $request A request object
	 */
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
    
     /**
     * 视频播放代理
     * @param sfWebRequest $request
     * @author lifucang
     */
    public function executeRelevance(sfWebRequest $request) 
    {
        $cardId = $request->getParameter("cardId");  //智能卡号
        $stbId  = $request->getParameter("stbId");   //机顶盒号
        $wiki_id = $request->getParameter("wiki_id");
        
        $mongo = $this->getMondongo();
        $wikiRep = $mongo->getRepository('wiki'); 
        $wiki = $wikiRep->findOneById(new MongoId($wiki_id));
        //$backurl = 'http://'.$request->getHost().'/wiki/show/slug/'.$wiki->getSlug();
        $backurl = 'http://'.$request->getHost().'/list';
        
        if($cardId == '8250102372401749'||$cardId=='8250102886999246'){
            $user_id = "99586611250057372";
        }else{
            $user_id = $stbId;
        }
        
        switch($wiki -> getModel()){
            case "film":
                $type = "Movie";
                break;
            case "teleplay":
                $type = "Series";
                break;
            default:
                $type = "News";
        }
        
        $this->movies = $this->getCenterVodPrograms($user_id, 20, $type, $backurl);        
    }
    
    /**
     * 根据模型（Movie,Series,News）获取运营中心的点播推荐。
     * @author superwen
     * @date   2013-01-03
     */
    protected function getCenterVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $user_id = $user_id ? $user_id."_0" : "99766609340071223_0";
        $filter  = $type ? urlencode("genre='".$type."'") : "";
        $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&lang=zh&urltype=1&alg=CF&uid='.$user_id.'&filter='.$filter.'&backurl='.$backurl;
        $recomTxt = Common::get_url_content($recomUrl);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson)
                $wikis = $recomJson['recommend'];
        }
        return $wikis;
    }
    
    /**
     * 视频播放代理
     * @param sfWebRequest $request
     * @author lifucang
     */
    public function executePlay(sfWebRequest $request) 
    {
        $asset_id = $request->getParameter('asset_id');
        $sp_code  = $request->getParameter('sp_code');
        $userid   = $request->getParameter('user_id','8250102886999238');
        $backurl = $request->getReferer() ? $request->getReferer() : sfConfig::get("app_base_url");        
        $userid = ($userid == "null") ? '8250102886999238' : $userid;
        
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
            exit;
        }else {
            
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
        $submit_url = sfConfig::get("app_cpgPortal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=y&contented=".$contented."&backurl=".urlencode($backurl); 
        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_USERPWD, sfConfig::get("app_cpgPortal_username").":".sfConfig::get("app_cpgPortal_password")); 
        curl_setopt($curl, CURLOPT_URL, $submit_url); 
        $data = curl_exec($curl);
        curl_close($curl); 
        if(!$data) {
            return '';
        }
        $xmls = @simplexml_load_string($data);        
        if(isset($xmls->url)) {
            return strval($xmls->url);
        }else{
            return '';
        }
	}
    
    private function getPPTVVideoUrl($userid,$asset_id)
    {    
        $url = sfConfig::get("app_linkQuery_center")."?spcode=SP1N02A08_003&assetid=$asset_id&usercode=$userid&stbno=10000&movieassetid=$asset_id";
        $data = Common::get_url_content($url);
        if($data){
    		$result = json_decode($data,true);
            if(isset($result['BackURL']))
                return $result['BackURL']; 
            else
                return '';
        }else{
            return '';
        }
    }
    
    private function getM1905VideoUrl($userid,$asset_id)
    {
        $url = sfConfig::get("app_linkQuery_center")."?spcode=SP1N02M04_030&assetid=$asset_id&usercode=$userid&stbno=10000&movieassetid=$asset_id";
        $data = Common::get_url_content($url);
        if($data){
    		$result = json_decode($data,true);
            if(isset($result['BackUrl']))
                return $result['BackUrl']; 
            else
                return '';
        }else{
            return '';
        }
    }
}
