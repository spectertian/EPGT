<?php

/**
 * simple_ad actions.
 *
 * @package    epg2.0
 * @subpackage simple_ad
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class simple_adActions extends sfActions
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
      return self::$mdb = $mongo->getRepository("SimpleAdvert");
    }else{
      return self::$mdb;
    }
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->pageTitle = '广告管理';
    $this->pager = new sfMondongoPager('SimpleAdvert', 20);
    $this->pager->setFindOptions(array('sort' => array('created_at' => -1)));
    //exit('aaa');
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
  }
  
  /**
   * Executes add action
   *
   * @param sfRequest $request A request object
   */
  public function executeAdd(sfWebRequest $request)
  {
    $name = $request->getParameter('name');
    $url  = $request->getParameter('url');
    $img  = $request->getParameter('ad');
    $start_time = $request->getPostParameter('start_time',date('Y-m-d',time()));
    $end_time   = $request->getPostParameter('end_time',self::last_month_today(strtotime(date('Y-m-d',time()))));
    if($name && $url){
      $sAd = new SimpleAdvert();
      $sAd->setName($name);
      $sAd->setUrl($url);
      $sAd->setImage($img['img']);
      $sAd->setStartTime($start_time);
      $sAd->setEndTime($end_time);
      $sAd->save();
      $this->getUser()->setFlash('notice', '新广告添加成功！');
    }
  }
  
  /**
   * Executes add action
   *
   * @param sfRequest $request A request object
   */
  public function executeEdit(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->id = $id;
    if($id){
      $mongo = self::getMdb();
      $this->ad = $mongo->findOneById(new MongoId($id));
      if(!$this->ad){
        $this->redirect('simple_ad/add');
      }
      if($request->isMethod("POST")){
        $name = $request->getParameter('name');
        $url  = $request->getParameter('url');
        if($name && $url){
          $img  = $request->getParameter('ad');
          $start_time = $request->getPostParameter('start_time',date('Y-m-d',time()));
          $end_time   = $request->getPostParameter('end_time',self::last_month_today(strtotime(date('Y-m-d',time()))));
          $this->ad->setName($name);
          $this->ad->setUrl($url);
          $this->ad->setStartTime($start_time);
          $this->ad->setEndTime($end_time);
          $this->ad->setImage($img['img']);
          $this->ad->save();
          $this->getUser()->setFlash('notice', '编辑广告成功！');
        }else{
          $this->getUser()->setFlash('error', '请完善信息！');
        }
      }
    }else{
      $this->redirect('simple_ad/index');
    }
  }
  
  /**
   * Executes delete action
   *
   * @param sfRequest $request A request object
   */
  public function executeDelete(sfWebRequest $request)
  {
    $id = strval($request->getParameter('id'));
    $this->ad = self::getMdb()->findOneByID(new MongoId($id));
    if($this->ad) {
      if(!$this->ad->delete())
        $this->getUser()->setFlash("notice",'删除成功!');
      else
        $this->getUser()->setFlash("error",'删除失败!');
    }else{
      $this->getUser()->setFlash("error",'该记录不存在!');
      $this->forwardUnless($this->ad, 'simple_ad', 'index');
    }
    $this->redirect($this->generateUrl('',array('module'=>'simple_ad','action'=>'index')));
  }
  
  /**
   * Executes batchdelete action
   *
   * @param sfRequest $request A request object
   */
  public function executeBatchDelete(sfWebRequest $request)
  {
    $ids = $request->getParameter('id');
    foreach($ids as $v){
      $this->ad = self::getMdb()->findOneByID(new MongoId($v));
      if($this->ad) {
        $this->ad->delete();
      }
    }
    $this->getUser()->setFlash("notice",'删除成功!');
    $this->redirect($this->generateUrl('',array('module'=>'simple_ad','action'=>'index')));
  }
  
  /**
   * 计算上一个月的今天，如果上个月没有今天，则返回上一个月的最后一天
   * @author gaobo
   * @param type $time
   * @return type
   */
  private function last_month_today($time){
    //$time = strtotime("2011-03-31");
    $last_month_time = mktime(date("G", $time), date("i", $time),
    date("s", $time), date("n", $time)+1, 1, date("Y", $time));
    $last_month_t =  date("t", $last_month_time);
    if ($last_month_t < date("j", $time)) {
      return date("Y-m-t", $last_month_time);
    }
    return date(date("Y-m", $last_month_time) . "-d", $time);
  }
}
