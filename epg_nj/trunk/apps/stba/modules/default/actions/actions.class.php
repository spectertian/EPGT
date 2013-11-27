<?php
/**
 * default actions.
 * @package    epg2.0
 * @subpackage default
 * @author     Huan lifucang
 */
class defaultActions extends sfActions
{
	/**
	 * 智能门户
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
        $this->tag = $request->getParameter('type','电视剧');  //为了给executeHot方法传递
        $mongo = $this->getMondongo();
        $this->wikis=array();
    }
	/**
	 * 猜你喜欢
	 * @param sfRequest $request A request object
	 */
	public function executeRecommend(sfWebRequest $request)
	{
	    $cardId = $request->getParameter("cardId");  //智能卡号
        $stbId  = $request->getParameter("stbId");   //机顶盒号
        if($_SERVER["SERVER_PORT"]==80||$_SERVER["SERVER_PORT"]=='80'){
            $backurl = 'http://'.$request->getHost().'/default';
            $backurla = urlencode(urlencode($backurl));
        }else{
            $backurl = 'http://'.$request->getHost().':'.$_SERVER["SERVER_PORT"].'/default';
            $backurla = urlencode(urlencode($backurl));
        }
        //点播来源
        $mongo = $this->getMondongo();
        $settingRepository = $mongo->getRepository('Setting');
        $rs = $settingRepository->findOne(array('query' => array( "key" => 'vodWho' )));
        if($rs){
            $interface=$rs->getValue();
        }else{
            $interface=sfConfig::get('app_recommend_vodWho');
        }
        //临时固定页面
        $pageObj = $settingRepository->findOne(array('query' => array( "key" => 'page' )));
        if($pageObj){
            $page=$pageObj->getValue();
        }else{
            $page=-1;
        }
        //根据$userId进行缓存
        if($interface=='center'){
            $userId = $stbId;
        }else{
            $userId = $cardId;
        }
        if($page==1){
            $wikis = $this->getCenterVodProgramsTemp($userId,8,'vod',$backurl);
            $refer='center';
        }else{
            $memcache = tvCache::getInstance(); 
            $mem_key="index_wikis_$interface_$userId";
            $wikis=$memcache->get($mem_key);
            if(!$wikis){
                switch($interface){
                    case 'tcl':     
                        $wikis = $this->getTclVodPrograms($cardId, 15, 'like', $backurl);
                        break;
                    case 'tongzhou':
                        $wikis = Recommand::getTongzhouVodPrograms($cardId, 15, 'like', $backurl);
                        break;
                    case 'center':
                        $wikis = Recommand::getCenterVodPrograms($stbId, 15, 'like', $backurla);
                        break;
                }
                //获取不到从固定推荐获取
                if(count($wikis)==0||!$wikis){
                    //$wikis = $this->getLocationVodPrograms($cardId,10,'',$backurl);   //从本地获取
                    //$refer = 'tcl'; 
                    $wikis = $this->getCenterVodProgramsTemp($userId,15,'vod',$backurl);
                    $refer='center';    
                }else{
                    $memcache->set($mem_key,$wikis,300);  //5分钟
                }
            }
        }
        return $this->renderPartial('recommend', array('refer'=>$refer,'wikis'=>$wikis)); 
    }
    /**
     * 获取Tcl的点播推荐。
     * @author superwen
     * @editor lifucang 2013-08-21
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
     * 获取本地点播节目
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */ 
    private function getLocationVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $memcache = tvCache::getInstance(); 
        $wikis=$memcache->get("index_wikis_local");
        if(!$wikis){
            $mongo = $this->getMondongo();
            $wrRepo = $mongo->getRepository("WikiRecommend");
            $wikiRecommends = $wrRepo->getWikiByPageAndSize(1,$count,$type); 
            foreach($wikiRecommends as $recommend){
                $wikis[]=$recommend->getWiki();
            }  
            $memcache->set("index_wikis_local",$wikis,3600*2);  //2小时
        }
        return $wikis;
    }    	
    /**
    * 404 错误页面
    */
    public function executeError404(sfWebRequest $request) {
      
    }
}
