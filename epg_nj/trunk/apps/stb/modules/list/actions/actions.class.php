<?php
/**
 * list actions.
 * @package    epg2.0
 * @subpackage list
 * @author     Huan lifucang
 * @version    1.0
 */
class listActions extends sfActions
{ 
    public static $typea = array("Series" => array("title" => "电视剧","model"=>'teleplay',"tags"=>array('电视剧')),
                                "Movie" => array("title" => "电影","model"=>'film',"tags"=>array('电影')),
                                "Sports" => array("title" => "体育","model"=>'television',"tags"=>array('体育','田径','足球','篮球')),
                                "Entertainment" => array("title" => "综艺","model"=>'television',"tags"=>array('综艺','娱乐','戏剧')),
                                "Cartoon" => array("title" => "动漫","model"=>'television',"tags"=>array('少儿','动漫','动画')),
                                "Culture" => array("title" => "文化","model"=>'television',"tags"=>array('教育','科教','人文','文化','科学')),
                                "News" => array("title" => "综合","model"=>'television',"tags"=>array('综合','民生','财经')));
    /**
    * Executes index action
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $this->tag = $request->getParameter('type','电视剧');
        $this->page = $request->getParameter('page',1);
        $this->types = self::$typea;
    }
    
    /**
    * ajax调用
    * @author lifucang
    */
    public function executeShowProgram(sfWebRequest $request)
    {
        $tag = $request->getParameter('type','Series');
        $page = $request->getParameter('page',1);
        $scrollpage = $request->getParameter('scrollpage',0);
	    $cardId = $request->getParameter("cardId");  //智能卡号
        $stbId  = $request->getParameter("stbId");   //机顶盒号
        //$backurl = 'http://'.$request->getHost().'/list';
        if($_SERVER["SERVER_PORT"]==80||$_SERVER["SERVER_PORT"]=='80'){
            $backurl = 'http://'.$request->getHost().'/list';
        }else{
            $backurl = 'http://'.$request->getHost().':'.$_SERVER["SERVER_PORT"].'/list';
        }
        $mongo = $this->getMondongo();
        $wikis = array();  
        $memcache = tvCache::getInstance(); 
        //运营中心
        if(($cardId == '8250102372401749') || ($cardId == '8250102886999246')){
            $stbId = "99586611250057372";
        }
        //点播来源
        $settingRepository = $mongo->getRepository('Setting');
        $rs = $settingRepository->findOne(array('query' => array( "key" => 'vodWho' )));
        if($rs){
            $interface=$rs->getValue();
        }else{
            $interface=sfConfig::get('app_recommend_vodWho');
        }
        //直播来源
        $rsa = $settingRepository->findOne(array('query' => array( "key" => 'liveWho' )));
        if($rsa){
            $interface_live=$rsa->getValue();
        }else{
            $interface_live = sfConfig::get('app_recommend_liveWho');
        }
        
        $refer = $interface;
        if($tag == 'vod'){
            $wikis=$memcache->get("list_wikis_$interface");
            if(!$wikis){
                switch($interface){
                    case 'tcl':     
                        $wikis = $this->getTclVodPrograms($cardId, 10, '', $backurl);
                        break;
                    case 'tongzhou':
                        $wikis = $this->getTongzhouVodPrograms($cardId, 10, '', $backurl);
                        break;
                    case 'center':
                        $wikis = $this->getCenterVodPrograms($stbId, 10, '', $backurl);
                        break;
                }
                $memcache->set("list_wikis_$interface",$wikis,3600);  //1小时
                //获取不到从本地获取
                if(count($wikis)==0||!$wikis){
                    $wikis = $this->getLocationVodPrograms($cardId,10,'',$backurl);  
                    $refer='local';    
                }
            }
            $programList = false;
        }else{ 
            $tag = isset(self::$typea[$tag]) ? $tag : 'Series'; 
            //因为直播缓存时间较短，所以直播缓存不在区分接口来源
            $live_key="list_programs".'_'.$tag;
            $programList=$memcache->get($live_key);
            if(!$programList){
                switch($interface_live){
                    case 'tcl':
                        $programList = $this->getTclLivePrograms($cardId, 20, $tag);
                        break;
                    case 'tongzhou':
                        $programList = $this->getTongzhouLivePrograms($cardId, 20, $tag);
                        break;
                    case 'center':
                        $programList = $this->getCenterLivePrograms($stbId, 20, $tag);
                }
                if(!$programList||count($programList)==0){
                    $programList = $this->getLocationLivePrograms($cardId, 20, self::$typea[$tag]["tags"]);
                }
                $memcache->set($live_key,$programList,60);  //1分钟
            }
            //用点播补齐
            $wikis=$memcache->get('list_wikis_'.$interface.'_'.$tag);
            if(!$wikis){
                switch($interface){
                    case 'tcl':      
                        $wikis = $this->getTclVodPrograms($cardId,10,$tag,$backurl);
                        break;
                    case 'tongzhou':
                        $wikis = $this->getTongzhouVodPrograms($cardId,10,$tag,$backurl);
                        break;
                    case 'center':
                        $wikis = $this->getCenterVodPrograms($stbId,10,$tag,$backurl,'RK');
                        break;
                }  
                $memcache->set('list_wikis_'.$interface.'_'.$tag,$wikis,3600);  //1小时
                //获取不到从本地获取
                if(count($wikis)==0||!$wikis){       
                    $wikis = $this->getLocationVodPrograms($cardId,10,self::$typea[$tag]["tags"],$backurl);  
                    $refer='local';                  
                } 
            }
        }        
        return $this->renderPartial('showProgram', array('programList'=>$programList,'wikis'=>$wikis,'refer'=>$refer)); 
    }
    
