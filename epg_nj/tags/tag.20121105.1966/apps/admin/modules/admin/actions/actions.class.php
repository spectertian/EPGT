<?php

/**
 * admin actions.
 *
 * @package    epg
 * @subpackage admin
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminActions extends autoadminActions
{

    public function executeCaptcha(sfWebRequest $request)
    {
            $img = new securimage();
            $img->image_width = 125;
            $img->image_height = 35;
            $img->gd_font_size = 12;
            $img->perturbation = 0.9;
            $img->code_length = 4;
            $img->image_bg_color = new Securimage_Color("#ffffff");
            $img->use_transparent_text = true;
            $img->text_transparency_percentage = 60; // 100 = completely transparent
            $img->num_lines = 1;
            $img->text_angle_minimum = 0;
            $img->image_signature = '';
            $img->session_name = '';
            $img->text_color = new Securimage_Color("#000000");
            $img->line_color = new Securimage_Color("#cccccc");
            return $img->show();
    }

    public function executeLogin(sfWebRequest $request) {
        if ($request->isMethod(sfRequest::POST)) {
            $code = $request->getPostParameter('validatorCode');
            $current_code = $this->getUser()->getAttribute('securimage_code_value');
            $username = $request->getPostParameter('username');
            $password = $request->getPostParameter('password');
            
			if(0)
            //if( strtolower($code) !=  $current_code )
            {
                $this->getUser()->setFlash('error', '验证码输入错误！请重新输入');
            }else{
                $this->getUser()->setAttribute('code','');
                $user = Doctrine::getTable('Admin')->createQuery()
                    ->where('username = ?', $username)
                    ->andWhere('password = ?', md5($password))
                    ->andWhere('status = ?', 1)
                    ->fetchOne();
                if ($user) {
                    $user->setLastLoginIp($request->getRemoteAddress());
                    $user->setLastLoginAt(date('Y-m-d H:i:s'));
                    $user->save();

                    $this->getUser()->setAuthenticated(true);
                    $this->getUser()->setAttribute('username', $username);
                    $this->getUser()->setAttribute('adminid', $user->getId());


                    $user_auth = Doctrine::getTable('AdminAuth')->createQuery()
                            ->where('admin_id = ?', $user->getId())
                            ->execute();
                    foreach ($user_auth as $auth) {
                        $this->getUser()->addCredential($auth->getCredential());
                    }
                    $this->redirect('admin/dashboard');
                } else {
                    $this->getUser()->setFlash('error', '登录失败，用户名或密码错误！');
                }
            }
        }
        
    }

    public function executeLogout(sfWebRequest $request) {
        $this->getUser()->setAuthenticated(false);
        $this->getUser()->setAttribute('username', '');
        $this->getUser()->clearCredentials();
        $this->getUser()->shutdown();
        $this->redirect('admin/login');
    }

    public function executeDashboard(sfWebRequest $request) {

    }

    /**
     * 查询用户权限
     * @param sfWebRequest $request
     */
    public function executeAuths(sfWebRequest $request) {
        if($request->isXmlHttpRequest()) {
            $this->getResponse()->setContentType('application/x-json');

            $admin_id = $request->getGetParameter('admin_id');

            $auths = Doctrine::getTable('AdminAuth')->createQuery()
                        ->select('credential')
                        ->where('admin_id = ?', $admin_id)
                        ->fetchArray();
            $ret = json_encode($auths);
            return $this->renderText($ret);
        }
    }
    
    public function executeNew(sfWebRequest $request){
        $this->form = $this->configuration->getForm();
        $this->admin = $this->form->getObject();
        $this->setTemplate('edit');
   }

    public function executeCreate(sfWebRequest $request){
        $this->form = $this->configuration->getForm();
        $this->admin = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->admin = $this->getRoute()->getObject();
    $this->form = $this->configuration->getForm($this->admin);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    
    $values = $request->getParameter($this->form->getName());
    if($values['password'] == ''){
        unset($values['password']);    
    }
    $form->bind($values, $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

      try {
        $admin = $form->save();
      } catch (Doctrine_Validator_Exception $e) {

        $errorStack = $form->getObject()->getErrorStack();

        $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
        foreach ($errorStack as $field => $errors) {
            $message .= "$field (" . implode(", ", $errors) . "), ";
        }
        $message = trim($message, ', ');

        $this->getUser()->setFlash('error', $message);
        return sfView::SUCCESS;
      }

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $admin)));

      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

        $this->redirect('@admin_new');
      }
      else
      {
        $this->getUser()->setFlash('notice', $notice);

        $this->redirect(array('sf_route' => 'admin_edit', 'sf_subject' => $admin));
      }
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }
  
  

}
