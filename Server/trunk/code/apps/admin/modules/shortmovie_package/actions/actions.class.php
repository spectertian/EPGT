<?php

/**
 * shortmovie_package actions.
 *
 * @package    epg2.0
 * @subpackage shortmovie_package
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class shortmovie_packageActions extends sfActions
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
      return self::$mdb = $mongo->getRepository("ShortMoviePackage");
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
    $this->pageTitle = '短视频包管理';
    $this->pager = new sfMondongoPager('ShortMoviePackage', 20);
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
	$desc   = $request->getParameter('desc');
    $cover  = $request->getParameter('sm');
    $tag    = $request->getParameter('tag');
    $state  = intval($request->getParameter('state'));
    if($name){
      $sAd = new ShortMoviePackage();
      $sAd->setName($name);
      $sAd->setDesc($desc);
      $sAd->setCover($cover['img']);
      $sAd->setTag($tag);
      $sAd->setState($state);
      if(!$sAd->save()){
        $this->getUser()->setFlash('notice', '短视频包添加成功!');
      }else{
        $this->getUser()->setFlash('error', '添加失败,请完善短视频包信息!');
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
        $this->redirect('shortmovie_package/add');
      }
      if($request->isMethod("POST")){
        $name   = $request->getParameter('name');
        $desc   = $request->getParameter('desc');
        $cover  = $request->getParameter('sm');
        $tag    = $request->getParameter('tag');
        $state  = intval($request->getParameter('state'));
        if($name){
          $this->sm->setName($name);
          $this->sm->setDesc($desc);
          $this->sm->setCover($cover['img']);
          $this->sm->setTag($tag);
          $this->sm->setState($state);
          $this->sm->save();
          $this->getUser()->setFlash('notice', '编辑短视频成功！');
        }else{
          $this->getUser()->setFlash('error', '请完善信息！');
        }
      }
    }else{
      $this->redirect('shortmovie_package/index');
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
        $smpmongo = $this->getMondongo()->getRepository("ShortMoviePackageItem");
        $smpiArr = $smpmongo->find(array('query'=>array('package_id'=>$id)));
        if($smpiArr){
          foreach ($smpiArr as $v){
            $v->delete();
          }
        }
        $this->getUser()->setFlash("notice",'删除成功!');
      }else{
        $this->getUser()->setFlash("error",'删除失败!');
      }
    }else{
      $this->getUser()->setFlash("error",'该记录不存在!');
      $this->forwardUnless($this->sm, 'shortmovie_package', 'index');
    }
    $this->redirect($this->generateUrl('',array('module'=>'shortmovie_package','action'=>'index')));
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
  public function executeBatchDelete(sfWebRequest $request)
  {
    $ids = $request->getParameter('id');
    foreach($ids as $v){
      $this->sm = self::getMdb()->findOneByID(new MongoId($v));
      if($this->sm) {
        $this->sm->delete();
      }
    }
    $this->getUser()->setFlash("notice",'删除成功!');
    $this->redirect($this->generateUrl('',array('module'=>'shortmovie_package','action'=>'index')));
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
    $this->redirect($this->generateUrl('',array('module'=>'shortmovie_package','action'=>'index')));
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
    $this->redirect($this->generateUrl('',array('module'=>'shortmovie_package','action'=>'index')));
  }
  
  public function executeAddshortmovie(sfWebRequest $request)
  {
    $smid  = $request->getParameter('smid');
    $smpid = $request->getParameter('smpid');
    $this->smpname = $request->getParameter('smpname');
    $this->smpid   = $smpid;
    if($smid && $smpid){
      $spiAd = new ShortMoviePackageItem();
      $spiAd->setPackageId($smpid);
      $spiAd->setShortMovieId($smid);
      if(!$spiAd->save()){
        $this->getUser()->setFlash('notice', '短视频关联成功!');
        $this->redirect($request->getReferer());
      }else{
        $this->getUser()->setFlash('error', '关联失败!');
        $this->redirect($request->getReferer());
      }
    }
  }
  
  public function executeDelsm(sfWebRequest $request)
  {
    $smid  = $request->getParameter('smid');
    $smpid = $request->getParameter('smpid');
    if($smid && $smpid){
      $smpmongo = $this->getMondongo()->getRepository("ShortMoviePackageItem");
      $smpiArr = $smpmongo->findOne(array('query'=>array('package_id'=>$smpid,'short_movie_id'=>$smid)));
      if(!$smpiArr){
        $this->redirect('shortmovie_package/add');
      }else{
        if(!$smpiArr->delete()){
          $this->getUser()->setFlash('notice', '短视频移除成功!');
          $this->redirect($request->getReferer());
        }else{
          $this->getUser()->setFlash('error', '移除失败!');
          $this->redirect($request->getReferer());
        }
      }
    }
  }
  
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeManage(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->smpname = $request->getParameter('smpname');
    $this->smpid = $id;
    if(!$id){$this->redirect($this->generateUrl('',array('module'=>'shortmovie_package','action'=>'index')));}
    
    $smpmongo = $this->getMondongo()->getRepository("ShortMoviePackageItem");
    $smids    = $smpmongo->find(array('query'=>array('package_id'=>$id),'sort' => array('created_at' => -1)));
    
    $smArr = array();
    if($smids){
      $mongo = $this->getMondongo()->getRepository("ShortMovie");
      foreach($smids as $v){
        $smArr[] = $mongo->findOneByID(new MongoId($v->getShortMovieId()));
      }
    }
    $this->items = $smArr;
    
    /* $this->pageTitle = '短视频包管理';
    $this->pager = new sfMondongoPager('ShortMoviePackageItem', 20);
    $this->pager->setFindOptions(array('query'=>array('package_id'=>$id),'sort' => array('created_at' => -1)));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init(); */
  }
  
  public function executeAddnewshortmovie(sfWebRequest $request)
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
              $smid  = $sAd->getId();
              $smpid = $request->getParameter('smpid');
              $this->smpname = $request->getParameter('smpname');
              $this->smpid   = $smpid;
              if($smid && $smpid){
                  $spiAd = new ShortMoviePackageItem();
                  $spiAd->setPackageId($smpid);
                  $spiAd->setShortMovieId($smid);
                  if(!$spiAd->save()){
                      $this->getUser()->setFlash('notice', '短视频关联成功!');
                      $this->redirect($request->getReferer());
                  }else{
                      $this->getUser()->setFlash('error', '关联失败!');
                      $this->redirect($request->getReferer());
                  }
              }
          }else{
              $this->getUser()->setFlash('error', '添加失败,请完善短视频信息!');
          }
      }
  }
}
