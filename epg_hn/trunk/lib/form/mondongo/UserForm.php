<?php

/**
 * User Form.
 */
class UserForm extends BaseUserForm
{
    public function configure() {
        parent::configure();
        $this->remove_fields();
      //  $this->widgetSchema['username'] = new sfWidgetFormInput();
        $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
        $this->widgetSchema['email'] = new sfWidgetFormInput();
        $this->widgetSchema['nickname'] = new sfWidgetFormInput();
        
        $this->validatorSchema['nickname'] = new sfValidatorRegex(
                array('max_length'=>20,
                       'min_length'=>2,
                        'trim' => true,
                        'required' => true,
                        'pattern' => '/^[0-9a-zA-Z\x{4E00}-\x{9FA5}]{2,20}$/u',
                    ),
                 array(
                     'max_length' => "合法长度为2～20个字符",
                     'min_length' => "合法长度为2～20个字符",
                     'required' => "请输入昵称",
                     'invalid' => "昵称格式错误",
                 )
         );

//        $this->validatorSchema['username'] = new sfValidatorRegex(
//                    array(
//                        'max_length' => 20,
//                        'min_length' => 2,
//                        'trim' => true,
//                        'required' => true,
//                        'pattern' => '/^[0-9a-zA-Z\x{4E00}-\x{9FA5}]{2,20}$/u'
//                    ),array(
//                        'max_length' => '合法长度为2～20个字符',
//                        'min_length' => '合法长度为2～20个字符',
//                        'required' => '请输入昵称',
//                        'invalid' => '昵称格式错误'
//                    )
//        );
        

//        $this->validatorSchema->setPostValidator(
//                            new sfValidatorMondongoUnique(
//                                array("model"=>'user','field'=>'username'),
//                                array(
//                                    'invalid' => '该昵称已经被注册，请选择其他昵称',
//                                    'required'=>'请输入昵称'
//                                )
//                            )
//                    );

        $this->validatorSchema['password'] = new sfValidatorRegex(
                                array(
                                    'max_length' => 16, 
                                    'min_length' => 6,
                                    'trim' => true,
                                    'pattern' => "/^[0-9a-zA-Z><,\[\]\{\}\?\/\+=\\\'\"\:\;\~\!\@\#\*\$\%\^\&\(\)\-\—\. \|]+$/"
                                ),
                                array(
                                    'max_length' => '6～16个字符（字母、数字、特殊符号），区分大小写',
                                    'min_length' => '6～16个字符（字母、数字、特殊符号），区分大小写',
                                    'invalid' => '密码格式错误',
                                    'required'=>'请输入密码'
                                )
                );
        
        $this->validatorSchema['email'] = new sfValidatorAnd(
                                array(
                                    new sfValidatorEmail(),
                                ),
                                array(),
                                array(
                                    'invalid' => 'Email格式错误',
                                    'required'=>'请输入Email'
                                )
                );
        

        
        $this->widgetSchema->setLabels(
                array(
                    'username'=>'用户名:',
                    'password'=>'密码:',
                    'email'=>'邮箱:',
                    'nickname'=> '昵称:'
                )
        );
    }

    protected function remove_fields() {
        unset(
                $this['original_avatar'],$this['created_at'],$this['updated_at'],$this['type'],$this['tags'],$this['textpass'],$this['avatar'],$this['city'],$this['province'],$this['desc'],$this['device_id'],$this['referer'],$this['username']
              );
    }
}