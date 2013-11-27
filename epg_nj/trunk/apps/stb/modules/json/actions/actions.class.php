<?php

/**
 * json actions.
 *
 * @package    epg
 * @subpackage json
 * @author     superwen
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
class jsonActions extends sfActions
{
    var $prefix_url;
    var $filter;
    var $videoUrl;
    var $response;
    var $category;    
    var $user;
    var $device;
	/**
     * 构造函数
     * @param sfWebRequest $request
     * @author superwen
     * @final 2012-07-09
     */
	public function __construct($context=null, $moduleName=null, $actionName=null)
    {
		parent::__construct($context, $moduleName, $actionName);
		//$this -> return_array = array("status" => array("code" => 0, "info" => ""));
        $this->setFilter();
        $this->setVideoUrl();
        $this->setCategory();
        $this->setPrefixUrl();  
        $this->user=array();
        $this->device=array();
		$this -> action_array = array(      
                            'GetChannels',                  //正用,根据sp_service获取频道         
              			    'GetChannelsBySP',   
              			    'GetProgramsByChannel',         //正用,根据sp_service获取频道     
              			    'GetLiveCategory',              //正用  
                            'GetLiveProgramesByTag',        //正用  
                            'GetRecommendByChannel',        //正用  
                            'SearchProgram',                //正用  
                            'GetMediaCategory',
                            'GetMediasByCategory',
                            'GetRecommendMedias',
                            'GetFilterOption',
                            'GetThemes',
                            'GetThemeById',
                            'SearchWiki',                    //正用  
                            'GetWikiInfo',                   //正用  
                            'GetWikisByWiki',                //正用  
                            'GetWikiMetas',                  //正用 
                            'GetWikiExtend',
                            'GetLivesByWiki',                //正用  
                            'SearchSuggest',
                            'ReportUserMediaAction',
                            'ReportUserProgramAction',
                            'ReportUserChannelAction',
                            'GetMediasByUser',
                            'GetProgramsByUser',
                            'GetChannelsByUser',
                            'DeleteUserMediaAction',
                            'DeleteUserProgramAction',
                            'GetSystemCitys',
                            'GetDtvSPList',
                            'SetUserConfig',
                            'GetUserConfig',
                            'GetProgramsByChannelGd',       //广电专用,导出数据用
                            'GetWikisDayGd',                //广电专用,导出数据用
                            'GetWikiIdDayGd',               //广电专用,导出数据用
                            'GetWikiInfoGd',                //广电专用,导出数据用
                            'GetWikiMetasGd',               //广电专用,导出数据用
                            'GetWikiInfoByAssetId',         //11月29日@gaobo
                            'GetAttachments',
                            );
    }
	
    /**
    * Executes index action
    *
    * @param sfRequest $request
    * @author superwen
    * @final 2012-07-09
    */
    public function executeIndex(sfWebRequest $request) 
	{
		$HTTP_RAW_POST_DATA = file_get_contents('php://input');
        $this->getResponse()->setContentType('text/plain');
        if ($request->getMethod() == 'POST') {
			if($request->getPostParameter('jsonstr')){
				$jsonstr = $request->getPostParameter('jsonstr');
			}else {
				//$jsonstr = str_replace("jsonstr=","",$HTTP_RAW_POST_DATA);
                $jsonstr = preg_replace("/^.*=$/","",$HTTP_RAW_POST_DATA);
			}
			$jsonstr = json_decode($jsonstr, true);
			
			if(!$jsonstr or JSON_ERROR_NONE != json_last_error()) {
				//$this -> return_array = array("status" => array("code" => 1001, "info" => "未知数据格式"));
                $nodeArray = $this->getErrArray(1001,'未知数据格式');
				return $this->renderText(json_encode($nodeArray)); 
			}
			if(!$jsonstr["action"] or !in_array($jsonstr["action"],$this -> action_array)) {
                $nodeArray = $this->getErrArray(1002,'未定义或者错误的方法');
				return $this->renderText(json_encode($nodeArray)); 
			}
			if(!$this->checkDevice($jsonstr["device"])){
			    $nodeArray = $this->getErrArray(1003,'未定义的设备');
				return $this->renderText(json_encode($nodeArray));
			}
            /*
			if(!$this->checkUser($jsonstr["user"])) {
			    $nodeArray = $this->getErrArray(1004,'未定义的用户');
				return $this->renderText(json_encode($nodeArray));
			}
            */			
			$method = $jsonstr["action"];
			//return $this->renderText($method);
			$nodeArray=$this->$method($jsonstr["param"]);			
			return $this->renderText(json_encode($nodeArray));
        } else {
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText('The Json Server accepts POST requests only.');
        }      
    }
	
    /**
     * 返回错误代码
     * @author lifucang
     * @final 2012-07-19
     */
    public function getErrArray($errorStatus=0,$message='',$total=null,$num=null){
        $nodeArray = array();
        $nodeArray['error'] = array(
                'code' => $errorStatus,
                'info' => $message,
        );
		if(!is_null($total))
		{
			$nodeArray['total'] = $total;
		}
		if(!is_null($num))
		{
			$nodeArray['num'] = $num;
		}
		return $nodeArray;
    }
	/**
	 * 根据数据获取设备参数
	 * @author superwen
	 * @final 2012-07-19
	 */
	private function checkDevice($device) {
		if(!$device['dnum']||!$device) {
			return false;
		}
		$this->device['dnum'] = $device['dnum'] ? $device['dnum'] : "123";
		$this->device['type'] = "mst123";
		return true;
	}
	
	/**
	 * 根据数据获取用户参数
	 * @author superwen
	 * @final 2012-07-19
	 */
	private function checkUser($user) {
		if(!$user['userid']||!$user) {
			return false;
		}
		$this->user['userid'] = $user['userid'] ? $user['userid'] : "123";
		$this->user['token'] = "ssdleeeccsodfasdf";
		return true;
	}
    //################################################################
    //################直播相关接口####################################
    //################################################################  
	/**
     * 获取所有频道
     * @param 无
     * @author lifucang
     * @final 2012-10-25
     */
    private function GetChannels($param) 
	{ 		
        $nodeArray = array();
        $type=$param['type'] ? $param['type'] : null;
        //$channels = Doctrine::getTable('Channel')->getChannels();
    	$mongo = sfContext::getInstance()->getMondongo();
    	$ServiceRepository = $mongo->getRepository('SpService');
        $channels = $ServiceRepository->getServicesByTag($type);
		if($channels) {
			$nodeArray = $this->getErrArray(0,'',count($channels));
            $i=0;
			foreach($channels  as $key =>$channel)
			{
				$nodeArray["channels"][$i] = array(
					'name' => $channel->getName(),
					'code' => $channel->getChannelCode(),
					'type' => $channel->getTags(),
					'logo' => file_url($channel->getChannelLogo()),
				  'serviceId' => $channel->getServiceId(),
				  'frequency' => $channel->getFrequency(),
				  'logicNumber' => $channel->getLogicNumber()
				);
                $i++;
			}
        }
        return $nodeArray;
    }		
	/**
     * 根据SP或省份获取频道列表
     * @param Array(type,typevalue) $param['typevalue']当$param['type']==1时为sp的名称，为2是省的名称
     * @author superwen
     * @editor lifucang
     * @final 2012-07-09
     */
    private function GetChannelsBySP($param) 
	{ 		
        $nodeArray = array();
        $type=$param['type'] ? $param['type'] : 1;
        $typevalue=$param['typevalue'];
        if($type==1){
            if($typevalue==''||empty($typevalue)){
                $channels = Doctrine::getTable('Channel')->getWeiShiChannels();
            }else{
                $mongo = sfContext::getInstance()->getMondongo();
                $spRepository = $mongo->getRepository('sp');
    		    $sp = $spRepository->getOneSpByName($typevalue);
                if($sp){
                    $channels = $sp->getChannelObjs();
                }else{
                    $nodeArray = $this->getErrArray(1,'无该运营商信息');
                }
            }
        }else{
        	if($typevalue==''||empty($typevalue))
        		$channels = Doctrine::getTable('Channel')->getWeiShiChannels();
        	else
        		$channels = Doctrine::getTable('Channel')->getUserChannels('',$typevalue);
        }
		if($channels) {
			$nodeArray = $this->getErrArray(0,'',count($channels));
			foreach($channels  as $key =>$channel)
			{
				$nodeArray["channels"][$key] = array(
					'name' => $channel['name'],
					'code' => $channel['code'],
					'memo' => $channel['memo'],
					'type' => $channel['type'],
					'logo' => file_url($channel['logo']),
					'hot'  => $channel['hot']
				);
			}
        }
        return $nodeArray;
    }	
	
	/**
     * 根据频道号和日期获取节目列表
     * @param $param Array(channel_code,start_time,end_time)
     * @author lifucang 针对南京项目做了更改
     * @final 2012-10-25
     */
    private function GetProgramsByChannel($param) 
	{        
    	$nodeArray = array();
    	$channelcode = $param["channel_code"] ? $param["channel_code"] : "cctv1";
    	$starttime = $param['start_time']?$param['start_time']:date('Y-m-d H:i:s');
    	$endtime = $param['end_time']?$param['end_time']:date('Y-m-d 23:59:59');
    	$mongo = sfContext::getInstance()->getMondongo();
    	$ProgramRepository = $mongo->getRepository('Program');
        //$channel = Doctrine_Core::getTable("channel")->findOneByCode($channelcode);
    	$mongo = sfContext::getInstance()->getMondongo();
    	$ServiceRepository = $mongo->getRepository('SpService');        
        $channel=$ServiceRepository->findOne(array('query'=>array('channel_code'=>$channelcode)));
        if($channel)
        {
            $programs = $ProgramRepository->getProgramsByCode($channelcode,$starttime,$endtime);
			$nodeArray = $this->getErrArray(0,'',count($programs));
	        $nodeArray['channel'] = array(
	        	'name'=>$channel->getName(),
	        	'code'=>$channel->getChannelCode(),
	        	'logourl'=>file_url($channel->getChannelLogo()),
	        	'hot'=>$channel->getHot(),
	        );
	        foreach($programs as $key =>$program)
	        {
	            $wiki_info = $program->getWiki();
	            //$hasVideo = ($wiki_info['has_video']>0)?'yes':'no';
	            //$source = implode(',',$wiki_info['source']);
                if($wiki_info){
    				$nodeArray['programs'][$key] = array(
    					'name' => $program['name'],
    					'date' => $program['date'],
    					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
    					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
    					'wiki_id' => $program['wiki_id'],
    					'wiki_cover' => file_url($wiki_info['cover']),
    					'tags' => $wiki_info['tags'],
    	            );    
                    $nodeArray = $this->getWikiVideoSource($wiki_info, $key, $nodeArray,'programs');  
                }else{
    				$nodeArray['programs'][$key] = array(
    					'name' => $program['name'],
    					'date' => $program['date'],
    					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
    					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
    					'wiki_id' => $program['wiki_id'],
    	            );  
                }
			}        	
        }else{
            $nodeArray = $this->getErrArray(1,'该频道不存在');
		}
		return $nodeArray;
    }
	
	/**
     * 获取直播的Tag
     * @param 无
     * @author superwen
     * @editor lifucang
     * @final 2012-07-20
     */
    private function GetLiveCategory($param) 
	{
		$nodeArray = array();
		$tags = array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
		$num = count($tags);
        $nodeArray = $this->getErrArray(0,'',$num);
        $nodeArray['class']=$tags;
        return $nodeArray;     
    }
	
    
	/**
     * 根据标签获取直播的节目
     * @param Array(tag,start_time,end_time)
     * @return json
     * @author lifucang
     */    
	private function GetLiveProgramesByTag($param)
	{
		$nodeArray = array();
    	$tag = $param['tag'];
    	$starttime = $param['start_time'];
    	$endtime = $param['end_time'];
    	if($starttime==''&&$endtime=='')$starttime = $endtime =date("Y-m-d H:i:s",time());

    	$channels = Doctrine::getTable('Channel')->getChannels();
		$mongo = sfContext::getInstance()->getMondongo();
		$ProgramRepository = $mongo->getRepository('Program');
		$WikiRepository = $mongo->getRepository('Wiki');
		
		//$next = $ProgramRepository->getNextUpdate($channels,$endtime,$tag);   //获取下一个节目时间
		$programs = $ProgramRepository->getPrograms($channels,$tag,$starttime,$endtime);unset($channels);
        $nodeArray = $this->getErrArray(0,'',count($programs));
        $i = 0;
		foreach($programs as $program_key =>$program)
		{
//			$wiki = $WikiRepository->findOneById(new MongoId($program['wiki_id']));
			$wiki = $WikiRepository->getWikiById($program['wiki_id']);
			if($wiki) 
			{
				$channel = $program->getSpService();
				$nodeArray['programs'][$i] = array(
					'wiki_id'    => (string)$wiki->getId(),
					'title'   => $wiki->getTitle(),
					'start_time' => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
					'end_time' => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
					'channel_code'=>$channel->getChannelCode(),
					'channel_name'=>$channel->getName(),
					'channel_logourl'=>thumb_url($channel->getChannelLogo(),93,50),
				);
				$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray,'programs');
				$i = $i+ 1;
			}
		}
        /*
		if($next)
			$nodeArray['nextupdate'] = date("Y-m-d H:i:s",$next->getStartTime()->getTimestamp());
		else
			$nodeArray['nextupdate'] ='';
		*/
        return $nodeArray;
	}

 
	/**
     * 获取电视频道的推荐列表
     * @param Array(channel_code)
     * @author lifucang  
     */
	public function GetRecommendByChannel($param)
	{
		$nodeArray = array();
    	$channelcode = $param["channel_code"] ? $param["channel_code"] : "cctv1";
    	$channel_recommends = Doctrine::getTable('ChannelRecommend')->createQuery('c')->where("channel_code = ?",$channelcode)->orderBy('sort')->execute();
    	if(count($channel_recommends)==0)
    	{
    		return $this->getErrArray(0,'无推荐频道');  		
    	}
    	$nodeArray = $this->getErrArray(0,'');
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$i = 0;
        foreach($channel_recommends as $key =>$channel_recommend)
        {
            $wiki = $WikiRepository->findOneById(new MongoId($channel_recommend['wiki_id']));
            if($wiki)
            {
				$nodeArray['medias'][$i] = array(
                            	'wiki_id'       => (string)$wiki->getId(),
                            	'title'    => $wiki->getTitle(),
								'playtime' => $channel_recommend->getPlaytime(),
								'remark'   => $channel_recommend->getRemark(),
								'img'      => file_url($channel_recommend->getPic()),
                    		);
				$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray);
				$i++;
            }
        }
		$nodeArray['total'] = $i;   
		return $nodeArray;
	}  
    
	/**
     * 根据关键字搜索节目列表
     * @param Array(province,key,start_time,end_time)
     * @author lifucang
     */
	public function SearchProgram($param)
	{
		$nodeArray = array();
    	$key = trim($param['key']);
    	$starttime = $param['start_time']?$param['start_time']:date('Y-m-d H:i:s');
    	//$endtime = $param['end_time']?$param['end_time']:date('Y-m-d H:i:s',strtotime('+0.5 day'));
        $endtime = $param['end_time']?$param['end_time']:date('Y-m-d 23:59:59');
    	if($key==''||empty($key))
    	{
    		return $this->getErrArray(1,'请传递key值');  		
    	}
	    
    	$channels = Doctrine::getTable('Channel')->getChannels(); //获取所有频道
		$nodeArray = $this->getErrArray(0,'');
		
		$mongo = sfContext::getInstance()->getMondongo();
		$ProgramRepository = $mongo->getRepository('Program');
		$WikiRepository = $mongo->getRepository('Wiki');
		
		$programs = $ProgramRepository->getProgramsByCKSE($channels,$key,$starttime,$endtime);
		unset($channels);
        $i = 0;
		foreach($programs as $program_key =>$program)
		{
			$wiki = $WikiRepository->getWikiById($program['wiki_id']);
			if($wiki) 
			{
				$channel = $program->getSpService();
				$nodeArray['programs'][$i] = array(
                    'channel_code' => $channel->getChannelCode(),
                    'channel_name' => $channel->getName(),
                    'channel_logo' => thumb_url($channel->getChannelLogo(),93,50),
					'name'         => $program->getName(),
					'date'         => $program->getDate(),
					'start_time'   => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
					'end_time'     => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
					'wiki_id'      => (string)$wiki->getId(),
					'wiki_cover'   => file_url($wiki->getCover()),
					'tags'         => !$wiki->getTags() ? '' : $this->getTag($wiki->getTags(),array($this->category[1]['name'],$this->category[2]['name'])),
				);
                $nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray,'programs');
				$i = $i+ 1;
			}
		}
		$nodeArray['total'] = $i;
		return $nodeArray;
	} 
    //################################################################
    //################点播相关接口####################################
    //################################################################    
    /**
     * 获取影视分类
     * @param 无
     * @author lifucang
     */
    public function GetMediaCategory($param){
        $nodeArray = array();
        $category = $this->category;
        if($category)
        {
            $nodeArray = $this->getErrArray(0,'',count($category));
            foreach($category  as $key =>$value)
            {
               if($value['name']){
                   if($value['child'])
                   {
                       $type = 1;
                       $categoryNum = count($value['child']);
                   }
                   $nodeArray['class'][$key] = array(
                       'id' => $key,
                       'title' => $value['name'],
                       'type' => $type,
                       'num'  => $categoryNum,
                       'img'  => '',
                   );
                   foreach($value['child'] as $index => $child)
                   {
                        $nodeArray['class'][$key]['subclass'][] = array(
                            'id'    => $index,
                            'title' => $child,
                            'num'   =>   0,
                            'img'   => '',
                        );
                   }
               }
            }
        }
        else
        {
            $nodeArray = $this->getErrArray(0,'无影视分类');
        }
        return $nodeArray;
    }

    /**
     * 获取分类影视列表
     * @param Array(cid,page,size,order,filter('type'=>'时间','value'=>'2011')))
     * @author lifucang
     */
    public  function GetMediasByCategory($param){
        $nodeArray = array();
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
 
        $cid = $param['cid'];
        $page = $param['page']?$param['page']:1;
        $size = $param['size']?$param['size']:8;
        $offset = $size * ($page-1);
        $order = $param['order']?$param['order']:1;
        $filters = $param['filter'];
        switch($order)
        {
        	case '1':
				$wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
				$tag = $this->getTagName($cid);
				if(!empty($tag))
				{
					$hotPlays = $wikiRecommendRepo->getWikiByTag($tag,$size,$offset);
				}
				else
				{
					$hotPlays = $wikiRecommendRepo->getWikiByPageAndSize($page,$size);
				}
				$wikiRepo = $mongo->getRepository("Wiki");
				//$buyaode = $wikiRepo->buyaode;
				$wikis = array();
				foreach($hotPlays as $hotPlay)
				{
					//if(in_array($hotPlay->getWikiId(),$buyaode))//删除
					//	continue;//删除
					$wiki = $wikiRepo->findOneById(new MongoId($hotPlay->getWikiId())); 
		            if (!empty($wiki)) 
		            {
		                $wikis[] = $wiki;
		                unset($wiki);
		            }
				}
				$num = count($wikis);//*********************************************************
				if(!empty($tag))
					$totalHotPlays = $wikiRecommendRepo->getWikiByTag($tag);//******************************************
				else
					$totalHotPlays = $wikiRecommendRepo->getWiki();
				$totalWikis = array();
        		foreach($totalHotPlays as $totalHotPlay)
				{
				//	if(in_array($totalHotPlay->getWikiId(),$buyaode))//删除
				//		continue;//删除
					$totalWiki = $wikiRepo->findOneById(new MongoId($totalHotPlay->getWikiId())); 
		            if (!empty($totalWiki)) 
		            {
		                $totalWikis[] = $totalWiki;
		                unset($totalWiki);
		            }
				}
				$total = count($totalWikis);					
                $nodeArray = $this->getErrArray(0,'',$total,$num);
				foreach($wikis as $key => $wiki)
				{
                    $nodeArray['media'][$key] = array(
                            'id'    => (string)$wiki->getId(),
                            'title'   => $wiki->getTitle(),
                    );
                    $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
                }			            
				break;
        	case '2':
				$nodeArray = $this->getMediaBySort($cid,$filters,$page,$size, 0);
				break;
        	case '3':
				$nodeArray = $this->getMediaBySort($cid,$filters,$page,$size, 2);
				break;
        	default:
                $nodeArray = $this->getErrArray(1,'order='.$order.'无匹配，可能是 参数/值 错误');
        }
        return $nodeArray;
    }
    /**
     * 获取推荐影视列表
     * @param Array(page,size,tag)
     * @author wangnan
     */
     
    public function GetRecommendMediaFromMondongo($param) 
    {
		$page = $param['page'] ? $param['page'] : 1;
		$size = $param['size'] ? $param['size'] : 8;
		$tag  = $param['tag'];         
    
		$mongo = sfContext::getInstance()->getMondongo();
		$wikiRepository = $mongo->getRepository('wiki');
		$wrRepo = $mongo->getRepository("WikiRecommend");
		$wikiRecs = $wrRepo->getWikiByPageAndSize($page,$size,$tag);
		$totalWikiRecs = $wrRepo->getWikiByPageAndSize(0,9999,$tag);
        if($wikiRecs){
            $nodeArray = $this->getErrArray(0,'',count($totalWikiRecs),count($wikiRecs));
            foreach($wikiRecs as $key => $wikiRec){
//					$wiki = $wikiRepository->findOneById(new MongoId($wikiRec['wiki_id']));
				$wiki = $wikiRepository->getWikiById($wikiRec['wiki_id']);
                if($wiki){
                    $nodeArray['media'][$key] = array(
                        'id' => (string)$wiki->getId(),
                        'title'=> $wiki->getTitle(),
                    );
                    $nodeArray = $this->getWikiVideoSource($wiki,$key, $nodeArray);
                }
            }
        }else{
            $nodeArray = $this->getErrArray(0,'');
        }
        return $nodeArray;
    }
    


    /**
     * 获取推荐影视列表
     * @direction  获取推荐影视列表，从Least Click TV API获取数据
     * @param Array(size,sort,detail)
     * @author lifucang
     */
     public function GetRecommendMedias($param) 
     {
		$size = $param['size'] ? $param['size'] : 10;
        $sort = $param['sort'] ? $param['sort'] : 'default';
        $detail = $param['detail'] ? $param['detail'] : false;
        $userId=$this->user['userid'];
        
        $memcache = tvCache::getInstance();
        $memcache_key = md5('GetRecommendMedias'.",$size,$sort,$detail,$userId");
        $arrmd5 = $memcache->get($memcache_key);
        if(!$arrmd5){
            $url=sfConfig::get('app_lct_server_url')."api/media/user/$userId/recommendations";
            $url.="?size=$size&sort=$sort&detail=$detail";
            
            $contents=file_get_contents($url);
            if(!$contents){
                return $this->GetRecommendMediaFromMondongo($param);  //如果获取不到从原来获取
            }
            $arr_contents=json_decode($contents);
            if($arr_contents->success==1){
                $arr = $this->getErrArray(0,'',count($arr_contents->objects),$size);
                foreach($arr_contents->objects as $key => $value){
                    $arr['media'][$key] = array(
                        'id' => (string)$value->id,
                        'title'=> $value->title,
                    );   
                    //获取详细信息
                    $arr = $this->getLctVideoSource($value,$key, $arr);                
                }
                $memcache->set($memcache_key,$arr); //设置memcache
            }else{
                //$arr = $this->getErrArray("false", null,null,'');
                return $this->GetRecommendMediaFromMondongo($param);  //如果获取不到从原来获取
            }            
        }else{
            $arr=$arrmd5;
        }
        return $arr;
    }

    /**
     * 获得筛选选项
     * @param  空
     * @author lifucang
     */
    public function GetFilterOption($param) {
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $listOption = $wikiRep->getUsedArr();
        $nodeArray = array();
        $nodeArray = $this->getErrArray(0,'',count($listOption));
        $i = 0;
        $nodeArray['filters'][0]['name'] = "地区";
        $nodeArray['filters'][0]['num'] = count($listOption['country']);        
        foreach($listOption['country'] as $area) {
            $nodeArray['filters'][0]['filter'][$i]['name'] = $area;
            $i++;
        }
        $j = 0;
        $nodeArray['filters'][1]['name'] = "时间";
        $nodeArray['filters'][1]['num'] = count($listOption['time']);
        foreach($listOption['time'] as $key => $time) {
            $nodeArray['filters'][1]['filter'][$j] = array('name'=>$key,'value'=>$time);
            $j++;
        }
        return $nodeArray;
    }
	/**
     * 获取专题列表
     * @param Array(page,size)
     * @author lifucang
     */
	public function GetThemes($args)
	{
		$nodeArray = array();
    	$page = $args['page'];
    	$size = $args['size'];
    	$page=empty($page)?1:$page;
    	$size=empty($size)?8:$size;
    	$countThemes = Doctrine::getTable('Theme')->getThemeByPageAndSize($page,$size);
    	$totalThemes = Doctrine::getTable('Theme')->getThemes();
		$count = count($countThemes);
		$total = count($totalThemes);
        $nodeArray = $this->getErrArray(0,'',$total,$count);
		foreach($countThemes as $key=>$theme)
		{   	
			$nodeArray['themes'][] = array(
                            'id'    => $theme->getId(),
                            'title'    => $theme->getTitle(),
                            'remark'    => $theme->getRemark(),
                            'img'   => file_url($theme->getImg()),
                    );
		}       
		return $nodeArray; 
	}    
	/**
     * 根据ID获取专题的详细信息
     * @param Array(tid)
     * @author lifucang
     */
	public function GetThemeById($args)
	{
		$nodeArray = array();
    	$tid = $args['tid'];
        if($tid==''){
    		return $this->getErrArray(1,'请填写tid');
        }
    	$theme = '';
    	$theme = Doctrine::getTable('Theme')->findOneById($tid);
    	if(!$theme)
    	{
    		return $this->getErrArray(1,'主题不存在');
    	}
    	if($theme->getPublish()==0)
    	{
    		return $this->getErrArray(1,'该主题未发布');
    	}
    	$items = Doctrine::getTable('ThemeItem')->getItemsByThemeId($tid);
		$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$total = count($items);
        $nodeArray = $this->getErrArray(0,'',$total);
		$nodeArray['theme'] = array(
			'title'  => $theme->getTitle(),
			'remark' => $theme->getRemark(),
			'img'    => file_url($theme->getImg())	
        );
		foreach($items as $key=>$item)
		{   	
			$wiki = $WikiRepository->findOneById(new MongoId($item->getWikiId()));
			$nodeArray['media'][$key] = array(
                            'id'    => (string)$wiki->getId(),
                            'title'   => $wiki->getTitle(),
							'remark' => $item->getRemark(),
							'img' => file_url($item->getImg()),
                    );
            $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
            $screen_num = $wiki->getScreenshotsCount();        
            $nodeArray['media'][$key]['screens']= array(
                            'num'    => $screen_num,
                    );
            $screens = $wiki->getScreenshotUrls();   
            foreach($screens as $k => $screen)
            {
	            $nodeArray['media'][$key]['screens']['screen'][$k]= array(
	                            'url'    =>  $screens[$k],
	                    ); 
            }
		}
		return $nodeArray; 
	}   
    /**
     * 通过关键字获得相应的wiki
     * @param Array(keyword,field,page,size)
     * @author lifucang
     */
    public function SearchWiki(array $parameter) {
        $keyWord = $parameter['keyword'];
        $field = $parameter['field'];
		if($field)
        {
        	$keyWord = '';
        	$fields = explode('+',$field);
        	foreach($fields as $field)
        	{
          		$keyWord .= $field.':'.$parameter['keyword'].' OR ';
        	}
        }
		$keyWord = preg_replace("/OR $/", "",  $keyWord);                
        $page = $parameter['page']?$parameter['page']:1;
        $size = $parameter['size']?$parameter['size']:10;
        $offset = $size * ($page - 1);
        $total = NULL;
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $result = $wikiRep->xun_search($keyWord, $total, (int)$offset, (int)$size,null,1);
        $pageTotal = count($result);
        $arr = array();
        if(!$result){
            $arr = $this->getErrArray(0,'');
        }
        else{
            $arr = $this->getErrArray(0,'',$total,$pageTotal);
            $i = 0;
            foreach($result as $res) {
                $arr['medias'][$i]['wiki_id'] = (string)$res->getId();
                $arr['medias'][$i]['title'] = $res->getTitle();
                $arr = $this->getWikiVideoSource($res, $i, $arr); //1,1为不显示getWikiVideoSource后面的信息
                $i++;
            }
        }
        return $arr;
    }      
	/**
     * 按照wiki_id获取wiki详细信息
     * @param Array(wiki_id)
     * @author lifucang 
     */
	public function GetWikiInfo($args)
	{
		$nodeArray = array();
    	$wiki_id = $args['wiki_id'];
        if(empty($wiki_id)){
    		return $this->getErrArray(1,'请填写wiki_id');
        }
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
		if($wiki) 
		{
			$nodeArray = $this->getErrArray(0,'',1);
			$nodeArray['media'] = array(
                            'wiki_id'    => (string)$wiki->getId(),
                            'title'   => $wiki->getTitle(),
            );
            $nodeArray = $this->getOneWikiVideoSource($wiki, 0, $nodeArray);
            /*
        	$userRepository = $mongo->getRepository('user');
        	$hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        	if($hasUser)
        	{        
        		$user_id = $hasUser->getId();    
				$chipRepository = $mongo->getRepository('singlechip');
				$chip = $chipRepository->getOneChip((string)$user_id,$wiki_id);
				if($chip)
				{
		            $nodeArray['media']['action']= array(
		                            'type' => 'favorite',
		            				'var'  => '1',
		            				'datetime' => date("Y-m-d H:i:s",$chip->getCreatedAt()->getTimestamp()),
		                    );
				}
				else
		            $nodeArray['media']['action']= array(
		                            'type' => '',
		            				'var'  => '',
		            				'datetime' => '',
		            	);				
        	}
        	else  
	            $nodeArray['media']['action']= array(
	                            'type' => '',
	            				'var'  => '',
	            				'datetime' => '',
	                    );  
            /*                  	         
		    $screen_num = $wiki->getScreenshotsCount();        
            $nodeArray['media']['screens']= array(
                            'num'    => $screen_num,
                    );
            $screens = $wiki->getScreenshotUrls();   
            foreach($screens as $k => $screen)
            {
	            $nodeArray['media']['screens']['screen'][$k]= array(
	                            'url'    =>  $screens[$k],
	                    ); 
            }   
            */ 
            //获取节目信息lfc
            /*
            $program_repository = $mongo->getRepository('Program');
            $programs = $program_repository->getdayUnPlayedProgramByWikiId($wiki_id);   
            $programs_num=count($programs);
            $nodeArray['media']['programs']= array(
                            'num'    => $programs_num,
                    );
            foreach($programs as $k => $program)
            {
				$endTime = $program->getEndTime();
	            $nodeArray['media']['programs']['program'][$k]= array(
	                            'channel_code'    =>  $program->getChannelCode(),
                                'channel_logo'    =>  $program->getChannel()->getLogoUrl(),
                                'channel_name'    =>  $program->getChannelName(),
                                'program_name'    =>  $program->getName(),
                                'start_time'      =>  date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
                                'end_time'        =>  !empty($endTime)?date("Y-m-d H:i:s",$endTime->getTimestamp()):'',
                        ); 
            }   
            */   
               
		}
		else 
		{
			$nodeArray = $this->getErrArray(1,'未找到数据');
		}
		return $nodeArray; 
	}
	/**
     * 按照wiki_id获取相关wiki
     * @param array(wiki_id)
     * @author lifucang
     */
	public function GetWikisByWiki($args)
	{
		$nodeArray = array();
    	$wiki_id    = $args['wiki_id'];
        if(empty($wiki_id)){
    		return $this->getErrArray(1,'请填写wiki_id');
        }
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$wikis = $WikiRepository->getWikisById($wiki_id);
		if($wikis){
            $nodeArray = $this->getErrArray(0,'',count($wikis));
            $i=0;
            foreach($wikis as $wiki){
				$nodeArray['medias'][$i] = array(
                            	'wiki_id'       => (string)$wiki->getId(),
                            	'title'    => $wiki->getTitle(),
                    		);
				$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray);
                $i++;
            }
		}
		return $nodeArray; 
	}
	/**
     * 按照wiki_id获取分集剧情
     * @param array(wiki_id)
     * @editor lifucang 2013-1-16
     */
	public function GetWikiMetas($args)
	{
		$nodeArray = array();
    	$wiki_id = $args['wiki_id'];
        $title   = $args['title'];
        $mark    = $args['mark'];
        $year    = $args['year'];
        $month   = $args['month'];
        if(empty($wiki_id)&&empty($title)){
    		return $this->getErrArray(1,'请填写wiki_id或title');
        }  
        if($wiki_id){
            $query = array('wiki_id'=>$wiki_id);
        }else{
            $query = array();
            if($title != '')
                $query['title'] = new MongoRegex("/.*$title.*/i");
            if($mark != '')
                $query['mark'] = $mark;
            if($year != '')
                $query['year'] = $year;
            if($month != '')
                $query['month'] = $month;
        }
    	$mongo = sfContext::getInstance()->getMondongo();
		$Repository = $mongo->getRepository('WikiMeta');
		$metas = $Repository->find(array('query'=>$query,'sort'=>'mark'));
        if($metas){
            $nodeArray = $this->getErrArray(0,'',count($metas));                   
    		foreach($metas as $meta)
    		{
    		    if($year){
        			$nodeArray[] = array(
                        'wiki_id'    => $meta->getWikiId(),
        				'title'      => $meta->getTitle(),
        				'content'    => $meta->getContent(),
        				'html_cache' => $meta->getHtmlCache(),
                        'guests'     => $meta->getGuests()?implode(',',$meta->getGuests()):null,
                        'year'       => $meta->getYear(),
                        'month'      => $meta->getMonth(),
        			); 
    		    }else{
        			$nodeArray[] = array(
                        'wiki_id'    => $meta->getWikiId(),
        				'title'      => $meta->getTitle(),
        				'content'    => $meta->getContent(),
        				'html_cache' => $meta->getHtmlCache(),
        				'mark'       => $meta->getMark()
        			); 
    		    }
    		}  
        }else {
			$nodeArray = $this->getErrArray(1,'未找到数据');
		}
		return $nodeArray;
	}
    
	/**
     * 按照wiki_id获取wiki扩展信息
     * @param array(wiki_id,extendtype)
     * @author lifucang
     */
	public function GetWikiExtend($args)
	{
		$nodeArray = array();
    	$wiki_id    = $args['wiki_id'];
    	$extendtype = $args['extendtype']?$args['extendtype']:4;
        if(empty($wiki_id)){
    		return $this->getErrArray(1,'请填写wiki_id');
        }
        if(empty($extendtype)){
    		return $this->getErrArray(1,'请填写extendtype');
        }        
    	$type = array(1,2,3,4);
    	if(in_array($extendtype,$type)===false) 
    		return $this->getErrArray(1,'extendtype只能为1,2,3,4');
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
		if($wiki) 
		{
			$nodeArray = $this->getErrArray(0,'');
			switch($extendtype)
			{
				case 1:
					break;
				case 2:
					break;
				case 3:
					break;
				case 4:
					$nodeArray = $this->getWikiComments($nodeArray,$wiki_id);
					break;
			}
		}
		else 
		{
			$nodeArray = $this->getErrArray(1,'该wiki不存在');
		}
		return $nodeArray; 
	}
	/**
     * 根据wiki_id获取直播列表
     * @param Array(wiki_id,start_time,end_time)
     * @author lifucang 
     */
	public function GetLivesByWiki($args)
	{
		$nodeArray = array();
    	$wiki_id = $args['wiki_id'];
    	$start_time = $param['start_time']?$param['start_time']:date('Y-m-d H:i:s');
        $end_time = $param['end_time']?$param['end_time']:date('Y-m-d 23:59:59');        
        if(empty($wiki_id)){
    		return $this->getErrArray(1,'请填写wiki_id');
        }
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
		if($wiki) 
		{
            //获取节目信息lfc
            $program_repository = $mongo->getRepository('Program');
            $programs = $program_repository->getProgramByWikiIdTime($wiki_id,$start_time,$end_time);   
            $nodeArray = $this->getErrArray(0,'',count($programs));
            $i=0;
            foreach($programs as $k => $program)
            {
				$endTime = $program->getEndTime();
	            $nodeArray['programs'][$i]= array(
                                'name'            =>  $program->getName(),
                                'start_time'      =>  date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
                                'end_time'        =>  !empty($endTime)?date("Y-m-d H:i:s",$endTime->getTimestamp()):'',
	                            'channel_code'    =>  $program->getChannelCode(),
                                'channel_name'    =>  $program->getSpName(),
                                'channel_logo'    =>  thumb_url($program->getSpLogo(),93,50),
                        ); 
                 $i++;       
            }  
		}else {
			$nodeArray = $this->getErrArray(1,'未找到数据');
		}
		return $nodeArray; 
	}    
    /**
     * 搜索建议
     * @param Array(keyword)
     * @author lifucang 
     */
    public function SearchSuggest(array $args) {
        $keyWord = $args['keyword'];
        $mongo = sfContext::getInstance()->getMondongo();
        if(empty($keyWord)){
            $setting = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => 'hotsearchkey' ));
            $rs = $setting->findOne($query);
            $arr = array();
            if($rs){
                $arr_value=json_decode($rs->getValue());  //数组
                $total = count($arr_value);
                $arr = $this->getErrArray(0,'',$total);
                $i = 0;
                if($total<5){
                    foreach($arr_value as $value) 
                    {
                        //if($i>=5)
                        //    break;
                        $arr['tag'][$i] = $value;
                        $i++;
                    }   
                }else{
                    $arr_use=array_rand($arr_value,5);
                    foreach($arr_use as $value) 
                    {
                        $arr['tag'][$i] = $arr_value[$value];
                        $i++;
                    }    
                }
            }else{
                $arr = $this->getErrArray("false", null,null);
            }
        }else{
            $wikiRep = $mongo->getRepository('wiki');
            $result = $wikiRep->xun_search("title:".$keyWord, $total, 0, 9999,null,1);
            $total = count($result);
            $arr = array();
            if(!$result)
            {
                $arr = $this->getErrArray(0,'');
            }
            else
            {
                $arr = $this->getErrArray(0,'',$total);
                $i = 0;
                foreach($result as $res) 
                {
                    $arr['media'][$i]['id'] = (string)$res->getId();
                    $arr['media'][$i]['title'] = $res->getTitle();
                    $arr['media'][$i]['extra'] = $res->getModel();
                    $i++;
                }
            }  
        }
        return $arr;
    } 
    //################################################################
    //################用户交互相关接口################################
    //################################################################    
    /**
     * 提交用户影视操作
     * @param array(type,mid(wiki_id),praise(0:不喜欢,1:喜欢),comment(评论内容))
     * type=1:影视wiki踩和顶操作
     * type=2:加入片单
     * type=3:添加看过
     * type=4:通过wiki_id,device_id 删除片单中wiki(已用DeleteUserMediaAction方法代替)
     * @author guoqiang.zhang
     */
    public function ReportUserMediaAction($data){
        $device = $this->device;
        $user = $this->user;
        if(!in_array($data['type'], array("1",'2','3'))){
            //接口方法是否正确
             return $this->getErrArray(1,'type处出错');
        }
        if(!$data['mid']){
            //wiki ID
             return $this->getErrArray(1,'mid处出错');
        }
        switch($data['type']){
            case '1':
                $nodeArray = $this->wikiScore($data,$device);
                break;
            case '2':
                $nodeArray = $this->AddChipByDevice($device, $data);
                break;
            case '3':
                $nodeArray = $this->addHaveSeen($device, $data);
                break;
//            case '4':
//                $nodeArray = $this->deleteChipByDevice($device, $data);
//                break; 
        }
        return $nodeArray;
    }
	/**
     * 提交用户节目操作(节目预约)
     * @param array(channel_code,name,start_time)
     * @author lifucang 
     */
	public function ReportUserProgramAction($args)
	{
        $nodeArray = $this->getErrArray(0,'');
    	$channel_code     = $args['channel_code'];
    	$name             = $args['name'];  //节目名称
    	$start_time       = $args['start_time'];

        if($channel_code==''){
            return $this->getErrArray(1,'channel_code不能为空');
        }
        if($start_time==''){
            return $this->getErrArray(1,'start_time不能为空');
        }        
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
            $ProgramUserRepository = $mongo->getRepository('Programe_user');
            $ProgramUser = new Programe_user();
            $rs=$ProgramUserRepository->SearchPrograme($user_id,$channel_code,$start_time);
            if($rs){
                $nodeArray = $this->getErrArray(1,'该用户已预约该节目');
            }else{
                $ProgramUser->add($user_id,$channel_code,$name,$start_time);
            }
        }else{
			$nodeArray = $this->getErrArray(1,'该用户不存在');
        }               	
        return $nodeArray;
	}   
	/**
     * 提交用户频道
     * @param array(channel_code)
     * @author lifucang 
     */
	public function ReportUserChannelAction($args)
	{
        $nodeArray = $this->getErrArray(0,'');
    	$channel     = $args['channel_code'];
        if($channel==''){
            return $this->getErrArray(1,'channel_code不能为空');
        }
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        if($hasUser){
			$userid =  (string)$hasUser->getId();
            //判断是否存在该频道
            $arrchannel = Doctrine::getTable('Channel')->createQuery()
                ->where('code = ?', $channel)
                ->orWhere('name = ?', $channel)
                ->fetchOne();
            if($arrchannel){
                $channel_code=$arrchannel->getCode();
            }else{
                $nodeArray = $this->getErrArray(1,'该频道不存在');
                return $nodeArray;
            }
            
            //是否有该用户记录
            $userliving = Doctrine::getTable('UserLiving')->createQuery()
                ->where('user_id = ?', $userid)
                ->fetchOne();
            if ($userliving) {
                //是否有该用户访问该频道记录
                $userlivinga = Doctrine::getTable('UserLiving')->createQuery()
                    ->where('user_id = ?', $userid)
                    ->andWhere('channel = ?', $channel_code)
                    ->fetchOne();
                if($userlivinga){
                    $userlivinga->setIsliving(1);
                    $userlivinga->setUpdatedAt(date('Y-m-d H:i:s'));
                    $userlivinga->save();   
                    //$info='有该用户查看该频道记录，更新';  
                    //$info=2;           
                }else{
                    //更新该用户其他频道活动标志为0
                    $q = Doctrine_Query::create() 
                             ->update('UserLiving') 
                             ->set('isliving=?',0) 
                             ->where('user_id = ?', $userid); 
                    $numrows = $q->execute(); 
                    //插入该频道记录
                    $living=new UserLiving();  //实例化类后调用
                    $living->setUserId($userid);
                    $living->setChannel($channel_code);
                    $living->setCreatedAt(date('Y-m-d H:i:s'));
                    $living->setUpdatedAt(date('Y-m-d H:i:s'));
                    $living->setIsliving(1);
                    $living->save();
                    //$info='无该用户查看该频道记录，添加并更新该用户其他频道活动状态';      
                    //$info=3;             
                } 
            } else {
                $living=new UserLiving();  //实例化类后调用
                $living->setUserId($userid);
                $living->setChannel($channel_code);
                $living->setCreatedAt(date('Y-m-d H:i:s'));
                $living->setUpdatedAt(date('Y-m-d H:i:s'));
                $living->setIsliving(1);
                $living->save();
                //$info='无该用户，保存记录';
                //$info=1;
            }
        }else{
			$nodeArray = $this->getErrArray(1,'该用户不存在');
        }               	
        return $nodeArray;
	} 
    /**
     * 返回用户影视列表
     * @param array(page,size,type)
     * type=1:用户收藏
     * type=2:用户喜欢
     * type=3:用户看过
     * @author lifucang
     */
     public function GetMediasByUser($data){
        $page = $data['page'] ? $data['page'] : 1;
        $size = $data['size'] ? $data['size'] : 8;
        $type = $data['type'] ? $data['type'] : 1;
        switch ($type){
            case '1':
                $nodeArray = $this->getUserChips($this->device['dnum'],$page,$size);
                break;
            case '2':
                $nodeArray = $this->getUserLikes($this->device['dnum'],$page,$size);
                break;  
            case '3':
                $nodeArray = $this->getUserWatched($this->device['dnum'],$page,$size);
                break;                              
        }
        return $nodeArray;
     }
	/**
     * 获取用户节目操作(节目预约)
     * @param array(page,size)
     * @author lifucang 
     */
     
    public function GetProgramsByUser($parameter) 
    {
    	$page = $parameter['page'] ? $parameter['page'] : 1;
    	$size = $parameter['size'] ? $parameter['size'] : 10; 
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        
        $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
            $ProgramUserRepository = $mongo->getRepository('Programe_user');
            $programusers=$ProgramUserRepository->getProgrameByUser($user_id,$page,$size);
            $totalprogramusers=$ProgramUserRepository->getProgrameCountByUser($user_id);  //获取总数
            if($programusers){
                $arr = $this->getErrArray(0,'',count($totalprogramusers), count($programusers));
                foreach($programusers as $key => $programuser){
                    $channel_code=$programuser->getChannelCode();
                    $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
                    if($channel){
                        $channel_name=$channel->getName();
                        $channel_logo=$channel->getLogoUrl();
                    }else{
                        $channel_name='';
                        $channel_logo='';
                    }                 
                    $arr['programs'][$key] = array(
                        'channel_code' => $channel_code,
                        'channel_name'=> $channel_name,
                        'channel_logo' => $channel_logo,
                        'name'=> $programuser->getName(),
                        'start_time'=> date('Y-m-d H:i:s',$programuser->getStartTime()->getTimestamp()),                   
                    );
                }
            }else{
                $arr = $this->getErrArray(0,'');
            }
        }else{
			$arr = $this->getErrArray(1,'该用户不存在');
        }               
        return $arr;
    }  
	/**
     * 获取用户频道
     * @param 无
     * @author lifucang 
     */
	public function GetChannelsByUser($args)
	{
        $nodeArray = $this->getErrArray(0,'');
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        if($hasUser){
			$userid =  (string)$hasUser->getId();
            $userliving = Doctrine::getTable('UserLiving')->createQuery()
                ->where('user_id = ?', $userid)
                ->andWhere('isliving=1')
                ->fetchOne();
            if($userliving){
                $nodeArray['userliving']=array(
                    'user_id'=>$userid,
                    'channel_code'=>$userliving->getChannel(),
                    'updated_at'=>$userliving->getUpdatedAt()
                );  
            }else{
                $nodeArray = $this->getErrArray(1,'该用户当前没有停留在任何频道');
            }

        }else{
			$nodeArray = $this->getErrArray(1,'该用户不存在');
        }               	
        return $nodeArray;
	} 
    /**
     * 删除用户影视操作
     * @param array(mid--wiki_id)
     * @author lifucang
     */
    public function DeleteUserMediaAction($data){
        $device = $this->device;
        if(!$data['mid']){
             return $this->getErrArray(1,'mid处出错');
        }
        $mongo = sfContext::getInstance()->getMondongo();
        $singleChipRepository = $mongo->getRepository('singleChip');
        $userRepository = $mongo->getRepository("user");
        $user = $userRepository->getUserIdByDeviceId($device['dnum']);
        $singleChip = $singleChipRepository->getOneChip((string)$user->getId(),$data['mid']);
        if($singleChip){
            $singleChip->delete();
			$commentRepository = $mongo->getRepository('Comment');
			$comment = $commentRepository->getOneComment((string)$user->getId(), $data['mid'], 'queue');
			if ($comment) $comment->delete();            
            $nodeArray = $this->getErrArray(0,'');
            $nodeArray['favorite'] = 0;    
        }else{
			$nodeArray = $this->getErrArray(0,'');                
            $nodeArray['favorite'] = 0;
        }
        return $nodeArray;
    }
	/**
     * 删除用户预约节目
     * @param array(channel_code,start_time)
     * @author lifucang 
     */
	public function DeleteUserProgramAction($args)
	{
        $nodeArray = $this->getErrArray(0,'');
    	$channel_code     = $args['channel_code'];
    	$start_time       = $args['start_time'];
        if($channel_code==''){
            return $this->getErrArray(1,'channel_code不能为空');
        }
        if($start_time==''){
            return $this->getErrArray(1,'start_time不能为空');
        }        
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
            $ProgramUserRepository = $mongo->getRepository('Programe_user');
	        $ProgramUserRepository->del($user_id,$channel_code,$start_time);
        }else{
			$nodeArray = $this->getErrArray(1,'该用户不存在');
        }               	
        return $nodeArray;
	}     
    //################################################################
    //################用户相关接口####################################
    //################################################################
    /*
     * 获取省市列表
     * @param  无
     * @author lifucang
     */
    public function GetSystemCitys($args){
        $province=Province::getProvinceAll();
        $city=Province::getCityAll();
        $total=count($province);
        $nodeArray = array();
        if($province)
        {
            $nodeArray = $this->getErrArray(0,'', $total);
            $i=0;
            foreach($province  as $key =>$value)
            {
                $nodeArray['province'][$i] = array(
                   'name' => $value,
                   'value'=> $key
                );
                foreach($city[$key] as $index => $child)
                {
                    $nodeArray['province'][$i]['city'][] = array(
                        'name'    => $child,
                        'value' => $index,
                    );
                }
                $i++;
            }
        }
        else
        {
            $nodeArray = $this->getErrArray(0,'');
        }
        return $nodeArray;
    }   
	/*
     * 按省市获取直播运营商列表
     * @param array(province)
     * @author lifucang
     */
    public function GetDtvSPList($args)
    {
    	$nodeArray = array();
    	$province = $args['province'];
        $mongo = sfContext::getInstance()->getMondongo();
        $splist = $mongo->getRepository('sp')->getSpByProvince($province);
        if($splist){
            $nodeArray = $this->getErrArray(0,'', count($splist));
    		foreach($splist  as $key =>$value)
    		{
    		    if($value['logo']!='')
                    $logo=file_url($value['logo']);
                else
                    $logo='';    
    			$nodeArray['sp'][$key] = array(
    				'signal'   => $value['signal'],
    				'name'     => $value['name'],
    				'remark'   => $value['remark'],
    				'logo'     => $logo,
    			);
    		}            
        }else{
            $nodeArray = $this->getErrArray(0,'');
        }
		return $nodeArray;
    }               	
	/*
     * 设置用户属性
     * @param array(province,city,dtvsp)
     * @author lifucang 
     */
	public function SetUserConfig($args)
	{
        $nodeArray = $this->getErrArray(0,'');
    	$province         = $args['province'];
    	$city             = $args['city'];  //节目名称
    	$dtvsp            = $args['dtvsp'];

        if($province==''){
            return $this->getErrArray(1,'province不能为空');
        }
        if($city==''){
            return $this->getErrArray(1,'city不能为空');
        }  
        if($dtvsp==''){
            return $this->getErrArray(1,'dtvsp不能为空');
        }       
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        if($hasUser){
            $hasUser->setProvince($province);
            $hasUser->setCity($city);
            $hasUser->setDtvsp($dtvsp);
            $hasUser->save();
        }else{
			$nodeArray = $this->getErrArray(1,'该用户不存在');
        }               	
        return $nodeArray;
	} 
	/*
     * 获取用户属性
     * @param 无
     * @author lifucang
     */
	public function GetUserConfig($args)
	{
		$nodeArray = array();
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($this->device['dnum']);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
			$nodeArray = $this->getErrArray(0,'');
			$nodeArray['user'] = array(
				'province' => $hasUser->getProvince(),
				'city'     => $hasUser->getCity(),
                'dtvsp'    => $hasUser->getDtvsp(),
	        );
        }else{
	        $nodeArray = $this->getErrArray(1,'该用户不存在');
        }                   	
        return $nodeArray;
	}     
    //################################################################
    //################调用函数########################################
    //################################################################
    /*
     * 影视wiki踩和顶操作
     * @param array $data
     * @param array $user
     * @return array $nodeArray
     * @author wangnan
     */
    public function wikiScore($data,$device){
		$nodeArray = $this->getErrArray(0,'');
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
        if($hasUser){
            $wikiId = $data['mid'];
            $wikiRepository = $mongo->getRepository("wiki");
            $wiki = $wikiRepository->getWikiById($wikiId);
            if($wiki){
            	$CommentRepository = $mongo->getRepository('Comment');
            	switch($data['praise'])
            	{
	            	case '1':
	            		$CommentRepository->scoreOperation($wiki,(string)$hasUser->getId(),'like',$data['comment']);
	                break;
	                
	            	case '0':
                        $CommentRepository->scoreOperation($wiki,(string)$hasUser->getId(),'dislike',$data['comment']);
						break;                                  		
            	}
            }
            else
            {
				$nodeArray = $this->getErrArray(1,'该wiki不存在');
            }
        }
        else
        {
            $nodeArray = $this->getErrArray(1,'该用户不存在');
        }
        return $nodeArray;
    }

    /*
     * 加入片单
     * @param array $user
     * @param array $data
     * @return int
     * @author guoqiang.zhang
     */
    public function AddChipByDevice($device,$data){
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $chip = $userRepository->getUserByDeviceId($device['dnum'],$data['mid']);
		$nodeArray = $this->getErrArray(0,'');      
        $nodeArray['favorite'] = 1;      
        return $nodeArray;
    }

    /*
     *通过wiki_id,device_id 删除片单中wiki
     * @param array $user
     * @param array $data
     * @return int
     * @author wangnan
     */
    public function deleteChipByDevice($device,$data){
        $mongo = sfContext::getInstance()->getMondongo();
        $singleChipRepository = $mongo->getRepository('singleChip');
        $userRepository = $mongo->getRepository("user");
        $user = $userRepository->getUserIdByDeviceId($device['dnum']);
        $singleChip = $singleChipRepository->getOneChip((string)$user->getId(),$data['mid']);
        if($singleChip){
            $singleChip->delete();
			$commentRepository = $mongo->getRepository('Comment');
			$comment = $commentRepository->getOneComment((string)$user->getId(), $data['mid'], 'queue');
			if ($comment) $comment->delete();            
            $nodeArray = $this->getErrArray(0,'');
            $nodeArray['favorite'] = 0;
            /*
            $nodeArray['data'] = array(
                    'favorite'  =>  '0'
                );
            */
                
        }else{
			$nodeArray = $this->getErrArray(0,'');                
            $nodeArray['favorite'] = 0;
        }
       return $nodeArray;
    }
    /*
     * 添加看过
     * @param array $user
     * @param array $data
     * @return int
     * @author wangnan
     */
    public function addHaveSeen($device,$data){
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki'); 
        $wiki = $wikiRepository->findOneById(new MongoId($data['mid']));
        if($wiki)
        {
			$userRepository = $mongo->getRepository("user");
			$hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
        	if($hasUser)
        	{
				$CommentRepository = $mongo->getRepository('Comment');
				$comment = $CommentRepository->getOneComment((string)$hasUser->getId(), $data['mid'], 'watched');
				if(!$comment)
				{				
					$comment = new Comment();
			        $comment->setUserId((string)$hasUser->getId());
			        $comment->setWikiId($data['mid']);
			        $comment->setParentId(0);
			        $comment->setType('watched');
			        $comment->setText($data['comment']);
			        $comment->save();
					$watchedNum = $wiki->getWatchedNum();
					if ($watchedNum) 
					{
						$watchedNum = $watchedNum + 1;
					} else 
					{
						$watchedNum = 1;
					}
					$wiki->setWatchedNum($watchedNum);
					$wiki->save();
				}
				$nodeArray = $this->getErrArray(0,'');
		        $nodeArray['favorite'] = 4;
		        return $nodeArray;
        	}
			else
        	{
			$nodeArray = $this->getErrArray(1,'该用户不存在');
	        return $nodeArray;         		
        	}
        }
        else
        {
			$nodeArray = $this->getErrArray(1,'该wiki不存在');
	        return $nodeArray;        	
        }
    }  
    /*
     * 获取用户收藏的
     * @param array $args
     * @return array nodeArray
     * @author guoqiang.zhang
     */
     public function getUserChips($device_id,$page,$size){
         $skip = ($page - 1) * $size;
         $mongo = sfContext::getInstance()->getMondongo();
         $userRepository = $mongo->getRepository("user");
         $user = $userRepository->getUserIdByDeviceId($device_id);
         if(!$user){
             return $this->getErrArray(1,'该用户不存在');
         }
         $userchips =  new sfMondongoPager('singleChip', $size);
         $options['query'] = array(
            'user_id'=> (string)$user->getId(),
            'is_public'=> true
          );
         $userchips->setFindOptions($options);
         $userchips->setPage($page);
         $userchips->init();
         $chips = $userchips->getResults();
         if($chips){
             $arr = $this->getErrArray(0,'', $userchips->getNbResults(),count($chips));
             foreach($chips as $key => $chip){
                  $wikiRepository = $mongo->getRepository("wiki");
                  $wiki = $wikiRepository->getWikiById($chip->getWikiId());
                  if($wiki)
                  {
	                  $arr['media'][$key]['id'] = (string)$wiki->getId();
	                  $arr['media'][$key]['title'] = $wiki->getTitle();
	                  $arr = $this->getWikiVideoSource($wiki, $key, $arr);
                  }
             }
         }else{
            $arr = $this->getErrArray(0,'');
         }
         return $arr;
     }
    /*
     * 获取用户喜欢的
     * @param array $args
     * @return array nodeArray
     * @author wangnan
     */     
    public function getUserLikes($device_id,$page,$size){
        $mongo = sfContext::getInstance()->getMondongo();
		$userRepository = $mongo->getRepository("user");
		$user = $userRepository->getUserIdByDeviceId($device_id); 
		if($user)
		{
			$comments = new sfMondongoPager('Comment', $size);
			$comments->setFindOptions(array('query'=>array('user_id'=>(string)$user->getId(),'is_publish'=>true,'type'=>'like'), 'sort'=>array('created_at' => -1)));
			$comments->setPage($page);
			$comments->init();    
			$results = $comments->getResults();
			if($results)
			{
				$nodeArray = $this->getErrArray(0,'',$comments->getNbResults(), count($results));
				foreach($results as $key => $comment){
					$wikiRepository = $mongo->getRepository("wiki");
					$wiki = $wikiRepository->getWikiById($comment->getWikiId());
					$nodeArray['media'][$key]['id'] = (string)$wiki->getId();
					$nodeArray['media'][$key]['title'] = $wiki->getTitle();
					$nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
				}
			}else
			{
				$nodeArray = $this->getErrArray(0, '');
			}
			return $nodeArray;		    			
		}
		else
		{
	        $nodeArray = $this->getErrArray(1,'没有该用户');
			return $nodeArray;	            			
		}       
    }
    /*
     * 获取用户看过的
     * @param array $args
     * @return array nodeArray
     * @author wangnan
     */     
    public function getUserWatched($device_id,$page,$size){
        $mongo = sfContext::getInstance()->getMondongo();
		$userRepository = $mongo->getRepository("user");
		$user = $userRepository->getUserIdByDeviceId($device_id);
		if($user)
		{
			$comments = new sfMondongoPager('Comment', $size);
			$comments->setFindOptions(array('query'=>array('user_id'=>(string)$user->getId(),'is_publish'=>true,'type'=>'watched'), 'sort'=>array('created_at' => -1)));
			$comments->setPage($page);
			$comments->init();    
			$results = $comments->getResults();
			if($results)
			{
				$nodeArray = $this->getErrArray(0,'', $comments->getNbResults(),count($results));
				foreach($results as $key => $comment){
					$wikiRepository = $mongo->getRepository("wiki");
					$wiki = $wikiRepository->getWikiById($comment->getWikiId());
					$nodeArray['media'][$key]['id'] = (string)$wiki->getId();
					$nodeArray['media'][$key]['title'] = $wiki->getTitle();
					$nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
				}
			}else
			{
				$nodeArray = $this->getErrArray(0,'');
			}
			return $nodeArray;		    			
		}
		else
		{
	        $nodeArray = $this->getErrArray(1,'没有该用户');
			return $nodeArray;	            			
		} 		        
    }         
    /*
     * 根据Least Click TV API获取数据
     * @return $nodeArray
     * @author lifucang
     * @editor lifucang
     */
     public function getLctVideoSource($wiki,$i,$nodeArray){
        $director = !$wiki->director ? '' : implode(',', $wiki->director);
        $actors = !$wiki->starring ? '' : implode(',', $wiki->starring);
        $tags = !$wiki->tags ? '' : implode(',', $wiki->tags);
        $area = !$wiki->country ? "" : $wiki->country;
        $language = !$wiki->language ? "" : $wiki->language;
        $score = 0;                                                             //该值不存在
        $playdate = !$wiki->runtime ? '' : $wiki->runtime;
        $praise = !$wiki->likeNum ? 0 : $wiki->likeNum;
        $dispraise = !$wiki->dislikeNum ? 0 : $wiki->dislikeNum;
        $videos = $wiki->videos;
        $refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
        $source = '';
        $prefer = "奇艺"; //优选片源
        /*
        if ($videos != NULL) {
            foreach ($videos as $video) {
                $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];  //暂未找到有值的
            }            
        }
        //$whether_mark = (gettype($type) =='array')?true:false;   //该值是什么
        */
        $nodeArray['media'][$i]['info'] = array(
            "director" => $director,
            "actors" => $actors,
            "type" => $tags,
            "area" => $area,
            "language" => $language,
            "score" => $score,
            "playdate" => $playdate,
            "praise" => $praise,
            "dispraise" => $dispraise,
            "source" => $source,
            "prefer" => $prefer
        );
        $nodeArray['media'][$i]['description'] = $wiki->content;
        $cover = $wiki->cover;
        if ($cover) {
            $nodeArray['media'][$i]['posters']['num'] = 3;
            $nodeArray['media'][$i]['posters']['poster'][0] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => thumb_url($cover, 120, 160),
            );
            $nodeArray['media'][$i]['posters']['poster'][1] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => thumb_url($cover, 240, 320),
            );
            $nodeArray['media'][$i]['posters']['poster'][2] = array(
                "type" => "max",
                "size" => "1240*460",
                "url" => thumb_url($cover, 1240, 460),
            );
        }
        /*暂时不取videos信息
        $model = $wiki->model;
        if ($model == 'film') {
            $videos = $wiki->videos;
            if ($videos != NULL) {
                foreach ($videos as $video) {
                    $tvconfig = $video->getConfig();
                    //$nodeArray=$this->addEpisodesFilm($i,$video,$nodeArray);
                    if ($video->getReferer() == 'qiyi') {
                        $nodeArray['data'][0]['media'][$i]['episodes'][0][DOM::ATTRIBUTES] = array(
                            "source" => "奇艺",
                            "num" => 1
                        );
                        $video_id = $video->getId();
                        $nodeArray['data'][0]['media'][$i]['episodes'][0]['episode'][0][DOM::ATTRIBUTES] = array(
                            "id" => $video_id,//由于数据由此处获取 无需判断传来的eide是否匹配此video_id
                            "index" => 1,
                            "size" => 0,
                            "length" => 0,
                            "format" => '',
                            "rate" => 0,
                            "vip" => 0,
                            "url" =>  $this->setVideoUrl($tvconfig['tvId']),
                            "live" => 0
                        );
                    }
                }
            }
        }
        if ($model == 'teleplay') {
            $playLists = $wiki->videoPlaylists;
            if ($playLists != NULL) {
                foreach ($playLists as $playList) {
                   //$nodeArray=$this->addEpisodesTeleplay($i,$playList,$nodeArray);
                    if ($playList->getReferer() == 'qiyi') {
                        $countVideo = $playList->countVideo();
                        $nodeArray['data'][0]['media'][$i]['episodes'][0][DOM::ATTRIBUTES] = array(
                            "source" => "奇艺",
                            "num" => $countVideo,
                        );
                        $videos = $playList->getVideos();
                        $j = 0;
                        if($whether_mark)
                        {
                        	 foreach ($videos as $video) {
	                            $tvconfig = $video->getConfig();
	                            if((string)$video->getId()==$type['eid'])
		                            $nodeArray['data'][0]['media'][$i]['episodes'][0]['episode'][$j][DOM::ATTRIBUTES] = array(
		                            	//"markid" => (string)$type['markid'],
		                            	//"marktime" => $type['marktime'],
		                                "id" => $video->getId(),
		                                "index" => $video->getMark(),
		                                "size" => 0,
		                                "length" => 0,
		                                "format" => '',
		                                "rate" => 0,
		                                "vip" => 0,
		                                "url" => $this->setVideoUrl($tvconfig['tvId']),
		                                "live" => 0
		                            );
                        	 }
                        }
                        else
                        {
	                        foreach ($videos as $video) {
	                            $tvconfig = $video->getConfig();
	                            $nodeArray['data'][0]['media'][$i]['episodes'][0]['episode'][$j][DOM::ATTRIBUTES] = array(
	                                "id" => $video->getId(),
	                                "index" => $j,
	                                "size" => 0,
	                                "length" => 0,
	                                "format" => '',
	                                "rate" => 0,
	                                "vip" => 0,
	                                "url" => $this->setVideoUrl($tvconfig['tvId']),
	                                "live" => 0
	                            );
	                            $j++;
	                        }
                        } 
                    }
                }
            }
        }
        */

        return $nodeArray;
     }  
    
    /*
     * wiki对象返回视频源数组
     * @param  mongo object  $wiki
     * @param  array $nodeArray
     * @param  int $type 默认为1 如果为GetEpisodeListByUser调用此函数则传入数组
     * $type['eid']：分集video_id
     * $type['marktime']：标记秒数
     * $type['markid']：mark_id
     * @return $nodeArray
     * @author guoqiang.zhang
     * @editor lifucang
     */
     private function getWikiVideoSource($wiki,$i,$nodeArray,$mytag='medias',$type='',$biaozhi=0){
        $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
        $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
        //$tags = !$wiki->getTags() ? '' : $this->getTag($wiki->getTags(),array($this->category[1]['name'],$this->category[2]['name']));
        $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
        $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
        $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
        $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
        $praise = !$wiki->getLikeNum() ? 0 : $wiki->getLikeNum();
        $dispraise = !$wiki->getDislikeNum() ? 0 : $wiki->getDislikeNum();
        $videos = $wiki->getVideos();
        $refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
        $source = '';
        $prefer = "奇艺"; //优选片源
        if ($videos != NULL) {
            foreach ($videos as $video) {
                $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
            }            
        }
        //将wiki的tags转成英文格式
		$model=$wiki->getModel();
		$arr_type=Common::englishGenres();   
		$arr_tag=array('film'=>'Movie','teleplay'=>'Series','actor'=>'other','television'=>'other','basketball_player'=>'other');
		$tags=array();
		if($wiki->getTags()){
		   foreach($wiki->getTags() as $value){
			   if($arr_type[$value]!=''){
				   if($model=='film'){
					   $tagvalue='Movie/'.$arr_type[$value];
				   }elseif($model=='teleplay'){
					   $tagvalue='Series/'.$arr_type[$value];
				   }else{
					   $tagvalue= $arr_type[$value];
				   }
				   $tags[]=$tagvalue;
			   }
		   }
		}
		if(count($tags)==0){
		   $tags[]=$arr_tag[$model];
		}
        $stag=implode(',',$tags);
        //将wiki的tags转成英文格式
        $whether_mark = (gettype($type) =='array')?true:false;
        $nodeArray[$mytag][$i]['info'] = array(
            "director" => $director,
            "actors" => $actors,
            "type" => $stag,
            "area" => $area,
            "language" => $language,
            "score" => $score,
            "playdate" => $playdate,
        );
        $nodeArray[$mytag][$i]['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if ($cover) {
            $nodeArray[$mytag][$i]['posters'][0] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => thumb_url($cover, 120, 160,'172.31.139.17'),
            );
            $nodeArray[$mytag][$i]['posters'][1] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => thumb_url($cover, 240, 320,'172.31.139.17'),
            );
            $nodeArray[$mytag][$i]['posters'][2] = array(
                "type" => "max",
                "size" => "1240*460",
                "url" => thumb_url($cover, 1240, 460,'172.31.139.17'),
            );
        }
        return $nodeArray;
     }    



    /*
     * wiki对象返回视频源数组
     * @param  mongo object  $wiki
     * @param  array $nodeArray
     * @param  int $type 默认为1 如果为GetEpisodeListByUser调用此函数则传入数组
     * $type['eid']：分集video_id
     * $type['marktime']：标记秒数
     * $type['markid']：mark_id
     * @return $nodeArray
     * @author guoqiang.zhang
     * @editor lifucang
     * 和getWikiVideoSource一样，只是$nodeArray['media'][$i]部分全部换为$nodeArray['media']，目前只有GetWikiInfo调用
     */
     private function getOneWikiVideoSource($wiki,$i,$nodeArray,$mytag='media',$type='',$biaozhi=0){
        $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
        $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
        //$tags = !$wiki->getTags() ? '' : $this->getTag($wiki->getTags(),array($this->category[1]['name'],$this->category[2]['name']));
        $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
        $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
        $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
        $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
        $praise = !$wiki->getLikeNum() ? 0 : $wiki->getLikeNum();
        $dispraise = !$wiki->getDislikeNum() ? 0 : $wiki->getDislikeNum();
        $videos = $wiki->getVideos();
        $refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
        $source = '';
        $prefer = "奇艺"; //优选片源
        if ($videos != NULL) {
            foreach ($videos as $video) {
                $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
            }            
        }
        //将wiki的tags转成英文格式
		$model=$wiki->getModel();
		$arr_type=Common::englishGenres();   
		$arr_tag=array('film'=>'Movie','teleplay'=>'Series','actor'=>'other','television'=>'other','basketball_player'=>'other');
		$tags=array();
		if($wiki->getTags()){
		   foreach($wiki->getTags() as $value){
			   if($arr_type[$value]!=''){
				   if($model=='film'){
					   $tagvalue='Movie/'.$arr_type[$value];
				   }elseif($model=='teleplay'){
					   $tagvalue='Series/'.$arr_type[$value];
				   }else{
					   $tagvalue= $arr_type[$value];
				   }
				   $tags[]=$tagvalue;
			   }
		   }
		}
		if(count($tags)==0){
		   $tags[]=$arr_tag[$model];
		}
        $stag=implode(',',$tags);
        //将wiki的tags转成英文格式
        $whether_mark = (gettype($type) =='array')?true:false;
        $nodeArray[$mytag]['info'] = array(
            "director" => $director,
            "actors" => $actors,
            "type" => $stag,
            "area" => $area,
            "language" => $language,
            "score" => $score,
            "playdate" => $playdate,
            "praise" => $praise,
            "dispraise" => $dispraise,
            "source" => $source,
            "prefer" => $prefer
        );
        $nodeArray[$mytag]['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if ($cover) {
            $nodeArray[$mytag]['posters'][0] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => thumb_url($cover, 120, 160,'172.31.139.17'),
            );
            $nodeArray[$mytag]['posters'][1] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => thumb_url($cover, 240, 320,'172.31.139.17'),
            );
            $nodeArray[$mytag]['posters'][2] = array(
                "type" => "max",
                "size" => "1240*460",
                "url" => thumb_url($cover, 1240, 460,'172.31.139.17'),
            );
        }
        $screens = $wiki->getScreenshotUrls();   
        foreach($screens as $k => $screen)
        {
            $nodeArray[$mytag]['screens'][$k]= array(
                            'url'    =>  $screens[$k],
                    ); 
        }
        return $nodeArray;
     }  
    /*
     * 获取热播|好评的影视
     * @parame int    $cid       在WikiRepository.php获取分类名称 
     * @param  string $filters  
     * @param  int    $page
     * @param  int    $size
     * @param  int    $order 0为最新 2为好评
     * @return array  $nodeArray 
     * @author wangnan
     */
     public function getMediaBySort($cid,$filters,$page,$size,$order)
     {
     	$nodeArray = array();
		$Condition = $this->getSearchText(null,$cid,$filters);
        if($Condition['range']==='error')
        {
            $nodeArray = $this->getErrArray(1,'请正确填写节点内年份搜索区间');
        	return $nodeArray;
        }
        $result = $this->getSearch($Condition['condition'],$page,$size,$Condition['range'],$order);
        $count = count($result['result']);
        if($count)
        {
            $nodeArray = $this->getErrArray(0,'',$result['total'],$count);
            foreach($result['result'] as $key => $wiki){
                $nodeArray['media'][$key] = array(
                        'id'    => (string)$wiki->getId(),
                        'title'   => $wiki->getTitle(),
                );
                $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
            }
        }
        else
        {
            $nodeArray = $this->getErrArray(0,'未找到数据');
        }
        return $nodeArray;         	    
     } 
	/*
	 * 获取wiki的相关评论
	 * @param $nodeArray
	 * @param $wiki_id
	 * @author wangnan
	 * @return xml
	 */
	public function getWikiComments($nodeArray,$wiki_id)
	{
		$mongo = sfContext::getInstance()->getMondongo();
		$commentsRepository = $mongo->getRepository('Comment');
		$comments = $commentsRepository->find(array(
									'query'=>array(
										'wiki_id' => $wiki_id	
									)
							));
        $nodeArray['total']=count($comments);                    
		foreach($comments as $key=>$comment)
		{
			$user = $comment->getUser();
			$nodeArray['comment'][$key] = array(
				'id'        => (string)$comment->getId(),
				'content'   => $comment->getText(),
				'username'  => $user->getNickname(),
				'userpic'   => file_url($user->getAvatar()),
				'userid'    => $comment->getUserId(),
				'createtime'=> date("Y-m-d H:i:s",$comment->getCreatedAt()->getTimestamp()),
				'score'     => 0,
			); 
		}
		return $nodeArray;
	}     
    /*
     * 从WikiRepository.php中根据键名获取tag名称
     * @param  int    $cid
     * @return string $tag
     * @author wangnan
     */
     public function getTagName($cid=0)
     {
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository("wiki");
        $category = $wikiRepository->getCategory();         	
    	if(array_key_exists($cid, $category))
    	{
            $tag = $category[$cid]['name'] ;
        }
        else
        {
            foreach($category as $cate)
            {
                if(array_key_exists($cid,$cate['child']))
                {
                   $tag = $cate['child'][$cid];
                }
            }
        }
        return $tag;         	    
     }    
    /*
     * 通过搜索关键字，分类，过滤选项来构建searchText和区间
     * @parame string $keyword
     * @param  int    $cid
     * @param  array  $filter
     * @return array  array('condition'=>searchText,'range'=>range)
     * @author guoqiang.zhang
     */
     public function getSearchText($keyword=null,$cid=0,$filters=null){
         $tmpstr = 'source:qiyi type:video';             //待构建搜索文本 默认搜索影视剧中有qiyi视频源的数据
         $range = '';                                    //搜索区间  目前只支持年份的区间搜索
         if($keyword){
            $tmpstr .= " ".$keyword;
         }      
         if($cid){
            $mongo = sfContext::getInstance()->getMondongo();
            $wikiRepository = $mongo->getRepository("wiki");
            $category = $wikiRepository->getCategory();
            if(array_key_exists($cid, $category)){
                $tmpstr .=  " tag:".$category[$cid]['name'] ;
            }else{
                foreach($category as $cate){
                    if(array_key_exists($cid,$cate['child'])){
                       $tmpstr .= " tag:".$cate['child'][$cid];
                    }
                }
            }
         }
         //待重构
         if($filters){
             foreach($filters as $key => $filter){
                 if($filter['type'] == "地区"){
                     $tmpstr .= " area:".$filter['value'];
                 }elseif($filter['type'] == "时间"){
                     $range = $this->getRang($filter['value']);
                 }
             }
         }
	     return $result = array('condition'=>$tmpstr,'range'=>$range);
     }
     /*
      * 返回相应格式的搜索年限区间 以供WikiRepository.php文件中getXunSearchRange函数使用
      * @param  string $str
      * @return string
      * @author wangnan
      */
     public function getRang($str){
         if(is_numeric($str)){
             return $str."-".$str;
         }
         else
         {
         	if(preg_match('/(^\d{4}-\d{4}$)|(^[a-z]{2}\d{4}$)|全部/',$str))
         	return $str;
         	else
         	return 'error';
         }
     }
     
     /*
      * 返回搜索列表
      * @param  string $searchText
      * @param  int    $page
      * @param  int    $limit
      * @return array  $wikilist
      * @author guoqiang.zhang
      */
     public function getSearch($searchText,$page=0,$limit=10,$searchRange=null,$sort=1){
        $array = Array();
        $offset = $limit * ($page-1);
        $total = NULL;
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $array['result'] = $wikiRep->xun_search($searchText, $total, (int)$offset, (int)$limit,$searchRange ,$sort);
        $array['total'] = $total;
        return $array;
     }
    
    /*
     * 返回搜索总数
     * @param  string $searchText
     * @return int    $total
     * @author guoqiang.zhang
     */
     public function getSearchTotal($searchText,$searchRange=null){
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $total = $wikiRep->getSearchCount($searchText, $searchRange);
        return $total;
     }     
    /*
     * 返回wiki的类型
     * @param array $tags
     * @return string $tag
     * author lifucang
     */
    public function getTag($tags,$arr){
        $tmpstr = array();
        foreach($tags as $tag){
            if(!array_search($tag, $arr)){
                $tmpstr[] = $tag;
            }
        }
       return implode(",",$tmpstr);
    }
         
    private function setVideoUrl($id=0){
        return "http://proxy.kkttww.net:8080/urlproxy/qiyi/?redirect=1&tv_id=".$id;
    }
    
    private function setFilter(){
        return $this->filter = array('时间'=>"year","地区"=>'area');
    }

    private function setPrefixUrl(){
        return $this->prefix_url = "http://www.epg.huan.tv/RPC/interface";
    }
    
    private function setRootAttribute(){
        return array('website' => $this->setPrefixUrl());
    }

    private function setCategory(){
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki');
        $category = $wikiRepository->getCategory();
        return $this->category = $category;
    }  
    
	/**
     * 广电接口
     * 根据频道号和日期获取节目列表
     * @param $param Array(channel_code,date)
     * @author lifucang
     * @final 2012-11-6
     */
    private function GetProgramsByChannelGd($param) 
	{        
    	$nodeArray = array();
    	$channelcode = $param["channel_code"] ? $param["channel_code"] : "cctv1";
    	$date = $param['date'] ? $param['date']:date('Y-m-d');
    	$mongo = sfContext::getInstance()->getMondongo();
    	$ProgramRepository = $mongo->getRepository('Program');
        $channel = Doctrine_Core::getTable("channel")->findOneByCode($channelcode);
        if($channel)
        {
            //$programs = $ProgramRepository->getProgramsByCode($channelcode,$starttime,$endtime);
            $programs = $ProgramRepository->getDayPrograms($channelcode,$date);
			$nodeArray = $this->getErrArray(0,'',count($programs));
	        $nodeArray['channel'] = array(
	        	'name'=>$channel['name'],
	        	'code'=>$channel['code'],
	        	'logourl'=>$channel->getLogoUrl(),
	        	'hot'=>$channel['hot'],
	        );
	        foreach($programs as $key =>$program)
	        {
				$nodeArray['program'][$key] = array(
                    'date' => $program['date'],
					'name' => $program['name'],
                    'publish' => $program['publish'],
                    'sort' => $program['sort'],
					'start_time' => date("Y-m-d H:i:s",$program['start_time']->getTimestamp()),
					'end_time' => $program['end_time']?date("Y-m-d H:i:s",$program['end_time']->getTimestamp()):null,
                    'time' => $program['time'],
                    'tags' => $program['tags'],
					'wiki_id' => $program['wiki_id'],
                    'tvsou_id' => $program['tvsou_id']
	            );
			}        	
        }else{
            $nodeArray = $this->getErrArray(1,'该频道不存在');
		}
		return $nodeArray;
    }    

	/**
     * 广电接口
     * 获取指定时间更新的wiki的Id--默认当天（GetWikisDayGd的精简版）
     * @author lifucang 
     */
	public function GetWikiIdDayGd($args)
	{
		$nodeArray = array();
        $starttime = $args['start_time']?new MongoDate(strtotime($args['start_time'])):new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime=$args['end_time']?new MongoDate(strtotime($args['end_time'])):new MongoDate(mktime(23, 59, 59, date('m'), date('d'), date('Y')));
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('wiki');
		//$wikis = $WikiRepository->getwikisByDay();
        $wikis = $WikiRepository->find(array('query'=>array('created_at'=>array('$gte' => $starttime,'$lte' => $endtime))));
		$nodeArray = $this->getErrArray(0,'',count($wikis));
        foreach($wikis as $wiki) 
		{
		    $nodeArray['wiki'][]=(string)$wiki->getId();
            
		}
		return $nodeArray; 
	} 
	/**
     * 广电接口
     * 获取指定时间更新的wiki--默认当天
     * @author lifucang 
     */
	public function GetWikisDayGd($args)
	{
		$nodeArray = array();
        $starttime = $args['start_time']?new MongoDate(strtotime($args['start_time'])):new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime=$args['end_time']?new MongoDate(strtotime($args['end_time'])):new MongoDate(mktime(23, 59, 59, date('m'), date('d'), date('Y')));
    	$queryat=$args['queryat']?$args['queryat']:'created_at';
        $mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('wiki');
		//$wikis = $WikiRepository->getwikisByDay();
        $wikis = $WikiRepository->find(array('query'=>array($queryat=>array('$gte' => $starttime,'$lte' => $endtime))));
		$nodeArray = $this->getErrArray(0,'',count($wikis));
        $i=0;
        foreach($wikis as $wiki) 
		{
            $model = $wiki->getModel();
			$nodeArray['wiki'][$i] = array(
                            'id'    => (string)$wiki->getId(),
                            'title'   => $wiki->getTitle(),
                            'slug'  => $wiki->getSlug(),
                            'tvsou_id'  =>$wiki->getTvsouId(),   
                            'model'   => $model,
                            'content' => $wiki->getContent(),
                            'html_cache'=>$wiki->getHtmlCache(),
                            'cover' => $wiki->getCover(),
                            'screens' => $wiki->getScreenshots(),
                            'tags' => $wiki->getTags(),  
                            'source'    =>$wiki->getSource(),  
                            'like_num' => $wiki->getLikeNum(),
                            'dislike_num' => $wiki->getDislikeNum(),     
                            'has_video'=>$wiki->getHasVideo(),   
                            'comment_tags'=>$wiki->getCommentTags(),
            );
            if ($model == 'actor') {
                $nodeArray['wiki'][$i]['info'] = array(
                    "english_name" => $wiki->getEnglishName(),
                    "nickname" => $wiki->getNickname(),
                    "sex" => $wiki->getSex(),
                    "birthday" => $wiki->getBirthday(),
                    "birthplace" => $wiki->getBirthplace(),
                    "occupation" => $wiki->getOccupation(),
                    "nationality" => $wiki->getNationality(),
                    "zodiac" => $wiki->getZodiac(),
                    "bloodType" => $wiki->getBloodType(),
                    "debut" => $wiki->getDebut(),
                    "height" => $wiki->getHeight(),
                    "weight" => $wiki->getWeight(),
                    "region" => $wiki->getRegion(),               
                );
            }elseif($model == 'film'){
                $nodeArray['wiki'][$i]['info'] = array(
                    "alias" =>$wiki->getAlias(),
                    "director" => $wiki->getDirector(),
                    "starring" => $wiki->getStarring(),
                    "released" => $wiki->getReleased(),
                    "language" => $wiki->getLanguage(),
                    "country" => $wiki->getCountry(),
                    "writer" => $wiki->getWriter(),
                    "distributor"=>$wiki->getDistributor(),
                    "runtime"=>$wiki->getRuntime(),
                    "produced"=>$wiki->getProduced(),
                );
            }elseif($model == 'teleplay'){
                $nodeArray['wiki'][$i]['info'] = array(
                    "alias" =>$wiki->getAlias(),
                    "director" => $wiki->getDirector(),
                    "starring" => $wiki->getStarring(),
                    "released" => $wiki->getReleased(),
                    "language" => $wiki->getLanguage(),
                    "country" => $wiki->getCountry(),
                    "writer" => $wiki->getWriter(),
                    "distributor"=>$wiki->getDistributor(),
                    "runtime"=>$wiki->getRuntime(),
                    "produced"=>$wiki->getProduced(),
                    "episodes"=>$wiki->getEpisodes(),
                );
            }elseif($model == 'television'){
                $nodeArray['wiki'][$i]['info'] = array(
                    "channel" =>$wiki->getChannel(),
                    "play_time" => $wiki->getPlayTime(),
                    "host" => $wiki->getHost(),
                    "guest" => $wiki->getGuests(),
                    "producer" => $wiki->getProducer(),
                    "alias" => $wiki->getAlias(),
                    "runtime"=>$wiki->getRuntime(),
                    "country"=>$wiki->getCountry(),
                    "language"=>$wiki->getLanguage(),
                );
            }
            $i++;
            
		}
		return $nodeArray; 
	} 
            
	/**
     * 广电接口
     * 按照wiki_id获取wiki详细信息
     * @param Array(wiki_id)
     * @author lifucang 
     */
	public function GetWikiInfoGd($args)
	{
		$nodeArray = array();
    	$wiki_id = $args['wiki_id'];
        if(empty($wiki_id)){
    		return $this->getErrArray(1,'请填写wiki_id');
        }
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('wiki');
		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
		if($wiki) 
		{
			$nodeArray = $this->getErrArray(0,'',1);
            $model = $wiki->getModel();
			$nodeArray['wiki'] = array(
                            'id'    => (string)$wiki->getId(),
                            'title'   => $wiki->getTitle(),
                            'slug'  => $wiki->getSlug(),
                            'tvsou_id'  =>$wiki->getTvsouId(),   
                            'model'   => $model,
                            'content' => $wiki->getContent(),
                            'html_cache'=>$wiki->getHtmlCache(),
                            'cover' => $wiki->getCover(),
                            'screens' => $wiki->getScreenshots(),
                            'tags' => $wiki->getTags(),  
                            'source'    =>$wiki->getSource(),  
                            'like_num' => $wiki->getLikeNum(),
                            'dislike_num' => $wiki->getDislikeNum(),     
                            'has_video'=>$wiki->getHasVideo(),   
                            'comment_tags'=>$wiki->getCommentTags(),                          
            );
            if ($model == 'actor') {
                $nodeArray['wiki']['info'] = array(
                    "english_name" => $wiki->getEnglishName(),
                    "nickname" => $wiki->getNickname(),
                    "sex" => $wiki->getSex(),
                    "birthday" => $wiki->getBirthday(),
                    "birthplace" => $wiki->getBirthplace(),
                    "occupation" => $wiki->getOccupation(),
                    "nationality" => $wiki->getNationality(),
                    "zodiac" => $wiki->getZodiac(),
                    "bloodType" => $wiki->getBloodType(),
                    "debut" => $wiki->getDebut(),
                    "height" => $wiki->getHeight(),
                    "weight" => $wiki->getWeight(),
                    "region" => $wiki->getRegion(),  
                );
            }elseif($model == 'film'){
                $nodeArray['wiki']['info'] = array(
                    "alias" =>$wiki->getAlias(),
                    "director" => $wiki->getDirector(),
                    "starring" => $wiki->getStarring(),
                    "released" => $wiki->getReleased(),
                    "language" => $wiki->getLanguage(),
                    "country" => $wiki->getCountry(),
                    "writer" => $wiki->getWriter(),
                    "distributor"=>$wiki->getDistributor(),
                    "runtime"=>$wiki->getRuntime(),
                    "produced"=>$wiki->getProduced(),
                );
            }elseif($model == 'teleplay'){
                $nodeArray['wiki']['info'] = array(
                    "alias" =>$wiki->getAlias(),
                    "director" => $wiki->getDirector(),
                    "starring" => $wiki->getStarring(),
                    "released" => $wiki->getReleased(),
                    "language" => $wiki->getLanguage(),
                    "country" => $wiki->getCountry(),
                    "writer" => $wiki->getWriter(),
                    "distributor"=>$wiki->getDistributor(),
                    "runtime"=>$wiki->getRuntime(),
                    "produced"=>$wiki->getProduced(),
                    "episodes"=>$wiki->getEpisodes(),
                );
            }elseif($model == 'television'){
                $nodeArray['wiki']['info'] = array(
                    "channel" =>$wiki->getChannel(),
                    "play_time" => $wiki->getPlayTime(),
                    "host" => $wiki->getHost(),
                    "guest" => $wiki->getGuests(),
                    "producer" => $wiki->getProducer(),
                    "alias" => $wiki->getAlias(),
                    "runtime"=>$wiki->getRuntime(),
                    "country"=>$wiki->getCountry(),
                    "language"=>$wiki->getLanguage(),
                );
            }          
		}
		else 
		{
			$nodeArray = $this->getErrArray(1,'未找到数据');
		}
		return $nodeArray; 
	} 
    
	/**
     * 广电接口
     * 根据wiki_id获取wiki_meta信息
     * @param array(wiki_id)
     * @author lifucang
     */
    public function GetWikiMetasGd($args)
    {
    	$nodeArray = array();
        $wiki_id=$args['wiki_id'];
        $mongo = sfContext::getInstance()->getMondongo();
        $list = $mongo->getRepository('wikiMeta')->getMetasByWikiId($wiki_id);
        if($list){
            $nodeArray = $this->getErrArray(0,'', count($list));
    		foreach($list  as $wikimeta)
    		{
    			$nodeArray['wikimetas'][] = array(
    				'title'   => $wikimeta->getTitle(),
    				'content'     => $wikimeta->getContent(),
    				'html_cache'   => $wikimeta->getHtmlCache(),
    				'mark'     => $wikimeta->getMark(),
                    
    			);
    		}            
        }else{
            $nodeArray = $this->getErrArray(0,'');
        }
		return $nodeArray;
    }
    
    /**
     * 广电接口
     * 根据AssetId获取wiki信息
     * @param string($asSetId)
     * @author gaobo
     */
    public function GetWikiInfoByAssetId($params)
    {
        $mongo  = sfContext::getInstance()->getMondongo()->getRepository('ContentImport');
        $getobj = $mongo->findOne(array('query' => array('from_id' => strval($params['asset_id']))));
        if($getobj){
        $args['wiki_id'] = $getobj->getWikiId();
        }else{
        $args['wiki_id'] = '';
        }
        return self::GetWikiInfo($args);
    }
    
    
    /**
     * 广电接口
     * 获取时间段attachments信息
     *
     * @author gaobo
     */
    public function GetAttachments($params)
    {
      $nodeArray = array();
      if(!isset($params['end_time'])){
        $params['end_time']   = date("Y-m-d H:i:s",time());                    //当前时间
      }
	    if(!isset($params['start_time'])){
        $params['start_time'] = date("Y-m-d",strtotime("-1 day")).' 00:00:00'; //前一天0点
      }
	    if($params['start_time'] >= $params['end_time']){
		    return $nodeArray = $this->getErrArray(0,'参数错误');
	    }

      $attachments = Doctrine::getTable('Attachments')->getMyAttachments($params);
      if($attachments){
        $nodeArray = $this->getErrArray(0,'', count($attachments));
        foreach($attachments  as $attachment)
    		{
    			$nodeArray['attachments'][] = array(
    				'id'          => $attachment->getId(),
    				'file_name'   => $attachment->getFileName(),
    				'created_at'  => $attachment->getCreatedAt(),
    				'updated_at'  => $attachment->getUpdatedAt(),
    				'source_name' => $attachment->getSourceName(),
    				'category_id' => $attachment->getCategoryId(),
    				'file_key'    => $attachment->getFileKey(),
    			);
    		}
      }else{
        $nodeArray = $this->getErrArray(0,'');
      }
		  return $nodeArray;
    }
}
?>
