<?php

/**
 * Theme form base class.
 *
 * @method Theme getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseThemeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'title'      => new sfWidgetFormInputText(),
      'remark'     => new sfWidgetFormTextarea(),
      'img'        => new sfWidgetFormInputText(),
      'publish'    => new sfWidgetFormInputText(),
      'model'      => new sfWidgetFormInputText(),
      'scene'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 250)),
      'remark'     => new sfValidatorString(array('max_length' => 4000)),
      'img'        => new sfValidatorString(array('max_length' => 250)),
      'publish'    => new sfValidatorInteger(array('required' => false)),
      'model'      => new sfValidatorString(array('max_length' => 250)),
      'scene'      => new sfValidatorString(array('max_length' => 250)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('theme[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Theme';
  }

}
