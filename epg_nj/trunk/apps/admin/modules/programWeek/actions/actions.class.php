<?php

/**
 * programWeek actions.
 * @package    epg2.0
 * @subpackage programWeek
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class programWeekActions extends sfActions
{
    /**
     * @author lifucang 
     * @date   2013-09-10 
     */
    public function executeIndex(sfWebRequest $request) {
        $this->type = $request->getParameter('type','cctv');
        //当前频道,当前时间获取
        $this->current_time = $request->getParameter('date', ( $this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d", time()) ));
        //$channel_code = $request->getParameter('channel_code', ( $this->getUser()->getAttribute('channel_code') ?  $this->getUser()->getAttribute('channel_code') : 'cctv1' ));
        $channel_code = $request->getParameter('channel_code','cctv1');
        $mongo = $this->getMondongo();
        $programWeek_rep = $mongo->getRepository("ProgramWeek");
        $spservice_rep = $mongo->getRepository("SpService");
        
        $channel = $spservice_rep->findOne(array('query'=>array('channel_code'=>$channel_code)));
        if($channel){
        	$this->channel_code=$channel_code;
        	$this->channelname = $channel->getName();

        	$this->getUser()->setAttribute('channel_code', $this->channel_code);
        	$this->getUser()->setAttribute('date',$this->current_time);

        	$this->programs = $programWeek_rep->getDayPrograms($this->channel_code, $this->current_time);
        }
    }
    /**
     * @todo 删除 
     * @date 2013-09-10 
     */
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
                   $program_mongo = $mongo->getRepository("ProgramWeek");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   $program->delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'programWeek','action'=>'index')));
    }
    /**
     * @todo ajax删除 
     * @date 2013-09-10 
     */
    public function executeDeleteAjax(sfWebRequest $request)
    {
       if ($request->isMethod("POST") && $request->isXmlHttpRequest())
       {
           $ids = $request->getParameter('ids');
           $ids =rtrim($ids,',');
           $ids=explode(',',$ids);
           if(count($ids)==0){
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
               return $this->renderText(0);
           }else{
               foreach($ids as $id){
                   $mongo = $this->getMondongo();
                   $program_mongo = $mongo->getRepository("ProgramWeek");
                   $program = $program_mongo->findOneById(new MongoId($id));
                   $program->delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
               return $this->renderText(1);
           }
       }
    }
    
    public function executeSave(sfWebRequest $request)
    {
        if($request->isMethod("POST") && $request->isXmlHttpRequest())
        {
            $this->getResponse()->setHttpHeader("Content-type",'application/json;charset=UTF-8');
            $id = $request->getParameter('id', 0);
            $program_name = trim($request->getParameter('name',''));
            $play_time = trim($request->getParameter("time",0));
            $play_date = trim($request->getParameter('date',0));
            if(empty($program_name)){
                return '';
            }
            $channel_code = $this->getUser()->getAttribute('channel_code');
            $return_status = array('program_id'=> false);
            $mongo =  $this->getMondongo();
            if ($id == 0) {
               $program = new Program();
            }else{
               $program_mongo = $mongo->getRepository("ProgramWeek");
               $program = $program_mongo->findOneById(new MongoId($id));
            }
            $program->setName($program_name);
            $program->setTime($play_time);
            $program->setDate($play_date);
            $program->setChannelCode($channel_code);
            //$program->setSort($sort);
            //$program->setPublish($publish);
            $admin = $this->getUser()->getAttribute("username");
            $program->setAdmin($admin);
            $program->save();

            $this->getUser()->setFlash("notice",'操作成功!');
            $return_status['program_id'] = (string)$program->getId();
            return $this->renderText(json_encode($return_status));
        }
    }
}
