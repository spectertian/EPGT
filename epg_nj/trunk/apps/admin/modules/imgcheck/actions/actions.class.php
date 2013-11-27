<?php

/**
 * imgcheck actions.
 *
 * @package    epg2.0
 * @subpackage imgcheck
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class imgcheckActions extends sfActions
{
 /**
   * Get mongodb handler
   * @return mongo | object
   */
  public static $mdb = null; 
  
  public function getMdb()
  {
    if(null == self::$mdb){
      $mongo = $this->getMondongo();
      return self::$mdb = $mongo->getRepository("Program");
    }else{
      return self::$mdb;
    }
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeWikis(sfWebRequest $request)
  {
    $this->currentNav = array("分类管理","WIKI图片检查"); 
    
    $this->pageTitle = 'WIKI图片检查';
    $this->pager = new sfMondongoPager('Wiki', 20);
    $this->pager->setFindOptions(array('query'=>array('$or'=>array(0=>array('cover'=>array('$exists'=>false)),1=>array('cover'=>'')))));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }
  
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executePrograms(sfWebRequest $request)
  {
    $start_time = $request->getParameter('start_time');
    $end_time   = $request->getParameter('end_time');
    
    $this->start_hi = $request->getParameter('start_hi');
    $this->end_hi = $request->getParameter('end_hi');
    
    if($this->start_hi){
      $start_hi = $this->start_hi.':00';
    }else{
      $start_hi = '00:00:00';
    }
    if($this->end_hi){
      $end_hi = $this->end_hi.':00';
    }else{
      $end_hi = '00:00:00';
    }
    
    if(($start_time>$end_time)){
      $this->getUser()->setFlash('error', '请正确输入时间段', false);
      return;
    }
    
    $query = array();
    if(!$start_time || !$end_time){
      $date = new MongoDate(time());
      //print_r($date);exit;
      $query['query']  = array('start_time'=>array('$lte'=>$date),'end_time'=>array('$gte'=>$date));
    }else{
      $start_time_temp = new MongoDate(strtotime($start_time.' '.$start_hi));
      $end_time_temp   = new MongoDate(strtotime($end_time.' '.$end_hi));
      $query['query']  = array('start_time'=>array('$gte'=>$start_time_temp),'end_time'=>array('$lte'=>$end_time_temp));
      $this->start_time= $start_time;
      $this->end_time  = $end_time;
    }
    $pMongo = $this->getMondongo()->getRepository("Program");
    $wMongo = $this->getMondongo()->getRepository("Wiki");
    
    $programs = $pMongo->find($query);

    $rs = array();
    if($programs){
      foreach ($programs as $k=>$v){
        if($v->getWikiId()){
          $wiki = $wMongo->findOneById(new MongoId($v->getWikiId()));
          if($wiki){
            if(!$wiki->getCover() || $wiki->getCover()==''){
              $rs[$k]['name']    = $v->getName();
              $rs[$k]['wiki_id'] = $v->getWikiId();
            }
          }
        }
      }
    }
    $this->programscoverno = $rs;
     
    /* $this->currentNav = array("分类管理","节目图片检查");
  
    $this->pageTitle = '节目图片检查';
    $this->pager = new sfMondongoPager('Program', 20);
    $this->pager->setFindOptions($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init(); */
  }
}
