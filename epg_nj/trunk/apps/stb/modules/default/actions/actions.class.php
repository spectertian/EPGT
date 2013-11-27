<?php
/**
 * default actions.
 *
 * @package    epg2.0
 * @subpackage default
 * @author     Huan lifucang
 */
class defaultActions extends sfActions
{
	/**
	 * Executes index action
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
        $this->tag = $request->getParameter('type','电视剧');  //为了给executeHot方法传递
        $mongo = $this->getMondongo();
        $this->wikis=array();
        $memcache = tvCache::getInstance();
        //专题
        $this->themes = $memcache->get("index_themes");
        if(!$this->themes) {
            $this->themes = Doctrine::getTable('theme')->getThemeByPageAndSize(1,10);
            $memcache->set("index_themes",$this->themes,3600*24);
        }     
    }
	/**
	 * Executes recommend action
	 * @param sfRequest $request A request object
	 */
	public function executeRecommend(sfWebRequest $request)
	{
	    $cardId = $request->getParameter("cardId");  //智能卡号
        $stbId  = $request->getParameter("stbId");   //机顶盒号
        //$backurl = 'http://'.$request->getHost().'/';
        if($_SERVER["SERVER_PORT"]==80||$_SERVER["SERVER_PORT"]=='80'){
            $backurl = 'http://'.$request->getHost().'/';
        }else{
            $backurl = 'http://'.$request->getHost().':'.$_SERVER["SERVER_PORT"].'/';
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
        //运营中心
        if(($cardId == '8250102372401749') || ($cardId == '8250102886999246')){
            $stbId = "99586611250057372";
        }
        //不能缓存，否则各个机顶盒显示的数据是一样的
        //$memcache = tvCache::getInstance(); 
        //$wikis=$memcache->get("index_wikis_$interface");
        $refer=$interface; 
        //if(!$wikis){ 
            switch($interface){
                case 'tcl': 
                    $wikis=$this->getTclVodPrograms($cardId, 15, '', $backurl);
                    break;
                case 'center':
                    $wikis = $this->getCenterVodPrograms($stbId, 15, '', $backurl);
                    break;
                case 'tongzhou':
                    $wikis = $this->getTongzhouVodPrograms($cardId, 15, '', $backurl);
                    break;
            }
            //$memcache->set("index_wikis_$interface",$wikis,3600);  //1小时
            //获取不到从本地获取
            if(count($wikis)==0||!$wikis){
                $wikis=$this->getLocationVodPrograms($cardId,15,'',$backurl);     
                $refer='local';  
            }
        //}
        return $this->renderPartial('recommend', array('refer'=>$refer,'wikis'=>$wikis)); 
    }
    /**
     * 获取Tcl的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTclVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository("Wiki");
        $user_id = substr($user_id,0,strlen($user_id)-1);
        $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=".$count."&uid=".$user_id."&backurl=".$backurl;
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
     * 获取运营中心的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getCenterVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $user_id = $user_id ? $user_id."_0" : "99586611250057372_0";
        //echo $user_id;exit;
        $filter  = $type ? urlencode("genre='".$type."'") : "";
        //$recomUrl = 'http://172.20.224.146:9090/ie/interface?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&lang=zh&urltype=1&alg=CF&uid='.$user_id.'&filter='.$filter.'&backurl='.$backurl;
        $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&lang=zh&urltype=1&alg=CF&uid='.$user_id.'&filter='.$filter.'&backurl='.$backurl;
        $recomTxt = Common::get_url_content($recomUrl, 2);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson)
                $wikis = $recomJson['recommend'];
        }
        return $wikis;
    }
    
    /**
     * 获取技术部同洲厂家的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTongzhouVodPrograms($user_id,$count=10,$type='',$backurl='')
    {
        $wikis = null;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        if($type!=''){
            //按标签推荐
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&genre='.$type.'&backurl='.$backurl;
        }else{
            //个性化推荐
            $recomUrl = sfConfig::get('app_recommend_tongzhouUrl').'?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&uid='.$user_id.'&backurl='.$backurl;
        }
        $recomTxt = Common::get_url_content($recomUrl);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson){
                $wikis = $recomJson['recommend'][0]['recommand'];    
            }
        }
        return $wikis;
    }
    /**
     * 获取本地点播节目
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */ 
    protected function getLocationVodPrograms($user_id,$count=10,$type='',$backurl='')
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
