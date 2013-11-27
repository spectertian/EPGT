<?php

/**
 * Admin form.
 *
 * @package    epg
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AdminForm extends BaseAdminForm
{
  public function configure()
  {
      unset(
          $this['created_at'], $this['updated_at'], $this['last_login_ip'], $this['last_login_at']
      );
      $this->validatorSchema['username'] = new sfValidatorString(array('required'=>true,'max_length' => 50),array('required'=>'请填写用户名','max_length'=>'不超过50字符'));
      $this->validatorSchema['email'] = new sfValidatorEmail(array('required' => true),array('required'=>'请填写邮箱','invalid' => '邮箱格式不正确'));
      //$this->validatorSchema['password'] = new sfValidatorString(array('required' => true),array('required'=>'密码不能为空'));
      $this->validatorSchema['password'] = new sfValidatorString(array('required' => false));
      
      $this->widgetSchema['status'] = new sfWidgetFormChoice(array('choices'=>array( '0' => '锁定','1' => '正常'),'default'=>'1'));
      //$this->widgetSchema['status']->setDefault(1);
      $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array('always_render_empty' => true));
	  $this->validatorSchema->setPostValidator(
        new sfValidatorDoctrineUnique(array('model' => 'Admin', 'column' => array('email')),array('invalid'=>'该邮箱已被注册!'))
      );     
      $this->validatorSchema->setPostValidator(
        new sfValidatorDoctrineUnique(array('model' => 'Admin', 'column' => array('username')),array('invalid'=>'该用户名已被注册'))
      );     

      $credentials = sfConfig::get('app_admin_credentials');
      //权限表单部分
      foreach($credentials as $name => $detail) {
          $this->validatorSchema[$name . '_auth'] = new sfValidatorPass(array('required' => false));

          $this->widgetSchema[$name . '_auth'] = new sfWidgetFormSelectCheckbox(
                      array(
                          'label' => $detail['label'] . '权限:',
                          'choices' => $detail['credential']
                      ));
      }
  }
}
