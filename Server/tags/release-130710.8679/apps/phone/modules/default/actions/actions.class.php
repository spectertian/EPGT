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
		
	    $st = $request->getParameter('st','day');
		if($st == 'day') {
			$mongo = $this->getMondongo();
			$wiki_repository = $mongo->getRepository('Wiki');
			$recommend_repository = $mongo->getRepository('Recommend');
			$this->recommends = $recommend_repository->getRecommendByScene('index', 20);
		} else {
			$mongo = $this->getMondongo();
			$wiki_repository = $mongo->getRepository('Wiki');
			$recommend_repository = $mongo->getRepository('Recommend');
			$this->recommends = $recommend_repository->getRecommendByScene('index', 20);
		}		
	}
}