    /**
     * 显示当天所有电视节目 海报。供编辑使用。
     * @author majun
     * @date   2012-12-29
     */
    public function executeAll(sfWebRequest $request)
    {
    	$this->page = $request->getParameter('page', 1);//页码
    	$this->date = $request->getParameter('date', date('Y-m-d', time()));//日期
    	
    	$mongo = $this->getMondongo();
    	$programRep = $mongo->getRepository('program');
    	$spsRep = $mongo->getRepository('SpService');
    	$wikiRep = $mongo->getRepository('wiki');
    	//从spservice 取出所有channelcode
    	$chanels = $spsRep -> find(
    		array('query'=>array(
    					'channel_code'=>array('$exists'=>"true",'$ne'=>null)
    		))
    	);
    	
    	foreach ($chanels as $channel) {
    		$channel_codes[] = $channel->getChannelCode();
    	}
    	//按channelcode 取出当天所有电视节目
    	$programs = $programRep -> find(
            array(
                'query' => array(
            		'channel_code' => array('$in' => $channel_codes),
                    'date' => $this->date,
                    'wiki_id'=>array('$exists'=>"true",'$ne'=>null)
                ),
                'sort' => array('time' => 1)
            )
        );
        //取出节目对应wiki  去重
        foreach ($programs as $program) {
        	$wikiArr[] = $program->getWikiId();
        }
        
        $wikiArr = array_unique($wikiArr);
        $this->totalNum = count($wikiArr);
        $wikiArr = array_chunk($wikiArr, 200);//把数据按200每条分割
        //获取该页的wikiid
        $page = $this->page-1;
        $pageArr = $wikiArr[$page];
        if (count($pageArr)>0){
        	foreach ($pageArr as $wikiId) {
        		$wiki = $wikiRep->findOneById(new MongoId($wikiId));
        		$wikis[]=array(
        			'cover'=>$wiki->getCover(),
					'name' =>$wiki->getTitle()        		
        		);
        	}
        }
        
        $this->wikisArr = $wikis;
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
        $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$ccount."&genre=".$type."&uid=".$user_id;
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
    protected function getCenterLivePrograms($user_id,$count=20,$type='')
    {
        return null;
    }
    
    /**
     * 获取技术部同洲厂家的直播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTongzhouLivePrograms($user_id,$count=20,$type='')
    {   
        $programList = null;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        $mongo = $this->getMondongo();
        $sp_repository = $mongo->getRepository('SpService');
        $programs = $mongo->getRepository('program');
        $url = sfConfig::get('app_recommend_tongzhouUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$count."&genre=".$type."&uid=".$user_id;
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
            if($program){
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
    protected function getLocationLivePrograms($user_id,$count=20,$type='')
    {
        $programList = null;
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository('program');
        
        $channels = $mongo->getRepository('SpService')->getServicesByTag(null,'hot',-1);
        $k = 0;
        
        foreach($channels as $channel){
            $program = $program_repo->getLiveProgramByChannelTag($channel->getChannelCode(),$type);
            if($program){
                $programList[]= $program;
                $k++;
            }
            if($k >= $count) break;
        }
        return $programList;
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
    protected function getCenterVodPrograms($user_id,$count=10,$type='',$backurl='',$alg='CF')
    {
        $wikis = null;
        $user_id = $user_id ? $user_id."_0" : "99766609340071223_0";
        $filter  = $type ? urlencode("genre='".$type."'") : "";
        $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count='.$count.'&lang=zh&urltype=1&alg='.$alg.'&uid='.$user_id.'&filter='.$filter.'&backurl='.$backurl;
        $recomTxt = Common::get_url_content($recomUrl);
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
        $wikis = null;
        $mongo = $this->getMondongo();
        $wrRepo = $mongo->getRepository("WikiRecommend");
        $wikiRecommends = $wrRepo->getWikiByPageAndSize(1,$count,$type); 
        foreach($wikiRecommends as $recommend){
            $wikis[]=$recommend->getWiki();
        }
        return $wikis;
    }
}
