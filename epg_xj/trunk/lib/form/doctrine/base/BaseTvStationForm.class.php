<?php

/**
 * TvStation form base class.
 *
 * @method TvStation getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTvStationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'parent_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'), 'add_empty' => false)),
      'name'       => new sfWidgetFormInputText(),
      'sort'       => new sfWidgetFormInputText(),
      'publish'    => new sfWidgetFormInputText(),
      'code'       => new sfWidgetFormInputText(),
      'province'   => new sfWidgetFormInputText(),
      'city'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'parent_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'sort'       => new sfValidatorInteger(array('required' => false)),
      'publish'    => new sfValidatorInteger(array('required' => false)),
      'code'       => new sfValidatorString(array('max_length' => 32)),
      'province'   => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'city'       => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('tv_station[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TvStation';
  }

}
