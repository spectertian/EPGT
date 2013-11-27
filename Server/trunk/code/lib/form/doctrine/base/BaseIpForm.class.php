<?php

/**
 * Ip form base class.
 *
 * @method Ip getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseIpForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'ip1'        => new sfWidgetFormInputText(),
      'ip2'        => new sfWidgetFormInputText(),
      'province'   => new sfWidgetFormInputText(),
      'city'       => new sfWidgetFormInputText(),
      'code'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ip1'        => new sfValidatorInteger(array('required' => false)),
      'ip2'        => new sfValidatorInteger(array('required' => false)),
      'province'   => new sfValidatorString(array('max_length' => 10)),
      'city'       => new sfValidatorString(array('max_length' => 10)),
      'code'       => new sfValidatorString(array('max_length' => 32)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ip[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Ip';
  }

}
