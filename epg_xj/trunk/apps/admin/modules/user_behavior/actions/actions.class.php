<?php

/**
 * user_behavior actions.
 *
 * @package    epg2.0
 * @subpackage user_behavior
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class user_behaviorActions extends sfActions
{
	/**
	 * Executes index action
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
		
		$this->userName = trim($request->getParameter('userName'));
		$this->access = trim($request->getParameter('access'));
      	
    	$this->pageTitle = '用户行为管理';
	    $this->pager = new sfMondongoPager('CategoryRecommend', 20);
	    
	    $querys=array();
		$sort=array('created_at' => -1);
    	if($this->userName != '')
 		{
 			$userName = "/.*".$this->userName.".*/i";
			$userName = new MongoRegex($userName);
 			$querys['user_name'] = $userName;
 		}
		if($this->access != '')
 		{
 			$reAccess = $this->access;
 			$access = "/.*".$reAccess.".*/i";
			$access = new MongoRegex($access);
 			$querys['access'] = $access;
 		}
		$this->pageTitle = '用户行为频管理';
	    $this->pager = new sfMondongoPager('UserBehavior', 20);
	    $this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
	    $this->pager->setPage($request->getParameter('page', 1));
	    $this->pager->init();
  	
	}
	/**
   * Get mongodb handler
   * @return mongo | object
   */
	public static $mdb = null;
	public function getMdb()
	{
		if(null == self::$mdb)
		{
			$mongo = $this->getMondongo();
			return self::$mdb = $mongo->getRepository("UserBehavior");
		}else
		{
			return self::$mdb;
    	}
	}
  
  
}
