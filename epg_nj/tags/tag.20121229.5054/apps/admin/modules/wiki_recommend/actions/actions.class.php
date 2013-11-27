<?php

/**
 * wiki_recommend actions.
 *
 * @package    epg
 * @subpackage wiki_recommend
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wiki_recommendActions extends sfActions
{
/**
  * 推荐维基列表
  * @author luren
  */
  public function executeIndex(sfWebRequest $request)
  {
        $this->m = $request->getGetParameter('m', 'all');
        $this->j = $request->getGetParameter('j', 'all');
        $this->pageTitle = '推荐维基列表';
        $this->pager = new sfMondongoPager('WikiRecommend', 20);
      
        $query_arr=array();
        if($this->j!='all')
            $query_arr['tags']=$this->j;
        if($this->m!='all')
            $query_arr['model']=$this->m;
        $this->pager->setFindOptions(array('query' => $query_arr, 'sort' => array('created_at' => -1)));
        /*
        if ('all' == $this->m) {
           $this->pager->setFindOptions(array('sort' => array('created_at' => -1)));
        } else {
          $this->pager->setFindOptions(array('query' => array( 'model'=> $this->m), 'sort' => array('created_at' => -1)));
        }
        */
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
  }

  /**
   * 批量删除已推荐维基
   *  @author luren
   */
  public function executeBatch(sfWebRequest $request) {

      $ids = $request->getParameter('id');

      if (count($ids) > 0) {
          $mongo = $this->getMondongo();
          $repository = $mongo->getRepository('WikiRecommend');

          foreach ($ids as $id) {
              $wikirecommend = $repository->findOneById(new MongoId($id));
              if (!is_null($wikirecommend)) {
                  $wikirecommend->delete();
              }
          }

          $this->getUser()->setFlash('notice', '已删除选择项！');
      } else {
          $this->getUser()->setFlash('error', '请选择操作项目！');
      }

      $this->redirect('wiki_recommend');
  }

  /**
   *
   * @param sfWebRequest $request
   * @author    ly
   * @date      2011-06-21
   */
  public function executeDelete(sfWebRequest $request){
      $id = $request->getParameter('id');
      $mongo = $this->getMondongo();
      $repository = $mongo->getRepository('WikiRecommend');
          $wikirecommend = $repository->findOneById(new MongoId($id));
          if (!is_null($wikirecommend)) {
              $wikirecommend->delete();
          }
      $this->getUser()->setFlash('notice', '已取消选择项！');
      $this->redirect('wiki_recommend');
  }

}
