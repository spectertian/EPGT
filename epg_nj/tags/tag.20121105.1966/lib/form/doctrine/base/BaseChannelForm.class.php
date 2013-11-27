<?php

/**
 * Channel form base class.
 *
 * @method Channel getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseChannelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'tv_station_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'), 'add_empty' => false)),
      'sort_id'       => new sfWidgetFormInputText(),
      'publish'       => new sfWidgetFormInputText(),
      'autosyn'       => new sfWidgetFormChoice(array('choices' => array(0 => 0, 1 => 1))),
      'code'          => new sfWidgetFormInputText(),
      'name'          => new sfWidgetFormInputText(),
      'memo'          => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'config'        => new sfWidgetFormTextarea(),
      'logo'          => new sfWidgetFormInputText(),
      'live'          => new sfWidgetFormChoice(array('choices' => array(0 => 0, 1 => 1))),
      'live_config'   => new sfWidgetFormTextarea(),
      'hot'           => new sfWidgetFormInputText(),
      'tvsou_update'  => new sfWidgetFormDateTime(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'tv_station_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'))),
      'sort_id'       => new sfValidatorInteger(array('required' => false)),
      'publish'       => new sfValidatorInteger(array('required' => false)),
      'autosyn'       => new sfValidatorChoice(array('choices' => array(0 => 0, 1 => 1), 'required' => false)),
      'code'          => new sfValidatorString(array('max_length' => 50)),
      'name'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'memo'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'config'        => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'logo'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'live'          => new sfValidatorChoice(array('choices' => array(0 => 0, 1 => 1), 'required' => false)),
      'live_config'   => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'hot'           => new sfValidatorInteger(array('required' => false)),
      'tvsou_update'  => new sfValidatorDateTime(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('channel[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Channel';
  }

}
