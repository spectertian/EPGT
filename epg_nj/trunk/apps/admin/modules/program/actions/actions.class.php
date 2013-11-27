<?php

/**
 * program actions.
 *
 * @package    epg
 * @subpackage mongo_program
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class programActions extends sfActions {

    /**
     * 
     * @param sfRequest $request A request object
     * @todo by zhigang 记住一个规则，action 只做自已应该做的事情，视图层需要的其他数据使用局部模板或组件调用
     * @todo 将选择频道的功能剥离出去
     * @todo 列表页面调用 Wiki 达100多次， 这是不正常的，找到原因并修复
     */
    public function executeIndex(sfWebRequest $request) {
        //输出省级电视台
        $this->type = $request->getParameter('type','cctv');
        $this->parentTvStations = Doctrine::getTable('TvStation')->getParentArray();
        //当前频道,当前时间获取
        $this->current_time = $request->getParameter('date', ( $this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d", time()) ));
        $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
        $channel_code = $request->getParameter('channel_code', ( $this->getUser()->getAttribute('$channel_code') ?  $this->getUser()->getAttribute('$channel_code') : 'cctv1' ));
        if(!$channel_code){
          $channel_code = 'cctv1';
        }
        /**
         *  @todo 获取 TvStation 使用 $channel->getTvStation() 方法
         */
        //获取当前Channel对象
        //$this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);
        //print_r(array($channel_code));exit;
        $channelArr = array();
        $this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
        if($this->channel){
        	$channelArr['id']=$this->channel->getId();
        	$channelArr['name']=$this->channel->getName();
        	$channelArr['code']=$this->channel->getCode();
        	$channelArr['tvsouupdate']=$this->channel->getTvsouUpdate();
        	$channelArr['tvstationid']=$this->channel->getTvStationId();
        	if(strtotime($channelArr['tvsouupdate'])>strtotime(date('Y-m-d 2:00'))){
        		$this->update=true;
        		$this->updatetime=$channelArr['tvsouupdate'];
        	}else{
        		$this->update=false;
        		$this->updatetime='';
        	}
        	//$this->channel_code=$this->channel->getCode();
        	$this->channel_code=$channel_code;
        	$this->channelname = $channelArr['name'];
        	
        	//if($this->channel){
        	//反向查询当前CHANNEL的顶级电视台
        	$tvStation = Doctrine::getTable('TvStation')->findOneById($channelArr['tvstationid']);
        	$tvStation_id = ($tvStation->getParentId() == 0 ? $tvStation->getId() : $tvStation->getParentId());
        	//输出当前默认电视台下的频道
        	$tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tvStation_id);
        	$this->channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
        	//设置CHANNEL_ID
        	$this->getUser()->setAttribute('channel_code', $this->channel_code);
        	$this->getUser()->setAttribute('channel_id', $channelArr['id']);
        	//设置TvStation_id
        	$this->getUser()->setAttribute('tv_station_id', $tvStation_id);
        	//设置当前选择日期
        	$this->getUser()->setAttribute('date',$this->current_time);
        	//获得MONGO对象
        	$mongo = $this->getMondongo();
        	$program_mongo = $mongo->getRepository("Program");
        	//获取当前节目表
        	$this->programs = $program_mongo->getDayPrograms($this->channel_code, $this->current_time);
        //}
        }
        
    }

    //对比本地和tvsou接口的区别
    public function executeTvsou(sfWebRequest $request) {
        //获取节目表的节目
        $date=$this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d");
        $channel_code=$request->getParameter('channel_code','cctv1');
        $this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
        $mongo = $this->getMondongo();
        $program_mongo = $mongo->getRepository("Program");        
        $this->programs = $program_mongo->getDayPrograms($channel_code, $date);
        //获取tvsou的节目
        $this->xml=null;
        $config=json_decode($this->channel->getConfig(),true);
        $channel_id=$config['tvsou']['channel_id'];
		$content = @file_get_contents("http://hz.tvsou.com/jm/hw/hw8901.asp?id=".$channel_id."&Date=".$date);
        $content = str_replace('gb2312', 'gb18030', $content);
		if($content) {
			$xml = simplexml_load_string($content);
			if($xml) {
				$this->xml=$xml;
			}
		}
    }   
    //处理区别
    public function executeTvsouOk(sfWebRequest $request) {
        $channel_code=$request->getParameter('channel_code','');
        if($channel_code!=''){
            /*
            $q = Doctrine_Query::create() 
                 ->update('channel') 
                 ->set('tvsou_update=?',null) 
                 ->where("code=?", $channel_code)
                 ->execute(); 
            */     
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            $channel->setTvsouUpdate(null);
            $channel->save();
        }
        $this->redirect($request->getReferer());
    }     
    //AJAX查询所有频道
    public function executeTvStationChannel(sfWebRequest $request) {
        if ($request->isMethod("POST") && $request->isXmlHttpRequest()) {
            $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
            $topTvStation_id = $request->getParameter('id');
            $tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($topTvStation_id);
            $channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
            return $this->renderText(json_encode($channels));
        }
    }

    public function executeDelete(sfWebRequest $request)
    {
       if($request->isMethod("POST"))
       {
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0)
           {
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

    public function executeDeleteAjax(sfWebRequest $request)
    {
       if ($request->isMethod("POST") && $request->isXmlHttpRequest())
       {
           $ids = $request->getParameter('ids');
           $ids =rtrim($ids,',');
           $ids=explode(',',$ids);
           //return $this->renderText(implode(',',$ids));
           if(count($ids)==0)
           {
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
    
    public function executePublish(sfWebRequest $request)
    {
       if($request->isMethod("POST"))
       {
           $ids = $request->getPostParameter('ids');
           $publish = $request->getPostParameter('publish',0);
           if(count($ids)==0)
           {
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

    public function executeSave(sfWebRequest $request)
    {
        if($request->isMethod("POST") && $request->isXmlHttpRequest())
        {
            $this->getResponse()->setHttpHeader("Content-type",'application/json;charset=UTF-8');
            $id = $request->getParameter('id', 0);
            $program_name = trim($request->getParameter('name',''));
            $channel_id = $request->getParameter('channel_id',0);
            $publish = $request->getParameter('publish',0);
            $play_time = trim($request->getParameter("time",0));
            $play_date = trim($request->getParameter('date',0));
            $wiki_id = trim($request->getParameter('wiki_id',''));
            $wiki_tags = trim($request->getParameter('tags',0));
            $sort = trim($request->getParameter('sort',0));
            if(empty($program_name)){
                return '';
            }
            $channel = Doctrine::getTable('Channel')->findOneById($channel_id);
            $channel_code = $channel->getCode();
            $return_status = array('program_id'=> false);
            $mongo =  $this->getMondongo();
            //将Tags最后一位为空的字符串弹出数组
            $tags = explode(",",$wiki_tags);
            if (strlen($tags[( count($tags) -1 )]) == 0) {
                array_pop($tags);
            }
            if ($id == 0) {
               $program = new Program();
            }else{
               $program_mongo = $mongo->getRepository("Program");
               $program = $program_mongo->findOneById(new MongoId($id));
            }

            $program->setName($program_name);
            $program->setTime($play_time);
            $program->setPublish($publish);
            $program->setDate($play_date);
            $program->setChannelCode($channel_code);
            $program->setTags($tags);
            $program->setWikiId($wiki_id);
            $program->setSort($sort);

            $admin = $this->getUser()->getAttribute("username");
            $program->setAdmin($admin);
            
            $program->save();
            
            //添加到editor_memory表:lfc
            if($wiki_id!=''){
                //首先更改wiki表里的tvsou_id
                $tvsou_id=$program->getTvsouId();
                if($tvsou_id!=''&&$tvsou_id!=0&&$tvsou_id!='0'){
                    $repository = $mongo->getRepository('Wiki');
                    //先去掉其他的tvsouid
                    $wikis       = $repository->find(array('query'=>array('tvsou_id'=>(string)$tvsou_id)));
                    foreach($wikis as $wiki){
                        $wiki -> setTvsouId(null);
                        $wiki -> save();
                    }
                    $wikia       = $repository->findOneById(new MongoId($wiki_id));
                    $wikia -> setTvsouId($tvsou_id);
                    $wikia -> save();
                 
                }else{
                    $editormemory = $mongo->getRepository('EditorMemory');
                    //$query = array('query' => array( "wiki_id" => $wiki_id,"program_name" => $program_name,"channel_code" => $channel_code ));
                    //$query = array('query' => array( "wiki_id" => $wiki_id,"program_id" => (string)$program->getId()));
                    $query = array('query' => array( "program_name" => $program_name,"channel_code" => $channel_code ));
                    $rs = $editormemory->findOne($query);
                    if(!$rs){
                        $memory = new EditorMemory();
                        $memory->setChannelCode($channel_code);
                        $memory->setProgramId($program->getId());
                        $memory->setProgramName($program_name);
                        $memory->setTags($tags);
                        $memory->setWikiId($wiki_id);
                        $memory->save();                        
                    }else{  
                        $rs->setTags($tags);
                        $rs->setWikiId($wiki_id);
                        $rs->save();
                    }     
                }
            }
            //添加到editor_memory表
            
            $this->getUser()->setFlash("notice",'操作成功!');
            $return_status['program_id'] = (string)$program->getId();
            return $this->renderText(json_encode($return_status));
        }
    }

    public function executeLoadWiki(sfWebRequest $request)
    {
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");
        $this->wikis = $wiki_mongo->likeWikiName($query);
    }

    public function executeDefault(sfWebRequest $request)
    {
        $this->getUser()->setAttribute('channel_id', '');
        $this->getUser()->setAttribute('tv_station_id', '');
        $this->getUser()->setAttribute('date','');
        $this->redirect('program/index');
    }
    
    public function executeGetSpNames(sfWebRequest $request)
    {
      $type = $request->getParameter('type',0);
      if($type){
        $mongo = $this->getMondongo()->getRepository("SpService");
        $names = $mongo->getServicesByTag($type);
        if($names){
          $res = array();
          foreach ($names as $k=>$v){
            $res[$k]['channelcode'] = $v->getChannelCode();
            $res[$k]['name'] = $v->getName();
          }
          return $this->renderText(json_encode($res,true));
        }
      }
    }
    /**
     * 根据字段名称，修改字段值
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_update(sfWebRequest $req) {
        $return = array('code'=>0, 'msg'=>'非法请求');
        $name   = $req->getParameter('key');
        $value  = $req->getParameter('value');
        $id     = $req->getParameter('id');
        $allow  = array('name','time');
        if(!in_array($name, $allow)) {
            return $this->renderText(json_encode(array('code' => 0 ,'msg' => '非法字段')));
        }
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
        $return = $repository->ajaxUpdate($id, $name, $value);
        return $this->renderText(json_encode($return));
    }
    /**
     * 根据字段名称，修改字段值
     * @param sfWebRequest $req
     * @return <type>
     */
    public function executeAjax_del(sfWebRequest $req) {
        $return = array('code'=>0, 'msg'=>'非法请求');
        $id     = $req->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 
        $program = $repository->findOneById(new mongoId($id));
        if($program)
        {
        	$program->delete();
        	$return = array('code'=>1, 'msg'=>'删除成功');
        }
        return $this->renderText(json_encode($return));
    }
    
    //#############从这开始是需要在正式服务器上加的方法
    //获取所有更新的epg频道
    public function executeEpgUpdate(sfWebRequest $request) {
        $channels = Doctrine::getTable("Channel")->getChannels();
        $this->channel_list=array();
        foreach ($channels as $channel){
            $editortime=strtotime($channel->getEditorUpdate());  //编辑确认时间
            $updatetime=strtotime($channel->getEpgUpdate());   //epg更新时间
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
                $this->channel_list[]=array('id'=>$channel->getId(),'code'=>$channel->getCode(),'name'=>$channel->getName(),'editor_update'=>$channel->getEditorUpdate(),'epg_update'=>$channel->getEpgUpdate(),'epg_get'=>$channel->getEpgGet());
            }
        }
    } 
    //对比本地和Epg(program_temp)的区别
    public function executeEpg(sfWebRequest $request) {
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
    //编辑确认区别
    public function executeEpgOk(sfWebRequest $request) {
        $channel_code=$request->getParameter('channel_code','');
        if($channel_code!=''){
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            //$channel->setEpgUpdate(null);
            $channel->setEditorUpdate(date('Y-m-d H:i:s'));
            $channel->save();
        }
        $this->redirect($request->getReferer());
    } 
    //重新抓取，
    public function executeEpgGet(sfWebRequest $request) {
        $channel_code=$request->getParameter('channel_code','');
        if($channel_code!=''){
            //将program_temp中该频道下今天及以后的记录都导入到program
            $date=date("Y-m-d");
            $mongo = $this->getMondongo();
            //移除当天及以后的数据
            $program_repository = $mongo->getRepository("Program");        
            $program_repository->removeDaysPrograms($channel_code, $date);
            
            $programtemp_mongo = $mongo->getRepository("ProgramTemp");        
            $programTemps = $programtemp_mongo->getDaysPrograms($channel_code, $date);
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
            //将临时表中节目当天及以后的数据删除
            $programtemp_mongo->removeDaysPrograms($channel_code, $date);
            //修改channel表编辑更新时间
            $channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);  
            $channel->setEpgGet(1);
            $channel->setEditorUpdate(date('Y-m-d H:i:s'));
            $channel->save();
        }
        $this->redirect($request->getReferer());
    } 
	/**
	 * 查看频道下面是否有节目，从channel表获取
	 * @param unknown_type $param
     * @author lifucang
     * @date 2013-01-18
	 */
	public function executeCountDayBak(sfWebRequest $request) {
	    $tvStation_id=$request->getParameter('tvid');
        $mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        if($tvStation_id){
            //获取该电视台下的所有频道
        	$tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tvStation_id);
        	$channels = Doctrine::getTable('Channel')->getChannelsByTvStations($tvStation_ids);
        }else{
            //获取央视卫视频道和指定电视台频道，114为江苏电视台
            $channels = Doctrine::getTable('Channel')->getLocalTvChannels(114);
        }
        $this->dates=array();
        for($days = 0; $days < 7 ; $days ++) {
            $this->dates[$days] = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));
        }
        $this->channelPrograms = array();       
        foreach($channels as $channel){
            $code = $channel->getCode();
            $name = $channel->getName();
            foreach($this->dates as $date) {
                $programNum = $programRes->countDayPrograms($code,$date);
                $this->channelPrograms[$name][]=$programNum;
            }
        }
	}
	/**
	 * 查看频道下面是否有节目
	 * @param unknown_type $param
     * @author lifucang
     * @date 2013-01-18
	 */
	public function executeCountDay(sfWebRequest $request) {
        $mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        $channels=$mongo->getRepository('SpService')->getServicesByTag();
        $this->dates=array();
        for($days = 0; $days < 7 ; $days ++) {
            $this->dates[$days] = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$days,date("Y")));
        }
        $this->channelPrograms = array();       
        foreach($channels as $channel){
            $code = $channel->getChannelCode();
            $name = $channel->getName();
            if($code){
                foreach($this->dates as $date) {
                    $programNum = $programRes->countDayPrograms($code,$date);
                    $this->channelPrograms[$name][]=$programNum;
                }  
            }
        }
	}
	/**
	 * 查看频道下面某天某节目段节目数量，未用，北京epg用到了
	 * @param unknown_type $param
     * @author lifucang
     * @date 2013-01-18
	 */
	public function executeCountTime(sfWebRequest $request) {
	    $tvStation_id=$request->getParameter('tvid');
        $this->date=$request->getParameter('date',date("Y-m-d"));
        $time1=$this->date.'00:00:00';
        $time2=$this->date.'09:00:00';
        $time3=$this->date.'18:00:00';
        $time4=$this->date.'23:59:59';
        $times=array(
            0 => array("startTime"=>$time1,"endTime"=>$time2),
            1 => array("startTime"=>$time2,"endTime"=>$time3),
            2 => array("startTime"=>$time3,"endTime"=>$time4),
        );
        $mongo = $this->getMondongo();
        $programRes = $mongo->getRepository('program');
        if($tvStation_id){
            //获取该电视台下的所有频道
        	$tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tvStation_id);
        	$channels = Doctrine::getTable('Channel')->getChannelsByTvStations($tvStation_ids);
        }else{
            //获取央视卫视频道和指定电视台频道，114为江苏电视台
            $channels = Doctrine::getTable('Channel')->getLocalTvChannels(114);
        }
        $this->channelPrograms = array();       
        foreach($channels as $channel){
            $code = $channel->getCode();
            $name = $channel->getName();
            foreach($times as $time) {
                $programNum = $programRes->countTimePrograms($code,$time['startTime'],$time['endTime']);
                $this->channelPrograms[$name][]=$programNum;
            }
            $programNum = $programRes->countDayPrograms($code,$this->date);
            $this->channelPrograms[$name][]=$programNum;
        }
	}
}
