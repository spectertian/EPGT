<?php

/**
 * cpg actions.
 *
 * @package    epg
 * @subpackage mongo_cpg
 * @author     Mozi Tek
 * @version    SVN: 2012-12-25
 */
class cpgActions extends sfActions {

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
        	$program_mongo = $mongo->getRepository("Cpg");
        	//获取当前节目表
        	$this->programs = $program_mongo->getDayPrograms($this->channel_code, $this->current_time);
        //}
        }
        
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
                   $program_mongo = $mongo->getRepository("Cpg");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   $program->delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'cpg','action'=>'index')));
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
                   $program_mongo = $mongo->getRepository("Cpg");
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
                   $program_mongo = $mongo->getRepository("Cpg");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   $program->setPublish($publish);
                   $program->save();
               }
               $this->getUser()->setFlash("notice",'操作成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'cpg','action'=>'index')));
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
               $program = new Cpg();
            }else{
               $program_mongo = $mongo->getRepository("Cpg");
               $program = $program_mongo->findOneById(new MongoId($id));
            }

            $program->setProgramName($program_name);
            //$program->setTime($play_time);
            //$program->setPublish($publish);
            $program->setDate($play_date);
            //$program->setChannelCode($channel_code);
            //$program->setTags($tags);
            //$program->setWikiId($wiki_id);
            //$program->setSort($sort);

            //$admin = $this->getUser()->getAttribute("username");
            //$program->setAdmin($admin);
            
            $program->save();
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
          exit(json_encode($res,true));
        }
      }
    }
}
