<?php

/**
 * user actions.
 *
 * @package    epg
 * @subpackage user
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{

    /**
     * 长虹用户登陆
     * @param sfWebRequest $request
     */
    public function  executeLogin(sfWebRequest $request)
    {
        $url     = 'channel/index';
        $success = $request->getReferer() == null ? 'channel' : $request->getReferer();
        $user    = $this->getUser()->getAttribute('user_id');
        $login   = $this->getUser()->isAuthenticated();
        $key     = $request->getParameter('key');
        //a.不存在用户信息
        if(empty($user) || !$login) {
            //1.获取userAgent
            $agent = $request->getHttpHeader('user-agent');
            preg_match('~changhong.*?(\d+);chwebkit~', $agent, $user_key);
            //测试使用
            if (!empty($key)){
                $user_key = array('dev_user-agent',$key);
            }
            $ip     = Common::get_remote_ip();
                       
            $user_from  = 'changhong';
            //2.用户信息初始化
//            if(array_key_exists('1', $user_key)) {
//                $user   = Doctrine::getTable('User')->user_exist($user_key['1']);
//
//                if (empty($user)) {
//                    //根据IP查询城市
//                    $city   = Doctrine::getTable('Ip')->getCity($ip);
//                    //初始化用户
//                    $user   = Doctrine::getTable('User')->user_init($user_key['1'], $ip, $city->getCity(), $user_from, $city->getProvince());
//                }
//            }else{
                //根据IP查询城市
                $city   = Doctrine::getTable('Ip')->getCity($ip);
                $user  = array('id'=> 0,'user_key' => 'guest', 'user_from' => $user_from, 'city'=>$city->getCity(),'email' => 'guest@xxx.com', 'username' => 'guest', 'province' => $city->getProvince());
                $this->getUser()->setFlash('user_notice', 'guest用户');
//            }
            $this->getUser()->user_login($user);
        }
//        unset($user);
//        unset($agent);
//        unset($user_key);
//        unset($user_from);
//        unset($info);
        if ($request->getParameter('module') != 'user' && $request->getParameter('action') != 'login') {
            $this->forward($request->getParameter('module'), $request->getParameter('action'));
        }else{
            $this->redirect($success);
        }
    }
    
    /**
     * 用户退出
     * @param sfWebRequest $request
     */
    public function executeLogout(sfWebRequest $request)
    {
        $this->getUser()->user_login_out();
        return sfView::NONE;
    }
    
    /**
     * ajax 保存运营商id
     * Enter description here ...
     * @param unknown_type $param
     */
    public function executeSaveNetWorkId(sfWebRequest $request) {
    	if($request->isXmlHttpRequest()){
    		$netWorkId = $request -> getParameter('channelNetWordId');
    		if ($netWorkId){
    			$netWorkId = substr($netWorkId, -5);//截取二进制数后五位
    			$netWorkId = substr($netWorkId,strchr($netWorkId,'1'));//去前边的0
    			$this->getUser()->setAttribute('netWorkId',$netWorkId);
    			$return_status = array('success'=>'1');
    		}
    	}else{
	    	$return_status = array('success'=>'0');
    	}
		return $this->renderText(json_encode($return_status));
    }
    
}
