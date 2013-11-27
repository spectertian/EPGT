<?php
/**
 * Description of Simple
 *
 * @author zgq
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
class Simple {
    var $prefix_url;
    var $filter;
    var $videoUrl;
    var $response;
    var $category;
    public function  __construct($post) {
        //获取根节点方法 $data->attributes()
        $this->setFilter();
        $this->setVideoUrl();
        $this->setCategory();
        $this->setPrefixUrl();
        $this->call($post); 
    }

    public function call($post){
        if($this->isVarSet($post,'未接收到xml请求信息')==false) return;
        $args = $this->xmlToArray($post);
        $param_attr = $args['parameter'][0]['__attributes__'];
        $request_attr = $args['website'];
        $device_attr = $args['parameter'][0]['device'][0]['__attributes__'];
        $methodArray = array(
                            'GetMediaCategory',
                            'GetRecommendMedia',                //增加memcache
                            'GetMediaListByCategory',
                            'GetFilterOption',
                            'ReportUserMediaAction',
                            'GetMediaListByUser',
                            'GetMediaListByMedia',
                            'SearchMedia',
                            'GetSpecifiedMedia',
							'GetChannelList',                   //增加memcache
        					'GetProgramListByChannel',
        					'GetRecommendByChannel',
        					'GetLivePrograme',
							'GetWikiInfo',
        					'GetThemeById',
        					'GetThemeList',                     //增加memcache
        					'ReportUserEpisodeAction',
        					'GetEpisodeListByUser',
                            'GetSpList',
                            'GetSpMediaListByCatelog',
        					'DeleteUserEpisodeAction',
        					'GetLiveCategory',
        					'SearchSuggest',
        					'GetChannelListBySP',               //增加memcache
        					'SearchProgram',
        					'GetWikiExtend',
                            'ReportUserProgramAction',
                            'GetProgramListByUser',
                            'ReportUserLivingAction',
                            'GetSystemCitys',
                            'GetDtvSPList',                     //增加memcache
                            'SetUserConfig',
                            'GetUserConfig',
                            'GetAllChannelProgram',             //增加memcache
                            'ReportChannelName',
            );
        /*
         * 传过来的null false 空格 " " 如果有""号包着 就不会被认为是空（因为是字符串了） 但是0除外 因为 0与"0"都在empty中包括
         */
        if($this->isVarSet($request_attr,'请填写接口地址')==false) return;
        if($this->isVarSet($device_attr['devmodel'],'请填写终端型号')==false) return;    
        if($param_attr['type']||$param_attr['type']=='0'){
            $method = (string)trim($param_attr['type']);
            if(in_array($method,$methodArray))
            {
                $this->$method($args);
            }
            else
            {
				if($this->isVarSet('','接口 '.$method.' 不存在 ')==false) return;
            }
        }
        else
		{
			if($this->isVarSet('','接口名称不能为空，请填写接口名称')==false) return;			
		}        	
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
		if(empty($var))
		{
			if($var=='0'){}
			else
			{
				$status = false;
				$nodeArray = $this->getErrArray("true",null,null,$message);
				$this->arrayToDom($nodeArray);
			}
		}
		return $status; 
    }
    
    /**
     * SimpleXML 对象转换为数组
     * @param SimpleXMLElement $xml
     * @return array
     * @author zhigang
     */
    private function xml2array($xml) {
        $str = serialize($xml); //serialize()  产生一个可存储的值的表示 
        $str = str_replace('O:16:"SimpleXMLElement"', 'a', $str);
        $arrstr = unserialize($str); //unserialize()  从已存储的表示中创建 PHP 的值
        return $arrstr;
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

    public function xmltoArray($xmlstring){
        return DOM::xmlStringToArray($xmlstring);
    }
    
    public function test($xml){
        
    }
    
    public function arrayToDom($array,$rootTag="response",$rootAttribute=null){
        if(!$rootAttribute){
            $rootAttribute = $this->setRootAttribute();
        }
        return $this->response = DOM::arrayToXMLString($array,$rootTag,$rootAttribute);
    }

    /*
     * 返回错误节点xml 数组结构
     * @param $errorStatus true 或者 false  前者代表有错误，后者代表无错误
     * @param $num 每页的个数  不为null时代表有数据  则输出data标签     当为null时 用于显示错误信息不输出data标签
     * @param $total 总个数  不为null时代表有数据  则输出data标签      当为null时 用于显示错误信息不输出data标签
     * @return array
     * @author guoqiang.zhang
     */
    public function getErrArray($errorStatus,$num=null,$total=null,$message=''){
        $nodeArray = array();
        $nodeArray['error'][0][DOM::ATTRIBUTES] = array(
                'type' => $errorStatus,
                'note' => $message,
                'servertime' => date("Y-m-d H:i:s"),
            );
		$nodeArray['data'][0][DOM::ATTRIBUTES]['language'] = 'zh-CN';
		if(!is_null($num))
		{
			$nodeArray['data'][0][DOM::ATTRIBUTES]['num'] = $num;
		}   
		if(!is_null($total))
		{
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
    public function GetMediaCategory($args){
        $nodeArray = array();
        $category = $this->category;
        if($category)
        {
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
        }
        else
        {
            $nodeArray = $this->getErrArray("false",0);
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
        }else{
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
			                $wikis[] = $wiki;
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
	                    );
	                    $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
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
                    );
                    $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
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
         public function getTagParentid($cid=0)
         {
            $mongo = sfContext::getInstance()->getMondongo();
            $wikiRepository = $mongo->getRepository("wiki");
            $category = $wikiRepository->getCategory();   
            foreach($category as $key=>$value){
                if($key==$cid){
                    return $key;
                }else{
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
         public function getSearchText($keyword=null,$cid=0,$filters=null,$model=null){
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
                     $range = $this->getRang($filter['__attributes__']['value']);
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
        
			$mongo = sfContext::getInstance()->getMondongo();
			$wikiRepository = $mongo->getRepository('wiki');
			$wrRepo = $mongo->getRepository("WikiRecommend");
			$wikiRecs = $wrRepo->getWikiByPageAndSize($page,$size,$tag);
			$totalWikiRecs = $wrRepo->getWikiByPageAndSize(0,9999,$tag);
            if($wikiRecs){
                $arr = $this->getErrArray("false", count($wikiRecs),count($totalWikiRecs));
                foreach($wikiRecs as $key => $wikiRec){
//					$wiki = $wikiRepository->findOneById(new MongoId($wikiRec['wiki_id']));
					$wiki = $wikiRepository->getWikiById($wikiRec['wiki_id']);
                    $arr['data'][0]['media'][$key][DOM::ATTRIBUTES] = array(
                       'id' => (string)$wiki->getId(),
                        'title'=> $wiki->getTitle(),
                    );
                    $arr = $this->getWikiVideoSource($wiki,$key, $arr);
                }
            }else{
                $arr = $this->getErrArray("false", null,null,'');
            }
        return $this->arrayToDom($arr);
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
		$size = $parameter['size'] ? $parameter['size'] : 10;
        $sort = $parameter['sort'] ? $parameter['sort'] : 'default';
        $detail = $parameter['detail'] ? $parameter['detail'] : false;
        
        $parameter_user = $arrs['parameter'][0]['user'][0]['__attributes__'];
        $userId=$parameter_user['huanid'] ? $parameter_user['huanid'] : '1234';
        
        $memcache = tvCache::getInstance();
        $memcache_key = md5('GetRecommendMedia'.",$size,$sort,$detail,$userId");
        $arrmd5 = $memcache->get($memcache_key);
        if(!$arrmd5){
            $url=sfConfig::get('app_lct_server_url')."api/media/user/$userId/recommendations";
            $url.="?size=$size&sort=$sort&detail=$detail";
            
            $contents=file_get_contents($url);
            if(!$contents){
                return $this->GetRecommendMediaFromMondongo($arrs);  //如果获取不到从原来获取
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
                $memcache->set($memcache_key,$arr); //设置memcache
            }else{
                //$arr = $this->getErrArray("false", null,null,'');
                return $this->GetRecommendMediaFromMondongo($arrs);  //如果获取不到从原来获取
            }            
        }else{
            $arr=$arrmd5;
        }
        return $this->arrayToDom($arr);
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
        $contents=file_get_contents($url);
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

        }else{
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
    public function getTag($tags,$arr){
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
                "size" => "120*160",
                "url" => thumb_url($cover, 120, 160),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][1][DOM::ATTRIBUTES] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => thumb_url($cover, 240, 320),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][2][DOM::ATTRIBUTES] = array(
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
     public function getWikiVideoSource($wiki,$i,$nodeArray,$type='',$biaozhi=0){
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
        $refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','tps'=>'tps');
        $source = '';
        $prefer = "奇艺"; //优选片源
        if ($videos != NULL) {
            foreach ($videos as $video) {
                $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
            }            
        }
        $whether_mark = (gettype($type) =='array')?true:false;
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
        $nodeArray['data'][0]['media'][$i]['description'] = $wiki->getContent();
        $cover = $wiki->getCover();
        if ($cover) {
            $nodeArray['data'][0]['media'][$i]['posters'][$i][DOM::ATTRIBUTES]['num'] = 3;
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][0][DOM::ATTRIBUTES] = array(
                "type" => "small",
                "size" => "120*160",
                "url" => thumb_url($cover, 120, 160),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][1][DOM::ATTRIBUTES] = array(
                "type" => "big",
                "size" => "240*320",
                "url" => thumb_url($cover, 240, 320),
            );
            $nodeArray['data'][0]['media'][$i]['posters'][$i]['poster'][2][DOM::ATTRIBUTES] = array(
                "type" => "max",
                "size" => "1240*460",
                "url" => thumb_url($cover, 1240, 460),
            );
        }
        //增加剧照显示lifucang(2012-7-18)
	    $screen_num = $wiki->getScreenshotsCount();        
        $nodeArray['data'][0]['media'][$i]['screens'][0][DOM::ATTRIBUTES]= array(
                        'num'    => $screen_num,
                );
        $screens = $wiki->getScreenshotUrls(288,217);   
        foreach($screens as $k => $screen)
        {
            $nodeArray['data'][0]['media'][$i]['screens'][0]['screen'][$k][DOM::ATTRIBUTES]= array(
                            'url'    =>  $screens[$k],
                    ); 
        }
        if($biaozhi==1){
            if($type['type']==1){
                $xianshi=false;
            }else{
                $xianshi=true;
            }
        }else{
            $xianshi=true;
        }
        if($xianshi){  //lfc增加
            $model = $wiki->getModel();
            if ($model == 'film') {
                $videos = $wiki->getVideos();
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
                $playLists = $wiki->getPlayList();
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
                                        $j++;
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
            
            if ($model == 'television') {
                $playLists = $wiki->getPlayList();
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
                                        $j++;
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
				"url" => ($referer=='qiyi')?$this->setVideoUrl($tvconfig['tvId']):$video->getUrl(),
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
		if (array_key_exists($referer, $refererSource)) 
		{
		    $source = $refererSource[$referer];
		}
		$url = ($referer == 'qiyi')?$this->setVideoUrl($tvconfig['tvId']):$video->getUrl();
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
    public function GetFilterOption($arrs) {
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
//        $wiki = $wikiRepository->findOneById(new MongoId($mid));
        $wiki = $wikiRepository->getWikiById($mid);
        if($wiki){
             $nodeArray = $this->getErrArray("false", 1,1);
             $nodeArray['data'][0]['media'][0][DOM::ATTRIBUTES] = array(
                'id' => $wiki->getId(),
                'title' => $wiki->getTitle(),
             );
             $nodeArray = $this->getWikiVideoSource($wiki, 0, $nodeArray);
        }else{
            $nodeArray = $this->getErrArray("false", null,null,'');
        }
        return  $this->arrayToDom($nodeArray);
    }
    
    /**
     * 通过关键字获得相应的结果
     * @param array $args
     * @author wangnan  
     * @return xml
     */
    public function SearchMedia(array $args) {
        $parameter = $args['parameter'][0]['data'][0]['__attributes__'];
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
        $page = $parameter['page'];
        $size = $parameter['size'];
        $offset = $size * ($page - 1);
        $total = NULL;
        $mongo = sfContext::getInstance()->getMondongo();
        $wikiRep = $mongo->getRepository('wiki');
        $result = $wikiRep->xun_search($keyWord, $total, (int)$offset, (int)$size,null,3); //按hasvideo排序
        $pageTotal = count($result);
        $arr = array();
        if(!$result){
            $arr = $this->getErrArray("false", 0,0);
        }
        else{
            $arr = $this->getErrArray("false", $pageTotal, $total);
            $i = 0;
            foreach($result as $res) {
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['id'] = $res->getId();
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES]['title'] = $res->getTitle();
                $arr = $this->getWikiVideoSource($res, $i, $arr);
                $i++;
            }
        }
        return $this->arrayToDom($arr);
    } 
    
    /**
     * GetMediaListByMedia
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
        $tags = $wiki->getTags();
        if (!empty($tags)) {
            $search_tags = array_map(function($tag) {
                return "tag:".$tag;
            }, $tags);
            $searchText = $this->getSearchText("(".implode(' OR ', $search_tags).")", 0, null);
            $movies = $this->getSearch($searchText['condition'], $page, $size);
        }
        if (isset($movies['result']) && !empty($movies['result'])) {
            $arr = $this->getErrArray("false",count($movies['result']),$movies['total']);
            $i = 1;
            foreach($movies['result'] as $wiki){
                $arr['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
                   'id' => (string)$wiki->getId(),
                   'title'=> $wiki->getTitle(),
                );
                $arr = $this->getWikiVideoSource($wiki, $i, $arr);
                $i++;
            } 
        }else{
            $arr = $this->getErrArray("false", 0);
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
         $userchips->setFindOptions($options);
         $userchips->setPage($page);
         $userchips->init();
         $chips = $userchips->getResults();
         if($chips){
             $arr = $this->getErrArray("false", count($chips), $userchips->getNbResults());
             foreach($chips as $key => $chip){
                  $wikiRepository = $mongo->getRepository("wiki");
                  $wiki = $wikiRepository->getWikiById($chip->getWikiId());
                  if($wiki)
                  {
	                  $arr['data'][0]['media'][$key][DOM::ATTRIBUTES]['id'] = $wiki->getId();
	                  $arr['data'][0]['media'][$key][DOM::ATTRIBUTES]['title'] = $wiki->getTitle();
	                  $arr = $this->getWikiVideoSource($wiki, $key, $arr);
                  }
             }
         }else{
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
					$nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
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
					$nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
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
        $device = $args['parameter'][0]['device'][0]['__attributes__'];
        $user = $args['parameter'][0]['user'][0]['__attributes__'];
        if(!$device['dnum']){
            //接口一律用huanid来识别用户
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
             return $this->arrayToDom($nodeArray);
        }
        if(!in_array($data['type'], array("1",'2','3','4'))){
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
                $nodeArray = $this->wikiScore($data,$device);
                break;
            case '2':
                $nodeArray = $this->AddChipByDevice($device, $data);
                break;
            case '3':
                $nodeArray = $this->deleteChipByDevice($device, $data);
                break;
            case '4':
                $nodeArray = $this->addHaveSeen($device, $data);
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
    public function wikiScore($data,$device){
		$nodeArray = $this->getErrArray("false",null,null,'');
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
				$nodeArray = $this->getErrArray("true",null,null,'该wiki不存在');
            }
        }
        else
        {
            $nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
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
            $nodeArray = $this->getErrArray("false",null,null,'');
            $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
                    'favorite'  =>  '0'
                );
        }else{
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
				$nodeArray = $this->getErrArray("false",null,null,'');
		        $nodeArray['data'][0][DOM::ATTRIBUTES] = array(
		                'favorite'  =>  '4'
		            );
		        return $nodeArray;
        	}
			else
        	{
			$nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
	        return $nodeArray;         		
        	}
        }
        else
        {
			$nodeArray = $this->getErrArray("true",null,null,'该wiki不存在');
	        return $nodeArray;        	
        }
    }

	/*
     * 获取电视频道列表
     * @param array
     * @return xml
     * @author wangnan
     */
    public function GetChannelList($args)
    {
    	$nodeArray = array();
    	$province = $args['parameter'][0]['data'][0]['__attributes__']['province'];
    	if(empty($province))
    		$channels = Doctrine::getTable('Channel')->getWeiShiChannels();
    	else
    		$channels = Doctrine::getTable('Channel')->getUserChannels('',$province);
		$nodeArray = $this->getErrArray("false",0);
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
				'hot'  => $channel['hot']
			);
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
					'wiki_cover' => file_url($wiki_info['cover']),
					'tags' => $wiki_info['tags'],
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
								'playtime' => $channel_recommend->getPlaytime(),
								'remark'   => $channel_recommend->getRemark(),
								'img'      => file_url($channel_recommend->getPic()),
                    		);
				$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray);
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
    	$province = $args['parameter'][0]['data'][0]['__attributes__']['province'];
    	$tag = $args['parameter'][0]['data'][0]['__attributes__']['tag'];
    	$starttime = $args['parameter'][0]['data'][0]['__attributes__']['start_time'];
    	$endtime = $args['parameter'][0]['data'][0]['__attributes__']['end_time'];
    	
    	if($starttime==''&&$endtime=='')$starttime = $endtime =date("Y-m-d H:i:s",time());
	    if(empty($province))
    		$channels = Doctrine::getTable('Channel')->getWeiShiChannels();
    	else
    		$channels = Doctrine::getTable('Channel')->getUserChannels('',$province);
		$i = 0;
		$nodeArray = $this->getErrArray("false",0);
		
		$mongo = sfContext::getInstance()->getMondongo();
		$ProgramRepository = $mongo->getRepository('Program');
		$WikiRepository = $mongo->getRepository('Wiki');
		
		$next = $ProgramRepository->getNextUpdate($channels,$endtime,$tag);
		$programs = $ProgramRepository->getPrograms($channels,$tag,$starttime,$endtime);unset($channels);
		foreach($programs as $program_key =>$program)
		{
//			$wiki = $WikiRepository->findOneById(new MongoId($program['wiki_id']));
			$wiki = $WikiRepository->getWikiById($program['wiki_id']);
			if($wiki) 
			{
				$channel = $program->getChannel();
				$nodeArray['data'][0]['media'][$i][DOM::ATTRIBUTES] = array(
					'id'    => $wiki->getId(),
					'title'   => $wiki->getTitle(),
					'start_time' => date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()),
					'end_time' => date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()),
					'channel_code'=>$channel->getCode(),
					'channel_name'=>$channel->getName(),
					'channel_logourl'=>$channel->getLogoUrl(),
				);
				$nodeArray = $this->getWikiVideoSource($wiki, $i, $nodeArray);
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
    	$device = $args['parameter'][0]['device'][0]['__attributes__'];
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
        	$userRepository = $mongo->getRepository('user');
        	$hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
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
							'remark' => $item->getRemark(),
							'img' => file_url($item->getImg()),
                    );
            $nodeArray = $this->getWikiVideoSource($wiki, $key, $nodeArray);
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
    	$page=empty($page)?1:$page;
    	$size=empty($size)?8:$size;
    	$countThemes = Doctrine::getTable('Theme')->getThemeByPageAndSize($page,$size);
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
    	$device  = $args['parameter'][0]['device'][0]['__attributes__'];
		
        if($this->isVarSet($device['dnum'],'dnum不为空')==false) return; 
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
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
        if($hasUser)
        {
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
        }
		else
        {
			$nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
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
		$marks = $urRepository->getUserMarkByObyId($eid);
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
				$urRepository -> createOneMark($user_id,'2',$mid,$eid,$flag);

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
    	$device  = $args['parameter'][0]['device'][0]['__attributes__'];

		if($this->isVarSet($device['dnum'],'请填写dnum')==false) return;         
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
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
		$device  = $args['parameter'][0]['device'][0]['__attributes__'];
        if($this->isVarSet($markid,'markid不为空')==false) return;
        
        $mongo = sfContext::getInstance()->getMondongo();
		$umRepository = $mongo->getRepository('UserMark');
        
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
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
        }else{
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
        }else{    
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
        }else{
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
     * 搜索建议
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
                }else{
                    $arr_use=array_rand($arr_value,5);
                    foreach($arr_use as $value) 
                    {
                        //if($i>=5)
                        //    break;
                        $arr['data'][0]['tag'][$i][DOM::ATTRIBUTES]['value'] = $arr_value[$value];
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
        if($sp)
        {
        	$channels = $sp->getChannelObjs();
        	$nodeArray = $this->getErrArray("false",null,count($channels));
        	
			foreach($channels as $key => $channel)
			{
  
				$nodeArray['data'][0]['channel'][$key][DOM::ATTRIBUTES] = array(
	                            'id'    => $channel->getId(),
	                            'name'  => $channel->getName(),
	                            'memo'  => $channel->getMemo(),
	                            'code'  => $channel->getCode(),
	                            'type'  => $channel->getType(),
	                            'logo'  => $channel->getLogoUrl() ,         
	            );
			}
        }else{    
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
		foreach($comments as $key=>$comment)
		{
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
        $nodeArray = $this->getErrArray("false",null,null,'');
        $device           = $args['parameter'][0]['device'][0]['__attributes__'];
    	$type             = $args['parameter'][0]['data'][0]['__attributes__']['type'];  //1:添加预约 2:删除预约
    	$channel_code     = $args['parameter'][0]['data'][0]['__attributes__']['channel_code'];
    	$name             = $args['parameter'][0]['data'][0]['__attributes__']['name'];  //节目名称
    	$start_time       = $args['parameter'][0]['data'][0]['__attributes__']['start_time'];
    	
        if(!$device['dnum']){
            //接口一律用huanid来识别用户
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($nodeArray);
        }
        if(!in_array($type, array('1','2'))){
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
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
            $ProgramUserRepository = $mongo->getRepository('Programe_user');
            $ProgramUser = new Programe_user();
	        switch($type){
	            case '1':
                    $rs=$ProgramUserRepository->SearchPrograme($user_id,$channel_code,$start_time);
                    if($rs){
                        $nodeArray = $this->getErrArray("true",null,null,'该用户已预约该节目');
                    }else{
                        $ProgramUser->add($user_id,$channel_code,$name,$start_time);
                    }
	                break;
	            case '2':
	                $ProgramUserRepository->del($user_id,$channel_code,$start_time);
	                break;
	            default:
	            	$nodeArray = $this->getErrArray("true",null,null,'type处出错');
	        }
        }else{
			$nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
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
        $device    = $args['parameter'][0]['device'][0]['__attributes__'];
        if(!$device['dnum']){
            //接口一律用huanid来识别用户
            $arr = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($arr);
        }

        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
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
                        $channel_name=$channel->getName();
                        $channel_logo=$channel->getLogoUrl();
                    }else{
                        $channel_name='';
                        $channel_logo='';
                    }                 
                    $arr['data'][0]['program'][$key][DOM::ATTRIBUTES] = array(
                        'channel_code' => $channel_code,
                        'channel_name'=> $channel_name,
                        'channel_logo' => $channel_logo,
                        'name'=> $programuser->getName(),
                        'start_time'=> date('Y-m-d H:i:s',$programuser->getStartTime()->getTimestamp()),                   
                    );
                }
            }else{
                $arr = $this->getErrArray("false", null,null,'');
            }
        }else{
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
        $device           = $args['parameter'][0]['device'][0]['__attributes__'];
    	$channel     = $args['parameter'][0]['data'][0]['__attributes__']['channel_code'];
    	
        if(!$device['dnum']){
            //接口一律用huanid来识别用户
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($nodeArray);
        }

        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
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
			$nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
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
        $province=Province::getProvinceAll();
        $city=Province::getCityAll();
        $total=count($province);
        
        $nodeArray = array();
        if($province)
        {
            $nodeArray = $this->getErrArray("false", $total);
            foreach($province  as $key =>$value)
            {
                
                $nodeArray['data'][0]['province'][$key][DOM::ATTRIBUTES] = array(
                   'name' => $value,
                   'value'=> $key
                );
                foreach($city[$key] as $index => $child)
                {
                    $nodeArray['data'][0]['province'][$key]['city'][$index][DOM::ATTRIBUTES] = array(
                        'name'    => $child,
                        'value' => $index,
                    );
                }
            }
        }
        else
        {
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
    		foreach($splist  as $key =>$value)
    		{
    		    if($value['logo']!='')
                    $logo=file_url($value['logo']);
                else
                    $logo='';    
    			$nodeArray['data'][0]['sp'][$key][DOM::ATTRIBUTES] = array(
    				'signal'   => $value['signal'],
    				'name'     => $value['name'],
    				'remark'   => $value['remark'],
    				'logo'     => $logo,
    			);
    		}            
        }else{
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
        $device           = $args['parameter'][0]['device'][0]['__attributes__'];
    	$province         = $args['parameter'][0]['data'][0]['__attributes__']['province'];
    	$city             = $args['parameter'][0]['data'][0]['__attributes__']['city'];  //节目名称
    	$dtvsp            = $args['parameter'][0]['data'][0]['__attributes__']['dtvsp'];
    	
        if(!$device['dnum']){
            //接口一律用huanid来识别用户
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
                     
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
        if($hasUser){
            $hasUser->setProvince($province);
            $hasUser->setCity($city);
            $hasUser->setDtvsp($dtvsp);
            $hasUser->save();
        }else{
			$nodeArray = $this->getErrArray("true",null,null,'该用户不存在');
        }               	
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
    	$device  = $args['parameter'][0]['device'][0]['__attributes__'];

        if(!$device['dnum']){
            //接口一律用huanid来识别用户
            $nodeArray = $this->getErrArray("true",null,null,'dnum处出错');
            return $this->arrayToDom($nodeArray);
        }     
        $mongo = sfContext::getInstance()->getMondongo();
        $userRepository = $mongo->getRepository('user');
        $hasUser = $userRepository->getUserIdByDeviceId($device['dnum']);
        if($hasUser){
			$user_id =  (string)$hasUser->getId();
			$nodeArray = $this->getErrArray("false",0);
			$nodeArray['data'][0][DOM::ATTRIBUTES] = array(
				'province' => $hasUser->getProvince(),
				'city'     => $hasUser->getCity(),
                'dtvsp'    => $hasUser->getDtvsp(),
	        );
        }else{
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
            $date=date('Y-m-d');        
        $arr_date=array(date('Y-m-d'),date('Y-m-d', strtotime("+1 day")));
        if(!in_array($date,$arr_date)){
            $nodeArray = $this->getErrArray("true",null,null,'date只能是当天和第二天的日期');
            return $this->arrayToDom($nodeArray);            
        }
        //获取缓存数据
        $memcache = tvCache::getInstance();
        $memcache_key = md5('GetAllChannelProgram'.$province.$date);
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
		foreach($channels  as $key =>$channel)
		{
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
	        foreach($programs as $key1 =>$program)
	        {
	            $wiki_info = $program->getWiki();
	            $hasVideo = ($wiki_info['has_video']>0)?'yes':'no';
	            $source = implode(',',$wiki_info['source']);
				$nodeArray['data'][0]['channel'][$key]['program'][$key1][DOM::ATTRIBUTES] = array(
					'name' => $program['name'],
					'date' => $program['date'],
					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
					'wiki_id' => $program['wiki_id'],
					'wiki_cover' => file_url($wiki_info['cover']),
					'tags' => $wiki_info['tags'],
					'hasvideo'=>$hasVideo,
					'source'=>$source,
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
}
?>
