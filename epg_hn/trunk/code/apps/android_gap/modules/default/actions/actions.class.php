<?php

/**
 * default actions.
 *
 * @package    epg
 * @subpackage default
 * @author     superwen
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
	{
	    //$this->location = $request->getParameter('location','');//地区
		//播出栏目
		$mongo = $this->getMondongo();
        $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
        $this->recommends = $wiki_recommend_repository->getRandWiki(5);
        //幻灯图部分
        $recommend_repository = $mongo->getRepository('Recommend');
        $this->images = $recommend_repository->getRecommendByScene('index', 6);        
	}
  /**
   * 404 错误页面
   */
	public function executeError404(sfWebRequest $request) 
	{
		
	}
	  
}
