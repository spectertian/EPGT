<?php

/**
 * television actions.
 *
 * @package    epg
 * @subpackage television
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class televisionActions extends sfActions
{

  public function executeIndex(sfWebRequest $request) {
	    $channel_id = $this->getUser()->getAttribute("channel_id");
	    $channel = Doctrine::getTable('Channel')->findOneById($channel_id);
        $channel_code = $channel->getCode();
        $mongo = $this->getMondongo();
        $television = $mongo->getRepository("television");
        $this->televisions = $television->getChannelTelevisions($channel_code);
        $this->channel_name = $channel->getName();
  }

  public function executeSave(sfWebRequest $request) {
      if( $request->isMethod("POST") && $request->isXmlHttpRequest() ) {
          $this->getResponse()->setHttpHeader("Content-type",'application/json;charset=UTF-8');
          $id = $request->getParameter('id',0);
          $television_title = trim($request->getParameter('name',''));
          $channel_id = $request->getParameter('channel_id',0);
          $play_time = trim($request->getParameter("time",''));
          $play_week = trim($request->getParameter('date',''));
          $wiki_id = trim($request->getParameter('wiki_id',''));
          $return_status = array('television_id'=> false);
          $mongo =  $this->getMondongo();
          if( $id == 0 ) {
            $television = new Television();
            $channel = Doctrine::getTable('Channel')->findOneById($channel_id);
            $channel_code = $channel->getCode();
            $television->setChannelCode($channel_code);
          }else{
            $television_mongo = $mongo->getRepository("Television");
            $television = $television_mongo->findOneById(new MongoId($id));
          }
          $television->setWikiTitle($television_title);
          $television->setPlayTime($play_time);
          $television->setWeekDay($play_week);
          $television->setWikiId($wiki_id);
          $television->save();
          $return_status['television_id'] = (string)$television->getId();
          return $this->renderText(json_encode($return_status));
      }
  }

   public function executeDelete(sfWebRequest $request) {
       if($request->isMethod("POST"))
       {
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0)
           {
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的节目！');
           }else{
               foreach($ids as $id){
                   $mongo = $this->getMondongo();
                   $television_mongo = $mongo->getRepository("Television");
                   $television = $television_mongo->findOneById(new MongoId($id));
                   $television->delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'television','action'=>'index')));
   }

}
