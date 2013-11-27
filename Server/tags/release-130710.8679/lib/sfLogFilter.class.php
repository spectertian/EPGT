<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfBasicSecurityFilter checks security by calling the getCredential() method
 * of the action. Once the credential has been acquired, sfBasicSecurityFilter
 * verifies the user has the same credential by calling the hasCredential()
 * method of SecurityUser.
 *
 * @package    symfony
 * @subpackage filter
 * @author     Sean Kerr <sean@code-box.org>
 * @version    SVN: $Id: sfBasicSecurityFilter.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

require_once(dirname(__FILE__).'/model/mondongo/UserBehavior.php');
class sfLogFilter extends sfFilter 
{
	/*
     * VisitControl
     *
	 * @author tianzhongsheng
	 * @time 2013-05-07 15:00:00
	 * 
	 */
	public function execute($filterChain)
	{
		$context = $this->getContext();	
	    $request = $context->getRequest();
        
 		$userId = $this->context->getUser()->getAttribute('adminid');
		$userName = $this->context->getUser()->getAttribute('username');
		// $url = $request->getUri();
		// $moduleName = $this->context->getModuleName();
		// $actionName = $this->context->getActionName();
		$url = $request->getParameterHolder()->getAll();
		$module = $url['module'];
		$action = $url['action'];
		unset($url['module']);
		unset($url['action']);
		$access = $module.'/'.$action;
		$values= json_encode($url);
		if($userId != '' && $userName != '') {
			$userBehaviors = new UserBehavior();
			$userBehaviors->setUserId($userId);
			$userBehaviors->setUserName($userName);
			$userBehaviors->setAccess($access);
			$userBehaviors->setValues($values);
			$userBehaviors->setDate(date("Y-m-d H:i:s"));
			$userBehaviors->save();
		}
		$filterChain->execute();
	}
}
