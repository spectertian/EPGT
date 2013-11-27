<?php

/**
 * index_recommend actions.
 *
 * @package    epg
 * @subpackage index_recommend
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class recommendActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {      
      $this->m = $request->getGetParameter('m', 'all');
      $this->show = $request->getGetParameter('s', 2);
      $this->pageTitle = '场景推荐列表';
      $this->pager = new sfMondongoPager('Recommend', 20);
      
      $querys=array();
      $sort=array('sort' => 1);
      if($this->m!='all')
          $querys['scene']= $this->m;
      if($this->show==1)
          $querys['is_public']= true;
      elseif($this->show==-1)
          //$query['ispublic']= false; 
          $querys['is_public']= false;
      $this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
      
      /*    
      if ('all' == $this->m) {
               $this->pager->setFindOptions();
      } else {
              $this->pager->setFindOptions(array('query' => array( 'scene'=> $this->m), 'sort' => array('sort' => 1)));   
      }
      */
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
  }
   
  /**
   * 编辑相应的推荐处理
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   */
  public function executeEdit(sfWebRequest $request) {
      $wiki_id = $request->getParameter('id');
      $mongo = $this->getMondongo();
      $recommendRep = $mongo->getRepository('Recommend');
      $this->recommend = $recommendRep->findOneById(new MongoId($wiki_id));
      if($this->recommend ==NULL)  $this->redirect('recommend');
      if($request->isMethod("POST")){
          $desc = $request->getPostParameter('recommend_title'); //recommend desc
          $name = $request->getPostParameter('name');
          //$pic = $request->getFiles("pic");
          //$smallpic = $request->getFiles("smallpic");
          $pic = $request->getPostParameter('pic');
          $smallpic = $request->getPostParameter('smallpic');
          $scene = $request->getPostParameter('scene'); //区域
          $sort = $request->getPostParameter('sort'); // 排序
          $sort = intval($sort);
          $ispublic = $request->getPostParameter('ispublic');
          $url = $request->getPostParameter('url');
          $isdesc = $request->getPostParameter('isdesc');
          if(!empty($desc)){
              $this->recommend->setDesc($desc);
          }
          /*
          if($pic['name']!=NULL) {  
            $storage = StorageService::get('photo');
            $file_name = $pic['name'];
            $file_ext_tmp = explode('.',$file_name);
            $file_ext = strtolower(array_pop($file_ext_tmp));
            $key = time().rand(100, 999);
            $res=$storage->save($key.'.'.$file_ext,$pic['tmp_name']);
            $this->recommend->setPic($key.'.'.$file_ext);
         }
         if($smallpic['name']!=NULL){
             $storage = StorageService::get('photo');
             $file_name = $smallpic['name'];
             $file_ext_tmp = explode('.',$file_name);
             $file_ext = strtolower(array_pop($file_ext_tmp));
             $key = time().rand(100, 999);
             $res=$storage->save($key.'.'.$file_ext,$smallpic['tmp_name']);
             $this->recommend->setSmallPic($key.'.'.$file_ext);
         }
         */
         $this->recommend->setPic($pic);
         $this->recommend->setSmallpic($smallpic);
         if(!empty($url)) {
             $this->recommend->setUrl($url);
         }
         if(!empty($name)) {
             $this->recommend->setTitle($name);
         }
          if(!empty($scene)){
              $this->recommend->setScene($scene);
          }
          if(!empty($sort)) {
              //var_dump($sort);exit;
              $this->recommend->setSort($sort);
          }
          if(!empty($ispublic)){
              if($ispublic=="false"){
                  $this->recommend->setIsPublic(0);
              }
              if($ispublic=="true") {
                  $this->recommend->setIsPublic(1);
              }
          }
          if(!empty($isdesc)){
              if($isdesc=="false"){
                  $this->recommend->setIsdescDisplay(0);
              }
              if($isdesc=="true") {
                  $this->recommend->setIsdescDisplay(1);
              } 
          }
          $this->recommend->save();
          $this->getUser()->setFlash('notice', '修改所选项成功');
      }
  }
  
  /**
   * 删除相应的推荐信息
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   */
  public function executeDelete(sfWebRequest $request) {
      if($request->isXmlHttpRequest()){
          $rs_id = $request->getParameter('id');
          $mongo = $this->getMondongo();
          $repository = $mongo->getRepository('Recommend');
          $recommend = $repository->findOneById(new MongoId($rs_id));
          if (!is_null($recommend)) {
                  $recommend->delete();
                  return $this->renderText(1);
          }
          return $this->renderText(2);
      }
  }
  
  /**
   * 添加进入recommend
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   * @editor lfc
   */
  public function executeAdd(sfWebRequest $request) {
      $wiki_id = $request->getParameter('id');
      $mongo = $this->getMondongo();
      $recommendRep = $mongo->getRepository('Recommend');
      $this->recommend = $recommendRep->findOneById(new MongoId($wiki_id));
      if($this->recommend!=NULL) $this->redirect('recommend/index');
      if($request->isMethod("POST")){
          $this->recommend = new Recommend();
          $desc = $request->getPostParameter('recommend_title'); //recommend desc
          //$pic = $request->getFiles("pic");
          //$smallpic = $request->getFiles("smallpic");
          $pic = $request->getPostParameter('pic');
          $smallpic = $request->getPostParameter('smallpic');
          $scene = $request->getPostParameter('scene'); //区域
          $sort = $request->getPostParameter('sort'); // 排序
          $sort = intval($sort);
          $ispublic = $request->getPostParameter('ispublic');
          $wiki_id = $request->getPostParameter('wiki_id');
          $wiki_name = $request->getPostParameter('wiki_name');
          $url = $request->getPostParameter('url');
          $name = $request->getPostParameter('name');
          $isdesc = $request->getPostParameter('isdesc');
          
          $this->recommend->setUrl($url);
          if(!empty($name)){
              $this->recommend->setTitle($name);
          }
          if(!empty($desc)){
              $this->recommend->setDesc($desc);
          }
          /*
          if($pic['name']!=NULL) {  
            $storage = StorageService::get('photo');
            $file_name = $pic['name'];
            $file_ext_tmp = explode('.',$file_name);
            $file_ext = strtolower(array_pop($file_ext_tmp));
            $key = time().rand(100, 999);
            $res=$storage->save($key.'.'.$file_ext,$pic['tmp_name']);
            $this->recommend->setPic($key.'.'.$file_ext);
         }
         if($smallpic['name']!=NULL) {
             $storage = StorageService::get('photo');
            $file_name = $smallpic['name'];
            $file_ext_tmp = explode('.',$file_name);
            $file_ext = strtolower(array_pop($file_ext_tmp));
            $key = time().rand(100, 999);
            $res=$storage->save($key.'.'.$file_ext,$smallpic['tmp_name']);
            $this->recommend->setPic($key.'.'.$file_ext);            
         }
         */
          $this->recommend->setPic($pic);
          $this->recommend->setSmallpic($smallpic);
          if(!empty($scene)){
              $this->recommend->setScene($scene);
          }
          if(!empty($sort)) {
              $this->recommend->setSort($sort);
          }
          if(!empty($ispublic)){
              if($ispublic=="false"){
                  $this->recommend->setIsPublic(0);
              }
              if($ispublic=="true") {
                  $this->recommend->setIsPublic(1);
              }
          }
          if(!empty($isdesc)){
              if($isdesc=="false"){
                  $this->recommend->setIsdescDisplay(0);
              }
              if($isdesc=="true") {
                  $this->recommend->setIsdescDisplay(1);
              } 
          }
          $this->recommend->save();
          $this->getUser()->setFlash('notice', '新的推荐添加成功');
          $this->redirect('recommend/index');
      }
      
  }
  
  /**
   * 通过wiki_id查收是否有相应的数据信息
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   */
  public function executeSearch(sfWebRequest $request) {
      if($request->isXmlHttpRequest()){
        $wiki_id = $request->getPostParameter("wiki_id");
        $mongo = $this->getMondongo();
        $recommendRep = $mongo->getRepository("Recommend");
        $this->recommend = $recommendRep->getRecommendByWikiId($wiki_id);
        if($this->recommend != null) return $this->renderText (1);
        $wikiRep = $mongo->getRepository("Wiki");
        $wiki = $wikiRep->findOneById(new Mongoid($wiki_id));
        if($wiki != NULL) return $this->renderText ($wiki->getTitle());
        return $this->renderText(2);
        
      }
      return sfView::NONE;
  }
  
  private function recommendList() {
      $arr = array();
      $arr['index'] = "首页";
      $arr['list'] ="列表";
      $arr['channel'] = "节目";
      $arr['search'] = "搜索";
      return $arr;
  }
  
}
