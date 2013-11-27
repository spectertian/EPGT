<?php

/**
 * program actions.
 *
 * @package    epg
 * @subpackage mongo_program
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class programActions extends sfActions 
{
    /**
     * 
     * @param sfRequest $request A request object
     * @todo by zhigang 记住一个规则，action 只做自已应该做的事情，视图层需要的其他数据使用局部模板或组件调用
     * @todo 将选择频道的功能剥离出去
     * @todo 列表页面调用 Wiki 达100多次， 这是不正常的，找到原因并修复
     */
    public function executeIndex(sfWebRequest $request) 
    {
        //输出省级电视台
        $this->parentTvStations = Doctrine::getTable('TvStation')->getParentArray();
        //当前频道,当前时间获取
        $this->current_time = $request->getParameter('date', ( $this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d", time())));
        $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
        //节目提醒 保存看过的电视台 --暂停使用
        /*$setcookie = $request->getParameter('setcookie');
        if($setcookie&&$request->getParameter('channel_id')){
        	$cookie = $_COOKIE['channelids'];
        	if ($cookie){
        		$cookie = $cookie.','.$request->getParameter('channel_id');
        		setcookie('channelids',$cookie,time()+60*5);
        	}else {
        		setcookie('channelids',$request->getParameter('channel_id'),time()+60*5);
        	}
        }*/
        
        /**
         *  @todo 获取 TvStation 使用 $channel->getTvStation() 方法
         */
        //获取当前Channel对象
        $this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);
        $editortime = strtotime($this->channel->getEditorUpdate());  //编辑确认时间
        $updatetime = strtotime($this->channel->getTvsouUpdate());   //tvsou更新时间
        if($updatetime>strtotime(date('Y-m-d 2:00'))){
            $this->updatetime=$this->channel->getTvsouUpdate();
            if($editortime){
                if($editortime>$updatetime){
                    $this->update=false;
                }else{
                    $this->update=true;
                }
            }else{
                $this->update=true;
            }
        }else{
            $this->update=false;
            $this->updatetime='';
        }
        $this->channel_code=$this->channel->getCode();
        
		//昨日回顾和下周预告的style下拉列表框设置 
		//tianzhongsheng-ex@huan.tv 2013-06-20 10:26:00
        $this->style = array(
        						'230*350' => '230*350',
        						'470*350' => '470*350'
        					);
		  
        //if($this->channel){
            //反向查询当前CHANNEL的顶级电视台
            $tvStation = Doctrine::getTable('TvStation')->findOneById($this->channel->getTvStationId());
            $tvStation_id = ($tvStation->getParentId() == 0 ? $tvStation->getId() : $tvStation->getParentId());
            //输出当前默认电视台下的频道
            $tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tvStation_id);
            $this->channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
            //设置CHANNEL_ID
            //print_r($this->getUser()->getAttribute('channel_id'));echo "/n";
            $this->getUser()->setAttribute('channel_id', $this->channel->getId());
            
            //设置TvStation_id
            $this->getUser()->setAttribute('tv_station_id', $tvStation_id);
            //设置当前选择日期
            $this->getUser()->setAttribute('date',$this->current_time);
            //获得MONGO对象
            $mongo = $this->getMondongo();
            $program_mongo = $mongo->getRepository("Program");
            //获取当前节目表
            $this->programs = $program_mongo->getDayPrograms($this->channel->getCode(), $this->current_time);
        //}
    } 
    
    /**
     * 获取所有更新的频道
     *
     * @param sfRequest $request A request object
     * @return 
     */    
    public function executeSetChannelUpdate(sfWebRequest $request)
    {
        if($request->isMethod("POST")){
            $this->getUser()->setAttribute('setid',$request->getParameter('id'));
            $this->getUser()->setFlash('notice','设置保存成功');
        }
        $this->getUser()->setAttribute('getlist',1);
        //self::executeChannelUpdate($request);
        self::getchannel($request);
        $this->getUser()->setAttribute('getlist',0);
    }
    
    /**
     * 获取所有频道
     *
     * @param sfRequest $request A request object
     * @return 
     */ 
    public function getchannel($request)
    {
        $arr = array();
        $this->cctv_channels = Doctrine::getTable('Channel')->getAllChannelByTv('cctv');
        foreach($this->cctv_channels as $val){
            $arr[] = $val;
        }
        $this->tv_channels = Doctrine::getTable('Channel')->getAllChannelByTv('tv');
        foreach($this->tv_channels as $val){
            $arr[] = $val;
        }
        $this->channel_list = $arr;
    }
    
    /**
     * 更新选中的频道
     *
     * @param sfRequest $request A request object
     * @return 
     */ 
    public function executeChannelUpdate(sfWebRequest $request) 
    {
        $channels = Doctrine::getTable("Channel")->getChannels();
        $this->channel_list=array();
        //echo count($channels);exit;
        foreach ($channels as $channel){
            $editortime=strtotime($channel->getEditorUpdate());  //编辑确认时间
            $updatetime=strtotime($channel->getTvsouUpdate());   //tvsou更新时间
            if($updatetime>strtotime(date('Y-m-d 2:00'))){
                if($editortime){
                    if($editortime>$updatetime){
                        $update=false;
                    }else{
                        $update=true;
                    }
                }else{
                    $update=true;
                }
            }else{
                $update=false;
            }
            if($update){
                $getlist = $this->getUser()->getAttribute('getlist');
                $setid = $this->getUser()->getAttribute('setid');
                if(!$getlist){
                    if($setid){
                        if(in_array($channel->getId(), $setid)){
                            $this->channel_list[]=array('id'=>$channel->getId(),'code'=>$channel->getCode(),'name'=>$channel->getName(),'tvsouupdate'=>$channel->getTvsouUpdate(),'tvsouget'=>$channel->getTvsouGet());
                        }
                    }else{
                        $this->channel_list[]=array('id'=>$channel->getId(),'code'=>$channel->getCode(),'name'=>$channel->getName(),'tvsouupdate'=>$channel->getTvsouUpdate(),'tvsouget'=>$channel->getTvsouGet());
                    }
                }else{
                    //exit;
                    $this->channel_list[]=array('id'=>$channel->getId(),'code'=>$channel->getCode(),'name'=>$channel->getName(),'tvsouupdate'=>$channel->getTvsouUpdate(),'tvsouget'=>$channel->getTvsouGet());
                }
            }
        }
    } 
    
    /**
     * 获取所有待重新抓取的频道
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeChannelGet(sfWebRequest $request) {
        $channels = Doctrine::getTable("Channel")->getChannelsByTvsouGet();
        $this->channel_list=array();
        foreach ($channels as $channel){
            $this->channel_list[]=array('id'=>$channel->getId(),'code'=>$channel->getCode(),'name'=>$channel->getName(),'tvsouupdate'=>$channel->getTvsouUpdate(),'tvsouget'=>$channel->getTvsouGet());
        }
    } 
    
    /**
     * 对比本地和tvsou接口的区别
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeTvsou(sfWebRequest $request) 
    {
        //获取节目表的节目
        $this->date=$request->getParameter('date', ($this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d")));
        if($request->getParameter('day')=='pre'){
            $this->date = date('Y-m-d',strtotime($this->date)-86400);
        }
        $channel_code=$request->getParameter('channel_code','cctv1');
        $this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
		$this->channel_name = 'tvsou数据对-'.$this->channel->getName();	//频道名称
		$this->channel_ids =  $this->channel->getId();
    	$editortime=strtotime($this->channel->getEditorUpdate());  //编辑确认时间
        $updatetime=strtotime($this->channel->getTvsouUpdate());   //tvsou更新时间
        if($updatetime>strtotime(date('Y-m-d 2:00'))){
            $this->updatetime=$this->channel->getTvsouUpdate();
            if($editortime){
                if($editortime>$updatetime){
                    $this->update=false;
                }else{
                    $this->update=true;
                }
            }else{
                $this->update=true;
            }
        }else{
            $this->update=false;
        }
        $mongo = $this->getMondongo();
        $program_mongo = $mongo->getRepository("Program");        
        $this->programs = $programs = $program_mongo->getDayPrograms($channel_code, $this->date);
      
        //获取tvsou的节目
        $this->xml=null;
        $config=json_decode($this->channel->getConfig(),true);
        $channel_id=$config['tvsou']['channel_id'];
        $this->channel_code = $channel_code;
		$content = Common::get_url_content("http://hz.tvsou.com/jm/hw/hw8901.asp?id=".$channel_id."&Date=".$this->date,20);
        $content = str_replace('gb2312', 'gb18030', $content);
		$this->tv_sous = simplexml_load_string($content)->C;
		$local_tv_array = array();
		$tv_sou_array = array();
		foreach ($this->programs as $local) {	
		    $localName=str_replace(' ', '', $local->getName());
			$local_tv_array[$localName.$local->getTime()] = $localName;
		}
    	foreach ($this->tv_sous as $tvsou) {
			$tvsou = (array)$tvsou;
			if(strlen($tvsou['pn'])){
                $tv_sou_name = str_replace(' ', '', $tvsou['pn']);
			}
			if($tvsou['pt']){
                $tv_sou_array[$tv_sou_name.date('H:i',strtotime($tvsou['pt']))] = $tv_sou_name;
			}
		}		
		$diff_lcoal = array_diff_assoc($local_tv_array,$tv_sou_array);
		$diff_tv_sou = array_diff_assoc($tv_sou_array,$local_tv_array);
		$last = array_merge($diff_lcoal, $diff_tv_sou);
		$diff_array = array_keys($last);
		
		$local_array = array();
		$tv_array = array();
		foreach($this->programs as $k) {
			$re_array = array();
			$re_array['id'] = $k->getId();
			$re_array['name'] = str_replace(' ', '', $k->getName());
			$re_array['time'] = $k->getTime();
			$re_array['end_time'] = $k->getEndTime()?$k->getEndTime()->format("H:i"):'';
			$re_array['publish'] = $k->getPublish();
			$re_array['imgsrc'] = $k->getPublishImgSrc();
			$re_array['wiki_id'] = $k->getWikiId();
			$re_array['wiki_title'] = $k->getWikiTitle();
//			$tags = '';
//			foreach($k->getTags() as $tag)
//			{
//				$tags = $tag.',';
//			}
//			$re_array['tags'] = $tags;
			$re_array['tags'] = $k->getTags();
			$re_array['wiki_id'] = $k->getWikiId();
			$re_array['date'] = $k->getDate();
			$re_array['createdat'] = $k->getCreatedAt()?$k->getCreatedAt()->format("Y-m-d H:i:s"):'';
			$re_array['sort'] = $k->getSort();
			$re_array['updateat'] = $k->getUpdatedAt()?$k->getUpdatedAt()->format("Y-m-d H:i:s"):'';
			$re_array['color'] = (in_array($re_array['name'].$k->getTime(),$diff_array))?'red':'black';
			$local_array[] = $re_array;
		}
    	foreach($this->tv_sous as $tvsou) {
			$tvsou = (array)$tvsou;
			$re_array = array();
			if(strlen($tvsou['pn'])){
                $tv_sou_name = str_replace(' ', '', $tvsou['pn']);
			}
			$re_array['name'] = $tv_sou_name;
			if($tvsou['pt']){
                $re_array['time'] = date('H:i',strtotime($tvsou['pt']));
                $re_array['color'] = (in_array($tv_sou_name.date('H:i',strtotime($tvsou['pt'])),$diff_array))?'red':'black';
			}
			$tv_array[] = $re_array;
		}		
		$this->programs = $local_array;
		$this->tv_sous = $tv_array;
    } 
    
    /**
     * 昨日、今日的区别
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeTyvs(sfWebRequest $request) 
    {
        //获取节目表的节目
        //$date=$this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d");
        $date=date("Y-m-d");
        $this->y_date = date("Y-m-d",strtotime($date." -1day"));
        $channel_code=$request->getParameter('channel_code','cctv1');
        $this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
        $mongo = $this->getMondongo();
        $program_mongo = $mongo->getRepository("Program");        
        $this->t_programs = $program_mongo->getDayPrograms($channel_code, $date);
        $this->y_programs = $program_mongo->getDayPrograms($channel_code, $this->y_date);

    } 
    
    /**
     * 处理区别
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeTvsouOk(sfWebRequest $request) 
    {
        $channel_code=$request->getParameter('channel_code','');
        if($channel_code!=''){
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            $channel->setTvsouUpdate(null);
            $channel->setEditorUpdate(date('Y-m-d H:i:s'));
            $channel->save();
        }
        $this->redirect($request->getReferer());
    }   

    /**
     * 设置重新抓取标志
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeTvsouGet(sfWebRequest $request)
	{
        $channel_code = $request->getParameter('channel_code','');
        if($channel_code!='')
        {
        	$memcache = tvCache::getInstance();//先清理下缓存 否则直接取到cache里的缓存 set save没意义的
        	$memcache_key = 'Channel_findOneByCode_'.$channel_code;
        	$memcache -> delete($memcache_key);
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            $channel->setTvsouGet(1);
            $channel->setEditorUpdate(date('Y-m-d H:i:s'));
            $channel->save();
        }
        //$this->getUser()->setFlash("notice",'重新抓取成功!');
        $this->redirect($request->getReferer());
    }  
    
    /**
     * 取消重新抓取
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeTvsouGetNo(sfWebRequest $request) {
        $channel_code=$request->getParameter('channel_code','');
        if($channel_code!=''){
        	$memcache = tvCache::getInstance();
        	$memcache_key = 'Channel_findOneByCode_'.$channel_code;
        	$memcache -> delete($memcache_key);
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            $channel->setTvsouGet(0);
            $channel->setEditorUpdate(date('Y-m-d H:i:s'));
            $channel->save();
        }
        $this->redirect($request->getReferer());
    }          
    
    /**
     * AJAX查询所有频道
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeTvStationChannel(sfWebRequest $request) 
    {
        if ($request->isMethod("POST") && $request->isXmlHttpRequest()) {
            $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
            $topTvStation_id = $request->getParameter('id');
            $tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($topTvStation_id);
            $channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
            return $this->renderText(json_encode($channels));
        }
    }

    /**
     * 删除节目
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeDelete(sfWebRequest $request)
    {
        if($request->isMethod("POST")) {
            $ids = $request->getPostParameter('ids');
            if(count($ids)==0) {
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
            }else{
                foreach($ids as $id){
                   $mongo = $this->getMondongo();
                   $program_mongo = $mongo->getRepository("Program");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   $program->delete();
                }
               $this->getUser()->setFlash("notice",'删除成功!');
            }
        }
        $this->redirect($this->generateUrl('',array('module'=>'program','action'=>'index')));
    }

    /**
     * AJAX删除节目
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeDeleteAjax(sfWebRequest $request)
    {
        if ($request->isMethod("POST") && $request->isXmlHttpRequest()) {
            $ids = $request->getParameter('ids');
            $ids = rtrim($ids,',');
            $ids = explode(',',$ids);
            //return $this->renderText(implode(',',$ids));
            if(count($ids)==0) {
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
               return $this->renderText(0);
            }else{
                foreach($ids as $id){
                   $mongo = $this->getMondongo();
                   $program_mongo = $mongo->getRepository("Program");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   //return $this->renderText(new MongoId($id));
                   $program->delete();
                }
                $this->getUser()->setFlash("notice",'删除成功!');
                return $this->renderText(1);
            }
        }
    }
    
    /**
     * 发布节目
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executePublish(sfWebRequest $request)
    {
        if($request->isMethod("POST")) {
            $ids = $request->getPostParameter('ids');
            $publish = $request->getPostParameter('publish',0);
            if(count($ids)==0){
                $this->getUser()->setFlash("error",'操作失败！请选择需要发布的节目！');
            }else{
                foreach($ids as $id){
                    $mongo = $this->getMondongo();
                    $program_mongo = $mongo->getRepository("Program");
                    $program = $program_mongo->findOneById(new MongoId($id));
                    $program->setPublish($publish);
                    $program->save();
                }
                $this->getUser()->setFlash("notice",'操作成功!');
            }
        }
        $this->redirect($this->generateUrl('',array('module'=>'program','action'=>'index')));
    }
    
    /**
     * 只修改此节目开始时间
     * @author gaobo
     */
    public function executeSaveStartTime(sfWebRequest $request)
    {
        if($request->isMethod("POST") && $request->isXmlHttpRequest()) {
            $this->getResponse()->setHttpHeader("Content-type",'application/json;charset=UTF-8');
            $id = $request->getParameter('id', 0);
            $start_time = $request->getParameter('start_time', 0);
        }
        if($id && $start_time){
            $mongo   = $this->getMondongo()->getRepository("Program");
            $program = $mongo->findOneById(new MongoId($id));
            $dateTime = $program->getDate().' '.$start_time;
            $program->setTime($start_time);
            $program->setStartTime(new DateTime($dateTime));
            $program->save();
        }
    }
    
    /**
     * 保存节目
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeSave(sfWebRequest $request)
    {
        if($request->isMethod("POST") && $request->isXmlHttpRequest()) {
            $this->getResponse()->setHttpHeader("Content-type",'application/json;charset=UTF-8');
            $id = $request->getParameter('id', 0);
            $program_name = trim($request->getParameter('name',''));
            $channel_id = $request->getParameter('channel_id',0);
            $publish = $request->getParameter('publish',0);
            $play_time = trim($request->getParameter("time",0));
            $play_date = trim($request->getParameter('date',0));//实际就是开始时间里的Y-m-d 目前列表中“创建日期列”显示状态是创建日期 编辑状态显示的是播放日期
            $wiki_id = trim($request->getParameter('wiki_id',''));
            $wiki_tags = trim($request->getParameter('tags',0));
            $sort = trim($request->getParameter('sort',0));
            $end_time = trim($request->getParameter('end_time',0));
            $TvsouId = '';
            
            if(empty($program_name)){
                return '';
            }
            
            $channel = Doctrine::getTable('Channel')->findOneById($channel_id);
            $channel_code = $channel->getCode();
            $channel_type = array($channel->getType());
            $channel_province = $channel->getProvince();
            $channel_city = $channel->getCity();
            if($channel_province){
                array_push($channel_type,$channel_province);
            }
            if($channel_city){
                array_push($channel_type,$channel_city);
            }
            $return_status = array('program_id'=> false);

            $mongo =  $this->getMondongo();
            //将Tags最后一位为空的字符串弹出数组
            if($wiki_tags){
                $tags = explode(",",$wiki_tags);
                if (strlen($tags[( count($tags) -1 )]) == 0) {
                    array_pop($tags);
                }
            }else{
                $tags=null;
            }
            $program_mongo = $mongo->getRepository("Program");
            if ($id == 0) {
               $program = new Program();
            }else{
               
               $program = $program_mongo->findOneById(new MongoId($id));
               $TvsouId = $program->getTvsouId();
            }
            
			$dateTime = $play_date.' '.$play_time;
			$endDateTime = $play_date.' '.$end_time;
            $program->setStartTime(new DateTime($dateTime));//add by wn
            $program->setName($program_name);
            $program->setTime($play_time);
            $program->setPublish($publish);
            $program->setDate($play_date);
            $program->setChannelCode($channel_code);
            $checkEndTime = $program->getEndTime();
            $program->setEndTime($endDateTime);
            $program->setTags($tags);
            
            $repository = $mongo->getRepository('Wiki');
            if($wiki_id !='')
            {
	            $wiki = $repository->findOneById(new MongoId($wiki_id));
	            if($wiki){
	                $program->setWikiId($wiki_id);
	            }else{
	                $wiki_id = '';
	            }
            }

            if($sort){
            	$program->setSort($sort);
            }else if ($wiki_id != '' && !($program->getSort())){
            	$liveRecRep = $mongo -> getRepository('WikiLiverecommend');
            	if($liveRecRep -> findOne(array('query'=>array('wiki_id'=>$wiki_id)))){
            		$program -> setSort(1);
            		$return_status['sort']='1';
            	}
            }
            
            $program->setChannelType($channel_type);
			
            $admin = $this->getUser()->getAttribute("username");
            $program->setAdmin($admin);
            $program->save();
            if($checkEndTime != $endDateTime){
                self::setEndTime($channel_code, $play_date);
            }
            //echo $wiki_id,'#',$TvsouId;exit;
            //自动为以后节目匹配wiki  edit by gaobo at 20130711 
            if($TvsouId && $wiki_id!='')
            {
            	$program_mongo->autoWikiByTvsouid($dateTime,$channel_code,$wiki_id,$TvsouId);//按tvsouId匹配
            }//elseif($wiki_id!=''){暂时只按照tvsouId匹配，名称匹配暂时弃用
             //  $program_mongo->autoWiki($dateTime,$channel_code,$wiki_id,$program_name);    //按名称匹配
            //}
            //添加到editor_memory表:lfc
            if($wiki_id != '')
            {
                //首先更改wiki表里的tvsou_id
                $tvsou_id=$program->getTvsouId();
                if($tvsou_id)
                {
                    //先去掉其他的tvsouid
					/**wiki中tvosou_id为空才保存tvsou_id tianzhongsheng-ex@huan.tv 2013-08-09 18:40
                    $wikis = $repository->find(array('query'=>array('tvsou_id'=>(string)$tvsou_id)));
                    if($wikis)
                    {
	                    foreach($wikis as $wiki)
	                    {
	                        $wiki -> setTvsouId(null);
	                        $wiki -> setAutoUpdate(false);
	                        $wiki -> save(false);
	                    }
                    }
					*/
                    $wikia = $repository->findOneById(new MongoId($wiki_id));
                    if(!$wikia->getTvsouId()) //tianzhongsheng-ex@huan.tv 2013-08-09 18:40
                    {
                    	$wikia -> setTvsouId($tvsou_id);
	                    $wikia->  setAutoUpdate(false);
	                    $wikia -> save();
                    }
                                   
                }else{
                    $editormemory = $mongo->getRepository('EditorMemory');
                    $program_name = $this->getFilterProgramName($program_name);
                    $query = array('query' => array( "program_name" => $program_name,"channel_code" => $channel_code ));
                    $rs = $editormemory->findOne($query);
                    if(!$rs)
                    {
                        $memory = new EditorMemory();
                        $memory->setChannelCode($channel_code);
                        $memory->setProgramName($program_name);
                        $memory->setWikiId($wiki_id);
                        $memory->save();                        
                    }else{  
                        //$rs->setTags($tags);
                        $rs->setWikiId($wiki_id);
                        $rs->save();
                    }     
                }
            }
            //添加到editor_memory表
            //更新频道时间
            $channel_updateres = $mongo->getRepository('ChannelUpdate');
            $querya = array('query' => array( "channel_code" => $channel_code ));
            $channel_update = $channel_updateres->findOne($querya);
            if(!$channel_update){
                $channel_update = new ChannelUpdate();
                $channel_update -> setChannelCode($channel_code);
            }
            $channel_update -> setTime(new DateTime());
            $channel_update -> save();
                        
            $this->getUser()->setFlash("notice",'操作成功!');
            $return_status['program_id'] = (string)$program->getId();
            return $this->renderText(json_encode($return_status));
        }
    }

    /**
     * 按照名称返回wiki
     *
     * @param sfRequest $request A request object
     * @return
     * @todo 给为从xunsearch里面检索
     */
    public function executeLoadWiki(sfWebRequest $request)
    {
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");//exit('a');
        //$this->wikis = $wiki_mongo->likeWikiName($query);
        //exit($query);
        $total = NULL;
        $condition = 'title:"'.$query.'"';//exit('a');
        $this->wikis = $wiki_mongo->xun_search($condition,$total,0,50,null,4);
    }

    /**
     * 默认页面
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeDefault(sfWebRequest $request)
    {
        $this->getUser()->setAttribute('channel_id', '');
        $this->getUser()->setAttribute('tv_station_id', '');
        $this->getUser()->setAttribute('date','');
        $this->redirect('program/index');
    }
    
    /**
     * 根据字段名称，修改字段值
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_update(sfWebRequest $req) 
    {
        $return = array('code'=>0, '非法请求');
        $name   = $req->getParameter('key');
        $value  = $req->getParameter('value');
        $id     = $req->getParameter('id');
        $allow  = array('name','time','wiki_title','tags');
        if(!in_array($name, $allow)) {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '非法字段')));
        }
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program');
        
        if($name == 'wiki_title') $name = 'wikiTitle';
        if($name == 'tags')
        {
        	$value = explode(',',$value);
        	$value = array_filter($value);
        }
        $return = $repository->ajaxUpdate($id, $name, $value);
        return $this->renderText(json_encode($return));
    }
    
    /**
     * 删除节目
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_del(sfWebRequest $req) 
    {
        $return = array('code'=>0, '非法请求');
        $id     = $req->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
        $program = $repository->findOneById(new mongoId($id));
        if($program) {
        	$program->delete();
        	$return = array('code'=>1, 'msg'=>'删除成功');
        }
        return $this->renderText(json_encode($return));
    }
    
 	/**
     * 删除wiki
     * @param sfWebRequest $req
     * @return <type>
     * @author tianzhongsheng-ex@huan.tv 
     * @time 2013-07-18 10:59:00
     */
	public function executeDelWiki(sfWebRequest $request)
    {
        if($request->isMethod("POST")) {
            $ids = $request->getPostParameter('ids');
            if(count($ids)==0)
            {
               $this->getUser()->setFlash("error",'维基删除失败！');
            }else{
                foreach($ids as $id)
                {
                   $mongo = $this->getMondongo();
                   $program_mongo = $mongo->getRepository("Program");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   if($program){
                       $program->setWikiId();
                       $program->save();
                   }
                }
               $this->getUser()->setFlash("notice",'维基删除成功!');
            }
        }
        $this->redirect($this->generateUrl('',array('module'=>'program','action'=>'index')));
    }
	/**
     * 删除单个wiki
     * @param sfWebRequest $req
     * @return <type>
     * @author tianzhongsheng-ex@huan.tv 
     * @time 2013-07-25 12:59:00
     */
    public function executeDelWikiId(sfWebRequest $request) 
    {
		$id = strval($request->getParameter('id'));
		$mongo = $this->getMondongo();
		$program_mongo = $mongo->getRepository("Program");
		$this->programs = $program_mongo->findOneById(new MongoId($id));

		if($this->programs)
		{
			$this->programs->setWikiId();
            $this->programs->save();
			$this->getUser()->setFlash("notice",'维基删除成功!');
			$this->redirect($request->getReferer());

		}
		$this->getUser()->setFlash("error",'该记录不存在!');
		$this->redirect($request->getReferer());
    }
    
    /**
     * 获取所有更新的epg频道
     * 从这开始是需要在正式服务器上加的方法
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeEpgUpdate(sfWebRequest $request) 
    {
        $channels = Doctrine::getTable("Channel")->getChannels();
        $this->channel_list=array();
        foreach ($channels as $channel){
            $editortime=strtotime($channel->getEditorUpdate());  //编辑确认时间
            $updatetime=strtotime($channel->getEpgUpdate());   //epg更新时间
            if($updatetime>strtotime(date('Y-m-d 00:00'))){
                if($editortime){
                    if($editortime>$updatetime){
                        $update=false;
                    }else{
                        $update=true;
                    }
                }else{
                    $update=true;
                }
            }else{
                $update=false;
            }
            if($update){
                $this->channel_list[]=array('id'=>$channel->getId(),'code'=>$channel->getCode(),'name'=>$channel->getName(),'editor_update'=>$channel->getEditorUpdate(),'epg_update'=>$channel->getEpgUpdate(),'epg_get'=>$channel->getEpgGet());
            }
        }
    } 
    
    /**
     * 和program_temp比较区别
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeEpg(sfWebRequest $request) 
    {
        //获取节目表的节目
        $date=date("Y-m-d");
        $channel_code=$request->getParameter('channel_code','cctv1');
        $this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
        $mongo = $this->getMondongo();
        //获取本地节目
        $program_mongo = $mongo->getRepository("Program");        
        $this->programs = $program_mongo->getDayPrograms($channel_code, $date);
        //获取epg的节目
        $programtemp_mongo = $mongo->getRepository("ProgramTemp");        
        $this->programTemps = $programtemp_mongo->getDayPrograms($channel_code, $date);
    } 
    
    /**
     * 编辑确认区别
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeEpgOk(sfWebRequest $request) 
    {
        $channel_code=$request->getParameter('channel_code','');
        if($channel_code!=''){
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            //$channel->setEpgUpdate(null);
            $channel->setEditorUpdate(date('Y-m-d H:i:s'));
            $channel->save();
        }
        $this->redirect($request->getReferer());
    } 
    
    /**
     * 重新抓取
     *
     * @param sfRequest $request A request object
     * @return 
     */
    public function executeEpgGet(sfWebRequest $request) 
    {
        $channel_code=$request->getParameter('channel_code','');
        if($channel_code!=''){
            //将program_temp中该频道下今天及以后的记录都导入到program
            $mongo = $this->getMondongo();
            $program_repository = $mongo->getRepository("Program"); 
            $programtemp_mongo = $mongo->getRepository("ProgramTemp");  
            for($days = 0; $days < 10 ; $days ++) {
                $date = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));
                $programTemps = $programtemp_mongo->getDayPrograms($channel_code, $date);
                if($programTemps){
                    //移除当天的数据 
                    $program_repository->removeDayPrograms($channel_code, $date);
                    foreach($programTemps as $programTemp){
                        $program = new Program();
                        $program->setChannelCode($programTemp->getChannelCode());
                        $program->setName($programTemp->getName());
                        $program->setStartTime($programTemp->getStartTime());
                        $program->setEndTime($programTemp->getEndTime());
                        $program->setTime($programTemp->getTime());
                        $program->setDate($programTemp->getDate());
                        $program->setWikiId($programTemp->getWikiId());  
                        $program->setTvsouId($programTemp->getTvsouId());
                        $program->setTags($programTemp->getTags());
                        $program->setPublish($programTemp->getPublish());
                        $program->setSort($programTemp->getSort());                       
                        $program->save();
                    }
                }

            }
            //将临时表中节目当天及以后的数据删除
            $datenow=date("Y-m-d");
            $programtemp_mongo->removeDaysPrograms($channel_code, $datenow);
            //修改channel表编辑更新时间
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            $channel->setEpgGet(1);
            $channel->setEditorUpdate(date('Y-m-d H:i:s'));
            $channel->save();
        }
        $this->redirect($request->getReferer());
    }   
    
    /**
     * ajax方式获取节目更新提醒
     *
     * @param sfRequest $request A request object
     * @return 
     */ 
    public function executeProgramNotice(sfWebRequest $request)
    {
    	$channels = Doctrine::getTable('Channel')->getChannels('cctv'); 
    	$date = date('Y-m-d',time());
    	foreach ($channels as $channel) {
    		$editortime=strtotime($channel->getEditorUpdate());  //编辑确认时间
    		$updatetime=strtotime($channel->getTvsouUpdate());   //tvsou更新时间
    		
    		if($updatetime>strtotime(date('Y-m-d 2:00'))){
    			if($editortime){
    				if($editortime>$updatetime){
    					continue;
    				}else{
    					$changedChannels[] = array('name'=>$channel->getName(),'id'=>$channel->getId());
    					continue;
    				}
    			}else{
    				$changedChannels[] = array('name'=>$channel->getName(),'id'=>$channel->getId());
    				continue;
    			}
    		}
    		
    	}
    	//过滤已经确认过的电视台
    	$cookieChannelIds = $_COOKIE['channelids'];
    	if($cookieChannelIds && $changedChannels){
    		$cookieChannelIds = explode(',', $cookieChannelIds);
    		foreach ($changedChannels as $changedChannel) {
    			if (!array_search($changedChannel['id'], $cookieChannelIds)){
    				$array[] = $changedChannel;
    			}
    		}
    	}else{
    		$array = $changedChannels;
    	}
    	if (isset($array)){
    		setcookie('hasview',1,time()+60*5);
    		return $this->renderText(json_encode($array));
    	}else {
    		return false;
    	}
    }
    
	/**
     *
     * @desc 入库到editor_memory表中的program_name过滤后返回的值
     * @param string
     * @return string
     * @author tianzhongsheng-ex@huan.tv 
     * @time 2013-08-07 16:49:00
     */
	public function getFilterProgramName($program_name)
    {
        //直接返回不再进行正则匹配的节目名称
    	$not_filter = array(
    			'/^非常静距离:/' => '非常静距离',
				'/^经济信息联播:/' => '经济信息联播',
				'/^寰宇万象:/' => '寰宇万象',
				'/^快乐大本营:/' => '快乐大本营',
				'/^非常了得:/' => '非常了得',
				'/^寰宇视野:/' => '寰宇视野',
				'/^生活早参考:/' => '生活早参考',
				'/^国学堂:/' => '	国学堂',
				'/^金牌调解:/' => '金牌调解',
				'/^传奇故事:/' => '传奇故事',
				'/^档案午间版:/' => '档案午间版',
				'/^天天高尔夫:/' => '天天高尔夫',
				'/^百家讲坛:/' => '百家讲坛',
				'/^收藏大讲堂:/' => '收藏大讲堂',
				'/^挑战名人墙:/' => '挑战名人墙',
				'/^艺眼看世界:/' => '艺眼看世界',
				'/^收藏马未都:/' => '收藏马未都',
				'/^军情第一线:/' => '军情第一线',
				'/^人文地理:/' => '人文地理',
				'/^探索·发现:/' => '探索·发现',
				'/^走近科学:/' => '走近科学',
				'/^2013年世界羽毛球锦标赛/' => '2013年世界羽毛球锦标赛',
				'/^快乐汉语:/' => '快乐汉语',
				'/^民族底片:/' => '民族底片',
				'/^文化大百科:/' => '文化大百科',
				'/^名段欣赏:/' => '名段欣赏',
				'/^绝版赏析:/' => '绝版赏析',
				'/^传奇人物:/' => '传奇人物',
				'/^终极探索:/' => '终极探索',
				'/^农业新天地:/' => '农业新天地',
				'/^调解面对面:/' => '调解面对面',
				'/^CCTV空中剧院:/' => 'CCTV空中剧院',
				'/^致富经:/' => '	致富经',
				'/^万象:/' => '万象',
				'/^每日农经:/' => '每日农经',
				'/^国宝档案:/' => '国宝档案',
				'/^书画人生:/' => '书画人生',
				'/^跟富老师学:/'=>"跟富老师学",
				'/^超级魔术师:/' => '超级魔术师',
				'/^环宇搜奇:/' => '环宇搜奇',
				'/^心在旅途:/' => '心在旅途',
				'/^中国旅游:/' => '中国旅游',
				'/^画中话:/' => '	画中话',
				'/^旅游新天地:/' => '旅游新天地',
				'/^魅力世界:/' => '魅力世界',
				'/^想钓鱼跟我走:/' => '想钓鱼跟我走',
				'/^快意TALK吧:/' => '	快意TALK吧:',
				'/^八卦车谈:/' => '八卦车谈',
				'/^藏友三家谭:/' => '藏友三家谭',
				'/^娱乐梦工厂:/' => '娱乐梦工厂',
				'/^钓赛进行时:/' => '钓赛进行时',
				'/^钓赛进行时:/' => '钓赛进行时',
				'/^户外生活:/' => '户外生活',
				'/^老梁观世界:/' => '老梁观世界',
				'/^爱情传送带:/' => '爱情传送带',
				'/^老梁故事汇:/' => '老梁故事汇',
				'/^芝麻开门:/' => '芝麻开门',
				'/^旅行者:/' => '	旅行者',
				'/^纪录片编辑室:/' => '纪录片编辑室',
				'/^喜剧一箩筐:/' => '喜剧一箩筐',
				'/^电视书苑:/' => '电视书苑',
				'/^凡人大爱:/' => '凡人大爱',
				'/^故事里的事:/' => '故事里的事',
				'/^九州大戏台:/' => '九州大戏台',
				'/^明星车模:/' => '明星车模',
				'/^立辨真伪:/' => '立辨真伪',        
				'/^皇室追踪:/' => '皇室追踪',
				'/^梨园春秋:/' => '梨园春秋',        
				'/^综艺喜乐汇:/' => '综艺喜乐汇',
				'/^中国京剧音配像精粹:/' => '中国京剧音配像精粹',        
				'/^超级访问:/' => '超级访问',
				'/^非你莫属:/' => '非你莫属',        
				'/^金牌调解:/' => '金牌调解',
				'/^世界大力士中国争霸赛:/' => '世界大力士中国争霸赛',        
				'/^生活·帮:/' => '生活·帮',
				'/^财经郎眼:/' => '财经郎眼',       
				'/^我要上春晚:/' => '我要上春晚',      
				'/^中国梦之声:/' => '中国梦之声',      
				'/^杨澜访谈录:/' => '杨澜访谈录',      
				'/^非诚勿扰::/' => '非诚勿扰',      
				'/^曾经的财富传奇:/' => '曾经的财富传奇',      
				'/^首席夜话:/' => '首席夜话',      
				'/^非常静距离:/' => '非常静距离', 
				'/^星光大道:/' => '星光大道',      
				'/^谁是我家人:/' => '谁是我家人',      
				'/^波士堂:/' => '波士堂',      
				'/^购时尚:/' => '购时尚',      
				'/^大家说健康:/' => '大家说健康',      
				'/^高尔夫赛事集锦:/' => '高尔夫赛事集锦',      
				'/^谢天谢地，你来啦:/' => '谢天谢地，你来啦',      
				'/^男左女右:/' => '男左女右',      
				'/^快乐男声\S*:/' => '快乐男声',      
				'/^超级演说家:/' => '超级演说家',      
				'/^看电影学英语:/' => '看电影学英语',      
				'/^足球华彩:/' => '足球华彩',
        );
        
        if(empty($program_name)) return $program_name;
		$ignores = array('/笑动*2013/','/快乐*365/');	//特殊的匹配
		foreach($ignores as $v)
		{
			$stats =  preg_match($v, $program_name);
			if($stats) return $program_name;
	
		}
		foreach($not_filter as $k => $v)
		{
			$stats =  preg_match($k, $program_name);
			if($stats) return $v;
	
		}
        #先将中文符号转化为英文符号
        $repale_array = array(
        					'：'=>':',
        					'，'=>',',
        					'('=>'(',
        					')'=>')',
        				);
		foreach($repale_array  as $k => $v)
		{
			$program_name = str_replace($k,$v,$program_name);
		}

        $patterns = array('/\d+―\d+/','/\(.*\)/','/、/','/ \d+/','/（.*）/','/^(\d+)$/','/\d+\/\d+/','/\d{2,3}$/','/\d+―\d+/','/\S+影院:*/','/\S+剧苑:*/',
                          '/电视剧:*/','/精华版:*/','/大结局:*/','/精装版:*/','/精编版:*/','/首播:*/','/复播:*/','/复:*/','/\(重播\)/','/中央台/','/第一动画乐园:*/', '/\S+大剧院:*/','/重播:*/','/转播/','/中央台/','/连续剧/',
                          '/故事片:*/','/译制片:*/','/动画片:*/','/\S+剧场(:|-)*/','/;提示/','/转播中央/','/前情提要:*/','/</','/>/','/《/','/》/',
                          '/第.*集/','/.*集:*/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/香港电影:*/','/儿童剧-{1,}/','/宣传片-/');
        $program_name = preg_replace($patterns, "", $program_name);          
        
		//希腊字母转换
        $repale_xila_array = array(
        					'Ⅰ'=>'1',
        					'Ⅱ'=>'2',
        					'Ⅲ'=>'3',
        					'Ⅳ'=>'4',
        					'Ⅴ'=>'5',
        					'Ⅵ'=>'6',
        					'Ⅶ'=>'7',
							'Ⅷ'=>'8',
        					'Ⅸ'=>'9',
        					'Ⅹ'=>'10'
        				);
		foreach($repale_xila_array  as $k => $v)
		{
			$program_name = str_replace($k,$v,$program_name);
		}
        return $program_name;
    }
    
    /**
     * 重新设置频道某天的结束时间
     * @author lifucang 2013-08-07
     */
    public function executeSetEndTime(sfWebRequest $request) 
    {
        //获取节目表的节目
        $date=$request->getParameter('date',date("Y-m-d"));
        $channel_code=$request->getParameter('channel_code',null);
        $prev_program = null;
        if($channel_code){
            $mongo = $this->getMondongo();
            $program_repostitory = $mongo->getRepository("Program");        
            $programs = $program_repostitory->getDayPrograms($channel_code, $date);
	        foreach($programs as $program) {
	            if(!$prev_program) {
	                $prev_program = $program;
	                continue;
	            }
	            $prev_program->setEndTime($program->getStartTime());
	            $prev_program->save();
	            $prev_program = $program;
	        }
	        //当天最后一条end_time取隔天第一条start_time
	        $next_program = $program_repostitory->findOne(
	                            array(
	                                'query' => array('channel_code' => $channel_code, 'date' => date('Y-m-d', strtotime('+1 day'))),
	                                'sort' => array('time' => 1)
	                            )
	                        );
	        if($next_program) {
	            $prev_program->setEndTime($next_program->getStartTime());
	            $prev_program->save();
	        }
	        
	        //重新计算此频道下正在直播节目信息，入program_live， add by gaobo
	        $programlive_repository = $mongo->getRepository("ProgramLive");
	        $starttime  = date("Y-m-d H:i");
	        $starttimes = strtotime($starttime);
	        $program = $program_repostitory->getLiveProgramByCode($channel_code);
	        $next_program = $program_repostitory->getNextProgram(array($channel_code));
	        $programLive = $programlive_repository->getProgramByCode($channel_code);
	        if($programLive){
	            $programLive->setName($program->getName());
                $programLive->setStartTime($program->getStartTime());
                if($program->getEndTime()){
                	$programLive->setEndTime($program->getEndTime());
                }else{
                	$l_endtime = date("Y-m-d H:i",$starttimes+60*30);
                	$programLive->setEndTime($l_endtime);
                }
                if($program->getWikiId()){
                    $wiki_repository = $mongo->getRepository('wiki');
                    $wiki = $wiki_repository->findOneById(new MongoId($program->getWikiId()));
                    if($wiki) {
                        $programLive->setWikiCover($wiki->getCover());
                        $programLive->setWikiTitle($wiki->getTitle());
                        $programLive->setWikiId((string)$wiki->getId());
                    }
                }
                if($next_program[0]){
                    $programLive->setNextName($next_program[0]->getName());
                }else{
                    $programLive->setNextName("未知");
                }
                $programLive->save();
	        }
	        
            $this->getUser()->setFlash("notice",'设置完毕!');
        }else{
            $this->getUser()->setFlash("error",'频道号错误!');
        }
        $this->redirect($request->getReferer());
    }
    
    private function setEndTime($channel_code,$date)
    {
        $prev_program = null;
        if($channel_code){
            $mongo = $this->getMondongo();
            $program_repostitory = $mongo->getRepository("Program");
            $programs = $program_repostitory->getDayPrograms($channel_code, $date);
            foreach($programs as $program) {
                if(!$prev_program) {
                    $prev_program = $program;
                    continue;
                }
                $prev_program->setEndTime($program->getStartTime());
                $prev_program->save();
                $prev_program = $program;
            }
            //当天最后一条end_time取隔天第一条start_time
            $next_program = $program_repostitory->findOne(
                    array(
                            'query' => array('channel_code' => $channel_code, 'date' => date('Y-m-d', strtotime('+1 day'))),
                            'sort' => array('time' => 1)
                    )
            );
            if($next_program) {
                $prev_program->setEndTime($next_program->getStartTime());
                $prev_program->save();
            }
        
            //重新计算此频道下正在直播节目信息，入program_live， add by gaobo
            $programlive_repository = $mongo->getRepository("ProgramLive");
            $starttime  = date("Y-m-d H:i");
            $starttimes = strtotime($starttime);
            $program = $program_repostitory->getLiveProgramByCode($channel_code);
            $next_program = $program_repostitory->getNextProgram(array($channel_code));
            $programLive = $programlive_repository->getProgramByCode($channel_code);
            if($programLive){
                $programLive->setName($program->getName());
                $programLive->setStartTime($program->getStartTime());
                if($program->getEndTime()){
                    $programLive->setEndTime($program->getEndTime());
                }else{
                    $l_endtime = date("Y-m-d H:i",$starttimes+60*30);
                    $programLive->setEndTime($l_endtime);
                }
                if($program->getWikiId()){
                    $wiki_repository = $mongo->getRepository('wiki');
                    $wiki = $wiki_repository->findOneById(new MongoId($program->getWikiId()));
                    if($wiki) {
                        $programLive->setWikiCover($wiki->getCover());
                        $programLive->setWikiTitle($wiki->getTitle());
                        $programLive->setWikiId((string)$wiki->getId());
                    }
                }
                if($next_program[0]){
                    $programLive->setNextName($next_program[0]->getName());
                }else{
                    $programLive->setNextName("未知");
                }
                $programLive->save();
            }
        }
    }
}