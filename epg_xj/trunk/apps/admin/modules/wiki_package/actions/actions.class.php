<?php

/**
 * index_recommend actions.
 *
 * @package    epg
 * @subpackage wiki_package
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wiki_packageActions extends sfActions
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
      $this->pageTitle = '首页推荐列表';
      $this->pager = new sfMondongoPager('wikipackage', 20);
      
      $querys=array();
      $sort=array('created_at' => -1);
      if($this->m!='all')
          $querys['scene']= $this->m;
      if($this->show==1)
          $querys['is_public']= true;
      elseif($this->show==-1)
          $querys['is_public']= false;
      $this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
      
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
  }
   
  /**
   * 编辑相应的wiki包处理
   * @param sfWebRequest $request
   * @return void
   * @author wn
   */
  public function executeEdit(sfWebRequest $request) {
      $id = $request->getParameter('id');
      $mongo = $this->getMondongo();
      $wiki_packageRep = $mongo->getRepository('wikipackage');
      $this->wiki_package = $wiki_packageRep->findOneById(new MongoId($id));
      if($this->wiki_package ==NULL)  $this->redirect('wiki_package');
      if($request->isMethod("POST")){
          $wiki_id = $request->getPostParameter('wiki_id');
          $name = $request->getPostParameter('name'); 
          $scene = $request->getPostParameter('scene'); //区域
          $sort = $request->getPostParameter('sort'); // 排序
          $sort = intval($sort);
          $start_time = $request->getPostParameter('start_time',date('Y-m-d',time()));
          $end_time = $request->getPostParameter('end_time',self::last_month_today(strtotime(date('Y-m-d',time()))));
          $ispublic = $request->getPostParameter('ispublic');
          if(!empty($name)){
              $this->wiki_package->setName($name);
          }
          if(!empty($scene)){
              $this->wiki_package->setScene($scene);
          }
          if(!empty($wiki_id)){
              $this->wiki_package->setWikiId($wiki_id);
          }
          if(!empty($sort)) {
              $this->wiki_package->setSort($sort);
          }
          if(!empty($ispublic)){
              if($ispublic=="false"){
                  $this->wiki_package->setIsPublic(0);
              }
              if($ispublic=="true") {
                  $this->wiki_package->setIsPublic(1);
              }
          }
          
          $this->wiki_package->setStartTime($start_time);
          $this->wiki_package->setEndTime($end_time);
          $this->wiki_package->save();
          $this->getUser()->setFlash('notice', '修改所选项成功');
      }
  }
  
  /**
   * 添加进入wiki_package
   * @param sfWebRequest $request
   * @return void
   * @author wn
   */
  public function executeAdd(sfWebRequest $request) {
      $wiki_id = $request->getParameter('id');
      $mongo = $this->getMondongo();
      $wiki_packageRep = $mongo->getRepository('wikipackage');
      $this->wiki_package = $wiki_packageRep->findOneById(new MongoId($wiki_id));
      if($this->wiki_package!=NULL) $this->redirect('wiki_package/index');
      if($request->isMethod("POST")){
          $this->wiki_package = new wikipackage();
          $wiki_id = $request->getPostParameter('wiki_id');
          $name = $request->getPostParameter('name');
          $scene = $request->getPostParameter('scene'); //区域
          $sort = $request->getPostParameter('sort'); // 排序
          $sort = intval($sort);
          $start_time = $request->getPostParameter('start_time',date('Y-m-d',time()));
          $end_time = $request->getPostParameter('end_time',self::last_month_today(strtotime(date('Y-m-d',time()))));
          $ispublic = $request->getPostParameter('ispublic');

          if(!empty($name)){
              $this->wiki_package->setName($name);
          }
          if(!empty($scene)){
              $this->wiki_package->setScene($scene);
          }
          if(!empty($wiki_id)){
              $this->wiki_package->setWikiId($wiki_id);
          }          
          if(!empty($sort)) {
              $this->wiki_package->setSort($sort);
          }
          if(!empty($ispublic)){
              if($ispublic=="false"){
                  $this->wiki_package->setIsPublic(0);
              }
              if($ispublic=="true") {
                  $this->wiki_package->setIsPublic(1);
              }
          }
          $this->wiki_package->setStartTime($start_time);
          $this->wiki_package->setEndTime($end_time);

          $this->wiki_package->save();
          $this->getUser()->setFlash('notice', '新的WIKI包添加成功');
          $this->redirect('wiki_package/index');
      }
      
  }

  
  /**
   * 删除相应的推荐信息
   * @param sfWebRequest $request
   * @return void
   * @author wn
   */
  public function executeDelete(sfWebRequest $request) {
      if($request->isXmlHttpRequest()){
          $wp_id = $request->getParameter('id');
          $mongo = $this->getMondongo();
          $repository = $mongo->getRepository('wikipackage');
          $wp = $repository->findOneById(new MongoId($wp_id));
          if (!is_null($wp)) {
                  $wp->delete();
                  return $this->renderText(1);
          }
          return $this->renderText(2);
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
    public function executeLoadWiki(sfWebRequest $request)
    {
        $str='';
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");
        $this->wikis = $wiki_mongo->likeWikiName($query);
        /*
        foreach($this->wikis as $wiki){
            $str     = '<li>' . $wiki->getTitle()."|".$wiki->getDisplayName() . '</li>';
        }
        return $this->renderText('<ul>'.$str.'</ul>'); 
        */ 
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
