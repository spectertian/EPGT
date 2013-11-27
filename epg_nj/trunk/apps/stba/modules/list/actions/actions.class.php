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
        //获取自动隐藏时间
        $mongo = $this->getMondongo();
        $settingRepository = $mongo->getRepository('Setting');
        $rs = $settingRepository->findOne(array('query' => array( "key" => 'autohidden' )));
        if($rs){
            $this->autohidden=$rs->getValue();
        }else{
            $this->autohidden=15000;
        }
        //应急页面,page=2:系统维护 page=3:空白退出页面
        $pageObj = $settingRepository->findOne(array('query' => array( "key" => 'page' )));
        if($pageObj){
            $page=$pageObj->getValue();
        }else{
            $page=-1;
        }
        if($page==2||$page==3){
            $this->setTemplate('error');
        }
        $this->page=$page;
    }
    
    /**
    * 直播和点播数据，ajax调用
    * @author lifucang
    */
    public function executeShowProgram(sfWebRequest $request)
    {
        $tag = $request->getParameter('type','Series');
	    $cardId = $request->getParameter("cardId",'825010288699921');    //智能卡号
        $stbId  = $request->getParameter("stbId",'99666611230068607');   //机顶盒号
        if($_SERVER["SERVER_PORT"]==80||$_SERVER["SERVER_PORT"]=='80'){
            $backurl = 'http://'.$request->getHost().'/list';
            $backurla = urlencode(urlencode($backurl));
        }else{
            $backurl = 'http://'.$request->getHost().':'.$_SERVER["SERVER_PORT"].'/list';
            $backurla = urlencode(urlencode($backurl));
        }
        
        $mongo = $this->getMondongo();
        $wikis = array();  
        $memcache = tvCache::getInstance(); 
        //点播来源
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
        //直播来源
        $rsa = $settingRepository->findOne(array('query' => array( "key" => 'liveWho' )));
        if($rsa){
            $interface_live=$rsa->getValue();
        }else{
            $interface_live = sfConfig::get('app_recommend_liveWho');
        }
        
        $refer = $interface;
        //根据$userId进行缓存
        if($interface=='center'){
            $userId = $stbId;
        }else{
            $userId = $cardId;
        }
        if($tag == 'vod'){
            if($page==1){
                $wikis = $this->getCenterVodProgramsTemp($userId,8,$tag,$backurl);
                $refer='center';
            }else{
                $wikis=$memcache->get("list_wikis_$interface_$userId");
                if(!$wikis){
                    switch($interface){
                        case 'tcl':     
                            $wikis = $this->getTclVodPrograms($cardId, 10, 'vod', $backurl);
                            break;
                        case 'tongzhou':
                            $wikis = Recommand::getTongzhouVodPrograms($cardId, 10, 'vod', $backurl);
                            break;
                        case 'center':
                            $wikis = Recommand::getCenterVodPrograms($stbId, 10, 'vod', $backurla);
                            break;
                    }
                    //获取不到从固定推荐获取
                    if(count($wikis)==0||!$wikis){
                        //$wikis = $this->getLocationVodPrograms($cardId,10,'',$backurl);   //从本地获取
                        //$refer = 'tcl'; 
                        $wikis = $this->getCenterVodProgramsTemp($userId,8,$tag,$backurl);
                        $refer='center';    
                    }else{
                        $memcache->set("list_wikis_$interface_$userId",$wikis,300);  //5分钟
                    }
                }
            }
            $programList = false;
        }else{ 
            $tag = isset(self::$typea[$tag]) ? $tag : 'Series'; 
            //因为直播缓存时间较短，所以直播缓存不在区分接口来源
            $live_key="list_programs".'_'.$tag.'_'.$userId;
            $programList=$memcache->get($live_key);
            if(!$programList){
                switch($interface_live){
                    case 'tcl':
                        $programList = $this->getTclLivePrograms($cardId, 6, $tag);
                        break;
                    case 'tongzhou':
                        $programList = $this->getTongzhouLivePrograms($cardId, 6, $tag);
                        break;
                    case 'center':
                        $programList = $this->getCenterLivePrograms($stbId, 6, $tag);
                        break;
                }
                if(!$programList||count($programList)==0){
                    $programList = $this->getLocationLivePrograms($cardId, 6, self::$typea[$tag]["tags"]);
                }
                $memcache->set($live_key,$programList,60);  //1分钟
            }
            if($page==1){
                $wikis = $this->getCenterVodProgramsTemp($userId,6,$tag,$backurl);
                $refer='center';
            }else{
                //右侧4个点播
                $mem_key='list_wikis_'.$interface.'_'.$tag.'_'.$userId;
                $wikis=$memcache->get($mem_key);
                if(!$wikis){
                    switch($interface){
                        case 'tcl':      
                            $wikis = $this->getTclVodPrograms($cardId,6,$tag,$backurl);
                            //如果不足4个从本地获取
                            /*
                            if(count($wikis)<4||$wikis==null){ 
                                $limit=6-count($wikis);  //多两个防止没有cover
                                $wikisb = $this->getLocationVod(self::$typea[$tag]["tags"],$limit);   
                                if($wikis==null){
                                    $wikis = $wikisb;       
                                }else{
                                    $wikis = array_merge($wikis,$wikisb);       
                                }   
                            }
                            */
                            break;
                        case 'tongzhou':
                            $wikis = Recommand::getTongzhouVodPrograms($cardId,6,$tag,$backurl);
                            break;
                        case 'center':
                            $wikis = Recommand::getCenterVodPrograms($stbId,6,$tag,$backurla);
                            break;
                    }  
                    if(count($wikis)==0||!$wikis){       
                        //$wikis = $this->getLocationVodPrograms($cardId,6,self::$typea[$tag]["tags"],$backurl);  
                        //$refer = 'tcl'; 
                        //从固定推荐获取
                        $wikis = $this->getCenterVodProgramsTemp($userId,6,$tag,$backurl);
                        $refer='center';                  
                    } else{
                        $memcache->set($mem_key,$wikis,300);  //5分钟
                    }
                }
            }
        }        
        return $this->renderPartial('showProgram', array('programList'=>$programList,'wikis'=>$wikis,'refer'=>$refer,'tag'=>$tag)); 
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
     * 显示当天所有电视节目 海报。供上海编辑使用。
     * @author majun
     * @date   2012-12-29
     */
    public function executeAllTemp(sfWebRequest $request)
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
            		//'channel_code' => array('$in' => $channel_codes),
                    'date' => $this->date,
                    'wiki_id'=>array('$exists'=>"true",'$ne'=>null)
                ),
                //'sort' => array('time' => 1)
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
        			'cover'=>$wiki?$wiki->getCover():'',
					'name' =>$wiki?$wiki->getTitle():''        		
        		);
        	}
        }
        
        $this->wikisArr = $wikis;
    }    
    /**
     * 获取图片。
     * @author lifucang
     * @date   2013-10-08
     */
    public function executeGetImage(sfWebRequest $request)
    {
    	$key = $request->getParameter('key', null);
    	if($key){
    	    exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --need_examine=no --file_key=".$key);
    	}
        $this->redirect($request->getReferer());
    }    
    /**
     * 显示当天所有电视节目中没有海报的。供上海编辑使用。
     * @author lifucang
     * @date   2013-05-24
     */
    public function executeAllNoCover(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        $storage = StorageService::get('photo');
        $this->date = $request->getParameter('date', date('Y-m-d'));
        $coverKeys=array();
              
        $query = array('date'=>$this->date,'wiki_id'=>array('$exists'=>true));        
        $programs=$programRes->find(array('query'=>$query));
        //echo $date,"开始统计没有海报的节目数量，请耐心等待！<br/>";
        foreach($programs as $program){
            $wiki = $program->getWiki();
            if($wiki){
                $wikiCover = $wiki->getCover();
                $content = $storage->get($wikiCover);
                if(!$content){
                    $key = $wiki->getTitle();
                    $coverKeys[$key]=$wikiCover;
                }
            }
        }
        //去除重复的key值
        $coverKeys=array_unique($coverKeys);
        //$this->count=count($coverKeys);
        $i=0;
        foreach($coverKeys as $key=>$coverKey){
                $wikis[]=array(
        			'cover'=>$coverKey,
					'name' =>$key        		
        		);
        } 
        $this->wikisArr = $wikis;
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
    private function getLocationLivePrograms($user_id,$count=20,$type='')
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
        $wikis = null;
        $mongo = $this->getMondongo();
        $wrRepo = $mongo->getRepository("WikiRecommend");
        $wikiRecommends = $wrRepo->getWikiByPageAndSize(1,$count,$type); 
        foreach($wikiRecommends as $recommend){
            $wikis[]=$recommend->getWiki();
        }
        return $wikis;
    }
    /**
     * 获取本地点播节目,tcl补齐用
     * @author lifucang
     * @date   2013-01-03
     */ 
    private function getLocationVod($type='',$count=10)
    {
        $wiki_arr = array();
        $mongo = $this->getMondongo();
        $wikiRepo = $mongo->getRepository("Wiki");
        $wikis = $wikiRepo->getWikiByTag($type,$count); 
        foreach($wikis as $wiki){
            $wiki_arr[]=$wiki;
        }
        return $wiki_arr;
    }
    /**
    * 记录直播点击次数
    * @author lifucang 2013-08-01
    */
    public function executeSetLiveHit(sfWebRequest $request)
    {
        $memcache = tvCache::getInstance(); 
        $mem_key = 'liveHit';
        $hits=$memcache->get($mem_key);
        if(!$hits){
            $hits = 0;
        }
        $memcache->set($mem_key,$hits+1,7200);  //2小时，计划任务1小时执行一次，写入数据库，同时将点击次数清0
    }  
}
