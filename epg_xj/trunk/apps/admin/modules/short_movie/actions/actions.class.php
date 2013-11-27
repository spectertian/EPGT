<?php

/**
 * short_movie actions.
 *
 * @package    epg2.0
 * @subpackage short_movie
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class short_movieActions extends sfActions
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
      return self::$mdb = $mongo->getRepository("ShortMovie");
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
    $this->pageTitle = '短视频管理';
    $this->pager = new sfMondongoPager('ShortMovie', 20);
    $this->pager->setFindOptions(array('sort' => array('created_at' => -1)));
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
    $name   = $request->getParameter('name');
    $cover  = $request->getParameter('sm');
    $url    = $request->getParameter('url');
    $tag    = $request->getParameter('tag');
    $state  = intval($request->getParameter('state'));
    $refer  = $request->getParameter('refer');
    $author = $request->getParameter('author');
    if($name && $url){
      $sAd = new ShortMovie();
      $sAd->setName($name);
      $sAd->setUrl($url);
      $sAd->setCover($cover['img']);
      $sAd->setTag($tag);
      $sAd->setState($state);
      $sAd->setRefer($refer);
      $sAd->setAuthor($author);
      if(!$sAd->save()){
        $this->getUser()->setFlash('notice', '短视频添加成功!');
      }else{
        $this->getUser()->setFlash('error', '添加失败,请完善短视频信息!');
      }
    }
  }
  
  
  /**
   * Executes edit action
   *
   * @param sfRequest $request A request object
   */
  public function executeEdit(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->id = $id;
    if($id){
      $mongo = self::getMdb();
      $this->sm = $mongo->findOneById(new MongoId($id));
      if(!$this->sm){
        $this->redirect('short_movie/add');
      }
      if($request->isMethod("POST")){
        $name   = $request->getParameter('name');
        $cover  = $request->getParameter('sm');
        $url    = $request->getParameter('url');
        $tag    = $request->getParameter('tag');
        $state  = intval($request->getParameter('state'));
        $refer  = $request->getParameter('refer');
        $author = $request->getParameter('author');
        if($name && $url){
          $this->sm->setName($name);
          $this->sm->setUrl($url);
          $this->sm->setCover($cover['img']);
          $this->sm->setTag($tag);
          $this->sm->setState($state);
          $this->sm->setRefer($refer);
          $this->sm->setAuthor($author);
          $this->sm->save();
          $this->getUser()->setFlash('notice', '编辑短视频成功！');
        }else{
          $this->getUser()->setFlash('error', '请完善信息！');
        }
      }
    }else{
      $this->redirect('short_movie/index');
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
    $this->sm = self::getMdb()->findOneByID(new MongoId($id));
    if($this->sm) {
      if(!$this->sm->delete()){
        $this->getUser()->setFlash("notice",'删除成功!');
        $smpmongo = $this->getMondongo()->getRepository("ShortMoviePackageItem");
        $smpiArr = $smpmongo->findOne(array('query'=>array('short_movie_id'=>$id)));
        if($smpiArr){
            $smpiArr->delete();
        }
      }else{
        $this->getUser()->setFlash("error",'删除失败!');
      }
    }else{
      $this->getUser()->setFlash("error",'该记录不存在!');
      $this->forwardUnless($this->sm, 'short_movie', 'index');
    }
    $this->redirect($this->generateUrl('',array('module'=>'short_movie','action'=>'index')));
  }
  
  /**
   * Executes batchdelete action
   *
   * @param sfRequest $request A request object
   */
  public function executeBatchDelete(sfWebRequest $request)
  {
    $ids = $request->getParameter('id');
    $smpmongo = $this->getMondongo()->getRepository("ShortMoviePackageItem");
    foreach($ids as $v){
      $this->sm = self::getMdb()->findOneByID(new MongoId($v));
      if($this->sm) {
        $this->sm->delete();
        $smpiArr = $smpmongo->findOne(array('query'=>array('short_movie_id'=>$v)));
        if($smpiArr){
            $smpiArr->delete();
        }
      }
    }
    $this->getUser()->setFlash("notice",'删除成功!');
    $this->redirect($this->generateUrl('',array('module'=>'short_movie','action'=>'index')));
  }
  
  /**
   * Executes batchdelete action
   *
   * @param sfRequest $request A request object
   */
  public function executePublishoff(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $sm = self::getMdb()->findOneByID(new MongoId($id));
    if($sm) {
      $sm->setState(0);
      $sm->save();
    }
    $this->getUser()->setFlash("notice",'取消发布成功!');
    $this->redirect($this->generateUrl('',array('module'=>'short_movie','action'=>'index')));
  }
  
  public function executeBatchPublish(sfWebRequest $request)
  {
    $ids   = $request->getParameter('id');
    $state = intval($request->getParameter('publish'));
    $mongo = self::getMdb();
    foreach($ids as $v){
      $smp = $mongo->findOneByID(new MongoId($v));
      if($smp){
        $smp->setState($state);
        $smp->save();
        $this->getUser()->setFlash("notice",'批量操作成功!');
      }
    }
    $this->redirect($request->getReferer());
  }
  
  /**
   * Executes batchdelete action
   *
   * @param sfRequest $request A request object
   */
  public function executePublishon(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $sm = self::getMdb()->findOneByID(new MongoId($id));
    if($sm) {
      $sm->setState(1);
      $sm->save();
    }
    $this->getUser()->setFlash("notice",'发布成功!');
    $this->redirect($this->generateUrl('',array('module'=>'short_movie','action'=>'index')));
  }
  
  public function executeLoadsm(sfWebRequest $request)
  {
    $str='';
    $query = $request->getParameter('query');
    $sm_mongo = self::getMdb();
    $this->wikis = $sm_mongo->likeShortName($query);
  }
}
