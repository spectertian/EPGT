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
        /*
        if($cardId=='8250102886999238'||$cardId=='8250102886999246'){
            $user_id="99586611250057372_0";
        }else{
            $user_id="99766609340071223_0";
        }
        */
        $mongo = $this->getMondongo();
        $this->wikis=array();
        $memcache = tvCache::getInstance(); 
        
        $interface='tcl';  //默认接口是tcl
        if($interface=='tcl'){
            $user_id=substr($cardId,0,strlen($cardId)-1);
        }elseif($interface=='tongzhou'){
            $user_id=$cardId;
        }else{
            $user_id=$stbId;
        }
        //从tcl接口获取点播信息
        if($interface=='tcl'){
            $wiki_repository = $mongo->getRepository('Wiki');
            $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=".$user_id;
            $contents=Common::get_url_content($url);
            if($contents){
                $arr_contents=json_decode($contents);
                foreach($arr_contents->recommend as $value){
                    $wiki_id = $value->contid_id;  
                    $this->wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
                }
            }
            $this->refer='tcl';  
        }
        //从运营中心获取
        if($interface=='center'){
            $this->wikis = $memcache->get("index_recomwikis");
            if(!$this->wikis) {
                $recomUrl = 'http://172.20.224.146:9090/ie/interface?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=10&lang=zh&urltype=1&alg=CF&uid='.$user_id.'&backurl=http://'.$request->getHost().'/';
                $recomTxt = Common::get_url_content($recomUrl);
                if($recomTxt){
                    $recomJson = json_decode($recomTxt,true);
                    if($recomJson){
                        //$this->wikis = $recomJson['recommend'][0]['recommend'];
                        $this->wikis = $recomJson['recommend'];
                        $memcache->set("index_recomwikis",$this->wikis,3600);    
                    }
                }
            } 
            $this->refer='center';  
        }
        //从同洲获取
        if($interface=='tongzhou'){
            $this->wikis = $memcache->get("tongzhou_recomwikis");
            if(!$this->wikis) {
                $recomUrl = 'http://172.31.178.6:10080/recommand/recommand/epgAction.action?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid='.$user_id.'&backurl=http://'.$request->getHost().'/';
                $recomTxt = Common::get_url_content($recomUrl);
                if($recomTxt){
                    $recomJson = json_decode($recomTxt,true);
                    if($recomJson){
                        //$this->wikis = $recomJson['recommend'][0]['recommend'];
                        $this->wikis = $recomJson['recommend'][0]['recommand'];
                        $memcache->set("tongzhou_recomwikis",$this->wikis,3600);    
                    }
                }
            } 
            $this->refer='tongzhou';  
        }
        //获取不到从本地获取
        if(count($this->wikis)==0||!$this->wikis){
            $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
            $recommends = $wiki_recommend_repository->getRandWiki(20);  
            foreach($recommends as $recommend){
                $this->wikis[]=$recommend->getWiki();
            }    
            $this->refer='local';  
        } 
        return $this->renderPartial('recommend', array('refer'=>$this->refer,'wikis'=>$this->wikis)); 
    }	
    /**
    * 404 错误页面
    */
    public function executeError404(sfWebRequest $request) {
      
    }
}
