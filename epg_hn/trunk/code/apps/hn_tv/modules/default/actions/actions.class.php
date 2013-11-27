<?php

/**
 * default actions.
 *
 * @package    epg2.0
 * @subpackage default
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		//推荐栏目
		$mongo = $this->getMondongo();
        $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
        $this->recommends = $wiki_recommend_repository->getRandWiki(2);
        //广告
        $recommend_repository = $mongo->getRepository('Recommend');
        $this->images = $recommend_repository->getRecommendByScene('index', 1);              
  }
  public function executeDetail(sfWebRequest $request)
  {
     //$this->forward('default', 'module');
  }  
}
