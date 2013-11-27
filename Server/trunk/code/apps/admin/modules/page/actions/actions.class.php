<?php

/**
 * 页面模板管理
 *
 * @package    epg
 * @subpackage page
 * @author     luren
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pageActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->pageTitle = '页面模板列表';
    $mongo = $this->getMondongo();
    $repository = $mongo->getRepository('Page');
    $this->indexPage = $repository->getNewestPageByName('首页');
    $this->yingshiPage = $repository->getNewestPageByName('影视');
    $this->zongyiPage = $repository->getNewestPageByName('综艺');
    $this->shekePage = $repository->getNewestPageByName('社科');
  }

  /**
   * 单个版本查看
   * @param sfWebRequest $request
   */
  public function executeShow(sfWebRequest $request) {
      $mongo = $this->getMondongo();
      $repository = $mongo->getRepository('Page');
      $this->page = $repository->findOneById(new MongoId($request->getParameter('id', null)));
      $this->redirectUnless($this->page, '@page_index');
       $this->form = new PageForm($this->page);
  }
  /**
   * 编辑模板
   * @param sfWebRequest $request
   */
  public function executeEdit(sfWebRequest $request) {
      $pageArr = array('首页','影视','综艺','社科');
      $pagename = $request->getParameter('pagename', null);
      if (in_array($pagename, $pageArr)) {
          $mongo = $this->getMondongo();
          $repository = $mongo->getRepository('Page');
          $this->page = $repository->getNewestPageByName($pagename);
          if (! $this->page) {
              $this->page = new Page();
              $this->page->setPagename($pagename);
          }
          $this->form = new PageForm($this->page);
      } else {
          $this->redirect404();
      }
  }
  /**
   * 更新模板
   * @param sfWebRequest $request
   */
  public function executeUpdate(sfWebRequest $request) {
      $this->processForm($request, new Page());
  }

  /**
   * 查看模板编辑历史记录
   * @param sfWebRequest $request
   */
  public function executeHistory(sfWebRequest $request) {
      $this->pagename = $request->getParameter('pagename', null);
      $this->pageTitle = $this->pagename . ' - 版本记录';
      $this->pager = new sfMondongoPager('Page', 20);
      $this->pager->setFindOptions(array('query' => array('pagename' => $this->pagename), 'sort' => array('version' => -1)));
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
  }

  /**
   * 处理表单
   * @param sfWebRequest $request
   * @param Page $page
   */
  protected function processForm(sfWebRequest $request, Page $page) {
    $pagePost = $request->getPostParameter('page');
    $page->setPagename($pagePost['pagename']);
    $page->setContent($pagePost['content']);
    $page->setAuthor($this->getUser()->getAttribute('username'));
    $page->save();
    $this->getUser()->setFlash('notice', '编辑模板成功，已经为最新模板！');
    $this->redirect('@page_index');
  }
}
