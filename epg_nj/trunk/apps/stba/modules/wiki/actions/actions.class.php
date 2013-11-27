<?php
/**
 * wiki actions.
 * @package    epg2.0
 * @subpackage user
 * @author     jianghongwei
 * @version    1.0
 */
use Mondongo\Tests\MondongoTest;
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
                $this->redirect("wiki/show?id=".$id, 301);
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
        $wikiId = $request->getParameter('id');
        $cardId = $request->getParameter("cardId","8250102372401749");  //智能卡号
        $stbId  = $request->getParameter("stbId","99586611250057372");  //机顶盒号
        $this->refer = $request->getParameter('refer','vod');
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');        
        $program_repository = $mongo->getRepository('Program');
        if ($slug){
        	$this->wiki = $wiki_repository->getWikiBySlug($slug);
        }else if ($wikiId){
        	//$this->wiki = $wiki_repository->getWikiById($wikiId);
            $this->wiki = $wiki_repository->findOneById(new MongoId($wikiId));
        }
        $verify = $this->wiki->getVerify();
        if(isset($verify)&&$verify==0){
            $this->forward404('该条维基还未通过审核！');
        }
        $this->forward404Unless($this->wiki, '该条维基不存在！');
        
        /*
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
                }
                if(!$programList||count($programList)==0){
                    $programList = $this->getLocationLivePrograms($cardId, 7);
                }
                $memcache->set("wiki_programs",$programList,60);  //1分钟
            }            
            $this->hot_programs = $programList;
        }
        */
        $this->setTemplate($this->wiki->getModel());
    } 

    /**
    * 单个维基显示页面
    * @param sfWebRequest $request
    * @author jianghongwei
    */
    public function executeProgramGuide(sfWebRequest $request)
    {
        $wiki_id = $request->getParameter('wiki_id');
        $mongo = $this->getMondongo();       
        $program_repository = $mongo->getRepository('Program');
        //根据wiki获取节目播出预告
        $startTime=date("Y-m-d",strtotime('-3 day'));
        $endTime=date("Y-m-d",strtotime('+3 day'));
        $played_programs = $program_repository->getDayPlayedProgramByWikiIdGd($wiki_id,3,$startTime); //获取当天已播放节目
        $unplayed_programs = $program_repository->getDayUnPlayedProgramByWikiIdGd($wiki_id,3,$endTime);  //获取当天未播放节目
        $count_programs = count($played_programs) + count($unplayed_programs);
        
        //无播出预告及回看则显示热播节目
        if($count_programs==0){
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
                        $programList = $this->getTclLivePrograms($cardId, 7,'hot');
                        break;
                    case 'tongzhou':
                        $programList = $this->getTongzhouLivePrograms($cardId, 7,'hot');
                        break;
                    case 'center':
                        $programList = $this->getCenterLivePrograms($stbId, 7,'hot');
                        break;
                }
                if(!$programList||count($programList)==0){
                    $programList = $this->getLocationLivePrograms($cardId, 7);
                }
                $memcache->set("wiki_programs",$programList,60);  //1分钟
            }            
            $hot_programs = $programList;
        }
        return $this->renderPartial('program_guide',array('count_programs' => $count_programs,'hot_programs'=>$hot_programs,'played_programs'=>$played_programs,'unplayed_programs'=>$unplayed_programs)); 
    } 
    /**
     * 获取tcl的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    private function getTclLivePrograms($user_id,$count=20,$type='')
    {
        $programList = null;
        $mongo = $this->getMondongo();
        $sp_repository = $mongo->getRepository('SpService');
        $programs = $mongo->getRepository('program');
        $ccount = $count*2;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        if($type=='hot'){
            $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.hotitem.v1&ctype=epg&period=monthly&count=".$ccount;
        }else{
            $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$ccount."&genre=".$type."&uid=".$user_id;
        }
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
     * 获取运营中心的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    private function getCenterLivePrograms($user_id,$count=20,$type='')
    {
        return null;
    }
    
    /**
     * 获取技术部同洲厂家的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    private function getTongzhouLivePrograms($user_id,$count=20,$type='')
    {   
        $programList = null;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        $mongo = $this->getMondongo();
        $sp_repository = $mongo->getRepository('SpService');
        $programs = $mongo->getRepository('program');
        if($type=='hot'){
            $url = sfConfig::get('app_recommend_tongzhouUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.hotitem.v1&ctype=epg&period=monthly&count=".$count;
        }else{
            $url = sfConfig::get('app_recommend_tongzhouUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$count."&genre=".$type."&uid=".$user_id;
        }
        $contents = Common::get_url_content($url);
        if(!$contents){
            return null;
        }        
        $arr_contents = json_decode($contents,true);
        if(!$arr_contents) {
            return null;
        }
        $k = 0;
        foreach($arr_contents['recommend'][0]['recommand'] as $value){
            $sp = $sp_repository->findOne(array('query'=>array('channel_id'=>$value['Channel_ID'])));
            $channelCode = $sp->getChannelCode(); 
            $program = $programs->getLiveProgramByChannel($channelCode);
            if($program&&$program->getWiki()){
                 $programList[]= $program;
                 $k++;
            }
            if($k >= $count) break;         
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
        $refer = $request->getParameter("refer",'yang.com');
        $hd = $request->getParameter("hd",'');
        
        $mongo = $this->getMondongo();
        $video_repository = $mongo->getRepository('Video');
        if($hd!=''){
            $videos=$video_repository->getVideosByWikiId($wiki_id,$refer,$hd);
        }else{
            $videos=$video_repository->getVideosByWikiId($wiki_id,$refer);
        }
        return $this->renderPartial('videos', array('videos'=>$videos,'model'=>$model)); 
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
        $backurla = urlencode(urlencode($backurl));
        
        //获取点播来源开始
        $settingRepository = $mongo->getRepository('Setting');
        $rs = $settingRepository->findOne(array('query' => array( "key" => 'vodRelatedwho' )));
        if($rs){
            $interface=$rs->getValue();
        }else{
            $interface=sfConfig::get('app_recommend_vodWho');
        }   
        $refer=$interface;
        switch($wiki -> getModel()){
            case "film":
                $type = "Movie";
                break;
            case "teleplay":
                $type = "Series";
                break;
            default:
                $type = "Entertainment";
        }  
        if($interface=='center'){
            $userId = $stbId;
        }else{
            $userId = $cardId;
        }   
        switch($interface){
            case 'tcl':      
                $wikis = $this->getTclVodPrograms($cardId,6,'corelation',$backurl,$wiki_id);
                break;
            case 'tongzhou':
                $wikis = Recommand::getTongzhouVodPrograms($cardId,6,$type,$backurl);
                break;
            case 'center':
                $wikis = Recommand::getCenterVodPrograms($user_id, 10, $type, $backurla);    
                break;
        }  
        //获取不到从固定推荐获取
        if(count($wikis)==0||!$wikis){       
            $wikis = $this->getCenterVodProgramsTemp($user_id, 10, $type, $backurl);    
            $refer='center';                  
        } 
        //获取点播来源结束  
        $this->wikis=$wikis;
        $this->refer=$refer;  
    }
    
    /**
     * 获取运营中心的点播推荐，固定推荐用。
     * @author lifucang
     * @date   2013-05-23
     */
    private function getCenterVodProgramsTemp($user_id,$count=10,$type='',$backurl='')
    {
        $mongo = $this->getMondongo();
        $recommandFix_rep = $mongo->getRepository("RecommandFix");
        $recommandFixs = $recommandFix_rep->find(array('query'=>array('type'=>$type),'limit'=>$count,'sort'=>array('_id'=>-1))); 
        $arr_vod=array();
        foreach($recommandFixs as $recommandFix){
            $url=$recommandFix->getUrl();
            $arr_vod[]=array(
               'poster' => $recommandFix->getPoster(),
               'Title' => $recommandFix->getTitle(),
               'url' => $url.$backurl
            );
        }
        return $arr_vod;
    }   
    /**
     * 获取Tcl的相关推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    private function getTclVodPrograms($user_id,$count=10,$type='',$backurl='',$cid='')
    {
        $wikis = null;
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository("Wiki");
        $user_id = substr($user_id,0,strlen($user_id)-1);
        if($type=='vod'){
            $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.hotitem.v1&ctype=vod&period=monthly&count=".$count."&backurl=".$backurl; 
        }elseif($type=='like'){
            $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=".$count."&uid=".$user_id."&backurl=".$backurl; 
        }elseif($type=='corelation'){
            $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.corelation.v1&ctype=vod&count=".$count."&cid=".$cid."&backurl=".$backurl; 
        }else{
            $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=".$count."&uid=".$user_id."&genre=".$type."&backurl=".$backurl; 
        }
        $contents = Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            foreach($arr_contents->recommend as $value){
                $wiki_id = $value->contid_id;  
                $wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
            }
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
        if(!$asset_id){
            return $this->renderText('');
        }
        $sp_code  = $request->getParameter('sp_code');
        $userid   = $request->getParameter('user_id','8250102886999238');
        $backurl = $request->getReferer() ? str_replace('history','vod',$request->getReferer()) : sfConfig::get("app_base_url");
        $backurla = urlencode(urlencode($backurl));
        $userid = ($userid == "null") ? '8250102886999238' : $userid;
        $wiki_id  = $request->getParameter('wiki_id',null);
        switch($sp_code){
            case 'yang.com':
                $url = $this->getYangVideoUrl($userid,$asset_id,$backurla);
                break;
            case 'avpress':
                //$url = $this->getAvpressVideoUrl($userid,$asset_id,$request);
                $url = $this->getYangVideoUrl($userid,$asset_id,$backurl);
                break;
            case '2A08_003':
            case 'CP1N02A08_003':
                $url = $this->getPPTVVideoUrl($userid,$asset_id,$backurl);
                break;
            case '1905yy00':
                $url = $this->getM1905VideoUrl($userid,$asset_id,$backurl);
                break;
        }  
        if($url&&$url!='null') {
            if($wiki_id){
                $mongo = $this->getMondongo();
                $wiki_repository = $mongo->getRepository("Wiki");
                $wiki=$wiki_repository->findOneById(new MongoId($wiki_id)); 
                if($wiki){
                    $watched_num=$wiki->getWatchedNum()?$wiki->getWatchedNum():0;
                    $wiki->setWatchedNum($watched_num+1);
                    $wiki->save();
                }
            }
            return $this->renderText($url);
            //$this->redirect($url);
        }else {
            return $this->renderText('');
            //$this->redirect($request->getReferer());
        }
    }
    
    private function getYangVideoUrl($clientid,$contented,$backurl)
	{
        $clientid = $clientid?$clientid:'01006608470056014';
        $playtype = 0;
        //$backurl = $request->getReferer() ? $request->getReferer() : sfConfig::get("app_base_url");
        if(!$contented) {
            //return $this->renderText("参数错误！");   
            return '';         
        }        
        $submit_url = sfConfig::get("app_cpgPortal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=y&contented=".$contented."&backurl=".urlencode($backurl); 
        //return $submit_url;
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
    private function getAvpressVideoUrl($clientid,$contented,$request)
	{
        return '';
	}
    private function getPPTVVideoUrl($userid,$asset_id,$backurl)
    {    
        $url = sfConfig::get("app_linkQuery_center")."?spcode=SP1N02A08_003&assetid=$asset_id&usercode=$userid&stbno=10000&movieassetid=$asset_id&backurl=".urlencode($backurl);
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
    
    private function getM1905VideoUrl($userid,$asset_id,$backurl)
    {
        $url = sfConfig::get("app_linkQuery_center")."?spcode=SP1N02M04_030&assetid=$asset_id&usercode=$userid&stbno=10000&movieassetid=$asset_id&backurl=".urlencode($backurl);
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
