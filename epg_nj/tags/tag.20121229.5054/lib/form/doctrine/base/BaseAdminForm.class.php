<?php

/**
 * Admin form base class.
 *
 * @method Admin getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAdminForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'username'      => new sfWidgetFormInputText(),
      'password'      => new sfWidgetFormInputText(),
      'name'          => new sfWidgetFormInputText(),
      'phone'         => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputCheckbox(),
      'email'         => new sfWidgetFormInputText(),
      'last_login_ip' => new sfWidgetFormInputText(),
      'last_login_at' => new sfWidgetFormDateTime(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'username'      => new sfValidatorString(array('max_length' => 50)),
      'password'      => new sfValidatorString(array('max_length' => 50)),
      'name'          => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'phone'         => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'status'        => new sfValidatorBoolean(array('required' => false)),
      'email'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'last_login_ip' => new sfValidatorString(array('max_length' => 20)),
      'last_login_at' => new sfValidatorDateTime(),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Admin', 'column' => array('email')))
    );

    $this->widgetSchema->setNameFormat('admin[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Admin';
  }

}
