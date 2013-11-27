<?php
sfContext::getInstance()->getConfiguration()->loadHelpers('Common');        
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
  /*
   * 登入验证,将验证通过的用户信息存入SESSION并跳转至指定URL
   * @param sfWebRequest $request
   * @author: huang
   */
   /*
  public function executeLogin(sfWebRequest $request) {
     if($this->getUser()->isAuthenticated()==TRUE) {
        $this->redirect('user/user_feed');
     }
     
             //qqt
    $Qqt_api_config = sfConfig::get("app_weibo_Qqt");
    //var_dump($Qqt_api_config);
    $QqtWeibo = new QqtWeiboOAuth($Qqt_api_config['akey'], $Qqt_api_config['skey'], NULL, NULL);
    $url = $Qqt_api_config['callback_url'].'connect_save?type=Qqt';
    $Qqtkey = $QqtWeibo->getRequestToken($url);
    $this->getUser()->setAttribute('Qqt_oauth_token',  $Qqtkey['oauth_token']);
    $this->getUser()->setAttribute('Qqt_oauth_token_secret',  $Qqtkey['oauth_token_secret']);
    $aurl = $QqtWeibo->getAuthorizeURL($this->getUser()->getAttribute('Qqt_oauth_token') ,false , '');
    $this->Qqt = $aurl;
    //sina
    $Sina_api_config = sfConfig::get("app_weibo_Sina");
    $SinaWeibo = new SinaWeiboOAuth($Sina_api_config['akey'], $Sina_api_config['skey'], NULL, NULL);
    $Sinakey = $SinaWeibo->getRequestToken();
    $this->getUser()->setAttribute('Sina_oauth_token',  $Sinakey['oauth_token']);
    $this->getUser()->setAttribute('Sina_oauth_token_secret',  $Sinakey['oauth_token_secret']);
    $url = $Sina_api_config['callback_url'].'connect_save?type=Sina';
    $aurl = $SinaWeibo->getAuthorizeURL($this->getUser()->getAttribute('Sina_oauth_token') ,false , $url);
    $this->Sina = $aurl;
    
    $this->gourl = $request->getGetParameter("url");
    if($request->isMethod('POST')) {
        $username = trim($request->getPostParameter('username',0));
        $password = md5($request->getPostParameter('password',0));
        $gourl = trim($request->getPostParameter('gourl', 0));
        $remember = $request->getPostParameter('remember', false);  //记住我
        $mongo = $this->getMondongo();
        $user_reqpository = $mongo->getRepository('user');
        $user = $user_reqpository->login($username,$password);
        if($user){
            $this->getUser()->setAuthenticated(true);
            $this->getUser()->setAttribute('nickname',  $user->getNickname());
            $this->getUser()->setAttribute('email',  $user->getEmail());
            $this->getUser()->setAttribute('user_id', (String)$user->getId());
            $this->getUser()->setAttribute('type', (int) $user->getType());
            $avatar = $user->getAvatar();
            $this->getUser()->setAttribute('avatar', $avatar);
            
            if ($request->isXmlHttpRequest()) {
                return $this->renderText(1);
            }
        }else{
            if ($request->isXmlHttpRequest()) {
                return $this->renderText(0);
            } else {
                $this->getUser()->setFlash('error','你的用户名和密码不符，请再试一次！');
            }
        }
    }

    if( $this->getUser()->isAuthenticated() ) {
        if ($request->getReferer() == $request->getUri() || empty($gourl)) {
            $this->redirect('user/user_feed');
        } else {
            $this->redirect($gourl);
        }
    }
  }
  */
  
  /*
   * 接口注册
   * @param sfWebRequest $request
   * @author: lifucang
   */
  public function regHuanid($arr) {
        $postinfo=array('action'=>'UserRegisterTC','locale'=>'zh_CN','device'=>array('devinfo'=>'123'),'user'=>array('pwd'=>md5($arr['pass']),'phone'=>'','mobile'=>0,'logname'=>'','nickname'=>$arr['nickname'],'avatarid'=>1,'gender'=>1,'birthday'=>'','email'=>$arr['email'],'loginstatus'=>1));
        $postinfojson=json_encode($postinfo); 
		$getinfo = Common::post_user_json($postinfojson);
		$result = json_decode($getinfo,true);
        return $result['user']['huanid'];
  } 
  /*
   * 接口登录
   * @param sfWebRequest $request
   * @author: lifucang
   */
  public function loginHuanid($arr) {
        $postinfo=array('action'=>'UserLogin','device'=>array('dnum'=>0,'didtoken'=>''),'user'=>array('huanid'=>$arr['username'],'pwd'=>$arr['password'],'holdhuanid'=>1,'holdpwd'=>1,'autologin'=>$arr['autologin'],'loginstatus'=>1));
        $postinfojson=json_encode($postinfo);   
        $getinfo = Common::post_user_json($postinfojson);     
        $result = json_decode($getinfo,true);  
        return $result;
  }     
  /*
   * 接口用户详细信息获取
   * @param sfWebRequest $request
   * @author: lifucang
   */
  public function getuserinfoHuanid($arr) {
        $postinfo=array('action'=>'GetUserProfile','user'=>array('huanid'=>$arr['username'],'token'=>$arr['token']),'param'=>array('huanid'=>$arr['username']));
		$postinfojson=json_encode($postinfo); 
		$getinfo = Common::post_user_json($postinfojson);
        $userinfo = json_decode($getinfo,true);
        return $userinfo;
  } 
  /*
   * 接口更改密码
   * @param sfWebRequest $request
   * @author: lifucang
   */
  public function updatepassHuanid($arr) {
        $postinfo=array('action'=>'UpdateUserPassword','user'=>array('huanid'=>$arr['username'],'token'=>$arr['token']),'param'=>array('oldpwd'=>$arr['oldpwd'],'newpwd'=>$arr['newpwd']));
        $postinfojson=json_encode($postinfo);      
        $getinfo  = Common::post_user_json($postinfojson);
        $info = json_decode($getinfo,true);
        return $info;
  }   
  /*
   * 登入验证,将验证通过的用户信息存入SESSION并跳转至指定URL
   * @param sfWebRequest $request
   * @author: huang
   */
  public function executeLogin(sfWebRequest $request) {
     if($this->getUser()->isAuthenticated()==TRUE) {
        $this->redirect('user/user_feed');
     }
     /*
             //qqt
    $Qqt_api_config = sfConfig::get("app_weibo_Qqt");
    //var_dump($Qqt_api_config);
    $QqtWeibo = new QqtWeiboOAuth($Qqt_api_config['akey'], $Qqt_api_config['skey'], NULL, NULL);
    $url = $Qqt_api_config['callback_url'].'connect_save?type=Qqt';
    $Qqtkey = $QqtWeibo->getRequestToken($url);
    $this->getUser()->setAttribute('Qqt_oauth_token',  $Qqtkey['oauth_token']);
    $this->getUser()->setAttribute('Qqt_oauth_token_secret',  $Qqtkey['oauth_token_secret']);
    $aurl = $QqtWeibo->getAuthorizeURL($this->getUser()->getAttribute('Qqt_oauth_token') ,false , '');
    $this->Qqt = $aurl;
    //sina
    $Sina_api_config = sfConfig::get("app_weibo_Sina");
    $SinaWeibo = new SinaWeiboOAuth($Sina_api_config['akey'], $Sina_api_config['skey'], NULL, NULL);
    $Sinakey = $SinaWeibo->getRequestToken();
    $this->getUser()->setAttribute('Sina_oauth_token',  $Sinakey['oauth_token']);
    $this->getUser()->setAttribute('Sina_oauth_token_secret',  $Sinakey['oauth_token_secret']);
    $url = $Sina_api_config['callback_url'].'connect_save?type=Sina';
    $aurl = $SinaWeibo->getAuthorizeURL($this->getUser()->getAttribute('Sina_oauth_token') ,false , $url);
    $this->Sina = $aurl;
    */
    $this->gourl = $request->getGetParameter("url");
    if($request->isMethod('POST')) {
        $username = trim($request->getPostParameter('username',0));
        $password = md5($request->getPostParameter('password',0));
        $gourl = trim($request->getPostParameter('gourl', 0));
        $remember = $request->getPostParameter('remember', false);  //记住我
        if($remember)
            $autologin=1;
        else
            $autologin=0;
        //获取用户令牌
        $arrlogin=array('username'=>$username,'password'=>$password,'autologin'=>$autologin);
        $result = $this->loginHuanid($arrlogin);
        
        if($result['error']['code']==0){  //没有错误
            $token=$result['user']['token'];   //得到用户令牌
            
            //获取用户详细信息
            $arruserinfo=array('username'=>$username,'token'=>$token);
            $userinfo = $this->getuserinfoHuanid($arruserinfo);
            
            //查找该用户信息是否已在数据库中，如果没有，则插入数据库
            $query = array('username'=>$username); 
            $mongo = $this->getMondongo();
            $users = $mongo->getRepository("user")->findOne(array('query' => $query));         
            if(!$users){
                $user=new User();
                $user->setUsername($username);
                $user->setPassword($password);
                $user->setNickname($userinfo['user']['nickname']);
                $user->setEmail($userinfo['user']['email']);
                $user->setAvatar($userinfo['user']['headurl']);
                $user->setType(0);
                $user->save();
            }

            //记录用户信息
            $mongo = $this->getMondongo();
            $user_reqpository = $mongo->getRepository('user');
            $user = $user_reqpository->login($username,$password);
            if($user){
                $this->getUser()->setAuthenticated(true);
                $this->getUser()->setAttribute('username',  $user->getUsername());  //欢id
                $this->getUser()->setAttribute('token',  $token);                   //令牌
                $this->getUser()->setAttribute('nickname',  $user->getNickname());
                $this->getUser()->setAttribute('email',  $user->getEmail());
                $this->getUser()->setAttribute('user_id', (String)$user->getId());
                $this->getUser()->setAttribute('type', (int) $user->getType());
                $avatar = $user->getAvatar();
                $this->getUser()->setAttribute('avatar', $avatar);
                if ($request->isXmlHttpRequest()) {
                    return $this->renderText(1);
                }
            }else{
                if ($request->isXmlHttpRequest()) {
                    return $this->renderText(0);
                } else {
                    $this->getUser()->setFlash('error','你的用户名和密码不符，请再试一次！');
                }
            }
        }else{
            $this->getUser()->setFlash('error','你的用户名和密码不符，请再试一次！');
        }
    }

    if( $this->getUser()->isAuthenticated() ) {
        if ($request->getReferer() == $request->getUri() || empty($gourl)) {
            $this->redirect('user/user_feed');
        } else {
            $this->redirect($gourl);
        }
    }
  }


  /*
   * 退出登入，清空用户信息并跳转至登入URL
   * @param sfWebRequest $request
   * @author: huang
  */
  public function executeLogout(sfWebRequest $request){
      $this->getUser()->setAuthenticated(false);
      $this->getUser()->setAttribute('nickname','');
      $this->getUser()->setAttribute('email','');
      $this->getUser()->setAttribute('user_id','');
      $this->getUser()->setAttribute('avatar', '');
      $this->getUser()->setAttribute('type', '');
      $this->redirect($request->getReferer());
  }

  /*
   * 用户注册，需要注意的是这里在表单验证之前，先注销掉了re_password
   * @param sfWebRequest $request
   * @author: huang
  */
  public function executeReg(sfWebRequest $request) {
    $this->form = new UserForm(); 
    /*
    //qqt
    $Qqt_api_config = sfConfig::get("app_weibo_Qqt");
    //var_dump($Qqt_api_config);
    $QqtWeibo = new QqtWeiboOAuth($Qqt_api_config['akey'], $Qqt_api_config['skey'], NULL, NULL);
    $url = $Qqt_api_config['callback_url'].'connect_save?type=Qqt';
    $Qqtkey = $QqtWeibo->getRequestToken($url);
    $this->getUser()->setAttribute('Qqt_oauth_token',  $Qqtkey['oauth_token']);
    $this->getUser()->setAttribute('Qqt_oauth_token_secret',  $Qqtkey['oauth_token_secret']);
    $aurl = $QqtWeibo->getAuthorizeURL($this->getUser()->getAttribute('Qqt_oauth_token') ,false , '');
    $this->Qqt = $aurl;
    //sina
    $Sina_api_config = sfConfig::get("app_weibo_Sina");
    $SinaWeibo = new SinaWeiboOAuth($Sina_api_config['akey'], $Sina_api_config['skey'], NULL, NULL);
    $Sinakey = $SinaWeibo->getRequestToken();
    $this->getUser()->setAttribute('Sina_oauth_token',  $Sinakey['oauth_token']);
    $this->getUser()->setAttribute('Sina_oauth_token_secret',  $Sinakey['oauth_token_secret']);
    $url = $Sina_api_config['callback_url'].'connect_save?type=Sina';
    $aurl = $SinaWeibo->getAuthorizeURL($this->getUser()->getAttribute('Sina_oauth_token') ,false , $url);
    $this->Sina = $aurl;
    */
    
    if( $request->isMethod("POST") ) {
        $post_user_info = $request->getPostParameter('user');
        unset($post_user_info['re_password']);
        //Email手动验证唯一性
        $email = $post_user_info['email'];
        $request->setParameter('field', 'email');
        $request->setParameter('value',$email);
        $email_validator = $this->executeCheck_value($request);
        $province = $request->getPostParameter('province');
        $city = $request->getPostParameter('city');
        //Username手动验证唯一性
        //$username = $post_user_info['username'];
//        $request->setParameter('field', 'username');
//        $request->setParameter('value',$username);
//        $username_validator = $this->executeCheck_value($request);
        $this->form->bind( $post_user_info );
        if( !$email_validator['user'] ) {
            $this->getUser()->setFlash('error','您使用的Email已经被注册，请输入新的Email地址');
        }else{
            if( $this->form->isValid() ) {
                //##################写入欢网接口
                $arr=array('pass'=>$post_user_info['password'],'nickname'=>$post_user_info['nickname'],'email'=>$email);
                $this->huanid=$this->regHuanid($arr);
                //##################写入欢网接口          
                $user = $this->form->save();
                $user->setPassword(md5($post_user_info['password']));
                $user->setType(0);
                $user->setProvince($province);
                $user->setCity($city);
                $user->setTextPass($post_user_info['password']);
                $user->setUsername($this->huanid);  //加入接口后加的
                $user->save();
                //##################得到登录令牌，修改密码用
                $arrlogin=array('username'=>$this->huanid,'password'=>$user->getPassword(),'autologin'=>1);
                $result = $this->loginHuanid($arrlogin);
                $token=$result['user']['token'];   //得到用户令牌
                //##################得到登录令牌，修改密码用
                $this->getUser()->setFlash('success','注册成功！');
                $this->getUser()->setAuthenticated(true);
                $this->getUser()->setAttribute('username',  $this->huanid);  //欢id
                $this->getUser()->setAttribute('token',  $token);            //令牌
                $this->getUser()->setAttribute('email',$user->getEmail());
                $this->getUser()->setAttribute('user_id',$user->getId()->__toString());
                $this->getUser()->setAttribute('avatar', $user->getAvatar());
                $this->getUser()->setAttribute('nickname', $user->getNickname());
                $this->getUser()->setAttribute('type', (int)$user->getType());
                //$this->redirect('user/user_feed'); 
                $this->setTemplate('regok');
            }
        }
    }
    
  }

  /*
   * 用户数据唯一性验证,根据field和对应的value查询相应的数据
   * @param sfWebRequest $request
   * @return Array Or JSON
   * @author: huang
  */
  public function executeCheck_value(sfWebRequest $request){
      if( $request->isMethod("POST") ) {
          $value = $request->getParameter('value','');
          $field = $request->getParameter('field','');
          $mongo = $this->getMondongo();
          $user_mongo = $mongo->getRepository("User");
          $has_user = $user_mongo->check_value($field,$value);
          $result = array('user'=>$has_user);
          if($request->isXmlHttpRequest()){
              return $this->renderText(json_encode($result));
          }
          return $result;
      }
  }
  
  /**
   * 用户分享设置
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   */
  public function executeShare(sfWebRequest $request){
      if(!$this->getUser()->isAuthenticated() ) {
        $this->redirect('user/login');
      }
      $this->getResponse()->setTitle("用户分享设置 - 我爱电视");
      $user_id = $this->getUser()->getAttribute('user_id');
      $mongo = $this->getMondongo();
      $shareRep = $mongo->getRepository('UserShare');
      $this->shares = $shareRep->getAllShareByUserId($user_id);
      $this->weiboTypes = sfConfig::get("app_weibo_type");
      $this->sharelist = array();
      foreach($this->weiboTypes as $key => $list) {
        $this->sharelist[$key]['name'] = $key;
        $this->sharelist[$key]['enable'] = $shareRep->getShareBySname($user_id, $key);
      }
      foreach($this->weiboTypes as $key=>$type) {
          if($key=='Qqt'){ //stop
            $Qqt_api_config = sfConfig::get("app_weibo_Qqt");
            $QqtWeibo = new QqtWeiboOAuth($Qqt_api_config['akey'], $Qqt_api_config['skey'], NULL, NULL);
            $url = $Qqt_api_config['callback_url'].'shareSave?type=Qqt';
            $Qqtkey = $QqtWeibo->getRequestToken($url);
            $this->getUser()->setAttribute('Qqt_oauth_token',  $Qqtkey['oauth_token']);
            $this->getUser()->setAttribute('Qqt_oauth_token_secret',  $Qqtkey['oauth_token_secret']);
            $aurl = $QqtWeibo->getAuthorizeURL($this->getUser()->getAttribute('Qqt_oauth_token') ,false , '');
            $this->Qqt = $aurl;
            
          }
          if($key=='Sina') {
            $Sina_api_config = sfConfig::get("app_weibo_Sina");
            $SinaWeibo = new SinaWeiboOAuth($Sina_api_config['akey'], $Sina_api_config['skey'], NULL, NULL);
            $Sinakey = $SinaWeibo->getRequestToken();
            //var_dump($Sinakey);
            $this->getUser()->setAttribute('Sina_oauth_token',  $Sinakey['oauth_token']);
            $this->getUser()->setAttribute('Sina_oauth_token_secret',  $Sinakey['oauth_token_secret']);
            $url = $Sina_api_config['callback_url'].'shareSave?type=Sina';
            $aurl = $SinaWeibo->getAuthorizeURL($this->getUser()->getAttribute('Sina_oauth_token') ,false , $url);
            $this->Sina = $aurl;
          }
      }
  }
  
  /**
   * 用户分享的保存
   * @param sfWebRequest $request
   * @author lizhi
   * @return void
   */
  public function executeShareSave(sfWebRequest $request){
      if(!$this->getUser()->isAuthenticated() ) {
        $this->redirect('user/login');
      }
      $user_id = $this->getUser()->getAttribute('user_id');
      if(!empty($user_id) && !empty($_GET['oauth_verifier'])){
          if(trim($_GET['type'])=='Sina') {
              $this->sina_api_config = sfConfig::get("app_weibo_Sina");
              $o = new SinaWeiboOAuth( $this->sina_api_config['akey'], $this->sina_api_config['skey'], $this->getUser()->getAttribute('Sina_oauth_token') , $this->getUser()->getAttribute('Sina_oauth_token_secret'));
              $last_key = $o->getAccessToken($_GET['oauth_verifier']) ;
              $mongo =  $this->getMondongo();
              $usershareRep = $mongo->getRepository("UserShare");
              $haveSave = $usershareRep->checkShare($user_id, 1); // maybe have a bug.
              if($haveSave==false){
                  $usershare = new UserShare();
                  $usershare->setUserId($user_id);
                  $usershare->setStype(1);
                  $usershare->setSname('Sina');
                  $usershare->setUserinfo($last_key['user_id']);
                  $usershare->setAccecssToken($last_key['oauth_token']);
                  $usershare->setAccecssTokenSecret($last_key['oauth_token_secret']);
                  $usershare->save();
              }else{
                  $usershare = $usershareRep->findOneById($haveSave->getId());
                  $usershare->setSname('Sina');
                  $usershare->setUserinfo($last_key['user_id']);
                  $usershare->setAccecssToken($last_key['oauth_token']);
                  $usershare->setAccecssTokenSecret($last_key['oauth_token_secret']);
                  $usershare->save();
              }
              $this->getUser()->setFlash('success','您的新浪微薄关联成功了！');
          }elseif(trim($_GET['type'])=='Qqt') {
              $this->Qqt_api_config = sfConfig::get("app_weibo_Qqt");
              $o = new QqtWeiboOAuth( $this->Qqt_api_config['akey'], $this->Qqt_api_config['skey'], $this->getUser()->getAttribute('Qqt_oauth_token') , $this->getUser()->getAttribute('Qqt_oauth_token_secret'));
              $last_key = $o->getAccessToken($_GET['oauth_verifier']);
              $mongo =  $this->getMondongo();
              $usershareRep = $mongo->getRepository("UserShare");
              $haveSave = $usershareRep->checkShare($user_id, 2);
              if($haveSave==false){
                  $usershare = new UserShare();
                  $usershare->setUserId($user_id);
                  $usershare->setStype(2);
                  $usershare->setSname('Qqt');
                  $usershare->setUserinfo($last_key['name']);
                  $usershare->setAccecssToken($last_key['oauth_token']);
                  $usershare->setAccecssTokenSecret($last_key['oauth_token_secret']);
                  $usershare->save();
              }else{
                  $usershare = $usershareRep->findOneById($haveSave->getId());
                  $usershare->setSname('Qqt');
                  $usershare->setUserinfo($last_key['name']);
                  $usershare->setAccecssToken($last_key['oauth_token']);
                  $usershare->setAccecssTokenSecret($last_key['oauth_token_secret']);
                  $usershare->save();
              }
              $this->getUser()->setFlash('success','您的腾讯微薄关联成功了！');
          }
      }
  }
  
  /**
   * Sina登录操作
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   */
  public function executeConnect(sfWebRequest $request) {
            //qqt
            $Qqt_api_config = sfConfig::get("app_weibo_Qqt");
            $QqtWeibo = new QqtWeiboOAuth($Qqt_api_config['akey'], $Qqt_api_config['skey'], NULL, NULL);
            $url = 'http://tv.com/frontend_dev.php/user/connect_save?type=Qqt';
            $Qqtkey = $QqtWeibo->getRequestToken($url);
            $this->getUser()->setAttribute('Qqt_oauth_token',  $Qqtkey['oauth_token']);
            $this->getUser()->setAttribute('Qqt_oauth_token_secret',  $Qqtkey['oauth_token_secret']);
            $aurl = $QqtWeibo->getAuthorizeURL($this->getUser()->getAttribute('Qqt_oauth_token') ,false , '');
            $this->Qqt = $aurl;
            //sina
            $Sina_api_config = sfConfig::get("app_weibo_Sina");
            $SinaWeibo = new SinaWeiboOAuth($Sina_api_config['akey'], $Sina_api_config['skey'], NULL, NULL);
            $Sinakey = $SinaWeibo->getRequestToken();
            $this->getUser()->setAttribute('Sina_oauth_token',  $Sinakey['oauth_token']);
            $this->getUser()->setAttribute('Sina_oauth_token_secret',  $Sinakey['oauth_token_secret']);
            $url = 'http://tv.com/frontend_dev.php/user/connect_save?type=Sina';
            $aurl = $SinaWeibo->getAuthorizeURL($this->getUser()->getAttribute('Sina_oauth_token') ,false , $url);
            $this->Sina = $aurl;
  }
  
  /**
   * connection save
   * @param sfWebRequest $request
   * @author lizhi
   * @return void
   */
  public function executeConnect_save(sfWebRequest $request) {
      $this->type = $request->getParameter('type');
      $this->oauth_token = $request->getParameter('oauth_token');
      $this->oauth_verifier = $request->getParameter('oauth_verifier');
      if(empty($this->type) || empty($this->oauth_verifier)){
          return false;
      }
      if($this->type=='Sina'){
         $this->sina_api_config = sfConfig::get("app_weibo_Sina");
         $o = new SinaWeiboOAuth( $this->sina_api_config['akey'], $this->sina_api_config['skey'], $this->getUser()->getAttribute('Sina_oauth_token') , $this->getUser()->getAttribute('Sina_oauth_token_secret'));
         $last_key = $o->getAccessToken($_GET['oauth_verifier']) ;
         $mongo = $this->getMondongo();
         $usershareRep = $mongo->getRepository('UserShare');
         $usershare = $usershareRep->getShareByUserinfo($last_key["user_id"], $this->type);
      }
      if($this->type=='Qqt') {
         $this->qqt_api_config = sfConfig::get("app_weibo_Qqt");
         $o = new QqtWeiboOAuth( $this->qqt_api_config['akey'], $this->qqt_api_config['skey'], $this->getUser()->getAttribute('Qqt_oauth_token') , $this->getUser()->getAttribute('Qqt_oauth_token_secret'));
         $last_key = $o->getAccessToken($_GET['oauth_verifier']) ;
         $mongo = $this->getMondongo();
         $usershareRep = $mongo->getRepository('UserShare');
         $usershare = $usershareRep->getShareByUserinfo($last_key["name"], $this->type);
      }
         if($usershare==false) {
             if($this->type=='Sina'){
                 //get user info from sina xml
                 $sinaInfo = $o->oAuthRequest('http://api.t.sina.com.cn/account/verify_credentials.xml', 'GET',array());
                 $sinaInfo = simplexml_load_string($sinaInfo);
                 $sinaInfo = (array)$sinaInfo;
                 var_dump($sinaInfo);exit;
                //save avatar
                 $context = array(
                      'http'=>array(
                            'method'=>"GET",
                            'header'=> "Accept-Language: zh-cn,zh;q=0.5 \r\n".
                            "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18\r\n".
                            //"Referer: http://movie.douban.com/photos/photo/\r\n".
                            //"Referer: $Referer\r\n".
                            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8 \r\n".
                            "Connection: keep-alive \r\n"
                          )
                 );
                 $context = stream_context_create($context);
                 $picContent = file_get_contents($sinaInfo['profile_image_url'].'.jpg', false, $context);
                 $key = time().rand(100, 999);
                 $picType = '.jpg';
                 $fileName = $key.$picType;
                 $temp = "/tmp/".$fileName;
                //$temp = $fileName;
                //写入临时文件
                @file_put_contents($temp,$picContent);
                $storage = StorageService::get('photo');
                $storage->save($fileName,$temp);
                @unlink($temp);
                 $user = new User();
                 $user->setDesc($sinaInfo['description']);
                 $user->setNickname($sinaInfo['screen_name']);
                 $user->setAvatar($fileName);
                 $user->setType(1);
                 $password = generatePassword();
                 $user->setPassword(md5($password));
                 $user->setTextpass($password);
                 $user->save();
                 //save user

                 //save user share
                  $usershare = new UserShare();
                  $usershare->setUserId($user->getId());
                  $usershare->setStype(1);
                  $usershare->setSname('Sina');
                  $usershare->setUserinfo($sinaInfo['id']);
                  $usershare->setAccecssToken($last_key['oauth_token']);
                  $usershare->setAccecssTokenSecret($last_key['oauth_token_secret']);
                  $usershare->save();
             }
             if($this->type=='Qqt'){
                 $qqtInfo = $o->oAuthRequest('http://open.t.qq.com/api/user/info?format=xml', 'GET',array());
                 $qqtInfo = json_decode($qqtInfo, true);
                 $qqtInfo = $qqtInfo['data'];
   
                 $user = new User();
                 $user->setDesc($qqtInfo['introduction']);
                 $user->setNickname($qqtInfo['nick']);
                 //$user->setAvatar($fileName);
                 $password = generatePassword();
                 $user->setType(1);
                 $user->setPassword(md5($password));
                 $user->setTextpass($password);
                 //$user->setUsername('qqt_'.$qqtInfo['name']);
                 $user->save();
                 //save user

                 //save user share
                  $usershare = new UserShare();
                  $usershare->setUserId($user->getId());
                  $usershare->setStype(2);
                  $usershare->setSname('Qqt');
                  $usershare->setUserinfo($qqtInfo['name']);
                  $usershare->setAccecssToken($last_key['oauth_token']);
                  $usershare->setAccecssTokenSecret($last_key['oauth_token_secret']);
                  $usershare->save();
             }
         }
         
        $userRep = $mongo->getRepository('User');
        $user = $userRep->findOneById(new MongoId($usershare->getUserId()));
        $this->getUser()->setAuthenticated(true);
        $this->getUser()->setAttribute('username',  $user->getUsername());
        $this->getUser()->setAttribute('email',  $user->getEmail());
        $this->getUser()->setAttribute('user_id', (String)$user->getId());
        $this->getUser()->setAttribute('nickname', $user->getNickname());
        $avatar = $user->getAvatar();

        $this->getUser()->setAttribute('avatar', $avatar);
        $this->redirect('user/user_feed');
  }
  
  /**
  * 取消授权
  * @param sfWebRequest $request
  * @return void
  * @author lizhi
  */
  public function executeCleanShare(sfWebRequest $request) {
    if(!$this->getUser()->isAuthenticated() ) {
        $this->redirect('user/login');
    }
    $this->user_id = $this->getUser()->getAttribute('user_id');
    $this->type = $request->getPostParameter('type');
    $mongo = $this->getMondongo();
    $usershareRep = $mongo->getRepository('UserShare');
    $sharedel = $usershareRep->getShareBySname($this->user_id, $this->type);
    $res=$sharedel->delete();
    if($request->isXmlHttpRequest()){
        if($res) {
            $this->getUser()->setFlash('error', "删除失败！");
            return $this->renderText(0);
        }else{
            $this->getUser()->setFlash('success', "删除成功！");
            return $this->renderText(1);
        }
    }
    return sfView::NONE;
    //$this->setTemplate("share");
  }
  
  /**
  * 删除头像
  * @param sfWebRequet $request
  * @author lizhi
  * return void
  */
  public function executeClean_avatar(sfWebRequest $request) {
    if(!$this->getUser()->isAuthenticated() ) {
        $this->redirect('user/login');
    }
    $this->user_id = $this->getUser()->getAttribute('user_id');
    $mongo = $this->getMondongo();
    $userRep = $mongo->getRepository('User');
    $user = $userRep->findOneById(new MongoId($this->user_id));
    $user->setAvatar(NULl);
    $this->getUser()->setAttribute('avatar', $user->getAvatar());
    $res = $user->save();
    if($request->isXmlHttpRequest()){
        if($res) {
            return $this->renderText(0);
        }else{
            return $this->renderText(1);
        }
    }
    return sfView::NONE;
  }
  
  
  /**
  * 片单
  * @param sfWebRequest $request
  * @author lizhi
  * @return void
  */
  public function executeCliplist(sfWebRequest $request) {
    $this->user_id = $request->getParameter('uid', 0);
    if($this->user_id==0) {
        if(!$this->getUser()->isAuthenticated() ) {
            $this->redirect('user/login');
        }
        $this->user_id = $this->getUser()->getAttribute('user_id');
    }
    $mongo = $this->getMondongo();
    $userRep = $mongo->getRepository("User");
    $this->user = $userRep->findOneById(new MongoId($this->user_id));
    $this->type=$request->getParameter('type', "default");
    if($this->user==NULL){
        $this->getResponse()->setTitle("用户片单 - 我爱电视");
    }else{
        $this->getResponse()->setTitle($this->user->getNickname()." 的片单 - 我爱电视");
    }
    $mongo = $this->getMondongo();
    switch($this->type) {
        case 'default':
            $this->chipPager = new sfMondongoPager('SingleChip', 10);
            $this->chipPager->setFindOptions(array('query'=>array('user_id'=>$this->user_id,'is_public'=>true),'sort'=>array('created_at' => -1)));
            $this->chipPager->setPage($request->getParameter('page', 1));
            $this->chipPager->init();
        break;
       case 'watched':   
       case 'like':
           $this->chipPager = new sfMondongoPager('Comment', 10);
           $this->chipPager->setFindOptions(array('query'=>array('user_id'=>$this->user_id,'is_publish'=>true,'type'=>$this->type), 'sort'=>array('created_at' => -1)));
           $this->chipPager->setPage($request->getParameter('page', 1));
           $this->chipPager->init();
    }
  }
  
  /**
  * 用户动态
  * @param sfWebRequest $request
  * @author lizhi
  * @return void
  */
  public function executeUser_feed(sfWebRequest $request) {
    $user_id = $request->getParameter("uid", 0);
    if($user_id==0){
        if($this->getUser()->isAuthenticated()==false) {
            $this->redirect('user/login');
        }
        $user_id = $this->getUser()->getAttribute('user_id');
        $this->myself = 1;
    }
    //$this->username = $this->getUser()->getAttribute('username');
    $mongo = $this->getMondongo();
    $this->user = $mongo->getRepository("User")->findOneById(new MongoId($user_id));
    if($this->user==NULL){
        $this->getResponse()->setTitle("我的片单 - 我爱电视");
    }else{
        $this->getResponse()->setTitle($this->user->getNickname()."的片单 - 我爱电视");
    }
    $this->page = $request->getParameter("page",0);
    $this->commentList = $this->getUserComment($user_id);
  }
  
  public function executeLoad_comment(sfWebRequest $request){
     if ($request->isXmlHttpRequest()) {
         $user_id = $request->getParameter("uid");
         $page = $request->getParameter("page",1);
         $dataType = $request->getParameter("dataType","html");
         $commentList = $this->getUserComment($user_id,$page);
         if($dataType == "html"){
             return $this->renderPartial("comments_list",array('comments' => $commentList, 'page' => $page));
         }else{
             return json_decode($commentList);
         }
     }
  }

  /**
   * 增加一个检查是否有更多的评论
   * @param sfWebRequest $request
   * @return <type>
   * @author luren
   */
  public function executeCheck_more(sfWebRequest $request) {
      if ($request->isXmlHttpRequest()) {
          $user_id = $request->getParameter("uid");
          $page = $request->getParameter("page",2);
          $skip = ($page-1) * 10;
          $mongo = $this->getMondongo();
          $commentRep = $mongo->getRepository("Comment");
          $comments = $commentRep->getCommentsByUserId($user_id, $skip, 1);
          if ($comments) {
              return $this->renderText(1);
          }else {
              return $this->renderText(0);
          }
      }
  }

  public function getUserComment($user_id,$page = 1,$limit = 10){
        $mongo = $this->getMondongo();
        $commentRep = $mongo->getRepository("Comment");
        $skip = ($page - 1) * $limit;
        $comments = $commentRep->getCommentsByUserId($user_id, $skip, $limit);
        $commentList = array();
        if($comments) {
          foreach($comments as $key => $comment) {
              if($comment->getWiki()==NULL) continue;
              $commentList[$key]['wiki'] = $comment->getWiki();
              $commentList[$key]['son'] = $comment->getSonComments();
              $commentList[$key]['sontotal'] = $comment->getSonCommentsCount();
              $commentList[$key]['type'] = $comment->getType();
              $commentList[$key]['text'] = $comment->getText();
              $commentList[$key]['time'] = $comment->getCreatedAt();
              $commentList[$key]['id'] = $comment->getId();
              $commentList[$key]['user'] = $comment->getUser();
          }
        }
        return $commentList;
  }
  /**
  * 用户channel
  * @param sfWebRequest $request
  * @author lizi
  * @return void
  */
  public function executeUser_channel(sfWebRequest $request) {
    $this->user_id = $request->getParameter('uid', 0);
    if($this->user_id==0){
        if(!$this->getUser()->isAuthenticated() ) {
            $this->redirect('user/login');
        }
        $this->user_id = $this->getUser()->getAttribute('user_id');
    }
    $mongo = $this->getMondongo();
    $userRep = $mongo->getRepository("User");
    $this->user = $userRep->findOneById(new MongoId($this->user_id));
    if($this->user==NULL){
        $this->getResponse()->setTitle("用户频道 - 我爱电视");
    }else{
        $this->getResponse()->setTitle($this->user->getNickname()." 的频道 - 我爱电视");
    }
    $mongo = $this->getMondongo();
    $channelFavoritesRep = $mongo->getRepository('ChannelFavorites');
    $this->type = $request->getParameter("type");
    if($this->type==NULL) {
        $this->type = 'default';
        $options['query'] = array(
            'user_id' => $this->user_id
        );
    }else{
        $options['query'] = array(
            'user_id' => $this->user_id,
            'channel_type' => $this->type
        );
    }
    $this->mychannelfavorites = $channelFavoritesRep->getChannelByUserChannelType($this->user_id, $this->type);
    if($this->mychannelfavorites==NULL) {
     $this->channels = NULL;
    }else{
        foreach($this->mychannelfavorites as $key=> $channel) {
            $channelcode[] = $channel->getChannelCode();
        }
        $this->channels = Doctrine::getTable('Channel')->findInCodes($channelcode);
    }
  }
  
  /**
  * 通过code 过来进行相应的删除
  * @author lizhi
  */
  public function executeRemovechannel(sfWebRequest $request) {
    if(!$this->getUser()->isAuthenticated() ) {
        $this->redirect('user/login');
    }
    $this->user_id = $this->getUser()->getAttribute('user_id');
    $mongo = $this->getMondongo();
    $channelFavoritesRep = $mongo->getRepository('ChannelFavorites');
    $this->code = $request->getPostParameter('code');
    $channel_id = $channelFavoritesRep->getOneChannelByUCode($this->user_id, $this->code);
    if($channel_id && $request->isXmlHttpRequest()) {
        $channel_id->delete();
        return $this->renderText(1);
    }else{
        return $this->renderText(0);
   }
  }
  
  /**
   * 通过ID删除相应的评议
   * @param string id
   * @return void
   * @author lizhi
   */
   public function executeDelcomment(sfWebRequest $request) {
    if($this->getUser()->isAuthenticated()==false) {
        $this->redirect('user/login');
    }
    $this->user_id = $this->getUser()->getAttribute('user_id');
    $id = $request->getPostParameter('id');
    $mongo = $this->getMondongo();
    $commentRep = $mongo->getRepository('Comment');
    $comment = $commentRep->getCommentByUserId($this->user_id, $id);
    if($comment && $request->isXmlHttpRequest()){
        $parent_wiki = $comment->getWiki();
        $action = $comment->getType();
        if($action=='replay'){
            $parent_wiki->setActionValue($action, false);
            $parent_wiki->save();
        }
        $comment->delete();
       // $this->getUser()->setFlash('success', "删除成功！");
        return $this->renderText(1);
    }else{
        //$this->getUser()->setFlash('error', "删除失败！");
        return $this->renderText(0);
    }
    return sfView::NONE;
   }
  
  /**
  * 频道收藏
  * @param sfWebRequest $request
  * @return void
  * @author lizhi
  */
  public function executeChannelFavorites(sfWebRequest $request) {
    if(!$this->getUser()->isAuthenticated() ) {
        $this->redirect('user/login');
    }
    $this->user_id = $this->getUser()->getAttribute('user_id');
    $mongo = $this->getMondongo();
    $channelFavoritesRep = $mongo->getRepository('ChannelFavorites');
    $this->mychannelfavorites = $channelFavoritesRep->getChannelByUserId($this->user_id, 0, 100);
    var_dump($this->mychannelfavorites);
    return sfView::NONE;
  }
  
  /**
  * 处理用户更新内容
  * @param sfWebRequest $request
  * @return void
  * @author lizhi
  */
  public function executeUpdateInfo(sfWebRequest $request) {
    if(!$this->getUser()->isAuthenticated() ) {
        $this->redirect('user/login');
    }
    $this->user_id = $this->getUser()->getAttribute('user_id');
    $this->username = $this->getUser()->getAttribute('username');

    $this->getResponse()->setTitle($this->username."后台中心 - 我爱电视");
    $mongo = $this->getMondongo();
    $userRep = $mongo->getRepository("user");
    //$user = $userRep->getUserById(new MongoId($this->user_id));
    $user = $userRep->findOneById(new MongoId($this->user_id));
    //var_dump($user);
    $this->email = $user->getEmail();
    $this->nickname = $user->getNickname();
    $this->city = $user->getCity();
    $this->province = $user->getProvince();
    $this->desc = $user->getDesc();
    $this->type = $user->getType();

    if($request->isMethod("POST")) {
        $file = $request->getFiles("avatar");
        $desc = $request->getPostParameter('desc');
        $city = $request->getPostParameter('city');
        $province = $request->getPostParameter('province');
        $nickName = $request->getPostParameter('nickname');
        if($desc){
            $user->setDesc($desc);
        }
        if($city) {
            $user->setCity($city);
        }
        if($province) {
            $user->setProvince($province);
        }
        if($nickName) {
            $user->setNickName($nickName);
        }
        if($file['name']!=NULL) {  
            $storage = StorageService::get('photo');
            $file_name = $file['name'];
            $file_ext_tmp = explode('.',$file_name);
            $file_ext = strtolower(array_pop($file_ext_tmp));
            $key = time().rand(100, 999);
            $res=$storage->save($key.'.'.$file_ext,$file['tmp_name']);
            $user->setAvatar($key.'.'.$file_ext);
            $this->getUser()->setAttribute('avatar', $key.'.'.$file_ext);
        }
        $user->save();
        $this->getUser()->setFlash('success', "信息修改成功");
        $this->redirect('user/updateInfo');
    } 
  }
  
  /**
   * 更新头像操作
   * @param sfWebRequest $request
   * @author lizhi
   * @return void
   */
  public function executeUpdate_avatar(sfWebRequest $request) {
    if($this->getUser()->isAuthenticated()==false) {
        $this->redirect('user/login');
    }
    $pic = $request->getPostParameter('pic');
    $this->user_id = $this->getUser()->getAttribute('user_id');
    $mongo = $this->getMondongo();
    $userRep = $mongo->getRepository("user");
    $this->user = $userRep->findOneById(new MongoId($this->user_id));
    if($request->isMethod('POST') && $pic=='upload'){
        $file = $request->getFiles('picfile');
        if($file['name']!=NULL) {  
            $storage = StorageService::get('photo');
            $file_name = $file['name'];
            $file_ext_tmp = explode('.',$file_name);
            $file_ext = strtolower(array_pop($file_ext_tmp));
            $key = time().rand(100, 999);
            $res=$storage->save($key.'.'.$file_ext,$file['tmp_name']);
            $this->user->setAvatar($key.'.'.$file_ext);
            $this->getUser()->setAttribute('avatar', $key.'.'.$file_ext);
        }
        $this->user->save();
        $this->getUser()->setFlash('success', '头像上传成功！');
        $this->redirect('user/update_avatar');
    }
    if($request->isMethod('POST') || $pic=='save') {
        if ($request->isXmlHttpRequest()){
            $x1 = $request->getParameter("x1");
            $y1 = $request->getParameter("y1");
            $width = $request->getParameter("width");
            $height = $request->getParameter("height");
            $url = $request->getParameter("url");
//            $category_id = $request->getParameter("category_id");
//            $key = time().rand(100, 999);
//            $fileName = $key.'.jpg';
            $lenth = strrpos($url,"/");
            $fileName = substr($url,$lenth+1);
            $dstFile = "/tmp/".$fileName;
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
            ImageService::cut_pic($url, $dstFile, $width, $height, $x1, $y1,array("width"=>160,"height"=>160));
            //保存
            $storage = StorageService::get('photo');
            //$storage->save($fileName,$dstFile);
            //@unlink($dstFile);
            $this->user->setAvatar($fileName);
            $key = time().rand(100, 999);
            $this->user->setAvatar($key.'.'.$file_ext);
            $this->user->setOriginalAvatar($fileName);
            $storage->save($key.'.'.$file_ext,$dstFile);
            $this->user->save();
            $this->getUser()->setAttribute('avatar', $key.'.'.$file_ext);
            $this->getUser()->setFlash('success', '头像裁剪成功！');
            return $this->renderText(json_encode($url));
        }
    }
  }
  
  /**
  * 忘记密码
  * @param sfWebRequest $request
  * @author lizhi
  * @return void
  */
  public function executeLostPassword(sfWebRequest $request) {
      $this->getResponse()->setTitle("找回密码 - 我爱电视");
      //var_dump($request->isMethod("POST"));
      if($request->isMethod("POST")){
        $userEmail = $request->getPostParameter('useremail');
        $request->setParameter('field', 'email');
        $request->setParameter('value',$userEmail);
        $validator = $this->executeCheck_value($request); //check email validator
        
        if($validator['user']==true){
            $this->getUser()->setFlash('success','系统中并没有相应的邮件地址');
        }else {
          //如果我们在数据仓库中设置了相应的密码，就直接发送到他们的邮箱中
          if(sfConfig::get("app_email_enable")==true){
              $to='chinawolfs@hotmail.com';
              $subject = 'Hello';
              $message = 'Test';
              $mail = new Mail($to, $subject, $message);
              if($mail){
                  $this->getUser()->setFlash('success', "邮件发送成功");
              }
          }
        }
      }
  }
  
  /**
  * 修改密码
  * @param sfWebRequest $request
  * @return void
  * @author lizhi
  */
  public function executeUpdatePassword(sfWebRequest $request) {
      if($this->getUser()->isAuthenticated()==false) {
         $this->redirect('user/login');
      }
      $user_id = $this->getUser()->getAttribute('user_id');
      $mongo =  $this->getMondongo();
      $userRep = $mongo->getRepository("User");
      $this->user = $userRep->findOneById(new MongoId($user_id));
      $username = $this->getUser()->getAttribute('username');
      $token = $this->getUser()->getAttribute('token');
      $this->getResponse()->setTitle("修改密码 - 我爱电视");
      $errorflag=false;  //是否有错误
      if( $request->isMethod("POST") && !empty($user_id) ){
         $input['oldpassword'] = $request->getPostParameter('oldpassword');
         $request->setParameter('field', 'password');
         $request->setParameter('value',md5($input['oldpassword']));
         /*从接口判断，该处可不用判断
         $password_validator = $this->executeCheck_value($request);
         
         if($password_validator['user']==true){
             //var_dump($password_validator);
             $this->getUser()->setFlash('error','原密码错误');
             $errorflag=true; //有错误
         }
         */
         $input['newpassword'] = $request->getPostParameter('newpassword');
         $input['renewpassword'] = $request->getPostParameter('renewpassword');
         if($input['newpassword'] !== $input['renewpassword']){
             $this->getUser()->setFlash('error', '两次密码不一样，请重新输入');
             $errorflag=true; //有错误
         }
         if(!$errorflag){
             //设置欢网接口密码
             $arrpass=array('username'=>$username,'token'=>$token,'oldpwd'=>md5($input['oldpassword']),'newpwd'=>md5($input['newpassword']));
             $returninfo=$this->updatepassHuanid($arrpass);
             if($returninfo['error']['code']!=0){
                 $this->getUser()->setFlash('error',$returninfo['error']['info']);
             }else{
                 $this->user->setPassword(md5($input['newpassword']));
                 $this->user->setTextPass($input['newpassword']);
                 $this->user->save();  
                               
                 $this->getUser()->setFlash('success', '密码修改成功,请重新登录');
                 $this->getUser()->setAuthenticated(false);
                 $this->getUser()->setAttribute('nickname','');
                 $this->getUser()->setAttribute('email','');
                 $this->getUser()->setAttribute('user_id','');
             } 
         }
         //$this->setTemplate("dashboard_index");
         if($this->getUser()->isAuthenticated()==false) {
            $this->redirect('user/login');
         }
      }
  }
    /**
   * 用户添加片单信息
   * @param sfWebRequest $request
   * @author lizhi
   * @return void
   */
  
  public function executeAddchip(sfWebRequest $request){
      $this->user_id = $this->getUser()->getAttribute("user_id");
      $this->username = $this->getUser()->getAttribute("username");
      $chip = new SingleChip();
      $chip->setName("Hello World");
      $chip->setUserId($this->user_id);
      $chip->setUserName($this->username);
      $value['4d2d4998c8ab2b2c0a000042'] = array('wiki_id'=>'4d2d4998c8ab2b2c0a000042', 'time'=>'14:16');
      $value['4d2d4946c8ab2b2c0a00003e'] = array('wiki_id'=>'4d2d4946c8ab2b2c0a00003e', 'time'=>'14:17');
      $chip->setWikiList($value);
      $chip->save();
  }
  /**
  * 取消片单
   * @param sfWebRequest $request
   * @author lizhi
   * @return void
   */
   public function executeCancel_chip(sfWebRequest $request) {
      if($this->getUser()->isAuthenticated()==false) {
         $this->redirect('user/login');
      }
      $wiki_id = $request->getPostParameter('wiki_id');
      $this->user_id = $this->getUser()->getAttribute("user_id");
      $mongo = $this->getMondongo();
      $commentRepository = $mongo->getRepository('Comment');
      $comment = $commentRepository->getOneComment($this->user_id, $wiki_id, 'queue');
      if ($comment)
      $chipRep = $mongo->getRepository('SingleChip');
      $chip = $chipRep->getOneChip($this->user_id, $wiki_id);
      if($request->isXmlHttpRequest()){
          if($chip && $comment) {
              $comment->delete();
              $chip->delete();
              return $this->renderText(1);
          }else{
              return $this->renderText(0);
          }
      }
   }
   
   /**
   * 处理成为看过
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   */
   public function executeWatched(sfWebRequest $request) {
      if($this->getUser()->isAuthenticated()==false) {
         $this->redirect('user/login');
      }
      $wiki_id = $request->getPostParameter('wiki_id');
      $this->user_id = $this->getUser()->getAttribute("user_id");
      $mongo = $this->getMondongo();
      $commentRepository = $mongo->getRepository('Comment');
      $comment = $commentRepository->getOneComment($this->user_id, $wiki_id, 'watched');
      if($request->isXmlHttpRequest()) {
        if($comment) {
            return $this->renderText(1);
        }else{
            $item = new Comment();
            $item->setUserId($this->user_id);
            $item->setWikiId($wiki_id);
            $item->SetType("watched");
            $item->setText("我看过喔!");
            $item->setIsPublish(TRUE);
            $item->setParentId(0);
            $item->save();
            return $this->renderText(2);
        }
      }
      return $this->renderText(0);
   }
  
  /**
   * 用户留言
   * @param sfWebRequest $request
   * @return void
   * @author lizhi
   */
  public function executeComment(sfWebRequest $request){
      $this->user_id = $this->getUser()->getAttribute('user_id');
      $this->username = $this->getUser()->getAttribute('username');
      $this->wiki_id = '4d2d4998c8ab2b2c0a000042';
      $this->parent_id = '0';
      $mongo = $this->getMondongo();
      $commentRep = $mongo->getRepository("Comment");
      $this->wikicomments = $commentRep->getAllCommentsByWikiId($this->wiki_id);
      $this->wikicom = $commentRep->getAllComment($this->wiki_id);
      $this->sina_api_config = sfConfig::get("app_weibo_sina");
      $shareRep = $mongo->getRepository('UserShare');
      $this->share = $shareRep->getAllShareByUserId($this->user_id);
      if($this->share != false){
      $this->shareitem = array();
          foreach($this->share as $sharlist){
              $this->shareitem[$sharlist->getSname()]['sname'] = $sharlist->getSname();
              $this->shareitem[$sharlist->getSname()]['accecss_token'] = $sharlist->getAccecssToken();
              $this->shareitem[$sharlist->getSname()]['accecss_token_secret'] = $sharlist->getAccecssTokenSecret();
              $client = WeiboClient::factory($sharlist->getSname(), $this->sina_api_config['akey'], $this->sina_api_config['skey'], $sharlist->getAccecssToken(), $sharlist->getAccecssTokenSecret());
          }
          
      }
     // $client = WeiboClient::factory('Sina', $akey , $skey , $accecss_token , $accecss_token_secret);
      if($request->isMethod("POST") && !empty($this->user_id)){
          $text = $request->getPostParameter('text');
          if(empty($text)) $this->getUser()->setFlash('error','您提提交的为空');
          if($this->parent_id=="") $this->parent_id = 0;
              $comment = new Comment();
              $comment->setWikiId($this->wiki_id);
              $comment->setUserId($this->user_id);
              $comment->setIsPublish(1);
              $comment->setText($text);
              $comment->setParentId($this->parent_id);
              $comment->setUsername($this->username);
              $comment->save();
              $text = "#".$this->wiki_id."#".$text;
              $client->update($text);
           $this->getUser()->setFlash('success', '您所留言成功');
      }
  }
  
  
  
  /**
  * 用户邮件方面设置与订阅
  * @param sfWebRequest $request
  * @return void
  * @author lizhi
  */
  public function executeUserEmail(sfWebRequest $request){
    $this->getResponse()->setTitle("用户邮件订阅 - 我爱电视");
    
  }
  
  /**
  * 用户管理后台
  * 
  */
  public function executeDashboard(sfWebRequest $request){
      //var_dump($this->getUser()->getAttribute("avatar"));
      if( $this->getUser()->isAuthenticated()==false ) {
            $this->redirect('user/login');
       }
      //var_dump(sfContext::getInstance());
      $this->getResponse()->setTitle("用户后台 - 我爱电视");
      
      $this->setTemplate("dashboard_index");
  }
  
  /**
   * 更新用户相应的信息
   * @param sfWebRequest request
   * @author lizhi
   * @return void
   */
  public function executeUpdate(sfWebRequest $request){
      $this->user_id = $this->getUser()->getAttribute('user_id');
      if($request->getMethod() == 'POST' && !empty($this->user_id)){
         //$this->getResponse()->setContentType('application/x-json'); 
        $file = $request->getFiles("avatar");
        $storage = StorageService::get('photo');
        $file_name = $file['name'];
        $file_ext_tmp = explode('.',$file_name);
        $file_ext = strtolower(array_pop($file_ext_tmp));
        $key = time().rand(100, 999);
        $res=$storage->save($key.'.'.$file_ext,$file['tmp_name']);
        $this->img = $storage->get($key.'.'.$file_ext);
         $mongo =  $this->getMondongo();
         $userRep = $mongo->getRepository("User");
         $user = $userRep->findOneById(new MongoId($this->user_id));
         $user->setAvatar($key.'.'.$file_ext);
         $user->save();
         $this->getUser()->setFlash('success', '您所留言成功');
      }
      $this->setTemplate("dashboard_index");
  }
  
  /*
   * 测试服务器是否支持json
   * @param sfWebRequest $request
   * @author: lifucang
   */
  //private $url='http://61.145.165.154:8080/uc/json';
  //private $url=sfConfig::get('app_huanurl');
  public function executeTestjson(sfWebRequest $request) {
        if($request->isMethod("POST")) {
            $postinfojson=trim($request->getPostParameter('json'));
						$result = Common::post_user_json($postinfojson);
            print_r(json_decode($result));
            return sfView::NONE;
        }

  }
}
