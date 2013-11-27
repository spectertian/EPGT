<?php

/**
 * reportchannel actions.
 *
 * @package    epg2.0
 * @subpackage reportchannel
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reportchannelActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
      $this->d = trim($request->getGetParameter('d', ''));
      $this->n = trim($request->getGetParameter('n', ''));
      $this->s = trim($request->getGetParameter('s', ''));
      $this->pager = new sfMondongoPager('ReportChannel', 20);
      
      $querys=array();
      $sort=array('created_at' => -1);
      if($this->d!='')
          $querys['dtvsp']= $this->d;
      if($this->n!='')
          $querys['name']= $this->n;
      if($this->s!='')
          $querys['state']= $this->s?true:false;     
      $this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
  }
  
    /**
    * 更改状态
    * @param sfWebRequest $request
    * @author lifucang
    */
    public function executeState(sfWebRequest $request) {
      $rs_id = $request->getParameter('id');
      $mongo = $this->getMondongo();
      $repository = $mongo->getRepository('ReportChannel');
      $rs = $repository->findOneById(new MongoId($rs_id));
      if (!is_null($rs)) {
          $rs->setState(true);
          $rs->setUser($this->getUser()->getAttribute('username'));
          $rs->save();  
      }
      $this->redirect($request->getReferer());
    } 
    /**
    * 更改状态
    * @param sfWebRequest $request
    * @author wangnan
    */
    public function executeUnstate(sfWebRequest $request) {
      $rs_id = $request->getParameter('id');
      $mongo = $this->getMondongo();
      $repository = $mongo->getRepository('ReportChannel');
      $rs = $repository->findOneById(new MongoId($rs_id));
      if (!is_null($rs)) {
          $rs->setState(false);
          $rs->setUser($this->getUser()->getAttribute('username'));
          $rs->save();  
      }
      $this->redirect($request->getReferer());
    } 
    /**
    * 批量更改状态
    * @param sfWebRequest $request
    * @author lifucang
    */
    public function executeBatchState(sfWebRequest $request)
    {
       if($request->isMethod("POST"))
       {
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0)
           {
               $this->getUser()->setFlash("error",'操作失败！请选择需要处理的频道名称！');
           }else{
               $mongo = $this->getMondongo();
               $repository = $mongo->getRepository('ReportChannel');
               $user=$this->getUser()->getAttribute('username');
               foreach($ids as $id){
                   $rs = $repository->findOneById(new MongoId($id));
                   $rs->setState(true);
                   $rs->setUser($user);
                   $rs->save();  
               }
               $this->getUser()->setFlash("notice",'处理成功!');
           }
       }
	   $this->redirect($request->getReferer());
    } 
    /**
    * 批量更改状态
    * @param sfWebRequest $request
    * @author lifucang
    */
    public function executeBatchUnState(sfWebRequest $request)
    {
       if($request->isMethod("POST"))
       {
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0)
           {
               $this->getUser()->setFlash("error",'操作失败！请选择需要处理的频道名称！');
           }else{
               $mongo = $this->getMondongo();
               $repository = $mongo->getRepository('ReportChannel');
               $user=$this->getUser()->getAttribute('username');
               foreach($ids as $id){
                   $rs = $repository->findOneById(new MongoId($id));
                   $rs->setState(false);
                   $rs->setUser($user);
                   $rs->save();  
               }
               $this->getUser()->setFlash("notice",'处理成功!');
           }
       }
	   $this->redirect($request->getReferer());
    }  
}
