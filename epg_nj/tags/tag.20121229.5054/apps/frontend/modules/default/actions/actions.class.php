<?php

/**
 * default actions.
 *
 * @package    epg
 * @subpackage default
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
//      $filter = $request->getParameter('filter', 'all');
      $mongo = $this->getMondongo();
      $wiki_repository = $mongo->getRepository('Wiki');
      $recommend_repository = $mongo->getRepository('Recommend');
      $this->recommends = $recommend_repository->getRecommendByScene('index', 20);
      $this->wikis = $wiki_repository->find(array(
                                        'query' => array(
                                                'do_date' => array(
                                                    '$exists' => true
                                                )
                                            ),
                                        'sort' => array('do_date' => -1),
                                        'limit' => 20
                                    )
                                );

  }

  public function executeYingshi(sfWebRequest $request) {
    $mongo = $this->getMondongo();
    $repository = $mongo->getRepository('Page');
    $this->page = $repository->getNewestPageByName('影视');
  }

  public function executeZongyi(sfWebRequest $request) {
    $mongo = $this->getMondongo();
    $repository = $mongo->getRepository('Page');
    $this->page = $repository->getNewestPageByName('综艺');
  }

  /**
   * 社科频道面面
   * @param sfWebRequest $request 
   */
  public function executeSheke(sfWebRequest $request) {
    $mongo = $this->getMondongo();
    $repository = $mongo->getRepository('Page');
    $this->page = $repository->getNewestPageByName('社科');
  }
  
  /**
   * 404 错误页面
   */
  public function executeError404(sfWebRequest $request) {
      
  }
}
