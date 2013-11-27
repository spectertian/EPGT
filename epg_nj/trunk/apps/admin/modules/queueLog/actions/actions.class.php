<?php

/**
 * queueLog actions.
 *
 * @package    epg2.0
 * @subpackage queueLog
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class queueLogActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $query_arr=array();
        $this->title = trim($request->getGetParameter('title', null));
        if($this->title){
            $query_arr['content']= new MongoRegex("/.*".$this->title.".*/i");
        }
        $this->pager = new sfMondongoPager('QueueLog', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('_id' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    public function executeDel(sfWebRequest $request)
    {
       if($request->isMethod("POST")){
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0){
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的日志！');
           }else{
               $mongo = $this->getMondongo();
               $reps = $mongo->getRepository("QueueLog");
               foreach($ids as $id){
                   $rs = $reps->findOneById(new MongoId($id));
                   $rs -> delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'queueLog','action'=>'index')));
    } 
    public function executeAddQueue(sfWebRequest $request)
    {
       $ids = $request->getParameter('ids');
       $id = $request->getParameter('id');
       if($id){
           $ids=array($id);
       }
       if(count($ids)==0){
           $this->getUser()->setFlash("error",'请选择需要重新加入队列的日志！');
       }else{
           $httpsqs = HttpsqsService::get();
           $mongo = $this->getMondongo();
           $reps = $mongo->getRepository("QueueLog");
           foreach($ids as $id){
               $rs = $reps->findOneById(new MongoId($id));
               $content = $rs->getContent();
               $state=$rs -> getState();
               if($state==0){
                   $rs -> setState(1);
                   $rs -> save();
                   $httpsqs->put('epg_queue', $content);
               }
           }
           $this->getUser()->setFlash("notice",'已成功加入队列!');
       }
       $this->redirect($this->generateUrl('',array('module'=>'queueLog','action'=>'index')));
    } 
}
