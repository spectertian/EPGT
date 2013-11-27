<?php
/**
 * api/interface2 的接口定义
 *
 * @author zgq
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');

class Simple2 
{
    var $prefix_url;
    var $filter;
    var $videoUrl;
    var $response;
    var $category;
    var $device;
    var $user;
    var $method;
    
    public function  __construct($post) 
    {
        $this->filter = array('时间'=>"year","地区"=>'area');
        $this->prefix_url = "http://www.epg.huan.tv/api/interface2";
        $this->category =  sfConfig::get("app_vod_category");

        //消息体验证
        if(!$post) {
            return $this->getErrDom('没有XML消息体！');
        }
        $postargs = $this->xmlToArray($post);
        //格式验证
        if(!$postargs) {
            return $this->getErrDom('XML格式错误！');
        }
        //接口验证
        if(!$postargs['website']) {
            return $this->getErrDom('请填写接口地址');
        }
        //设备验证
        if(!$this->checkDevice($postargs)) {
            return false;
        }        
        //用户验证
        if(!$this->checkUser($postargs)) {
            return false;
        }
        //开发者验证
        if(!$this->checkDeveloper($postargs)) {
            return false;
        }
        //方法验证
        if(!$this->checkMethod($postargs)) {
            return false;
        }
        //调用方法
        $method = $this->method;
        $this->$method($postargs);
    }  

    /**
     * 设备验证
     * @param unknown $developer
     * @return superwen
     */
    private function checkDevice($postargs) 
    {
        $this->device = $postargs['parameter'][0]['device'][0]['__attributes__'];
        if(!$this->device['dnum']) {            
            return $this->getErrDom("设备号不正确！");            
        } 
        if(!$this->device['devmodel']) {            
            return $this->getErrDom("设备型号不正确！");            
        } 
        return true;
    }
    
    /**
     * 用户验证
     * @param unknown $developer
     * @return superwen
     */
    private function checkUser($postargs) 
    {
        $this->user = $postargs['parameter'][0]['user'][0]['__attributes__'];
        if(!$this->user['huanid']) {            
            return $this->getErrDom("用户ID不正确！");            
        } 
        return true;
    }
    
    /**
     * 开发者验证
     * @param unknown $developer
     * @return bool
     * @create jhm
     * @modify superwen
     */
    private function checkDeveloper($postargs) 
    {
        //print_r($postargs['parameter']);exit;
        $developer = $postargs['parameter'][0]['developer'][0]['__attributes__'];
 
    	if (!$developer || !$developer['apikey'] || !$developer['secretkey']) {
            return $this->getErrDom('开发者信息不完整！');
    	} 
        $key = "developer_".$developer['apikey'];
       
        if(tvCache::getInstance()->has($key)) {
            $cacheDeveloper = tvCache::getInstance()->get($key);
        }
        
    	if (!$cacheDeveloper){
            $mongo = sfContext::getInstance()->getMondongo();
    		$repository = $mongo->getRepository('Developer');
            $cacheDeveloper = $repository->updateCahceByApikey($developer['apikey']);
        }
        if(!$cacheDeveloper) {
            return $this->getErrDom('未知开发者！');
        }
        if($cacheDeveloper->getSecretkey() != $developer['secretkey']) {
            return $this->getErrDom('开发者安全密钥不正确！');
        }
        if($cacheDeveloper->getState() == '0'){
            return $this->getErrDom('该开发者已锁定！');
        }
    	$developer['sources'] = $cacheDeveloper -> getSources();
    	$this->developer = $developer;
        return true;
    }
    
    /**
     * 方法验证
     * @param $method
     * @return superwen
     */
    private function checkMethod($postargs) 
    {
        $this->method = (string)trim($postargs['parameter'][0]['__attributes__']['type']);
        if(!$this->method) {
            return $this->getErrDom('接口名称不能为空');
        }
        $methodArray = array(
                            'GetMediaCategory',
                            'GetRecommendMedia',  
                            'GetMediaListByCategory',
                            'GetFilterOption',
                            'ReportUserMediaAction',
                            'GetMediaListByUser',
                            'GetMediaListByMedia',
                            'SearchMedia',
                            'SearchWiki',
                            'GetSpecifiedMedia',
							'GetChannelList',                   
        					'GetProgramListByChannel',
        					'GetRecommendByChannel',
        					'GetLivePrograme',
							'GetWikiInfo',
        					'GetThemeById',
        					'GetThemeList',                     
        					'ReportUserEpisodeAction',
        					'GetEpisodeListByUser',
                            'GetSpList',
                            'GetSpMediaListByCatelog',
        					'DeleteUserEpisodeAction',
        					'GetLiveCategory',
        					'SearchSuggest',
        					'GetChannelListBySP',
        					'SearchProgram',
        					'GetWikiExtend',
                            'ReportUserProgramAction',
                            'GetProgramListByUser',
                            'ReportUserLivingAction',
                            'GetSystemCitys',
                            'GetDtvSPList', 
                            'SetUserConfig',
                            'GetUserConfig',
                            'GetAllChannelProgram', 
                            'ReportChannelName',
        					'GetHotRecommendList',
        					'ReportUserChannelAction',
        					'GetChannelListByUser',
        					'GetLiveProgrameByUser',
        					'GetProgramListByMedia',
        					'GetWikiPackage',
                            'GetCategoryRecommend',
                            'GetShortMoviePackages',
                            'GetShortMoviePackageInfoById',
        					'ReportTVSPNIT',
        					'ReportTVSPEPG',
                            'GetYesterdayProgramByDate',
                            'GetNextweekProgramByDate',
                            'GetProgramRec'           
        );
        if(!in_array($this->method,$methodArray)) {            
            return $this->getErrDom("方法不存在");            
        }
        return true;
    }    
  
    /**
     * 判断变量传入与否
     * @param mixed  $var
     * @param string $message
     * @return bool
     * @author wangnan
     */
    private function isVarSet($var='',$message) 
    {
    	$status = true;
		if(empty($var)) {
			if($var != '0'){
				$status = false;
				$nodeArray = $this->getErrArray("true",null,null,$message);
				$this->arrayToDom($nodeArray);
			}
		}
		return $status; 
    }
    
    /**
     * 设置奇异视频的代理地址
     * @param int $tv_id
     * @return bool
     * @author wangnan
     */
    private function setVideoProxyUrl($tv_id = 0) {
        return "http://proxy.kkttww.net:8080/urlproxy/qiyi/?redirect=1&tv_id=".$tv_id;
    }

    /**
     * xml串转换为array
     * @param string $xmlstring
     * @return bool
     * @author wangnan
     */
    public function xmltoArray($xmlstring){
        return DOM::xmlStringToArray($xmlstring);
    }
    
    /**
     * array串转换为dom
     * @param array $array
     * @return dom
     * @author wangnan
     */
    public function arrayToDom($array,$rootTag="response",$rootAttribute=null)
    {
        if(!$rootAttribute){
            $rootAttribute = array('website' => $this->prefix_url);
        }
        return $this->response = DOM::arrayToXMLString($array,$rootTag,$rootAttribute);
    }
    
    /**
     * 获取错误的dom
     * @param array $array
     * @return dom
     * @author wangnan
     */
    public function getErrDom($message)
    {
		$this->arrayToDom($this->getErrArray("true",null,null,$message));
        return false;
    }

    /*
     * 返回错误节点xml 数组结构
     * @param $errorStatus true 或者 false  前者代表有错误，后者代表无错误
     * @param $num 每页的个数  不为null时代表有数据  则输出data标签     当为null时 用于显示错误信息不输出data标签
     * @param $total 总个数  不为null时代表有数据  则输出data标签      当为null时 用于显示错误信息不输出data标签
     * @return array
     * @author guoqiang.zhang
     */
    public function getErrArray($errorStatus,$num=null,$total=null,$message='')
    {
        $nodeArray = array();
        $nodeArray['error'][0][DOM::ATTRIBUTES] = array(
                'type' => $errorStatus,
                'note' => $message,
                'servertime' => date("Y-m-d H:i:s"),
            );
		if(!is_null($num)){
			$nodeArray['data'][0][DOM::ATTRIBUTES]['num'] = $num;
		}   
		if(!is_null($total)){
			$nodeArray['data'][0][DOM::ATTRIBUTES]['total'] = $total;
		}
		return $nodeArray;
    }

    /*
     * 获取影视分类
     * @param  $args
     * @retrun xml
     * @author guoqiang.zhang
     */
    public function GetMediaCategory($args)
    {
        $nodeArray = array();
        $category = $this->category;
        if(!$category){
           $nodeArray = $this->getErrArray("false",0); 
           return $this->arrayToDom($nodeArray);
        }
        $nodeArray = $this->getErrArray("false", count($category));
        foreach($category  as $key =>$value)
        {
           if($value['name']){
               if($value['child'])
               {
                   $type = 1;
                   $categoryNum = count($value['child']);
               }
               $nodeArray['data'][0]['class'][$key][DOM::ATTRIBUTES] = array(
                   'id' => $key,
                   'title' => $value['name'],
                   'type' => $type,
                   'num'  => $categoryNum,
                   'img'  => '',
               );
               foreach($value['child'] as $index => $child)
               {
                    $nodeArray['data'][0]['class'][$key]['subclass'][$index][DOM::ATTRIBUTES] = array(
                        'id'    => $index,
                        'title' => $child,
                        'num'   =>   0,
                        'img'   => '',
                    );
               }
           }
        }
        return $this->arrayToDom($nodeArray);        
    }
    

    /**
     *获取分类影视列表
     * @param  $args
     * @retrun xml
     * @author ly
     * @editor wangnan
     */
    public  function GetMediaListByCategory($args){
        $nodeArray = array();
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
 
        //获取提交过来的参数
        if(!$args['parameter'][0]['data'][0]['__attributes__']){
             $nodeArray = $this->getErrArray("true", null,null,'请正确填写节点data内参数');
        } else {
            $cid = $args['parameter'][0]['data'][0]['__attributes__']['cid'];
            $page = $args['parameter'][0]['data'][0]['__attributes__']['page']?$args['parameter'][0]['data'][0]['__attributes__']['page']:1;
            $size = $args['parameter'][0]['data'][0]['__attributes__']['size']?$args['parameter'][0]['data'][0]['__attributes__']['size']:8;
            $offset = $size * ($page-1);
            $order = $args['parameter'][0]['data'][0]['__attributes__']['order']?$args['parameter'][0]['data'][0]['__attributes__']['order']:1;
            $filters = $args['parameter'][0]['data'][0]['filter'];
            $model=null;
			if(!empty($cid))
			{
                $fuid=$this->getTagParentid($cid);
                if($fuid==1)
			        $model='film';
                elseif($fuid==2)  
                    $model='teleplay';
            }                   
            switch($order)
            {
            	case '1':
					$wikiRecommendRepo = $mongo->getRepository("WikiRecommend");
					$tag = $this->getTagName($cid);
					if(!empty($tag))
					{
                        /*
                        $fuid=$this->getTagParentid($cid);
                        if($fuid==1)
					        $model='film';
                        elseif($fuid==2)  
                            $model='teleplay';
                        */    
                        //$nodeArray=$this->getErrArray("true", null,null,"$cid|$fuid|$model");    
                        //return $this->arrayToDom($nodeArray);
						$hotPlays = $wikiRecommendRepo->getWikiByTag($tag,$size,$offset,$model);
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
			                //if($wiki->getHasVideo() && $wiki->getSource()){
			                    $wikis[] = $wiki;
			                //}
			                unset($wiki);
			            }
					}
					$num = count($wikis);//*********************************************************
					if(!empty($tag))
						$totalHotPlays = $wikiRecommendRepo->getWikiByTag($tag,999,null,$model);//******************************************
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
            		$nodeArray = $this->getErrArray("false", $num, $total);
					foreach($wikis as $key => $wiki)
					{
	                    $nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
	                            'id'    => $wiki->getId(),
	                            'title'   => $wiki->getTitle(),
	                      	    'model' =>$wiki->getModel()
	                    );
	                    $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray,'',0,false);
	                }			            
					break;
            	case '2':
					$nodeArray = $this->getMediaBySort($cid,$filters,$page,$size, 0,$model);
					break;
            	case '3':
					$nodeArray = $this->getMediaBySort($cid,$filters,$page,$size, 2,$model);
					break;
            	default:
            		$nodeArray = $this->getErrArray("true", null,null,'order='.$order.'无匹配，可能是 参数/值 错误');
            }
        }
        return $this->arrayToDom($nodeArray);
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
     public function getMediaBySort($cid,$filters,$page,$size,$order,$model=null)
     {
        $nodeArray = array();
        $Condition = $this->getSearchText(null,$cid,$filters,$model);
        if($Condition['range']==='error')
        {
            $nodeArray = $this->getErrArray("true", null,null,'请正确填写节点内年份搜索区间');
            return $this->arrayToDom($nodeArray);
        }
        $result = $this->getSearch($Condition['condition'],$page,$size,$Condition['range'],$order);
        $count = count($result['result']);
        if($count)
        {
            $nodeArray = $this->getErrArray("false", $count,$result['total']);
            foreach($result['result'] as $key => $wiki){
                $nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
                        'id'    => $wiki->getId(),
                        'title'   => $wiki->getTitle(),
                        'model' =>$wiki->getModel()
                );
                $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray, '', 0, false);
            }
        }
        else
        {
            $nodeArray = $this->getErrArray("false", 0,0,'未找到数据');
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
        $category = $this->category;         	
        if(array_key_exists($cid, $category)) {
            $tag = $category[$cid]['name'] ;
        } else {
            foreach($category as $cate) {
                if(array_key_exists($cid,$cate['child'])) {
                   $tag = $cate['child'][$cid];
                }
            }
        }
        return $tag;         	    
     }
     
    /*
     * 从WikiRepository.php中根据键名获取parent tag名称
     * @param  int    $cid
     * @return string $tag
     * @author wangnan
     */ 
     public function getTagParentid($cid=0)
     {
        $category = $this->category;   
        foreach($category as $key=>$value){
            if($key==$cid){
                return $key;
            } else {
                if(array_key_exists($cid, $value['child'])){
                    return $key;				
                }
            } 
        } 	    
     } 
     
    /*
     * 通过搜索关键字，分类，过滤选项来构建searchText和区间
     * @parame string $keyword
     * @param  int    $cid
     * @param  array  $filter
     * @return array  array('condition'=>searchText,'range'=>range)
     * @author guoqiang.zhang
     */
    public function getSearchText($keyword=null,$cid=0,$filters=null,$model=null)
    {
        $tmpstr = 'hasvideo:1';
        $range = '';
        if($keyword){
            $tmpstr .= " ".$keyword;
        }      
        if($cid){
            $category = $this->category;
            if(array_key_exists($cid, $category)){
                $tmpstr .=  " tag:".$category[$cid]['name'] ;
            } else {
                foreach($category as $cate){
                    if(array_key_exists($cid,$cate['child'])){
                       $tmpstr .= " tag:".$cate['child'][$cid];
                    }
                }
            }
            if($model){
                //$tmpstr .= " AND model:".$model;
                $tmpstr .= " model:".$model;
            }            
        }
        //待重构
        if($filters){
            foreach($filters as $key => $filter){
                if($filter['__attributes__']['type'] == "地区"){
                    $tmpstr .= " area:".$filter['__attributes__']['value'];
                }elseif($filter['__attributes__']['type'] == "时间"){
                    $range = $this->getYearRang($filter['__attributes__']['value']);
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
    public function getYearRang($str)
    {
        if(is_numeric($str)){
            return $str."-".$str;
        } else {
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
    public function getSearch($searchText,$page=0,$limit=10,$searchRange=null,$sort=1)
    {
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
    public function getSearchTotal($searchText,$searchRange=null)
    {
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $total = $wikiRep->getSearchCount($searchText, $searchRange);
        return $total;
    }
    
    /**
     * GetRecommendMedia
     * @author wangnan
     * @return object
     */     
    public function GetRecommendMediaFromMondongo($arrs) 
    {
        $parameter = $arrs['parameter'][0]['data'][0]['__attributes__'];
        $page = $parameter['page'] ? $parameter['page'] : 1;
        $size = $parameter['size'] ? $parameter['size'] : 8;
        $tag  = $parameter['tag'];
        $type  = $parameter['type'];
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki');
        $wrRepo = $mongo->getRepository("WikiRecommend");
        $userId='';
        
        $wikiRecs = $wrRepo->getWikiByPageAndSize($page,$size,$tag);
        $totalWikiRecs = $wrRepo->getWikiByTagNoLimit($tag);
        
        if($wikiRecs){
        	$count_wikiRecs=0;       	
        	$arr = $this->getErrArray("false", $count_wikiRecs,count($totalWikiRecs));
            foreach($wikiRecs as $key => $wikiRec){
                $wiki = $wikiRepository->getWikiById($wikiRec['wiki_id']); 
                if($wiki){ 
                    if($wiki->getHasVideo()>1){ 
	                    $arr['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
	                            'id' => (string)$wiki->getId(),
	                            'title'=> $wiki->getTitle(),
	                            'model' =>$wiki->getModel()
	                    );
	                    $count_wikiRecs = $count_wikiRecs+1;
	                    $arr = $this->getWikiVideoSource($wiki,$key, $arr, '', 0, false);
                    }   
                }
            }
            if($count_wikiRecs){
                $arr['data'][0][DOM::ATTRIBUTES]['num'] = $count_wikiRecs; //重新设置返回条数
            }
        } else {
            $arr = $this->getErrArray("false", null,null,'');
        }
        return $this->arrayToDom($arr);
    }   

    /*
     * 获取猜你喜欢的影视
     * @param  int    $page
     * @param  int    $size
     * @return array  $nodeArray
     * @author wangnan
     */
    public function getRecommendMediaByHuanIrs($arrs)
    {
        $parameter = $arrs['parameter'][0]['data'][0]['__attributes__'];
        $page = $parameter['page'] ? $parameter['page'] : 1;
        $size = $parameter['size'] ? $parameter['size'] : 8;
        $tag  = $parameter['tag'];
        $parameter_user = $arrs['parameter'][0]['user'][0]['__attributes__'];
        $userId = $parameter_user['huanid'] ? $parameter_user['huanid'] : '1234';
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki');
        $wrRepo = $mongo->getRepository("WikiRecommend");
        $wikiRecs = $wrRepo->getWikiByHuanIrs($userId,$size);
        $totalWikiRecs = $size;
        if(!$wikiRecs){
            $wikiRecs = $wrRepo->getWikiByPageAndSize($page,$size,$tag);
            $totalWikiRecs = $wrRepo->getWikiByTagNoLimit($tag);
        }
        if($wikiRecs){
        	$count_wikiRecs=0;       	
        	$arr = $this->getErrArray("false", $count_wikiRecs,count($totalWikiRecs));
            foreach($wikiRecs as $key => $wikiRec){
                $wiki = $wikiRepository->getWikiById($wikiRec['wiki_id']); 
                if($wiki){ 
                    if($wiki->getHasVideo()>1){ 
	                    $arr['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
	                            'id' => (string)$wiki->getId(),
	                            'title'=> $wiki->getTitle(),
	                            'model' =>$wiki->getModel()
	                    );
	                    $count_wikiRecs = $count_wikiRecs+1;
	                    $arr = $this->getWikiVideoSource($wiki,$key, $arr, '', 0, false);
                    }   
                }
            }
            if($count_wikiRecs){
                $arr['data'][0][DOM::ATTRIBUTES]['num'] = $count_wikiRecs; //重新设置返回条数
            }
        } else {
            $arr = $this->getErrArray("false", null,null,'');
        }
        return $this->arrayToDom($arr);        
    }    

    /*
     * 获取随机推荐的影视
     * @param  int    $page
     * @param  int    $size
     * @return array  $nodeArray
     * @author wangnan
     */
    public function getRecommendMediaByRand($arrs)
    {
        $nodeArray = array();
        $parameter = $arrs['parameter'][0]['data'][0]['__attributes__'];
        $size = $parameter['size'] ? $parameter['size'] : 10;
        $tag  = $parameter['tag'] ? $parameter['tag'] : '';
        if($tag){
            $str = ' tag:'.$tag;
        } else {
            $str = '';
        }
        
        $Condition = array('condition'=>'hasvideo:1'.$str);
        
        $hasvideo = 1;
        $result = $this->getSearch($Condition['condition'],1,$size);
        $page = rand(1,ceil($result['total']/$size));
        $result = $this->getSearch($Condition['condition'],$page,$size);
        $count = count($result['result']);
        if(!$count){
            $result = $this->getSearch($Condition['condition'],1,$size);
            $count = count($result['result']);
        }
        if($count){
            $nodeArray = $this->getErrArray("false", $count,$result['total']);
            foreach($result['result'] as $key => $wiki){
                $nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
                        'id'    => $wiki->getId(),
                        'title'   => $wiki->getTitle(),
                        'model' =>$wiki->getModel()
                );
                $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray,'',0,false);
            }
            return $this->arrayToDom($nodeArray);
        } else {
            $nodeArray = $this->getErrArray("false", 0,0,'未找到数据');
            return $this->arrayToDom($nodeArray);
        }
    }
    
    /**
     * GetRecommendMedia
     * @direction  获取推荐影视列表，从Least Click TV API获取数据
     * @author lifucang
     * @return object
     */
    public function GetRecommendMedia($arrs) 
    {
    	$parameter = $arrs['parameter'][0]['data'][0]['__attributes__'];
        $type = $parameter['type'] ? $parameter['type'] : 1;        
        switch($type){
            case 4:
                return $this->getRecommendMediaByRand($arrs);
            case 3:
                return $this->GetRecommendMediaFromMondongo($arrs);  
            case 2:       
                return $this->getRecommendMediaByHuanIrs($arrs);         
            default:
                return $this->GetRecommendMediaFromMondongo($arrs);
        }
    }
    
    /**
     * GetMediaListByMedia
     * @direction  获取相关影视列表，从Least Click TV API获取数据
     * @author lifucang
     * @return object
     */
    public function GetMediaListByMedia($arrs) 
    {
		$parameter = $arrs['parameter'][0]['data'][0]['__attributes__'];
		$size = $parameter['size'] ? $parameter['size'] : 10;
        $sort = $parameter['sort'] ? $parameter['sort'] : 'default';
        $detail = $parameter['detail'] ? $parameter['detail'] : false;
        $mediaId = $parameter['mid'];
        
        $parameter_user = $arrs['parameter'][0]['user'][0]['__attributes__'];
        $userId=$parameter_user['huanid'] ? $parameter_user['huanid'] : '1234';
        
        $url=sfConfig::get('app_lct_server_url')."api/media/$mediaId/user/$userId/recommendations";
        $url.="?size=$size&sort=$sort&detail=$detail";
        $contents=Common::get_url_content($url);
        if(!$contents){
            return $this->GetMediaListByMediaFromGetMondongo($arrs); //如果获取不到从原来获取
        }
        $arr_contents=json_decode($contents);
        if($arr_contents->success==1){
            $arr = $this->getErrArray("false", $size,count($arr_contents->objects));
            foreach($arr_contents->objects as $key => $value){
                $arr['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
                    'id' => (string)$value->id,
                    'title'=> $value->title,
                );   
                //获取详细信息
                $arr = $this->getLctVideoSource($value,$key, $arr);                
            }

        } else {
            //$arr = $this->getErrArray("false", null,null,'');
            return $this->GetMediaListByMediaFromGetMondongo($arrs); //如果获取不到从原来获取
        }
        return $this->arrayToDom($arr);
    } 
    
    /*
     * 返回wiki的类型
     * @param array $tags
     * @return string $tag
     * author guoqiang.zhang
     */
    public function getTag($tags,$arr)
    {
        $tmpstr = array();
        foreach($tags as $tag){
            if(!array_search($tag, $arr)){
                $tmpstr[] = $tag;
            }
        }
       return implode(",",$tmpstr);
    }    
    
    /*
     * 根据Least Click TV API获取数据
     * @return $nodeArray
     * @author lifucang
     * @editor lifucang
     */
    public function getLctVideoSource($wiki,$i,$nodeArray)
    {
        $director = !$wiki->director ? '' : implode(',', $wiki->director);
        $actors = !$wiki->starring ? '' : implode(',', $wiki->starring);
        $tags = !$wiki->tags ? '' : implode(',', $wiki->tags);
        $area = !$wiki->country ? "" : $wiki->country;
        $language = !$wiki->language ? "" : $wiki->language;
        $score = 0;  //该值不存在
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
        $nodeArray['data'][0]['media'][$i]['info'][$i][DOM::ATTRIBUTES] = array(
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
        $nodeArray['data'][0]['media'][$i]['description'] = $wiki->content;
        $cover = $wiki->cover;
        if ($cover) {
            $nodeArray['data'][0]['media'][$i]['posters'][$i][DOM::ATTRIBUTES]['num'] = 3;
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][0][DOM::ATTRIBUTES] = array(
                "type" => "small",
                "size" => "108*160",
                "url" => thumb_url($cover, 108, 160),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][1][DOM::ATTRIBUTES] = array(
                "type" => "big",
                "size" => "216*320",
                "url" => thumb_url($cover, 216, 320),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][2][DOM::ATTRIBUTES] = array(
                "type" => "max",
                "size" => "324*480",
                "url" => thumb_url($cover, 324, 480),
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
                            "url" =>  $this->setVideoProxyUrl($tvconfig['tvId']),
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
		                                "url" => $this->setVideoProxyUrl($tvconfig['tvId']),
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
	                                "url" => $this->setVideoProxyUrl($tvconfig['tvId']),
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
    public function getWikiVideoSource($wiki,$i,$nodeArray,$type='',$biaozhi=0,$juji=true)
    {
        $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
        $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
        $tags = !$wiki->getTags() ? '' : $this->getTag($wiki->getTags(),array($this->category[1]['name'],$this->category[2]['name']));
        $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
        $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
        $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
        $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
        $praise = !$wiki->getLikeNum() ? 0 : $wiki->getLikeNum();
        $dispraise = !$wiki->getDislikeNum() ? 0 : $wiki->getDislikeNum();
        $videos = $wiki->getVideos();
        $refererSource = array(
                    'qiyi'          =>  '奇艺',
                    'youku'         =>  '优酷',
                    'sina'          =>  '新浪',
                    'sohu'          =>  '搜狐',
                    'tps'           =>  'tps',
        			'baidu_qiyi'    =>  '百度-奇艺',
                    'baidu_youku'   =>  '百度-优酷',
                    'baidu_sina'    =>  '百度-新浪',
                    'baidu_sohu'    =>  '百度-搜狐',
                    'baidu_pptv'    =>  '百度-PPTV',
                    'baidu_pps'     =>  '百度-PPS',
                    'baidu_letv'    =>  '百度-乐视',
                    'baidu_tudou'   =>  '百度-土豆',
        			'baidu_tencent' =>  '百度-腾讯',
                    'cntv'          =>  'CNTV',
                    'wasu'          =>  'WASU',
                ); 
        $source = '';
        $prefer = "奇艺"; //优选片源
        if ($videos != NULL) {
			foreach($sources = $wiki->getSource() as $key){
			 	$source = $source ? $source.",".$refererSource[$key]: $refererSource[$key];;//editor by wangnan 2013-02-26
			}
        }
        $whether_mark = (gettype($type) =='array')?true:false;
        $model = $wiki->getModel();
        if ($model == 'actor') {
            $nodeArray['data'][0]['media'][$i]['info'][$i][DOM::ATTRIBUTES] = array(
                "sex" => $wiki->getSex()?$wiki->getSex():'',
                "birthday" => $wiki->getBirthday()?$wiki->getBirthday():'',
                "birthplace" => $wiki->getBirthplace()?$wiki->getBirthplace():'',
                "occupation" => $wiki->getOccupation()?$wiki->getOccupation():'',
                "zodiac" => $wiki->getZodiac()?$wiki->getZodiac():'',
                "bloodType" => $wiki->getBloodType()?$wiki->getBloodType():'',
                "nationality" => $wiki->getNationality()?$wiki->getNationality():'',
                "region" => $wiki->getRegion()?$wiki->getRegion():'',
                "height" => $wiki->getHeight()?$wiki->getHeight():'',
                "weight" => $wiki->getWeight()?$wiki->getWeight():'',
                "debut" => $wiki->getDebut()?$wiki->getDebut():'',
            );
        } else {
            $nodeArray['data'][0]['media'][$i]['info'][$i][DOM::ATTRIBUTES] = array(
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
        }
        if($model == 'television'){
        	$play_time = !$wiki->getPlayTime() ? '' : $wiki->getPlayTime();
        	$nodeArray['data'][0]['media'][$i]['info'][$i][DOM::ATTRIBUTES]['playtime'] = $play_time;
        }        
        $nodeArray['data'][0]['media'][$i]['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if($cover) {
            $nodeArray['data'][0]['media'][$i]['posters'][$i][DOM::ATTRIBUTES]['num'] = 3;
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][0][DOM::ATTRIBUTES] = array(
                "type" => "small",
                "size" => "108*160",
                "url" => thumb_url($cover, 108, 160),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][1][DOM::ATTRIBUTES] = array(
                "type" => "big",
                "size" => "216*320",
                "url" => thumb_url($cover, 216, 320),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][2][DOM::ATTRIBUTES] = array(
                "type" => "max",
                "size" => "324*480",
                "url" => thumb_url($cover, 324, 480),
            );
        }
        //增加剧照显示lifucang(2012-7-18)
	    $screen_num = $wiki->getScreenshotsCount();        
        $nodeArray['data'][0]['media'][$i]['screens'][0][DOM::ATTRIBUTES]= array(
            'num'    => $screen_num,
        );
        $screens = $wiki->getScreenshotUrls(288,217);   
        foreach($screens as $k => $screen){
            $nodeArray['data'][0]['media'][$i]['screens'][0]['screen'][$k][DOM::ATTRIBUTES]= array(
                'url'    =>  $screens[$k],
            ); 
        }
        if($juji){
	        if($biaozhi==1){
	            if($type['type']==1){
	                $xianshi=false;
	            } else {
	                $xianshi=true;
	            }
	        } else {
	            $xianshi=true;
	        }
	        if($xianshi){  //lfc增加
	            if ($model == 'film') {
	                $videos = $wiki->getVideos();
	                if ($videos != NULL) {
	                	$a=0;
	                    foreach ($videos as $video) {
	                        //$tvconfig = $video->getConfig();
	                        //if(!$tvconfig && $video->getReferer() == 'qiyi')//如果没有config字段
	                        //	continue;
	                        //else
	                        //{
		                    //    if ($video->getReferer() == 'qiyi')
		                     //   	$url = $this->setVideoProxyUrl($tvconfig['tvId']);
		                    //    else 
		                        	$url = $video->getUrl();
		                        //$nodeArray=$this->addEpisodesFilm($i,$video,$nodeArray);
		                        //if ($video->getReferer() == 'qiyi') {
		                            $nodeArray['data'][0]['media'][$i]['episodes'][$a][DOM::ATTRIBUTES] = array(
		                                //"source" => "奇艺",
		                                "source" => $video->getReferer(),
		                                "num" => 1
		                            );
		                            $video_id = $video->getId();
		                            $nodeArray['data'][0]['media'][$i]['episodes'][$a]['episode'][0][DOM::ATTRIBUTES] = array(
		                                "id" => $video_id,//由于数据由此处获取 无需判断传来的eide是否匹配此video_id
		                                "index" => 1,
		                                "size" => 0,
		                                "length" => 0,
		                                "format" => '',
		                                "rate" => 0,
		                                "vip" => 0,
		                                //"url" =>  $this->setVideoProxyUrl($tvconfig['tvId']),
		                                "url" =>  $url,
		                                "live" => 0,
		    		                            	"title"=>$video->getTitle(),
		                            );
		                            $a++;
		                        //}
	                    	//}
	                    }
	                }
	            }
	            if ($model == 'teleplay') {
	                $playLists = $wiki->getPlayList();
	                if ($playLists != NULL) {
	                    foreach ($playLists as $k=>$playList) {
	                       //$nodeArray=$this->addEpisodesTeleplay($i,$playList,$nodeArray);
	                        //if ($playList->getReferer() == 'qiyi') {
	                            $countVideo = $playList->countVideo();
	                            $nodeArray['data'][0]['media'][$i]['episodes'][$k][DOM::ATTRIBUTES] = array(
	                               //"source" => "奇艺",
	                                "source" => $playList->getReferer(),
	                                "num" => $countVideo,
	                            );
	                            $videos = $playList->getTeleplayVideos();
	                            $j = 0;
	                            if($whether_mark)
	                            {
	                            	 foreach ($videos as $video) {
	    	                                //$tvconfig = $video->getConfig();
	    	                                //if(!$tvconfig  && $video->getReferer() == 'qiyi')
	    	                                //	continue;
	    	                                //else
	    	                                //{
						                    //    if ($video->getReferer() == 'qiyi')
						                    //    	$url = $this->setVideoProxyUrl($tvconfig['tvId']);
						                    //    else 
						                        	$url = $video->getUrl();    	                                
		    	                                if((string)$video->getId()==$type['eid'])
		    		                            $nodeArray['data'][0]['media'][$i]['episodes'][$k]['episode'][$j][DOM::ATTRIBUTES] = array(
		    		                            	//"markid" => (string)$type['markid'],
		    		                            	//"marktime" => $type['marktime'],
		    		                                "id" => $video->getId(),
		    		                                "index" => $video->getMark(),
		    		                                "size" => 0,
		    		                                "length" => 0,
		    		                                "format" => '',
		    		                                "rate" => 0,
		    		                                "vip" => 0,
		    		                                //"url" => $this->setVideoProxyUrl($tvconfig['tvId']),
		    		                                "url" => $url,
		    		                                "live" => 0,
		    		                            	"title"=>$video->getTitle(),
		    		                            );
		                                        $j++;
	    	                                //}
	                            	 }
	                            }
	                            else
	                            {
	    	                        foreach ($videos as $video) {
	    	                            //$tvconfig = $video->getConfig();
	    	                        	//if(!$tvconfig  && $video->getReferer() == 'qiyi')
	    	                        	//	continue;
	    	                        	//else
	    	                        	//{
					                        //if ($video->getReferer() == 'qiyi')
					                        //	$url = $this->setVideoProxyUrl($tvconfig['tvId']);
					                        //else 
					                        	$url = $video->getUrl();    	                            
		    	                            $nodeArray['data'][0]['media'][$i]['episodes'][$k]['episode'][$j][DOM::ATTRIBUTES] = array(
		    	                                "id" => $video->getId(),
		    	                                "index" => $video->getMark(),
		    	                                "size" => 0,
		    	                                "length" => 0,
		    	                                "format" => '',
		    	                                "rate" => 0,
		    	                                "vip" => 0,
		//    	                                "url" => $this->setVideoProxyUrl($tvconfig['tvId']),
		    	                                "url" => $url,
		    	                                "live" => 0,
		    		                            "title"=>$video->getTitle(),
		    	                            );
		    	                            $j++;
	    	                        	//}
	    	                        }
	                            } 
	                        //}
	                    }
	                }
	            }
	            
	            if ($model == 'television') {
	                $playLists = $wiki->getPlayList();
	                if ($playLists != NULL) {
	                    foreach ($playLists as $k=>$playList) {
	                       //$nodeArray=$this->addEpisodesTeleplay($i,$playList,$nodeArray);
	                        //if ($playList->getReferer() == 'qiyi') {
	                            $countVideo = $playList->countVideo();
	                            $nodeArray['data'][0]['media'][$i]['episodes'][$k][DOM::ATTRIBUTES] = array(
	                                "source" => $playList->getReferer(),
	                                "num" => $countVideo,
	                            );
	                            $videos = $playList->getVideos();
	                            $j = 0;
	                            if($whether_mark)
	                            {
	                            	 foreach ($videos as $video) {
	    	                                //$tvconfig = $video->getConfig();
					                        //if ($video->getReferer() == 'qiyi')
					                        //	$url = $this->setVideoProxyUrl($tvconfig['tvId']);
					                        //else 
					                        	$url = $video->getUrl();    	                                
	    	                                if((string)$video->getId()==$type['eid'])
	    		                            $nodeArray['data'][0]['media'][$i]['episodes'][$k]['episode'][$j][DOM::ATTRIBUTES] = array(
	    		                            	//"markid" => (string)$type['markid'],
	    		                            	//"marktime" => $type['marktime'],
	    		                                "id" => $video->getId(),
	    		                                "index" => $video->getMark(),
	    		                                "size" => 0,
	    		                                "length" => 0,
	    		                                "format" => '',
	    		                                "rate" => 0,
	    		                                "vip" => 0,
	//    		                                "url" => $this->setVideoProxyUrl($tvconfig['tvId']),
	    		                                "url" => $url,
	    		                                "live" => 0,
		    		                            	"title"=>$video->getTitle(),
	    		                            );
	                                        $j++;
	                            	 }
	                            }
	                            else
	                            {
	    	                        foreach ($videos as $video) {
	    	                            //$tvconfig = $video->getConfig();
				                        //if ($video->getReferer() == 'qiyi')
				                        //	$url = $this->setVideoProxyUrl($tvconfig['tvId']);
				                        //else 
				                        	$url = $video->getUrl();    	                            
	    	                            $nodeArray['data'][0]['media'][$i]['episodes'][$k]['episode'][$j][DOM::ATTRIBUTES] = array(
	    	                                "id" => $video->getId(),
	    	                                "index" => $video->getMark(),
	    	                                "size" => 0,
	    	                                "length" => 0,
	    	                                "format" => '',
	    	                                "rate" => 0,
	    	                                "vip" => 0,
	//    	                                "url" => $this->setVideoProxyUrl($tvconfig['tvId']),
	    	                                "url" => $url,
	    	                                "live" => 0,
		    		                        "title"=>$video->getTitle(),
	    	                            );
	    	                            $j++;
	    	                        }
	                            } 
	                        //}
	                    }
	                }
	            }            
	        }
        }

        return $nodeArray;
    }  
    
    /**
     * 添加episodes   for  teleplay
     * @param  int    $i
     * @param  object $playList
     * @param  array  $nodeArray
     * @author wangnan
     * @return array
     */
    public function addEpisodesTeleplay($i,$playList,$nodeArray) 
    {
        static $n=0;
        $referer = $playList->getReferer();
    	$refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
		if (array_key_exists($referer, $refererSource)) 
		{
		    $source = $refererSource[$referer];
		}
		$countVideo = $playList->countVideo();
		$nodeArray['data'][0]['media'][$i]['episodes'][$n][DOM::ATTRIBUTES] = array(
			"source" => $source,
			"num" => $countVideo,
		);
		$videos = $playList->getVideos();
		$j = 1;
		foreach ($videos as $video) 
		{
			$tvconfig = $video->getConfig();
			$nodeArray['data'][0]['media'][$i]['episodes'][$n]['episode'][$j][DOM::ATTRIBUTES] = array(
				"id" => $video->getId(),
				"index" => $j,
				"size" => 0,
				"length" => 0,
				"format" => '',
				"rate" => 0,
				"vip" => 0,
				"url" => ($referer=='qiyi')?$this->setVideoProxyUrl($tvconfig['tvId']):$video->getUrl(),
				"live" => 0
			);
			$j++;
		}
		$n++;
		return $nodeArray;
    }
    
    /**
     * 添加episodes   for  film
     * @param  int    $i
     * @param  object $video
     * @param  array  $nodeArray 
     * @author wangnan
     * @return array
     */
    public function addEpisodesFilm($i,$video,$nodeArray) 
    {
        static $n=0;
        $tvconfig = $video->getConfig();
        $referer = $video->getReferer();
    	$refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
		if (array_key_exists($referer, $refererSource)){
		    $source = $refererSource[$referer];
		}
		$url = ($referer == 'qiyi')?$this->setVideoProxyUrl($tvconfig['tvId']):$video->getUrl();
		$nodeArray['data'][0]['media'][$i]['episodes'][$n][DOM::ATTRIBUTES] = array(
			"source" => $source,
			"num" => 1
		);
		$nodeArray['data'][0]['media'][$i]['episodes'][$n]['episode'][0][DOM::ATTRIBUTES] = array(
			"id" => $video->getId(),
			"index" => 1,
			"size" => 0,
			"length" => 0,
			"format" => '',
			"rate" => 0,
			"vip" => 0,
			"url" =>  $url,
			"live" => 0
		);
		$n++;
		return $nodeArray;
    }
    
    /**
     * GetFilterOption 获得筛选选项
     * @param  array $arr
     * @author lizhi
     * @return void
     */
    public function GetFilterOption($arrs) 
    {
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $listOption = $wikiRep->getUsedArr();
        $arr = array();
        $arr = $this->getErrArray("false",count($listOption));
        $i = 0;
        $arr['data'][0]['filters'][0][DOM::ATTRIBUTES]['name'] = "地区";
        $arr['data'][0]['filters'][0][DOM::ATTRIBUTES]['num'] = count($listOption['country']);        
        foreach($listOption['country'] as $area) {
            $arr['data'][0]['filters'][0]['filter'][$i][DOM::ATTRIBUTES]['name'] = $area;
            $i++;
        }
        $j = 0;
        $arr['data'][0]['filters'][1][DOM::ATTRIBUTES]['name'] = "时间";
        $arr['data'][0]['filters'][1][DOM::ATTRIBUTES]['num'] = count($listOption['time']);
        foreach($listOption['time'] as $key => $time) {
            $arr['data'][0]['filters'][1]['filter'][$j][DOM::ATTRIBUTES] = array('name'=>$key,'value'=>$time);
            $j++;
        }
        return $this->arrayToDom($arr);
    }

    /*
     * 根据wikiid返回数据
     * @param  array
     * @return xml
     * @author ly
     */
    public function GetSpecifiedMedia($args){
        $mid = $args['parameter'][0]['data'][0]['__attributes__']['mid'];
        $nodeArray = array();        
        if($this->isVarSet($mid,'参数mid缺失')==false) return;
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki');
//      $wiki = $wikiRepository->findOneById(new MongoId($mid));
        $wiki = $wikiRepository->getWikiById($mid);
        if($wiki){
             $nodeArray = $this->getErrArray("false", 1,1);
             $nodeArray['data'][0]['media'][0][DOM::ATTRIBUTES] = array(
                'id' => $wiki->getId(),
                'title' => $wiki->getTitle(),
             	'model' =>$wiki->getModel()
             );
             $nodeArray = $this->getWikiVideoSource($wiki, 0, $nodeArray);
        } else {
            $nodeArray = $this->getErrArray("false", null,null,'');
        }
        return  $this->arrayToDom($nodeArray);
    }
    
    /**
     * 通过关键字获得相应的结果用到了XS
     * @param array $args
     * @author wangnan  
     * @return xml
     */
    public function SearchMedia(array $args) {
        $parameter = $args['parameter'][0]['data'][0]['__attributes__'];
        $keyWord = $parameter['keyword'];
        $field = $parameter['field'];
		if($field){
        	$keyWord = '';
        	$fields = explode('+',$field);
        	foreach($fields as $field){
          		$keyWord .= $field.':'.$parameter['keyword'].' OR ';
        	}
        }
		$keyWord = preg_replace("/OR $/", "",  $keyWord); 
        $page = $parameter['page'];
        $size = $parameter['size'];
        $offset = $size * ($page - 1);
        $total = NULL;
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $result = $wikiRep->xun_search("hasvideo:1 $keyWord", $total, (int)$offset, (int)$size,null,1,$parameter['keyword']); 
        $pageTotal = count($result);
        $arr = array();
        if(!$result){
            $arr = $this->getErrArray("false", 0,0);
        } else {
            $arr = $this->getErrArray("false", $pageTotal, $total);
            $i = 0;
            foreach($result as $res) {
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['id'] = $res->getId();
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['title'] = $res->getTitle();
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['model'] = $res->getModel();
                $arr = $this->getWikiVideoSource($res, $i, $arr, '', 0, false);
                $i++;
            }
        }
        return $this->arrayToDom($arr);
    } 
    
    /**
     * 通过关键字获得相应的结果用到了XS
     * @param array $args
     * @author wangnan  
     * @return xml
     */
    public function SearchWiki(array $args) {
        $parameter = $args['parameter'][0]['data'][0]['__attributes__'];
        $keyWord = $parameter['keyword'];
        $field = $parameter['field'];
		if($field){
        	$keyWord = '';
        	$fields = explode('+',$field);
        	foreach($fields as $field){
          		$keyWord .= $field.':'.$parameter['keyword'].' OR ';
        	}
        }
		$keyWord = preg_replace("/OR $/", "",  $keyWord); 
        $page = $parameter['page'];
        $size = $parameter['size'];
        $offset = $size * ($page - 1);
        $total = NULL;
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $result = $wikiRep->xun_search($keyWord, $total, (int)$offset, (int)$size,null,1,$parameter['keyword']); 
        $pageTotal = count($result);
        $arr = array();
        if(!$result){
            $arr = $this->getErrArray("false", 0,0);
        } else {
            $arr = $this->getErrArray("false", $pageTotal, $total);
            $i = 0;
            foreach($result as $res) {
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['id'] = $res->getId();
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['title'] = $res->getTitle();
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['model'] = $res->getModel();
                $arr = $this->getWikiVideoSource($res, $i, $arr, '', 0, false);
                $i++;
            }
        }
        return $this->arrayToDom($arr);
    }
    
    /**
     * GetMediaListByMedia  用到了XS
     * editor lifucang 2012-8-30 增加了艺人判断
     */
    public function GetMediaListByMediaFromGetMondongo(array $args) {
        $rootArgs = array('website' => $this->prefix_url);
        $wiki_id = $args['parameter'][0]['data'][0]['__attributes__']['mid'];
        $page = $args['parameter'][0]['data'][0]['__attributes__']['page'];
        $size = $args['parameter'][0]['data'][0]['__attributes__']['size'];
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $wiki = $wikiRep->getWikiById($wiki_id);
        if ($wiki == NULL) {
            $arr = $this->getErrArray("false", 0);
            return $this->arrayToDom($arr);
        }
        //从此处开始判断
        if ($wiki->getModel() == 'actor') {
            $movies = $wiki->getFilmographyBySize($wiki->getTitle(),$page,$size);
            $moviesAll = $wiki->getFilmographyCount($wiki->getTitle());
            if($movies){
                $arr = $this->getErrArray("false",count($movies),$moviesAll);
                $i = 1;
                foreach($movies as $wiki){
                    $arr['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
                       'id' => (string)$wiki->getId(),
                       'title'=> $wiki->getTitle(),
                       'model' =>$wiki->getModel()
                    );
                    $arr = $this->getWikiVideoSource($wiki, $i, $arr, '', 0, false);
                    $i++;
                }                 
            } else {
                $arr = $this->getErrArray("false", 0);
            }
        } else {
            $tags = $wiki->getTags();
            if (!empty($tags)) {
                $search_tags = array_map(function($tag) {
                    return "tag:".$tag;
                }, $tags);
                $searchText = $this->getSearchText("(".implode(' OR ', $search_tags).") NOT id:".$wiki_id, 0, null);
                $movies = $this->getSearch($searchText['condition'], $page, $size);
            }
            if (isset($movies['result']) && !empty($movies['result'])) {
                $arr = $this->getErrArray("false",count($movies['result']),$movies['total']);
                $i = 0;
                foreach($movies['result'] as $wiki){
                    $arr['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
                       'id' => (string)$wiki->getId(),
                       'title'=> $wiki->getTitle(),
                       'model' =>$wiki->getModel()
                    );
                    $arr = $this->getWikiVideoSource($wiki, $i, $arr, '', 0, false);
                    $i++;
                } 
            } else {
                $arr = $this->getErrArray("false", 0);
            }   
        }
        return $this->arrayToDom($arr);
    }

    /*
     * 返回用户影视列表
     * @param array $args
     * @return array nodeArray
     * @author guoqiang.zhang
     */
    public function GetMediaListByUser($args){
        $data = $args['parameter'][0]['data'][0]['__attributes__'];
        $device = $args['parameter'][0]['device'][0]['__attributes__'];
        if($this->isVarSet($device['dnum'],'请填写dnum')==false) return;
        $page = $data['page'] ? $data['page'] : 1;
        $size = $data['size'] ? $data['size'] : 8;
        $type = $data['type'] ? $data['type'] : 1;
        switch ($type){
            case '1':
                $nodeArray = $this->getUserChips($device['dnum'],$page,$size);
                break;
            case '2':
                $nodeArray = $this->getUserLikes($device['dnum'],$page,$size);
                break;  
            case '3':
                $nodeArray = $this->getUserWatched($device['dnum'],$page,$size);
                break;                              
        }
        return $this->arrayToDom($nodeArray);
        
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
            return $this->getErrArray("true", null,null,'该用户不存在');
        }
        $userchips =  new sfMondongoPager('singleChip', $size);
        $options['query'] = array(
            'user_id'=> (string)$user->getId(),
            'is_public'=> true
        );
        $options['sort'] = array('created_at'=>-1);
        $userchips->setFindOptions($options);
        $userchips->setPage($page);
        $userchips->init();
        $chips = $userchips->getResults();
        if($chips){
            $arr = $this->getErrArray("false", count($chips), $userchips->getNbResults());
            foreach($chips as $key => $chip){
                $wikiRepository = $mongo->getRepository("wiki");
                $wiki = $wikiRepository->getWikiById($chip->getWikiId());
                if($wiki){
	                $arr['data'][0]['media'][$key][DOM::ATTRIBUTES]['id'] = $wiki->getId();
	                $arr['data'][0]['media'][$key][DOM::ATTRIBUTES]['title'] = $wiki->getTitle();
	                $arr['data'][0]['media'][$key][DOM::ATTRIBUTES]['model'] = $wiki->getModel();
	                $arr = $this->getWikiVideoSource($wiki, $key, $arr,'',0,false);
                }
            }
        } else {
            $arr = $this->getErrArray("false", 0, 0);
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
				$nodeArray = $this->getErrArray("false", count($results), $comments->getNbResults());
				foreach($results as $key => $comment){
					$wikiRepository = $mongo->getRepository("wiki");
					$wiki = $wikiRepository->getWikiById($comment->getWikiId());
					$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES]['id'] = $wiki->getId();
					$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES]['title'] = $wiki->getTitle();
					$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES]['model'] = $wiki->getModel();
					$nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray,'',0,false);
				}
			}else
			{
				$nodeArray = $this->getErrArray("false", 0, 0);
			}
			return $nodeArray;		    			
		}
		else
		{
	        $nodeArray = $this->getErrArray("true", null, null,'没有该用户');
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
				$nodeArray = $this->getErrArray("false", count($results), $comments->getNbResults());
				foreach($results as $key => $comment){
					$wikiRepository = $mongo->getRepository("wiki");
					$wiki = $wikiRepository->getWikiById($comment->getWikiId());
					$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES]['id'] = $wiki->getId();
					$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES]['title'] = $wiki->getTitle();
					$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES]['model'] = $wiki->getModel();
					$nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray, '', 0, false);
				}
			}else
			{
				$nodeArray = $this->getErrArray("false", 0, 0);
			}
			return $nodeArray;		    			
		}
		else
		{
	        $nodeArray = $this->getErrArray("true", null, null,'没有该用户');
			return $nodeArray;	            			
		} 		        
    }    
    /*
     * 提交用户影视操作
     * @param array $args
     * @return array　$nodeArray
     * @author guoqiang.zhang
     */
    public function ReportUserMediaAction($args){
        $data = $args['parameter'][0]['data'][0]['__attributes__'];
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
        if(!$dnum){
            //接口一律用huanid来识别用户
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
             return $this->arrayToDom($nodeArray);
        }
        if(!in_array($data['type'], array("1",'2','3','4','5'))){
            //接口方法是否正确
            $nodeArray = $this->getErrArray("true",null,null,'type处出错');
             return $this->arrayToDom($nodeArray);
        }
        if(!$data['mid']){
            //wiki ID
            $nodeArray = $this->getErrArray("true",null,null,'mid处出错');
             return $this->arrayToDom($nodeArray); 
        }
        switch($data['type']){
            case '1':
                $nodeArray = $this->wikiScore($data,$dnum,$huanid);
                break;
            case '2':
                $nodeArray = $this->AddChipByDevice($dnum,$huanid, $data);
                break;
            case '3':
                $nodeArray = $this->deleteChipByDevice($dnum,$huanid, $data);
                break;
            case '4':
                $nodeArray = $this->addHaveSeen($dnum,$huanid, $data);
                break;
            case '5':
                $nodeArray = $this->delall($dnum,$huanid, $data);
                break;
            
        }
        return $this->arrayToDom($nodeArray);
    }
    

    /*
     * 影视wiki踩和顶操作
     * @param array $data
     * @param array $user
     * @return array $nodeArray
     * @author wangnan
     */
    public function wikiScore($data,$dnum,$huanid){
		$nodeArray = $this->getErrArray("false",null,null,'');
        $mongo = sfContext::getInstance()->getMondongo();
		$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);

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
			$nodeArray = $this->getErrArray("true",null,null,'该wiki不存在');
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
    public function AddChipByDevice($dnum,$huanid,$data){
		$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);
		
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $chip = $userRepository->addChipByDevice((string)$hasUser->getId(),$data['mid']);
        
		$nodeArray = $this->getErrArray("false",null,null,'');            
        $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
                'favorite'  =>  '1'
            );
        return $nodeArray;
    }

    /*
     *通过wiki_id,device_id 删除片单中wiki
     * @param array $user
     * @param array $data
     * @return int
     * @author wangnan
     */
    public function deleteChipByDevice($dnum,$huanid,$data){
		$user = $this->WhetherIssetUserForGet($dnum,$huanid);
		
        $mongo = sfContext::getInstance()->getMondongo();
        $singleChipRepository = $mongo->getRepository('singleChip');
        $singleChip = $singleChipRepository->getOneChip((string)$user->getId(),$data['mid']);
        if($singleChip){
            $singleChip->delete();
			$commentRepository = $mongo->getRepository('Comment');
			$comment = $commentRepository->getOneComment((string)$user->getId(), $data['mid'], 'queue');
			if ($comment) $comment->delete();            
            $nodeArray = $this->getErrArray("false",null,null,'');
            $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
                    'favorite'  =>  '0'
                );
        } else {
			$nodeArray = $this->getErrArray("false",null,null,'');                
            $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
                    'favorite'  =>  '0'
                );
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
    public function addHaveSeen($dnum,$huanid,$data){
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki'); 
        $wiki = $wikiRepository->findOneById(new MongoId($data['mid']));
        if($wiki)
        {
			$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);
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
			$nodeArray = $this->getErrArray("false",null,null,'');
	        $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
	                'favorite'  =>  '4'
	            );
	        return $nodeArray;

        }
        else
        {
			$nodeArray = $this->getErrArray("true",null,null,'该wiki不存在');
	        return $nodeArray;        	
        }
    }
    /*
     *通过wiki_id,device_id 删除片单中 所有 wiki
     * @param array $user
     * @param array $data
     * @return int
     * @author wangnan
     */
    public function delall($dnum,$huanid,$data){
        $mongo = sfContext::getInstance()->getMondongo();
        $singleChipRepository = $mongo->getRepository('singleChip');
		$user = $this->WhetherIssetUserForGet($dnum,$huanid);
        if(!$user)
        {
           return $nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
        }
        $singleChips = $singleChipRepository->getUserChipByUserId((string)$user->getId());
        if($singleChips)
        {
        	foreach($singleChips as $singleChip)
        	{
        		$wiki_id = $singleChip->getWikiId();
	            $singleChip->delete();
				$commentRepository = $mongo->getRepository('Comment');
				$comment = $commentRepository->getOneComment((string)$user->getId(), $wiki_id, 'queue');
				if ($comment) $comment->delete();            
        	}
            $nodeArray = $this->getErrArray("false",null,null,'');
            $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
                    'favorite'  =>  '0'
                );        		
        } else {
			$nodeArray = $this->getErrArray("false",null,null,'');                
            $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
                    'favorite'  =>  '0'
                );
        }
       return $nodeArray;
    }
	/*
     * 获取电视频道列表
     * @param array
     * @return xml
     * @author wangnan
     */
    public function GetChannelList($args)
    {
    	$mongo = sfContext::getInstance()->getMondongo();
    	$programlive_repository  = $mongo->getRepository('ProgramLive');
    	$nodeArray = array();
    	$province = $args['parameter'][0]['data'][0]['__attributes__']['province'];
    	$type = $args['parameter'][0]['data'][0]['__attributes__']['type'];
    	$arr = array('cctv','tv','edu','local','all');
    	$type =  in_array($type,$arr)?$type:'all';
		$page      = max(1,intval($args['parameter'][0]['data'][0]['__attributes__']['page']));        
    	$pagesize  = intval($args['parameter'][0]['data'][0]['__attributes__']['pagesize']) ? intval($args['parameter'][0]['data'][0]['__attributes__']['pagesize']) : 100;
		$nodeArray = $this->getErrArray("false",0);
		$sort = $args['parameter'][0]['data'][0]['__attributes__']['sort'];
        $offset = ($page -1)*$pagesize;
    	switch($type)
    	{
    		case 'cctv':
    			$channels = Doctrine::getTable('Channel')->getYangShiChannels($offset,$pagesize);
    			break;
    		case 'tv':
    			$channels = Doctrine::getTable('Channel')->getWeiShiChannels($offset,$pagesize);
    			break;
    		case 'edu':
    			$channels = Doctrine::getTable('Channel')->getEduChannels($offset,$pagesize);
    			break;
    		case 'local':
    			if(!empty($province))
    				$channels = Doctrine::getTable('Channel')->getLocalChannels('',$province,$offset,$pagesize);
				else
    				return $this->arrayToDom($nodeArray);
    			break;
    		case 'all':
    			if(!empty($province))
	    			$channels = Doctrine::getTable('Channel')->getUserChannels('',$province,'',$offset,$pagesize);
    			else
    				$channels = Doctrine::getTable('Channel')->getAllChannelByTv('',$offset,$pagesize);
    			break;
    	}
        
    	
    	if($sort=='2'){
    		
          $channels = $this->sortChannelByUser($channels,$userid);
         
    	}  
		$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
			'language'  =>  'zh-CN',
			'total'       =>  count($channels),
		);
		foreach($channels  as $key =>$channel)
		{
			$nodeArray['data'][0]['channel'][$key][DOM::ATTRIBUTES] = array(
				'id'   => $channel['id'],
                'name' => $channel['name'],
				'code' => $channel['code'],
				'memo' => $channel['memo'],
				'type' => $channel['type'],
				'logo' => $channel->getLogoUrl(),
				'hot'  => $channel['hot'],
				'like_num'  => $channel['like_num'],
				'dislike_num'  => $channel['dislike_num'],
				'recommend'  => $channel['recommend'],
					
					
			);
            //
			$liveprogram = $programlive_repository->getProgramByCode($channel['code']);
			if($liveprogram)
			{
				$nodeArray['data'][0]['channel'][$key]['program'][0][DOM::ATTRIBUTES] = array(
					'name' => $liveprogram['name'],
					'start_time' => date("H:i",$liveprogram['start_time']->getTimestamp()),
					'end_time' => $liveprogram['end_time']?date("H:i",$liveprogram['end_time']->getTimestamp()):'',
					'wiki_id' => $liveprogram['wiki_id'],
					'wiki_title' => $liveprogram['wiki_title'],
					'wiki_cover' => file_url($liveprogram['wiki_cover']),
					'next_name'=>$liveprogram->getNextName(),
				);
			}
		}
		return $this->arrayToDom($nodeArray);
    }
	/*
     * 获取电视频道的节目列表
     * @param array
     * @return xml
     * @author wangnan
     * @editor lifucang(输入参数名称更改channel_code,start_time,end_time)
     */
    public function GetProgramListByChannel($args)
    {
    	$nodeArray = array();
    	$channelcode = $args['parameter'][0]['data'][0]['__attributes__']['channel_code'];
    	$starttime = $args['parameter'][0]['data'][0]['__attributes__']['start_time'];
    	$endtime = $args['parameter'][0]['data'][0]['__attributes__']['end_time'];
    	$mongo = sfContext::getInstance()->getMondongo();
    	$ProgramRepository = $mongo->getRepository('Program');
    	$programs = $ProgramRepository->getProgramsByCode($channelcode,$starttime,$endtime);
    	
        $channel = Doctrine_Core::getTable("channel")->findOneByCode($channelcode);
        if($channel)
        {
			$nodeArray = $this->getErrArray("false",0);
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'language'  =>  'zh-CN',
				'total'       => count($programs),
	        );
	        
	        $nodeArray['data'][0]['channel'][0][DOM::ATTRIBUTES] = array(
	        	'name'=>$channel['name'],
	        	'code'=>$channel['code'],
	        	'logourl'=>$channel->getLogoUrl(),
	        	'hot'=>$channel['hot'],
	        	'like_num'  => $channel['like_num'],
	        	'dislike_num'  => $channel['dislike_num'],
	        
	        );
	        foreach($programs as $key =>$program)
	        {
	            $wiki_info = $program->getWiki();
	            $hasVideo = ($wiki_info['has_video']>0)?'yes':'no';
	            $source = implode(',',$wiki_info['source']);
				$nodeArray['data'][0]['channel'][0]['program'][$key][DOM::ATTRIBUTES] = array(
					'name' => $program['name'],
					'date' => $program['date'],
					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
					'wiki_id' => $program['wiki_id'],
					'model' =>$wiki_info['model'],
					'wiki_cover' => file_url($wiki_info['cover']),
					'tags' => !$wiki_info['tags'] ? '' : $this->getTag($wiki_info['tags'],array($this->category[1]['name'],$this->category[2]['name'])),
					'hasvideo'=>$hasVideo,
					'source'=>$source,
	                   );
			}        	
        }
		else
		{
			$nodeArray = $this->getErrArray("true",null,null,'该频道不存在');
		}
		return $this->arrayToDom($nodeArray);
    }
	/*
     * 获取电视频道的推荐列表
     * @param array
     * @return xml
     * @author wangnan
     * @editor lifucang (输入参数名称更改，channel_code)
     */
	public function GetRecommendByChannel($args)
	{
		$nodeArray = array();
    	$channelcode = $args['parameter'][0]['data'][0]['__attributes__']['channel_code'];
    	if($this->isVarSet($channelcode,'参数channelcode缺失')==false) return;
    	$channel_recommends = Doctrine::getTable('ChannelRecommend')->createQuery('c')->where("channel_code = ?",$channelcode)->orderBy('sort')->execute();
    	if(count($channel_recommends)==0)
    	{
    		$nodeArray = $this->getErrArray("true",null,null,$channelcode.'无推荐信息');
    		return $this->arrayToDom($nodeArray);    		
    	}
    	$nodeArray = $this->getErrArray("false");
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$i = 0;
        foreach($channel_recommends as $key =>$channel_recommend)
        {
            $wiki = $WikiRepository->findOneById(new MongoId($channel_recommend['wiki_id']));
            if($wiki)
            {
				$nodeArray['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
                            	'id'       => $wiki->getId(),
                            	'title'    => $wiki->getTitle(),
								'model'    =>$wiki->getModel(),
								'playtime' => $channel_recommend->getPlaytime(),
								'remark'   => $channel_recommend->getRemark(),
								'img'      => file_url($channel_recommend->getPic()),
                    		);
				$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray,'',0,false);
				$i++;
            }
        }
		$nodeArray['data'][0][DOM::ATTRIBUTES]['total'] = $i;        
		return $this->arrayToDom($nodeArray);
	}    
	/*
     * 按照分类的直播列表
     * @param array
     * @return xml
     * @author wangnan
     */    
	public function GetLivePrograme($args)
	{
		$nodeArray = array();
    	$province  = $args['parameter'][0]['data'][0]['__attributes__']['province'];
    	$tag       = $args['parameter'][0]['data'][0]['__attributes__']['tag'];
    	$starttime = $args['parameter'][0]['data'][0]['__attributes__']['start_time'];
    	$endtime   = $args['parameter'][0]['data'][0]['__attributes__']['end_time'];
    	$type      = $args['parameter'][0]['data'][0]['__attributes__']['type']?$args['parameter'][0]['data'][0]['__attributes__']['type']:'1';        
    	$page      = max(1,intval($args['parameter'][0]['data'][0]['__attributes__']['page']));        
    	$pagesize  = intval($args['parameter'][0]['data'][0]['__attributes__']['pagesize']) ? intval($args['parameter'][0]['data'][0]['__attributes__']['pagesize']) : 100;
    	$sort = $args['parameter'][0]['data'][0]['__attributes__']['sort'];
		if($tag)
        	$tags = explode('+',$tag); 
        	
    	if($type != '2'){
	    	if($starttime==''&&$endtime=='')$starttime = $endtime =date("Y-m-d H:i:s",time());
    	}
    	
    	$channels = Doctrine::getTable('Channel')->getUserChannels('',$province);
    	
    	if($sort=='2'){
    		$channels = $this->sortChannelByUser($channels,$userid);
    	}
    	
		$i = 0;
		$nodeArray = $this->getErrArray("false",0);
		
		$mongo = sfContext::getInstance()->getMondongo();
		$ProgramRepository = $mongo->getRepository('Program');
		$WikiRepository    = $mongo->getRepository('Wiki');
		
		$next = $ProgramRepository->getNextUpdate($channels,$endtime,$tags);
		if($type != '2')
			$programs = $ProgramRepository->getPrograms($channels,$tags,$starttime,$endtime,$page,$pagesize);
		else
			$programs = $ProgramRepository->getStartPrograms($channels,$tags,$starttime,$endtime,$page,$pagesize);
		
		unset($channels);
		foreach($programs as $program_key =>$program)
		{
			$wiki = $WikiRepository->getWikiById($program['wiki_id']);
			if($wiki) 
			{
				$channel = $program->getChannel();
				$nodeArray['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
					'id'    => $wiki->getId(),
					'title'   => $wiki->getTitle(),
					'model' =>$wiki->getModel(),
					'start_time' => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
					'end_time' => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
					'channel_code' => $channel->getCode(),
					'channel_name' => $channel->getName(),
					'channel_logourl' => $channel->getLogoUrl(),
					'channel_memo' => $channel->getMemo(),
                    'channel_hot' => $channel->getHot(),
					'likenum' => $channel->getLikeNum(),
					'dislikenum' => $channel->getDislikeNum(),
				);
				$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray, '', 0, false);
				$i = $i+ 1;
			}
		}
		$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
			'language'  => 'zh-CN',
			'total'     => $i,
		);
		if($next)
			$nodeArray['data'][0][DOM::ATTRIBUTES]['nextupdate'] = date("Y-m-d H:i:s",$next->getStartTime()->getTimestamp());
		else
			$nodeArray['data'][0][DOM::ATTRIBUTES]['nextupdate'] ='';
			
		return $this->arrayToDom($nodeArray);
	}                
	/*
     * 按照wiki_id获取wiki详细信息
     * @param array
     * @return xml
     * @author wangnan 
     * @editor lifucang(输出参数名称更改,如channelcode变为channel_code)
     */
	public function GetWikiInfo($args)
	{
		$nodeArray = array();
    	$wiki_id = $args['parameter'][0]['data'][0]['__attributes__']['wiki_id'];
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];    	
    	if($this->isVarSet($wiki_id,'请填写wiki_id')==false) return;  
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
		if($wiki) 
		{
			$nodeArray = $this->getErrArray("false",0);
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'language'  =>  'zh-CN',
				'total'       => 1,
	        );
			$nodeArray['data'][0]['media'][0][DOM::ATTRIBUTES] = array(
                            'id'    => $wiki->getId(),
                            'title'   => $wiki->getTitle(),
                            'model'   => $wiki->getModel(),
                    );
            $nodeArray = $this->getWikiVideoSource($wiki, 0, $nodeArray);
			$hasUser = $this->WhetherIssetUserForGet($dnum,$huanid);
        	if($hasUser)
        	{        
        		$user_id = $hasUser->getId();    
				$chipRepository = $mongo->getRepository('singlechip');
				$chip = $chipRepository->getOneChip((string)$user_id,$wiki_id);
				if($chip)
				{
		            $nodeArray['data'][0]['media'][0]['action'][0][DOM::ATTRIBUTES]= array(
		                            'type' => 'favorite',
		            				'var'  => '1',
		            				'datetime' => date("Y-m-d H:i:s",$chip->getCreatedAt()->getTimestamp()),
		                    );
				}
				else
		            $nodeArray['data'][0]['media'][0]['action'][0][DOM::ATTRIBUTES]= array(
		                            'type' => '',
		            				'var'  => '',
		            				'datetime' => '',
		            	);				
        	}
        	else  
	            $nodeArray['data'][0]['media'][0]['action'][0][DOM::ATTRIBUTES]= array(
	                            'type' => '',
	            				'var'  => '',
	            				'datetime' => '',
	                    );        	         
		    $screen_num = $wiki->getScreenshotsCount();        
            $nodeArray['data'][0]['media'][0]['screens'][0][DOM::ATTRIBUTES]= array(
                            'num'    => $screen_num,
                    );
            $screens = $wiki->getScreenshotUrls();   
            foreach($screens as $k => $screen)
            {
	            $nodeArray['data'][0]['media'][0]['screens'][0]['screen'][$k][DOM::ATTRIBUTES]= array(
	                            'url'    =>  $screens[$k],
	                    ); 
            }    
            //获取节目信息lfc
            $program_repository = $mongo->getRepository('Program');
            $programs = $program_repository->getdayUnPlayedProgramByWikiId($wiki_id);   
            $programs_num=count($programs);
            $nodeArray['data'][0]['media'][0]['programs'][0][DOM::ATTRIBUTES]= array(
                            'num'    => $programs_num,
                    );
            foreach($programs as $k => $program)
            {
				$endTime = $program->getEndTime();
	            $nodeArray['data'][0]['media'][0]['programs'][0]['program'][$k][DOM::ATTRIBUTES]= array(
	                            'channel_code'    =>  $program->getChannelCode(),
                                'channel_logo'    =>  $program->getChannel()->getLogoUrl(),
                                'channel_name'    =>  $program->getChannelName(),
                                'program_name'    =>  $program->getName(),
                                'start_time'      =>  date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
                                'end_time'        =>  !empty($endTime)?date("Y-m-d H:i:s",$endTime->getTimestamp()):'',
                        ); 
            }      
               
		}
		else 
		{
			$nodeArray = $this->getErrArray("false", 0,0,'未找到数据');
		}
		return $this->arrayToDom($nodeArray); 
	}
	/*
     * 根据ID获取专题的详细信息
     * @param array
     * @return xml
     * @author wangnan
     */
	public function GetThemeById($args)
	{
		$nodeArray = array();
    	$tid = $args['parameter'][0]['data'][0]['__attributes__']['tid'];
    	if($this->isVarSet($tid,'请填写tid')==false) return;
    	$theme = '';
    	$theme = Doctrine::getTable('Theme')->findOneById($tid);
    	if(!$theme)
    	{
    		$nodeArray = $this->getErrArray("true",null,null,'主题不存在');
    		return $this->arrayToDom($nodeArray); 
    	}
    	if($theme->getPublish()==0)
    	{
    		$nodeArray = $this->getErrArray("true",null,null,'该主题未发布');
    		return $this->arrayToDom($nodeArray); 
    	}
    	$items = Doctrine::getTable('ThemeItem')->getItemsByThemeId($tid);
		$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$nodeArray = $this->getErrArray("false",0);
		$total = count($items);
		$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
			'language'  =>  'zh-CN',
			'title'  => $theme->getTitle(),
			'remark' => $theme->getRemark(),
			'img'    => file_url($theme->getImg()),		
			'total'       => $total,
        );
		foreach($items as $key=>$item)
		{   	
			$wiki = $WikiRepository->findOneById(new MongoId($item->getWikiId()));
			$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
                            'id'    => $wiki->getId(),
                            'title'   => $wiki->getTitle(),
							'model' =>$wiki->getModel(),
							'remark' => $item->getRemark(),
							'img' => file_url($item->getImg()),
                    );
            $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray, '', 0, false);
            $screen_num = $wiki->getScreenshotsCount();        
            $nodeArray['data'][0]['media'][$key]['screens'][0][DOM::ATTRIBUTES]= array(
                            'num'    => $screen_num,
                    );
            $screens = $wiki->getScreenshotUrls();   
            foreach($screens as $k => $screen)
            {
	            $nodeArray['data'][0]['media'][$key]['screens'][0]['screen'][$k][DOM::ATTRIBUTES]= array(
	                            'url'    =>  $screens[$k],
	                    ); 
            }
		}
		return $this->arrayToDom($nodeArray); 
	}
	/*
     * 获取专题列表
     * @param array
     * @return xml
     * @author wangnan
     */
	public function GetThemeList($args)
	{
		$nodeArray = array();
    	$page = $args['parameter'][0]['data'][0]['__attributes__']['page'];
    	$size = $args['parameter'][0]['data'][0]['__attributes__']['size'];
    	$scene = $args['parameter'][0]['data'][0]['__attributes__']['scene'];
    	$page=empty($page)?1:$page;
    	$size=empty($size)?8:$size;
    	$scene = empty($scene)?'tcl':$scene;
    	$countThemes = Doctrine::getTable('Theme')->getThemeByPageAndSizeFrontend($page,$size,$scene);
    	$totalThemes = Doctrine::getTable('Theme')->getThemes();
		$nodeArray = $this->getErrArray("false",0);
		$count = count($countThemes);
		$total = count($totalThemes);
		$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
			'language' =>  'zh-CN',
			'num'    => $count,
			'total'    => $total,
        );
		foreach($countThemes as $key=>$theme)
		{   	
			$nodeArray['data'][0]['theme'][$key][DOM::ATTRIBUTES] = array(
                            'id'    => $theme->getId(),
                            'title'    => $theme->getTitle(),
                            'remark'    => $theme->getRemark(),
                            'img'   => file_url($theme->getImg()),
                    );
		}       
		return $this->arrayToDom($nodeArray); 
	}

	/*
     * 提交用户分集操作
     * @param array
     * @return xml
     * @author wangnan 
     */
	public function ReportUserEpisodeAction($args)
	{
		$nodeArray = array();
    	$type    = $args['parameter'][0]['data'][0]['__attributes__']['type'];
    	$mid     = $args['parameter'][0]['data'][0]['__attributes__']['mid'];
    	$eid     = $args['parameter'][0]['data'][0]['__attributes__']['eid'];
    	$flag    = $args['parameter'][0]['data'][0]['__attributes__']['marktime'];
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
		

        
        if($this->isVarSet($dnum,'dnum不为空')==false) return; 
        if($this->isVarSet($mid,'mid不为空')==false) return;
        if($type==2)
        	if($this->isVarSet($eid,'eid不为空')==false) return;
        
        $mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$videoRepository = $mongo->getRepository('Video');
		
		$wiki = $WikiRepository->findOneById(new MongoId($mid));
		if($this->isVarSet($wiki,'该wiki不存在')==false) return;
		
		if($type==2)
		{
			$video = $videoRepository->findOneById(new MongoId($eid));
			if($this->isVarSet($video,'该video不存在')==false) return ; 
		}      
		$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);

		$user_id =  (string)$hasUser->getId();
        switch($type){
            case '1':
                $nodeArray = $this->seenVideo((string)$mid,$user_id);
                break;
            case '2':
                $nodeArray = $this->markVideo((string)$mid,(string)$eid,$flag,$user_id);
                break;
            case '3':
                $nodeArray = $this->markVideo((string)$mid,(string)$eid,$flag,$user_id);
                break;
            default:
            	$nodeArray = $this->getErrArray("true",null,null,'type处出错');
        }
               	
        return $this->arrayToDom($nodeArray);
	}
	
	/*
     * 看过分集操作
     * @param string $mid wiki_id
     * @param string $user_id 用户id
     * @return xml
     * @author wangnan
     */
	public function seenVideo($mid,$user_id)
	{	
		$nodeArray = array();
		$mongo = sfContext::getInstance()->getMondongo();
		$urRepository = $mongo->getRepository('UserMark');
		$marks = $urRepository->findOne(array('query'=>array('user_id'=>$user_id,'wiki_id'=>$mid,'type'=>'1')));
		if($marks)
		{
            $nodeArray = $this->getErrArray("false",null,null,'');
            return $nodeArray;
		}
		else
		{
			$user_mark = new UserMark();
			$user_mark->setUserId($user_id);
			$user_mark->setType('1');
			$user_mark->setWikiId($mid);
			$user_mark->save();
            $nodeArray = $this->getErrArray("false",null,null,'');
		
			$data = array("userId" => $user_id,
			  "time" => time(),
			  "action" => "ok",
			  "contentId" => $mid,
			  "videoId" => "");
			Common::postUserActionToLCT($data);

            return $nodeArray;
		}				
	}
	/*
     * 标记分集操作
     * @param string $mid wiki_id
     * @param string $eid video_id
     * @return xml
     * @author wangnan
     */
	public function markVideo($mid,$eid,$flag,$user_id)
	{	
		$nodeArray = array();
		$mongo = sfContext::getInstance()->getMondongo();
		$urRepository = $mongo->getRepository('UserMark');
		$marks = $urRepository->getUserMarkByObyId($eid,$user_id);
		$has_yet = false;
		if($marks)
		{
			foreach($marks as $mark)
			{
				if($mark->getExtra() == $flag)$has_yet = true;
			}
			if($has_yet){}
			else
			{			
				$mark->setExtra($flag);
				$mark->save();
			}
            $nodeArray = $this->getErrArray("false",null,null,'');
            return $nodeArray;
		}
		else
		{
			$urRepository -> createOneMark($user_id,2,$mid,$eid,$flag);
			$nodeArray = $this->getErrArray("false",null,null,'');
            return $nodeArray;
		}		
	}
		
	/*
     * 获取用户分集列表
     * @param array
     * @return xml
     * @author wangnan 
     * @editor lfc
     */
	public function GetEpisodeListByUser($args)
	{
		$nodeArray = array();
    	$type    = $args['parameter'][0]['data'][0]['__attributes__']['type'];
    	$page    = $args['parameter'][0]['data'][0]['__attributes__']['page'];
    	$size    = $args['parameter'][0]['data'][0]['__attributes__']['size'];
    	$dnum    = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];

		if($this->isVarSet($dnum,'请填写dnum')==false) return; 
		        
        $mongo = sfContext::getInstance()->getMondongo();
		$hasUser = $this->WhetherIssetUserForGet($dnum,$huanid);
        if($hasUser)
        {
			$user_id =  (string)$hasUser->getId();
	    	$page = empty($page)?1:$page;
	    	$size = empty($size)?8:$size;
			$urRepository = $mongo->getRepository('UserMark');
			$mks = $urRepository->getUserMarksByUserIdAndType($user_id,$type);
			$nodeArray = $this->getErrArray("false",0);
			$marks = $urRepository->getUserMarksByUserIdTypePageSize($user_id,$type,$page,$size);
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'language' => 'zh-CN',
				'num'    => count($marks),
				'total'    => count($mks),
	        );
        
			foreach($marks as $key => $mark)
			{
				$wikiRepository = $mongo->getRepository('Wiki');
				$wiki = $wikiRepository->findOneById(new MongoId($mark->getWikiId()));
				$wikiId = empty($wiki)?'':$wiki->getId();
				$wikiSlug = empty($wiki)?'':$wiki->getTitle();
				$nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
	                            'id'      => $wikiId,
	                            'title'   => $wikiSlug,
								'model'   => $wiki->getModel(),
                                'markid'  => (string)$mark->getId(),  //lfc
                                'marktime'=> $mark->getExtra()
	                    );	
	            if(!empty($wiki))	
					$nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray,array('eid'=>$mark->getObjId(),'marktime'=>$mark->getExtra(),'markid'=>$mark->getId(),'type'=>$type),1); //lfc
			}
        }
        else
	    {
	        $nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
        }                   	
        return $this->arrayToDom($nodeArray);
	}
	/*
     * 删除用户分集操作
     * @param array
     * @return xml
     * @author wangnan 
     */
	public function DeleteUserEpisodeAction($args)
	{
		$nodeArray = array();
    	$markid    = $args['parameter'][0]['data'][0]['__attributes__']['markid'];
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
        if($this->isVarSet($markid,'markid不为空')==false) return;
        
        $mongo = sfContext::getInstance()->getMondongo();
		$umRepository = $mongo->getRepository('UserMark');
        
		$hasUser = $this->WhetherIssetUserForGet($dnum,$huanid);
        if($hasUser)
        {
			$user_id =  (string)$hasUser->getId();
			$mark = $umRepository->findOneById(new MongoId($markid));
			if($mark) $mark->delete();
            $nodeArray = $this->getErrArray("false",null,null,'');
        }
		else
        {
			$nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
        }               	
        return $this->arrayToDom($nodeArray);
	}    


	/*
     * 获取运营商
     * @param array
     * @return xml
     * @author lfc
     */
	public function GetSpList($args)
	{
		$nodeArray = array();
    	$num    = $args['parameter'][0]['data'][0]['__attributes__']['num'];
            
        $mongo = sfContext::getInstance()->getMondongo();
        $spRepository = $mongo->getRepository('sp');
        

        if($num!=''){
            $query=array('limit'=>array('offset'=>0,'rows'=>$num));
            $sp=$spRepository->find($query);
        } else {
            $sp=$spRepository->find();
        }
        if($sp)
        {
                       
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'language' => 'zh-CN',
				'num'    => count($sp),
				'total'    => count($sp)
	        );
			foreach($sp as $key => $value)
			{
  
				$nodeArray['data'][0]['info'][$key][DOM::ATTRIBUTES] = array(
	                            'signal'    => $value->getSignal(),
	                            'name'      => $value->getName(),
	                            'remark'    => $value->getRemark(),
	                            'logo'      => $value->getLogo()           
	            );
			}
        } else {    
			$nodeArray = $this->getErrArray("true",null,null,'暂无运营商信息');
        }        	
        return $this->arrayToDom($nodeArray);
	}

    /*
     * 获取运营商关联wiki列表
     * @param  $args(signal,page,size)
     * @retrun xml
     * @author lfc
     */

    public  function GetSpMediaListByCatelog($args){
        $nodeArray = array();
        //获取提交过来的参数
        //var_dump($args['parameter']['data']['@attributes']);
        if(!$args['parameter'][0]['data'][0]['__attributes__']){
             $nodeArray = $this->getErrArray("true", null,null,'请正确填写节点data内参数');
        } else {
            $signal = $args['parameter'][0]['data'][0]['__attributes__']['signal'];
            $page = $args['parameter'][0]['data'][0]['__attributes__']['page']?$args['parameter'][0]['data'][0]['__attributes__']['page']:1;
            $size = $args['parameter'][0]['data'][0]['__attributes__']['size']?$args['parameter'][0]['data'][0]['__attributes__']['size']:8;
            
            $wikis = new XapianPager('Wiki', $size);
            $wikis ->setSearchText('source:'.$signal);
            $wikis ->setPage($page);
            $wikis ->init();
            
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'language' => 'zh-CN',
				'page'     => $page,
                'size'     => $size,
				'total'    => count($wikis),
	        );          
              
			foreach($wikis as $key => $wiki)
			{
                $nodeArray['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
                        'id'    => $wiki->getId(),
                        'title'   => $wiki->getTitle(),
                		'model' =>$wiki->getModel()
                );
            }			            
        }
        return $this->arrayToDom($nodeArray);
    }
     
    /*
     * 获取直播的分类标签 
     * @retrun xml
     * @author lfc
     */

    public  function GetLiveCategory()
    {
		$nodeArray = array();
		$tags = array('电视剧','电影','体育','娱乐','少儿','科教','财经','综合');
		$num = count($tags);
        $nodeArray = $this->getErrArray("false", $num);
        foreach($tags as $key=>$tag)
        {
	        $nodeArray['data'][0]['class'][$key][DOM::ATTRIBUTES]=array(
	        	'title'=>$tag
	        );
        }
        return $this->arrayToDom($nodeArray);
    } 

    /**
     * 搜索建议 用到了XS
     * @param string $args 搜索关键字
     * @author wangnan 
     * @editor lifucang
     * @return xml
     */
    public function SearchSuggest(array $args) {
        $keyWord = $args['parameter'][0]['data'][0]['__attributes__']['keyword'];
        $mongo = sfContext::getInstance()->getMondongo();
        if(empty($keyWord)){
            $setting = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => 'hotsearchkey' ));
            $rs = $setting->findOne($query);
            $arr = array();
            if($rs){
                $arr_value=json_decode($rs->getValue());  //数组
                $total = count($arr_value);
                $arr = $this->getErrArray("false",$total);
                $i = 0;
                if($total<5){
                    foreach($arr_value as $value) 
                    {
                        //if($i>=5)
                        //    break;
                        $arr['data'][0]['tag'][$i][DOM::ATTRIBUTES]['value'] = $value;
                        $i++;
                    }   
                } else {
                    $arr_use=array_rand($arr_value,5);
                    foreach($arr_use as $value) 
                    {
                        //if($i>=5)
                        //    break;
                        $arr['data'][0]['tag'][$i][DOM::ATTRIBUTES]['value'] = $arr_value[$value];
                        $i++;
                    }    
                }
            } else {
                $arr = $this->getErrArray("false", null,null);
            }
        } else {
            $wikiRep = $mongo->getRepository('wiki');
            $result = $wikiRep->xun_search("title:".$keyWord, $total, 0, 9999,null,1,$keyWord);
            $total = count($result);
            $arr = array();
            if(!$result)
            {
                $arr = $this->getErrArray("false", null,null);
            }
            else
            {
                $arr = $this->getErrArray("false",$total);
                $i = 0;
                foreach($result as $res) 
                {
                    $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['id'] = $res->getId();
                    $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['title'] = $res->getTitle();
                    $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['extra'] = $res->getModel();
                    $i++;
                }
            }  
        }
        return $this->arrayToDom($arr);
    } 
	/*
     * 根据SP获取频道列表
     * @param string $args
     * @return xml
     * @author wangnan
     */
	public function GetChannelListBySP($args)
	{
		$nodeArray = array();
    	$spname    = $args['parameter'][0]['data'][0]['__attributes__']['spname'];
            
        $mongo = sfContext::getInstance()->getMondongo();
        $spRepository = $mongo->getRepository('sp');
        
		$sp = $spRepository->getOneSpByName($spname);
        if($sp) {
        	$channels = $sp->getChannelObjs();
        	$nodeArray = $this->getErrArray("false",null,count($channels));
        	
			foreach($channels as $key => $channel){  
				$nodeArray['data'][0]['channel'][$key][DOM::ATTRIBUTES] = array(
	                'id'    => $channel->getId(),
	                'name'  => $channel->getName(),
	                'memo'  => $channel->getMemo(),
	                'code'  => $channel->getCode(),
	                'type'  => $channel->getType(),
	                'logo'  => $channel->getLogoUrl() ,         
	            );
			}
        } else {    
			$nodeArray = $this->getErrArray("true",null,null,'暂无该运营商信息');
        }        	
        return $this->arrayToDom($nodeArray);
	}        
	/*
     * 根据地区搜索节目列表
     * @param string $args
     * @return xml
     * @author wangnan
     * @editor lifucang  2012-5-28
     */
	public function SearchProgram($args)
	{
		$nodeArray = array();
    	$province = $args['parameter'][0]['data'][0]['__attributes__']['province'];
    	$key = trim($args['parameter'][0]['data'][0]['__attributes__']['key']);
    	if($this->isVarSet($key,'请填写key')==false) return;
    	$starttime = $args['parameter'][0]['data'][0]['__attributes__']['starttime'];
    	$endtime = $args['parameter'][0]['data'][0]['__attributes__']['endtime'];
    	
	    if(empty($province))
    		$channels = Doctrine::getTable('Channel')->getWeiShiChannels();
    	else
    		$channels = Doctrine::getTable('Channel')->getUserChannels('',$province);
		$i = 0;
		$nodeArray = $this->getErrArray("false",0);
		
		$mongo = sfContext::getInstance()->getMondongo();
		$ProgramRepository = $mongo->getRepository('Program');
		$WikiRepository = $mongo->getRepository('Wiki');
		
		$programs = $ProgramRepository->getProgramsByCKSE($channels,$key,$starttime,$endtime);
		unset($channels);
		foreach($programs as $program_key =>$program)
		{
			$wiki = $WikiRepository->getWikiById($program['wiki_id']);
			if($wiki) 
			{
				$channel = $program->getChannel();
				$nodeArray['data'][0]['program'][$i][DOM::ATTRIBUTES] = array(
                    'channel_id'   => $channel->getId(),
                    'channel_code' => $channel->getCode(),
                    'channel_name' => $channel->getName(),
                    'channel_logo' => $channel->getLogoUrl(),
                    'channel_hot'  => $channel->getHot(),
					'likenum'      => $channel->getLikeNum(),
					'dislikenum'   => $channel->getDislikeNum(),
					'name'         => $program->getName(),
					'date'         => $program->getDate(),
					'start_time'   => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
					'end_time'     => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
					'wiki_id'      => (string)$wiki->getId(),
					'wiki_cover'   => file_url($wiki->getCover()),
					'tags'         => !$wiki->getTags() ? '' : $this->getTag($wiki->getTags(),array($this->category[1]['name'],$this->category[2]['name'])),
					'hasvideo'     => $wiki->getHasVideo()>0?'yes':'no',
					'source'       => implode(',',$wiki->getSource()),
				);
				$i = $i+ 1;
			}
		}
		$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
			'language'  => 'zh-CN',
			'total'     => $i,
		);
		return $this->arrayToDom($nodeArray);
	}
    
	/*
     * 按照wiki_id获取wiki扩展信息
     * @param array
     * @return xml
     * @author wangnan 
     */
	public function GetWikiExtend($args)
	{
		$nodeArray = array();
    	$wiki_id    = $args['parameter'][0]['data'][0]['__attributes__']['wiki_id'];
    	$extendtype = $args['parameter'][0]['data'][0]['__attributes__']['extendtype'];
    	if($this->isVarSet($wiki_id,'请填写wiki_id')==false) return; 
    	if($this->isVarSet($extendtype,'请填写extendtype')==false) return; 
    	$type = array(1,2,3,4);
    	if(in_array($extendtype,$type)===false) 
    		return $this->arrayToDom($this->getErrArray("false", 0,0,'未找到数据')); 
    	$mongo = sfContext::getInstance()->getMondongo();
		$WikiRepository = $mongo->getRepository('Wiki');
		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
		if($wiki) 
		{
			$nodeArray = $this->getErrArray("false");
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
			$nodeArray = $this->getErrArray("false", 0,0,'该wiki不存在');
		}
		return $this->arrayToDom($nodeArray); 
	}
    
	/**
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
		foreach($comments as $key=>$comment){
			$user = $comment->getUser();
			$nodeArray['data'][0]['comment'][$key][DOM::ATTRIBUTES] = array(
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
     * 提交用户节目操作(节目预约)
     * @param array
     * @return xml
     * @author lifucang 
     */
	public function ReportUserProgramAction($args)
	{
        $nodeArray        = $this->getErrArray("false",null,null,'');
    	$dnum             = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid           = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
    	$type             = $args['parameter'][0]['data'][0]['__attributes__']['type'];  //1:添加预约 2:删除预约
    	$channel_code     = $args['parameter'][0]['data'][0]['__attributes__']['channel_code'];
    	$name             = $args['parameter'][0]['data'][0]['__attributes__']['name'];  //节目名称
    	$start_time       = $args['parameter'][0]['data'][0]['__attributes__']['start_time'];
    	$wiki_id          = $args['parameter'][0]['data'][0]['__attributes__']['wiki_id'];  //节目维基的id modify by tianhzongsheng-ex@huan.tv Time 2013-04-23 14:55:00
    	
        if(!$dnum){
            //接口一律用huanid来识别用户
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($nodeArray);
        }
        if(!in_array($type, array('1','2','3'))){
            //接口方法是否正确
            $nodeArray = $this->getErrArray("true",null,null,'type处出错');
            return $this->arrayToDom($nodeArray);
        }
        if($channel_code==''){
            $nodeArray = $this->getErrArray("true",null,null,'channel_code不能为空');
            return $this->arrayToDom($nodeArray);
        }
        if($start_time==''){
            $nodeArray = $this->getErrArray("true",null,null,'start_time不能为空');
            return $this->arrayToDom($nodeArray);
        }        
		$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);;
		$user_id =  (string)$hasUser->getId();
		
        $mongo = sfContext::getInstance()->getMondongo();
        $WikiRepository = $mongo->getRepository('wiki');
        $wikiResult = $WikiRepository->findOneById(new MongoId($wiki_id));
        if(!empty($wikiResult)){
        	$wiki_title = $wikiResult->getTitle();
        	$wiki_cover = $wikiResult->getCover();
        }
        
		$ProgramUserRepository = $mongo->getRepository('Programe_user');
		$ProgramUser = new Programe_user();
        switch($type){
            case '1':
				$rs=$ProgramUserRepository->SearchPrograme($user_id,$channel_code,$start_time);
				if($rs)
					$nodeArray = $this->getErrArray("true",null,null,'该用户已预约该节目');
				else
					$ProgramUser->add($user_id,$channel_code,$name,$start_time,$wiki_id,$wiki_title,$wiki_cover);
                break;
            case '2':
                $ProgramUserRepository->del($user_id,$channel_code,$start_time);
                break;
            case '3':
                $pus = $ProgramUserRepository->find();
                if($pus){
                	foreach($pus as $pu){
                			$pu->delete();
                	}
                }
                break;
            default:
            	$nodeArray = $this->getErrArray("true",null,null,'type处出错');
        }   	
        return $this->arrayToDom($nodeArray);
	}      
    
	/*
     * 获取用户节目操作(节目预约)
     * @param array
     * @return xml
     * @author lifucang 
     */     
    public function GetProgramListByUser($args) 
    {
    	$parameter = $args['parameter'][0]['data'][0]['__attributes__'];
    	$page = $parameter['page'] ? $parameter['page'] : 1;
    	$size = $parameter['size'] ? $parameter['size'] : 10;
    	$type = $parameter['type'] ? $parameter['size'] : 1; 
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
        if(!$dnum){
            $arr = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($arr);
        }

		$hasUser = $this->WhetherIssetUserForGet($dnum,$huanid);
        $mongo = sfContext::getInstance()->getMondongo();
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
            $ProgramUserRepository = $mongo->getRepository('Programe_user');
            $programusers=$ProgramUserRepository->getProgrameByUser($user_id,$page,$size);
            $totalprogramusers=$ProgramUserRepository->getProgrameCountByUser($user_id);  //获取总数
            if($programusers){
                $arr = $this->getErrArray("false", count($programusers),count($totalprogramusers));
                foreach($programusers as $key => $programuser){
                    $channel_code=$programuser->getChannelCode();
                    $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
                    if($channel){
                        $channel_name = $channel->getName();
                        $channel_logo = $channel->getLogoUrl();
                    } else {
                        $channel_name = '';
                        $channel_logo = '';
                    }
                    // Modify by tianhzongsheng-ex@huan.tv 增加wiki返回的数据 Time 2013-04-27 14:10
                    $return_array = array();
                    $return_array['channel_code'] = $channel_code;
                    $return_array['channel_name'] = $channel_name;
                    $return_array['channel_logo'] = $channel_logo;
                    $return_array['name'] = $programuser->getName();
                    $return_array['start_time'] = date('Y-m-d H:i:s',$programuser->getStartTime()->getTimestamp());
                    $wiki_id = $programuser->getWikiId();
                    $wiki_title = $programuser->getWikiTitle();
                    $wiki_cover = $programuser->getWikiCover();
                    if($wiki_id){
	                    $return_array['wiki_id'] = $wiki_id;
                    }
                	if($wiki_title){
	                    $return_array['wiki_title'] = $wiki_title;
                    }
                	if($wiki_cover){
	                    $return_array['wiki_cover'] = $wiki_cover;
                    }
                    $arr['data'][0]['program'][$key][DOM::ATTRIBUTES] = $return_array;
                }
            } else {
                $arr = $this->getErrArray("false", null,null,'');
            }
        } else {
			$arr = $this->getErrArray("true",null,null,'该用户不存在');
        }               
        return $this->arrayToDom($arr);
    }
    
	/*
     * 提交用户收看信息
     * @param array
     * @return xml
     * @author lifucang 
     * 和androidtv.postUserLiving类似
     */
	public function ReportUserLivingAction($args)
	{
        $nodeArray = $this->getErrArray("false",null,null,'');
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
    	$channel     = $args['parameter'][0]['data'][0]['__attributes__']['channel_code'];
    	
        if(!$dnum){
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($nodeArray);
        }

        $mongo = sfContext::getInstance()->getMondongo();
		$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);

		$userid =  (string)$hasUser->getId();
         //判断是否存在该频道
        $arrchannel = Doctrine::getTable('Channel')->createQuery()
            ->where('code = ?', $channel)
            ->orWhere('name = ?', $channel)
            ->fetchOne();
        if($arrchannel){
            $channel_code=$arrchannel->getCode();
        } else {
            $nodeArray = $this->getErrArray("true",null,null,'该频道不存在');
            return $this->arrayToDom($nodeArray);
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
                $ts = new transferStatistics(); 
                $ts->setUserid($userid);    
                $ts->setTochannelCode($channel_code);    
                $ts->save();   
            } else {
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
                $ts = new transferStatistics(); 
                $ts->setUserid($userid);    
                $ts->setTochannelCode($channel_code);    
                $ts->save();                                  
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
            $ts = new transferStatistics(); 
            $ts->setUserid($userid);    
            $ts->setTochannelCode($channel_code);    
            $ts->save();                   
        }	
        return $this->arrayToDom($nodeArray);
	}

    /*
     * 获取省市列表
     * @param  $args
     * @retrun xml
     * @author lifucang
     */
    public function GetSystemCitys($args){
        $province = Province::getProvinceAll();
        $city = Province::getCityAll();
        $total = count($province);
        
        $nodeArray = array();
        if($province) {
            $nodeArray = $this->getErrArray("false", $total);
            foreach($province  as $key =>$value) {                
                $nodeArray['data'][0]['province'][$key][DOM::ATTRIBUTES] = array(
                   'name' => $value,
                   'value'=> $key
                );
                foreach($city[$key] as $index => $child) {
                    $nodeArray['data'][0]['province'][$key]['city'][$index][DOM::ATTRIBUTES] = array(
                        'name'    => $child,
                        'value' => $index,
                    );
                }
            }
        } else {
            $nodeArray = $this->getErrArray("false",0);
        }
        return $this->arrayToDom($nodeArray);
    }

	/*
     * 按省市获取直播运营商列表
     * @param array
     * @return xml
     * @author lifucang
     */
    public function GetDtvSPList($args)
    {
    	$nodeArray = array();
    	$province = $args['parameter'][0]['data'][0]['__attributes__']['province'];
        $mongo = sfContext::getInstance()->getMondongo();
        $splist = $mongo->getRepository('sp')->getSpByProvince($province);
        if($splist){
            $nodeArray = $this->getErrArray("false", count($splist));
    		foreach($splist  as $key =>$value) {
    		    if($value['logo'] != '') {
                    $logo = file_url($value['logo']);
                } else {
                    $logo = '';   
                }                    
    			$nodeArray['data'][0]['sp'][$key][DOM::ATTRIBUTES] = array(
    				'signal'   => $value['signal'],
    				'name'     => $value['name'],
    				'remark'   => $value['remark'],
    				'logo'     => $logo,
    			);
    		}            
        } else {
            $nodeArray = $this->getErrArray("false",0);
        }
		return $this->arrayToDom($nodeArray);
    } 
    
	/*
     * 设置用户属性
     * @param array
     * @return xml
     * @author lifucang 
     */
	public function SetUserConfig($args)
	{
        $nodeArray = $this->getErrArray("false",null,null,'');
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
    	$province         = $args['parameter'][0]['data'][0]['__attributes__']['province'];
    	$city             = $args['parameter'][0]['data'][0]['__attributes__']['city'];  //节目名称
    	$dtvsp            = $args['parameter'][0]['data'][0]['__attributes__']['dtvsp'];
    	
        if(!$dnum){
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($nodeArray);
        }
        if($province==''){
            $nodeArray = $this->getErrArray("true",null,null,'province不能为空');
            return $this->arrayToDom($nodeArray);
        }
        if($city==''){
            $nodeArray = $this->getErrArray("true",null,null,'city不能为空');
            return $this->arrayToDom($nodeArray);
        }  
        if($dtvsp==''){
            $nodeArray = $this->getErrArray("true",null,null,'dtvsp不能为空');
            return $this->arrayToDom($nodeArray);
        } 
        
		$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);

		$hasUser->setProvince($province);
		$hasUser->setCity($city);
		$hasUser->setDtvsp($dtvsp);
		$hasUser->save();
        	
        return $this->arrayToDom($nodeArray);
	} 
    
	/*
     * 获取用户属性
     * @param array
     * @return xml
     * @author lifucang
     */
	public function GetUserConfig($args)
	{
		$nodeArray = array();
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];

        if(!$dnum) {
            //接口一律用huanid来识别用户
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($nodeArray);
        }     
		$hasUser = $this->WhetherIssetUserForGet($dnum,$huanid);
        if($hasUser) {
			$user_id = (string)$hasUser->getId();
			$nodeArray = $this->getErrArray("false",0);
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'province' => $hasUser->getProvince(),
				'city'     => $hasUser->getCity(),
                'dtvsp'    => $hasUser->getDtvsp(),
	        );
        } else {
	        $nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
        }                   	
        return $this->arrayToDom($nodeArray);
	}   
    
	/*
     * 获取所有频道的节目列表
     * @param array
     * @return xml
     * @author lifucang
     */
    public function GetAllChannelProgram($args)
    {
    	$nodeArray = array();
    	$province = $args['parameter'][0]['data'][0]['__attributes__']['province'];
        $date = $args['parameter'][0]['data'][0]['__attributes__']['date'];
        if(empty($date))
            $date = date('Y-m-d');        
        $arr_date = array(date('Y-m-d'),date('Y-m-d', strtotime("+1 day")));
        if(!in_array($date,$arr_date)){
            $nodeArray = $this->getErrArray("true",null,null,'date只能是当天和第二天的日期');
            return $this->arrayToDom($nodeArray);            
        }
        //获取缓存数据
        $memcache = tvCache::getInstance();
        $memcache_key = 'GetAllChannelProgram'.$province.$date;
        $nodeArray = $memcache->get($memcache_key);
        if($nodeArray){    
            return $this->arrayToDom($nodeArray);
        }            
        //没有缓存执行以下步骤
    	if(empty($province))
    		$channels = Doctrine::getTable('Channel')->getWeiShiChannels();
    	else
    		$channels = Doctrine::getTable('Channel')->getUserChannels('',$province);
		$nodeArray = $this->getErrArray("false",0);
		$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
			'language'  =>  'zh-CN',
			'total'       =>  count($channels),
		);
    	$mongo = sfContext::getInstance()->getMondongo();
    	$ProgramRepository = $mongo->getRepository('Program');        
		foreach($channels  as $key =>$channel){
			$nodeArray['data'][0]['channel'][$key][DOM::ATTRIBUTES] = array(
				'id'   => $channel['id'],
                'name' => $channel['name'],
				'code' => $channel['code'],
				'memo' => $channel['memo'],
				'type' => $channel['type'],
				'logo' => $channel->getLogoUrl()
			);
            $shijian=strtotime($date);
        	$programs = $ProgramRepository->getProgramsByCode($channel['code'],date('Y-m-d 00:00:00',$shijian),date('Y-m-d 23:59:59',$shijian));
	        foreach($programs as $key1 =>$program){
	            $wiki_info = $program->getWiki();
	            $hasVideo = ($wiki_info['has_video']>0)?'yes':'no';
	            $source = implode(',',$wiki_info['source']);
				$nodeArray['data'][0]['channel'][$key]['program'][$key1][DOM::ATTRIBUTES] = array(
					'name'       => $program['name'],
					'date'       => $program['date'],
					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
					'end_time'   => date("H:i",$program['end_time']->getTimestamp()),
					'wiki_id'    => $program['wiki_id'],
					'wiki_cover' => file_url($wiki_info['cover']),
					'tags'       => $wiki_info['tags'],
					'hasvideo'   => $hasVideo,
					'source'     => $source,
	            );
			}        	
         
		}
        $memcache->set($memcache_key,$nodeArray);   
		return $this->arrayToDom($nodeArray);
    }   
    
	/*
     * 频道名称上报
     * @param array
     * @return xml
     * @author lifucang 
     */
	public function ReportChannelName($args)
	{
        $nodeArray = $this->getErrArray("false",null,null,'');
    	$dtvsp            = $args['parameter'][0]['data'][0]['__attributes__']['dtvsp'];  //Dtvsp的标识
    	$name             = $args['parameter'][0]['data'][0]['__attributes__']['name'];  //频道名称
        if($dtvsp==''){
            $nodeArray = $this->getErrArray("true",null,null,'dtvsp不能为空');
            return $this->arrayToDom($nodeArray);
        }
        if($name==''){
            $nodeArray = $this->getErrArray("true",null,null,'name不能为空');
            return $this->arrayToDom($nodeArray);
        } 
        $mongo = sfContext::getInstance()->getMondongo();
        $Repository = $mongo->getRepository('ReportChannel');  
        $arr_name=explode(',',$name);
        foreach($arr_name as $value){
            $rs=$Repository->SearchByDtvspAndName($dtvsp,$value);
            if(!$rs){
                $ReportChannel = new ReportChannel();
                $ReportChannel->add($dtvsp,$value);
            }   
        }
        return $this->arrayToDom($nodeArray);
	}
    
	/*
     * 后台推荐接口
     * @param string $args
     * @return xml
     * @author wangnan
     */
	public function GetHotRecommendList($args)
	{
		$parameter = $args['parameter'][0]['data'][0]['__attributes__'];
		$page = $parameter['page'] ? $parameter['page'] : 1;
		$size = $parameter['size'] ? $parameter['size'] : 8;
		$scene  = $parameter['scene'];
	
		$mongo = sfContext::getInstance()->getMondongo();
		$recommendRepository = $mongo->getRepository('recommend');
		$recommends = $recommendRepository->getRecommendByPageAndSize($page,$size,$scene);
		$totalrecommendRecs = $recommendRepository->getRecommendBySceneNoLimit($scene);
		if($recommends){
            $nodeArray = $this->getErrArray("false", count($recommends),count($totalrecommendRecs));
			foreach($recommends as $recommend_key =>$recommend){
				$nodeArray['data'][0]['recommend'][$recommend_key][DOM::ATTRIBUTES] = array(
	                    'id'             => $recommend->getId(),
	                    'title'          => $recommend->getTitle(),
	                    'is_public'      => $recommend->getIsPublic(),
	                    'isdesc_display' => $recommend->getIsdescDisplay(),
	                    'scene'          => $recommend->getScene(),
	                    'sort'           => $recommend->getSort(),
	                    'pic'            => file_url($recommend->getPic()),
	                    'smallpic'       => file_url($recommend->getSmallpic()),
	                    'url'            => $recommend->getUrl(),
				);
				$nodeArray['data'][0]['recommend'][$recommend_key]['desc'] = $recommend->getDesc();
			}
		}else{
			$nodeArray = $this->getErrArray("false", null,null,'');
		}
        return $this->arrayToDom($nodeArray);
    }
   	
	/*
     * 收藏频道、删除收藏频道
     * @param array
     * @return xml
     * @author wangnan 
     */
	public function ReportUserChannelAction($args)
	{
		$nodeArray = array();
    	$type    = $args['parameter'][0]['data'][0]['__attributes__']['type'];
    	$channel_code    = $args['parameter'][0]['data'][0]['__attributes__']['channel_code'];
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];

		if($this->isVarSet($dnum,'请填写dnum')==false) return;         
        $mongo = sfContext::getInstance()->getMondongo();
        $ucRepository = $mongo->getRepository('UserChannel');
		$hasUser = $this->WhetherIssetUserForReport($dnum,$huanid);

		$user_id =  (string)$hasUser->getId();
		if($type == 1){
			$userchannel = $ucRepository->findOneByChannelCode($user_id,$channel_code);
			if(!$userchannel){
				$channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
				if($channel){
					$userChannel = new UserChannel;			
					$userChannel->setChannelCode($channel_code);
					$userChannel->setUserId($user_id);
					$userChannel->setName($channel->getName());
					$userChannel->save();
				} else {
					return $this->arrayToDom($this->getErrArray("true",null,null,'频道不存在'));
				}
			}
			$nodeArray = $this->getErrArray("false",null,null,'');
		}
		if($type == 2){
			$userChannel = $ucRepository->findOneByChannelCode($user_id,$channel_code);
			if($userChannel){
				$userChannel->delete();
				$nodeArray = $this->getErrArray("false",null,null,'');
			} else {
				$nodeArray = $this->getErrArray("true",null,null,'数据不存在');
            }
		}
		if($type == 3){
			$userChannels = $ucRepository->getUserChannels($user_id);
			if($userChannels){
				foreach($userChannels as $userChannel){
					$userChannel->delete();
				}
				$nodeArray = $this->getErrArray("false",null,null,'');
			} else {
				$nodeArray = $this->getErrArray("true",null,null,'数据不存在');	
            }                
		}
                  	
        return $this->arrayToDom($nodeArray);
	}
    
	/*
     * 获取用户收藏的频道
     * @param array
     * @return xml
     * @author wangnan
     */
    public function GetChannelListByUser($args)
    {
    	$nodeArray = array();
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
		if($this->isVarSet($dnum,'请填写dnum')==false) return; 
    
    	$parameter = $args['parameter'][0]['data'][0]['__attributes__'];
    	$page = $parameter['page'] ? $parameter['page'] : 1;
    	$size = $parameter['size'] ? $parameter['size'] : 10;
		$nodeArray = $this->getErrArray("false",0);
		
        $mongo = sfContext::getInstance()->getMondongo();
        $ucRepository = $mongo->getRepository('UserChannel');
        
		$hasUser = $this->WhetherIssetUserForGet($dnum,$huanid);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
	        $ucs = $ucRepository->getUserChannels($user_id);		
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'language'  =>  'zh-CN',
				'total'       =>  count($ucs),
			);
			$channels = $ucRepository->getUserChannelsByPS($user_id,$page,$size);	
			if($channels){
				$i = 0;
				foreach($channels  as $channel){
					$mysql_channel = $channel->getChannel();
					if($mysql_channel){
						$nodeArray['data'][0]['channel'][$i][DOM::ATTRIBUTES] = array(
							'id'   => $channel->getId(),
			                'name' => $channel->getName(),
							'code' => $channel->getChannelCode(),
							'memo' => $mysql_channel->getMemo(),
							'type' => $mysql_channel->getType(),
							'logo' => $mysql_channel->getLogoUrl(),
                            'hot' => $mysql_channel->getHot(),
							'recommend' => $mysql_channel->getRecommend(),
							'likenum' => $mysql_channel->getLikeNum(),
							'dislikenum' => $mysql_channel->getDislikeNum(),
						);
						$i = $i + 1;
					}
				}
			}
        	
        } else {            
			$user = $this->WhetherIssetUserForReport($dnum,$huanid);
        	$settingRepository = $mongo->getRepository('setting');
        	$settings = $settingRepository->findOne(array('query'=>array('key'=>'collectionsearchkey')));
        	$channelcodes = json_decode($settings->getValue());
        	$channels = Doctrine_Query::create()->from('Channel c')->whereIn('c.code',$channelcodes)->execute();
        	if($channels){
	        	foreach($channels as $i=>$channel){
		        	$uc = new UserChannel();
		        	$uc->setUserId((string)$user->getId());
		        	$uc->setName($channel['name']);
		        	$uc->setChannelCode($channel['code']);
		        	$uc->save();
					$nodeArray['data'][0]['channel'][$i][DOM::ATTRIBUTES] = array(
						'id'   => $channel->getId(),
		                'name' => $channel->getName(),
						'code' => $channel->getCode(),
						'memo' => $channel->getMemo(),
						'hot' => $channel->getHot(),
						'type' => $channel->getType(),
						'logo' => $channel->getLogoUrl(),
						'recommend' => $channel->getRecommend(),
						'likenum' => $channel->getLikeNum(),
						'dislikenum' => $channel->getDislikeNum(),
					);	        	
	        	}
        	}
        }                   	
        return $this->arrayToDom($nodeArray); 
    }
    
	/*
     * 获取用户收藏的频道的直播列表
     * @param array
     * @return xml
     * @author wangnan
     */
    public function GetLiveProgrameByUser($args)
    {
    	$nodeArray = array();
    	$dnum  = $args['parameter'][0]['device'][0]['__attributes__']['dnum'];
    	$huanid  = $args['parameter'][0]['user'][0]['__attributes__']['huanid'];
		if($this->isVarSet($dnum,'请填写dnum')==false) return; 
    
    	$starttime = $args['parameter'][0]['data'][0]['__attributes__']['start_time'];
    	$endtime = $args['parameter'][0]['data'][0]['__attributes__']['end_time'];
		if($starttime==''&&$endtime=='')$starttime = $endtime =date("Y-m-d H:i:s",time());
		$nodeArray = $this->getErrArray("false",0);
		
        $mongo = sfContext::getInstance()->getMondongo();
        $ucRepository = $mongo->getRepository('UserChannel');
        
		$hasUser = $this->WhetherIssetUserForGet($dnum,$huanid);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
	        $ucs = $ucRepository->getUserChannels($user_id);		
			$channels = $ucRepository->getUserChannels($user_id);	
			if($channels){
				foreach($channels  as $key =>$channel){
					$codes[] = $channel->getChannelCode();
				}
				$ProgramRepository = $mongo->getRepository('Program');
				$WikiRepository = $mongo->getRepository('Wiki');
				$programs = $ProgramRepository->getProgramsByCodes($codes,'',$starttime,$endtime);unset($code);  
				$i=0;
				if($programs){
					foreach($programs as $program_key =>$program)
					{
                        //$wiki = $WikiRepository->findOneById(new MongoId($program['wiki_id']));
						$wiki = $WikiRepository->getWikiById($program['wiki_id']);
						if($wiki){
							$channel = $program->getChannel();
							$nodeArray['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
								'id'              => $wiki->getId(),
								'title'           => $wiki->getTitle(),
								'model'           =>$wiki->getModel(),
								'start_time'      => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
								'end_time'        => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
								'channel_code'    => $channel->getCode(),
								'channel_name'    => $channel->getName(),
								'channel_logourl' => $channel->getLogoUrl(),
								'channel_hot' => $channel->getHot(),
								'likenum' => $channel->getLikeNum(),
								'dislikenum' => $channel->getDislikeNum()
                            );
							$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray,array('type'=>1), 1, false);
							$i = $i+ 1;
						}
					}
					$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
						'language'  => 'zh-CN',
						'total'     => $i,
					); 			      	
				}				
			} else {
				return $this->arrayToDom($nodeArray); 
            }                
        } else {
	        $nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
        }                   	
        return $this->arrayToDom($nodeArray);
    } 
    
	/*
     * 获取wiki的播出预告
     * @param array
     * @return xml
     * @author wangnan
     */
    public function GetProgramListByMedia($args)
    {
    	$nodeArray = array();
    	$wiki_id = $args['parameter'][0]['data'][0]['__attributes__']['wiki_id'];
    	$starttime = $args['parameter'][0]['data'][0]['__attributes__']['start_time'];
    	$endtime = $args['parameter'][0]['data'][0]['__attributes__']['end_time'];
    	$type = $args['parameter'][0]['data'][0]['__attributes__']['type'];
    	$mongo = sfContext::getInstance()->getMondongo();
    	$ProgramRepository = $mongo->getRepository('Program');
    	$programs = $ProgramRepository->getProgramsByWikiId($wiki_id,$starttime,$endtime);
    	
		$nodeArray = $this->getErrArray("false",0);
        $i = 0;
        foreach($programs as $key =>$program) {
            $channel = $program->getChannel();
            if(($type != "") && (in_array($type,array('tv','cctv','tvandcctv')))) {
            	if($type == 'tvandcctv'){
                    if($channel->getType() == '') continue;
                } else {
                    if($channel->getType() != $type) continue;
                }
            }
            $wiki_info = $program->getWiki();
            $hasVideo = ($wiki_info['has_video']>0)?'yes':'no';
            $source = implode(',',$wiki_info['source']);
			$nodeArray['data'][0]['program'][$i][DOM::ATTRIBUTES] = array(
				'name'            => $program['name'],
				'date'            => $program['date'],
				'start_time'      => date("H:i",$program['start_time']->getTimestamp()),
				'end_time'        => date("H:i",$program['end_time']->getTimestamp()),
				'wiki_id'         => $program['wiki_id'],
				'wiki_cover'      => file_url($wiki_info['cover']),
				'tags'            => $wiki_info['tags'],
				'hasvideo'        => $hasVideo,
				'source'          => $source,
				'channel_code'    => $channel->getCode(),
				'channel_name'    => $channel->getName(),
				'channel_logourl' => $channel->getLogoUrl(),
				'channel_type'    => $channel->getType(),
				'channel_memo'    => $channel->getMemo(),
            );
			$i = $i + 1;               
		}        	
		$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
			'language'  =>  'zh-CN',
			'total'       => $i,
        );
		return $this->arrayToDom($nodeArray);
    }
    
    /**
     * GetWikiPackage
     * @author wangnan
     * @return object
     */     
    public function GetWikiPackage($arrs) 
    {
        $parameter = $arrs['parameter'][0]['data'][0]['__attributes__'];
        $page = $parameter['page'] ? $parameter['page'] : 1;
        $size = $parameter['size'] ? $parameter['size'] : 8;
        $scene  = $parameter['scene'];         
    
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRepository = $mongo->getRepository('wiki');
        $wpRepo = $mongo->getRepository("wikipackage");
        $wikiRecs = $wpRepo->getWikiByPageAndSize($page,$size,$scene,array(true));
        $totalWikiRecs = $wpRepo->getWikiBySceneNoLimit($scene,array(true));
        if($wikiRecs){
            $arr = $this->getErrArray("false", count($wikiRecs),count($totalWikiRecs));
            $i =0;
            foreach($wikiRecs as $wikiRec){
                $wiki = $wikiRepository->findOneById(new MongoId($wikiRec['wiki_id']));
                //$wiki = $wikiRepository->getWikiById($wikiRec['wiki_id']);
                if($wiki){
                    $arr['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
                        'id' => (string)$wiki->getId(),
                        'title'=> $wiki->getTitle(),
                        'model' =>$wiki->getModel()
                    );
                    $arr = $this->getWikiVideoSource($wiki,$i, $arr,'',0,false);
                    $i = $i + 1;
                }	                   
            }
        } else {
            $arr = $this->getErrArray("false", null,null,'');
        }
        return $this->arrayToDom($arr);
    }
    
    /**
     * GetCategoryRecommend
     * @author gaobo
     * @return xml
     */
    public function GetCategoryRecommend($args)
    {
        $category   = $args['parameter'][0]['data'][0]['__attributes__']['tag'];
        $start_time = date('Y-m-d',time());
        $end_time   = self::last_month_today(strtotime(date('Y-m-d',time())));

        $mongo      = sfContext::getInstance()->getMondongo();
        $cate_rec   = $mongo->getRepository('CategoryRecommend');
        $crs        = $cate_rec->findOne(array('query'=>array('category'=>$category,'start_time'=>array('$gte'=>$start_time),'end_time'=>array('$lte'=>$end_time))));
        if(empty($crs)){
            $crs = $cate_rec->findOne(array('query'=>array('category'=>$category,'is_default'=>true)));
        }
      
        if($crs){
            $arr = $this->getErrArray("false", count($crs));
            $i = 0;
            if($crs->getTemplate()){
                $json2arr = json_decode($crs->getTemplate(),true);
                foreach($json2arr as $key=>$val){
                    $a = array();
                    foreach($val as $realkey=>$realval){
                        if($realkey == 'img'){
                            $a['img'] = file_url($realval);
                            continue;
                        }
                        $a[$realkey] = $realval;
                    }
                    $arr['blocks'][$i]['block'][$key][DOM::ATTRIBUTES] = $a;
                }
            }
            $i++;
        } else {
            $arr = $this->getErrArray("false", null,null,'未查询到信息');
        }
        return $this->arrayToDom($arr);
    }
    
    /**
     * GetShortMoviePackages
     * @author gaobo
     * @return XML
     */
    public function GetShortMoviePackages($args)
    {
        $currPage = intval($args['parameter'][0]['data'][0]['__attributes__']['page']);
        $pageSize = intval($args['parameter'][0]['data'][0]['__attributes__']['pagesize']);
        $currPage = max($currPage, 0);
        $pageSize = max($pageSize, 5);
        $skip     = ($currPage - 1) * $pageSize;
        
        $mongo    = sfContext::getInstance()->getMondongo()->getRepository('ShortMoviePackage');
        $smps     = $mongo->find(array('query'=>array('state'=>1),'sort'=>array('created_at'=>-1),'skip'=>$skip,'limit'=>$pageSize));
        if($smps){
            $arr = $this->getErrArray("false", count($smps));
            $i = 0;
            foreach($smps as $k=>$v){
                if($v->getTag()){
                    $tagtmp = $v->getTag();
                    $tag = $tagtmp[0];
                } else {
                    $tag = '';
                }
                $temp['id']         = $v->getId();
                $temp['name']       = $v->getName();
                $temp['desc']       = $v->getDesc();
                $temp['cover']      = file_url($v->getCover());
                $temp['tag']        = $tag;
                $temp['created_at'] = date('Y-m-d H:i:s',$v->getCreatedAt()->getTimestamp());
                $arr['packages'][$i]['package'][$k][DOM::ATTRIBUTES] = $temp;
            }
           $i++;
        } else {
            $arr = $this->getErrArray("false", null,null,'未查询到信息');
        }
        return $this->arrayToDom($arr);
    }
    
    /**
     * GetPackageInfoById
     * @author gaobo
     * @return XML
     */
    public function GetShortMoviePackageInfoById($args)
    {
        $packageId = strval($args['parameter'][0]['data'][0]['__attributes__']['packageid']);
        if($packageId){
            $pmongo  = sfContext::getInstance()->getMondongo()->getRepository('ShortMoviePackage');
            $package = $pmongo->findOneById(new MongoId($packageId));
            if($package){
                $arr = $this->getErrArray("false", count($package));
                if($package->getTag()){
                    $tagtmp = $package->getTag();
                    $tag = $tagtmp[0];
                } else {
                    $tag = '';
                }
                $packarr['id'] = $package->getId();
                $packarr['name'] = $package->getName();
                $packarr['desc'] = $package->getDesc();
                $packarr['cover'] = file_url($package->getCover());
                $packarr['tag'] = $tag;
                $packarr['created_at'] = date('Y-m-d H:i:s',$package->getCreatedAt()->getTimestamp());
                $arr['package'][0][DOM::ATTRIBUTES] = $packarr;
            } else {
                $arr = $this->getErrArray("false", null,null,'未查询到信息');
                return $this->arrayToDom($arr);
            }
            
            $mongo   = sfContext::getInstance()->getMondongo()->getRepository('ShortMoviePackageItem');
            $smpItem = $mongo->find(array('query'=>array('package_id'=>$packageId)));
            if($smpItem){
                $i = 0;
                $mongo   = sfContext::getInstance()->getMondongo()->getRepository('ShortMovie');
                foreach($smpItem as $k=>$v){
                    $shortMovieDetail = $mongo->findOneById(new MongoId($v->getShortMovieId()));
                    if(@$shortMovieDetail->getTag()){
                        $tagtmp = @$shortMovieDetail->getTag();
                        $tag = @$tagtmp[0];
                    } else {
                        $tag = '';
                    }
                    $temp['id']         = $shortMovieDetail->getId();
                    $temp['name']       = $shortMovieDetail->getName();
                    $temp['cover']      = file_url($shortMovieDetail->getCover());
                    $temp['url']        = $shortMovieDetail->getUrl();
                    $temp['tag']        = $tag;
                    $temp['refer']      = $shortMovieDetail->getRefer();
                    $temp['author']     = $shortMovieDetail->getAuthor();
                    $temp['created_at'] = date('Y-m-d H:i:s',$v->getCreatedAt()->getTimestamp());
                    $arr['package'][$i]['shortmovie'][$k][DOM::ATTRIBUTES] = $temp;
                }
                $i++;
            }
        } else {
            $arr = $this->getErrArray("false", null,null,'请给出packageID');
        }
        return $this->arrayToDom($arr);
    }
    
    /**
     * 计算上一个月的今天，如果上个月没有今天，则返回上一个月的最后一天
     * @author gaobo
     * @param type $time
     * @return type
     */
    private function last_month_today($time){
        $last_month_time = mktime(date("G", $time), date("i", $time),
        date("s", $time), date("n", $time)+1, 1, date("Y", $time));
        $last_month_t =  date("t", $last_month_time);
        if ($last_month_t < date("j", $time)) {
            return date("Y-m-t", $last_month_time);
        }
        return date(date("Y-m", $last_month_time) . "-d", $time);
    }
    
	/*
     * 运营商上报NIT信息表
     * @param string $args
     * @return xml
     * @author wangnan
     */
	public function ReportTVSPNIT($args)
	{
		$newwork_id    = $args['parameter'][0]['data'][0]['__attributes__']['newwork_id'];
		$newwork_name  = $args['parameter'][0]['data'][0]['__attributes__']['newwork_name'];
		$version       = $args['parameter'][0]['data'][0]['__attributes__']['version'];
		$city          = $args['parameter'][0]['data'][0]['__attributes__']['city'];
		$channels      = $args['parameter'][0]['data'][0]['channel'];
        	
		$mongo = sfContext::getInstance()->getMondongo();
		$mongo->setDefaultConnectionName('mondongosp');
		$reportSpRepository = $mongo->getRepository('reportSp');
		$reportSp = $reportSpRepository->device_report($newwork_id,$newwork_name,$version,$city);
		$report_num = $reportSp->getNum();
		if($report_num <= 3) {
			if($channels && is_array($channels)) {
				foreach($channels as $channel) {
					$reportSpchannel = new reportSpchannel();
					$reportSpchannel->setSpid((string)$reportSp->getId());
					$reportSpchannel->setName($channel['__attributes__']['name']);
					$reportSpchannel->setServiceId($channel['__attributes__']['serviceId']);
					$reportSpchannel->setFrequency($channel['__attributes__']['frequency']);
					$reportSpchannel->setSymbolRate($channel['__attributes__']['symbolRate']);
					$reportSpchannel->setModulation($channel['__attributes__']['modulation']);
					$reportSpchannel->setOnId($channel['__attributes__']['onId']);
					$reportSpchannel->setTsId($channel['__attributes__']['tsId']);
					$reportSpchannel->setLogicNumber($channel['__attributes__']['logicNumber']);
					$reportSpchannel->setNum($report_num);
					$reportSpchannel->save();
				}
				$nodeArray = $this->getErrArray("fasle", null,null,'');
			} else {
				$nodeArray = $this->getErrArray("false", null,null,'channel节点为空');
            }
		} else {
			$nodeArray = $this->getErrArray("true", null,null,'上报超过三次');
        }
        return $this->arrayToDom($nodeArray);
    }

	/*
     * 运营商上报EPG信息表
     * @param string $args
     * @return xml
     * @author wangnan
     */
	public function ReportTVSPEPG($args)
	{
		$newwork_id    = $args['parameter'][0]['data'][0]['__attributes__']['newwork_id'];
		$newwork_name  = $args['parameter'][0]['data'][0]['__attributes__']['newwork_name'];
		$version       = $args['parameter'][0]['data'][0]['__attributes__']['version'];
		$city          = $args['parameter'][0]['data'][0]['__attributes__']['city'];
		$channels      = $args['parameter'][0]['data'][0]['channel'];
        	
		$mongo = sfContext::getInstance()->getMondongo();
		$mongo->setDefaultConnectionName('mondongosp');
		$reportSpRepository = $mongo->getRepository('reportSp');
		$reportSp = $reportSpRepository->getOneSpByNIC($newwork_id,$city);
		if(!$reportSp) {
			$nodeArray = $this->getErrArray("true", null,null,'未找到相关运营商信息');
	        return $this->arrayToDom($nodeArray);
		} else {
			$spid = (string)$reportSp->getId();
			$reportProgramnumRepository = $mongo->getRepository('reportProgramnum');
			foreach($channels as $channel) {
				$date = $channel['__attributes__']['date'];
				$name = $channel['__attributes__']['name'];
				$reportProgramnum = $reportProgramnumRepository->device_report_programnum($reportSp,$date);
				$report_program_num = $reportProgramnum->getNum();
				if($report_program_num <= 3) {
					$programs = $channel['program'];
					if($programs && is_array($programs)) {					
						foreach($programs as $program) {
							$reportProgram = new reportProgram();
							$reportProgram->setSpid((string)$reportSp->getId());
							$reportProgram->setChannelName($name);
							$reportProgram->setName($program['__attributes__']['name']);
							$reportProgram->setStartTime($program['__attributes__']['start_time']);
							$reportProgram->setEndTime($program['__attributes__']['end_time']);
							$reportProgram->setTag($program['__attributes__']['tag']);
							$reportProgram->setDate($date);
							$reportProgram->setNum($report_program_num);
							$reportProgram->save();
						}
						$nodeArray = $this->getErrArray("fasle", null,null,'');
					}
				} else {
					$nodeArray = $this->getErrArray("true", null,null,'上报超过三次');
                }                    
			}			
		}
        return $this->arrayToDom($nodeArray);
    }
    
    
    /**
     * 获取昨日回顾
     * @author gaobo
     */
    private function GetYesterdayProgramByDate($args)
    {
        $date = strval($args['parameter'][0]['data'][0]['__attributes__']['date']);
        $key = 'yesterday'.$date;
        $memcache = tvCache::getInstance();
        $arr = $memcache->get($key);
        if($arr){
            return $this->arrayToDom($arr);
        }
        
        $mongo = sfContext::getInstance()->getMondongo()->getRepository('YesterdayProgram');
        $ym  = $mongo->find(array('query'=>array('date'=>$date,'state'=>true),'sort'=>array('sort'=>1)));
        if($ym){
            $arr = $this->getErrArray("false", count($ym));
            $i = 0;
            foreach($ym as $k=>$v){
                if($v->getTags()){
                    $tagtmp = $v->getTags();
                    $tag = implode($tagtmp, ',');
                }else{
                    $tag = '';
                }
                $temp['program_name'] = $v->getProgramName();
                $temp['channel_code'] = $v->getChannelCode();
                $channel = Doctrine::getTable('Channel')->findOneByCode($temp['channel_code']);
                if($channel){
                    $temp['channel_name']    = $channel->getName();
                    $temp['channel_memo']    = $channel->getMemo();
                    $temp['channel_logourl'] = $channel->getLogoUrl();
                } else {
                    $temp['channel_name']    = '';
                    $temp['channel_memo']    = '';
                    $temp['channel_logourl'] = '';
                }                
                $temp['date']         = $v->getDate();
                $temp['start_time']   = date('Y-m-d H:i:s',$v->getStartTime()->getTimestamp());
                $temp['end_time']     = date('Y-m-d H:i:s',$v->getEndTime()->getTimestamp());
                $temp['wiki_id']      = $v->getWikiId();
                $temp['aspect']       = $v->getAspect();
                $temp['play_url']     = $v->getPlayUrl();
                $temp['style']        = $v->getStyle() ? $v->getStyle() : "";
                if($temp['style'] == "470*350") {
                    $temp['wiki_posterurl'] = thumb_url($v->getPoster(), 470, 350);                
                } else {
                    $temp['wiki_posterurl'] = thumb_url($v->getPoster(), 230, 350); 
                } 
                $temp['tag']          = $tag;
                $arr['data'][0]['programs'][$i]['program'][$k][DOM::ATTRIBUTES] = $temp;
            }
            $i++;
        }else{
            $arr = $this->getErrArray("false", null,null,'未查询到信息');
        }
        $memcache->set($key,$arr);
        return $this->arrayToDom($arr);
    }
    
    /**
     *获取下周预告
     *@author gaobo
     */
    private function GetNextweekProgramByDate($args)
    {
        $date = strval($args['parameter'][0]['data'][0]['__attributes__']['date']);
        $key = 'nextweek'.$date;
        $memcache = tvCache::getInstance();
        $arr = $memcache->get($key);
        if($arr){
            return $this->arrayToDom($arr);
        }
        
        $mongo = sfContext::getInstance()->getMondongo()->getRepository('NextweekProgram');
        $ym  = $mongo->find(array('query'=>array('date'=>$date,'state'=>true),'sort'=>array('sort'=>1)));
        if($ym){
            $arr = $this->getErrArray("false", count($ym));
            $i = 0;
            foreach($ym as $k=>$v){
                if($v->getTags()){
                    $tagtmp = $v->getTags();
                    $tag = implode($tagtmp, ',');
                }else{
                    $tag = '';
                }
                $temp['id']           = $v->getId();
                $temp['program_name'] = $v->getProgramName();
                $temp['channel_code'] = $v->getChannelCode();
                $channel = Doctrine::getTable('Channel')->findOneByCode($temp['channel_code']);
                if($channel){
                    $temp['channel_name']    = $channel->getName();
                    $temp['channel_memo']    = $channel->getMemo();
                    $temp['channel_logourl'] = $channel->getLogoUrl();
                } else {
                    $temp['channel_name']    = '';
                    $temp['channel_memo']    = '';
                    $temp['channel_logourl'] = '';
                }                
                $temp['date']         = $v->getDate();
                $temp['start_time']   = date('Y-m-d H:i:s',$v->getStartTime()->getTimestamp());
                $temp['end_time']     = date('Y-m-d H:i:s',$v->getEndTime()->getTimestamp());
                $temp['wiki_id']      = $v->getWikiId();                
                $temp['aspect']       = $v->getAspect();
                $temp['style']        = $v->getStyle() ? $v->getStyle() : "";
                if($temp['style'] == "470*350") {
                    $temp['wiki_posterurl'] = thumb_url($v->getPoster(), 470, 350);                
                } else {
                    $temp['wiki_posterurl'] = thumb_url($v->getPoster(), 230, 350); 
                } 
                $temp['tag']          = $tag;
                $arr['data'][0]['programs'][$i]['program'][$k][DOM::ATTRIBUTES] = $temp;
            }
            $i++;
        }else{
            $arr = $this->getErrArray("false", null,null,'未查询到信息');
        }
        $memcache->set($key,$arr);
        return $this->arrayToDom($arr);
    }
    
    
	/*
	 * 用户提交的时候根据dnum,huanid节点去判断用户是否存在不存在就创建 *必定返回一个不为空的user对象*
	 * @return object user
	 * @author wangnan
	 */
	private function WhetherIssetUserForReport($dnum,$huanid)
	{
		$hasUser='';
    	if(empty($huanid) || (int)$huanid<10000)
    		$val = $dnum;
    	else
    		$val = $huanid;
    	$mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $userRepository->device_user($val);
        $hasUser = $userRepository->getUserIdByDeviceId($val);
        return $hasUser;		
	}
    
	/*
	 * 用户获取的时候根据dnum,huanid节点去判断用户是否存在 
	 * @return object || '' 
	 * @author wangnan
	 */
	private function WhetherIssetUserForGet($dnum,$huanid)
	{
		$hasUser = '';
    	if(empty($huanid) || (int)$huanid<10000)
    		$val = $dnum;
    	else
    		$val = $huanid;
    	$mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($val);
        return $hasUser;		
	}
	/**
	 * 为猜你喜欢排序
	 * @return object || ''
	 * @param int $userid 用户ID
     * @param object $channels 推荐频道列表
	 * @author jhm
	 */
    private function sortChannelByUser($channels,$userid,$size=100)
    {
        $url = sfConfig::get("app_huan_irs_url");
        $userid = $userid ? $userid : "CH_78cfb72afedf324d9e83d1a76cef55b5746415dd";
        $key = "irs_$userid_$size";
        $memcache = tvCache::getInstance();
        $chs = $memcache->get($key);

        if(!$chs) {
        	$size=100;
            $content = Common::get_url_content($url."/recommender/ItemCFRecommenderAction?userID=".$userid."&howMany=".$size); 
            $jsoncontent = json_decode($content,true);
            $chs = $jsoncontent['channelIDs'];
            $memcache->set($key,$chs,time()+1800);
        }

        if(count($chs) <= 1) {
            return $channels;
        }
        foreach($channels  as $key =>$channel)
        {
        	if(in_array($channel->getId(),$chs)){ 
        		$channels_prior[]=$channel;
        	}else{ 
        		$channels_after[]=$channel;
        	}	 
        }
				if(!empty($channels_prior)){
          $wikis=(object) array_merge($channels_prior,$channels_after); //合并数组对象
          return $wikis;
				}else{
			    return $channels;
				}
    }
    
    /**
     * 节目直播推荐
     * @author gaobo
     * @Specialstatement 如果按时间段取数据则打开所有注释，并注释掉4327行，开启4326行。
     */
    private function GetProgramRec($args)
    {
        $memcache = tvCache::getInstance();
        $nowdate = strval($args['parameter'][0]['data'][0]['__attributes__']['date']);
        if(!$nowdate){
            $nowdate = date('Y-m-d',time());
        }
        
        $key = 'Programrec_'.$nowdate;
        $nodeArray = $memcache->get($key);
        if($nodeArray){
           return $this->arrayToDom($nodeArray);
        }
        
        $mongo = sfContext::getInstance()->getMondongo()->getRepository('ProgramRec');
        $programRec  = $mongo->find(array('query'=>array('date'=>$nowdate),'sort'=>array('sort'=>1)));
        if($programRec){            	
            $arr = $this->getErrArray("false", count($programRec));
            $WikiRepository    = sfContext::getInstance()->getMondongo()->getRepository('Wiki');
            $i = 0;
            foreach($programRec as $program_key =>$program) {
                $wiki = $WikiRepository->getWikiById($program['wiki_id']);
                if($wiki) {
                    $channel = $program->getChannel();
                    $nodeArray['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
                            'id'    => $wiki->getId(),
                            'title'   => $wiki->getTitle(),
                            'model' =>$wiki->getModel(),
                            'start_time' => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
                            'end_time' => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
                            'channel_code' => $channel->getCode(),
                            'channel_name' => $channel->getName(),
                            'channel_logourl' => $channel->getLogoUrl(),
                            'channel_memo' => $channel->getMemo(),
                            'channel_hot' => $channel->getHot(),
                            'likenum' => $channel->getLikeNum(),
                            'dislikenum' => $channel->getDislikeNum(),
                    );
                    $nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray,'',0,false);
                    $i = $i+ 1;
                }
            }
            $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
                    'language'  => 'zh-CN',
                    'total'     => $i,
            );
        }else{
            $nodeArray = $this->getErrArray("false", null,null,'未查询到信息');
        }        
        $memcache->set($key,$nodeArray,86400);
        return $this->arrayToDom($nodeArray);
    }
}
?>