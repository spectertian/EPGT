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
      'code'          => new sfWidgetFormInputText(),
      'name'          => new sfWidgetFormInputText(),
      'memo'          => new sfWidgetFormInputText(),
      'type'          => new sfWidgetFormInputText(),
      'province'      => new sfWidgetFormInputText(),
      'city'          => new sfWidgetFormInputText(),
      'logo'          => new sfWidgetFormInputText(),
      'tv_station_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'), 'add_empty' => false)),
      'sort_id'       => new sfWidgetFormInputText(),
      'publish'       => new sfWidgetFormInputText(),
      'autosyn'       => new sfWidgetFormChoice(array('choices' => array(0 => 0, 1 => 1))),
      'config'        => new sfWidgetFormTextarea(),
      'hot'           => new sfWidgetFormInputText(),
      'tvsou_update'  => new sfWidgetFormDateTime(),
      'editor_update' => new sfWidgetFormDateTime(),
      'tvsou_get'     => new sfWidgetFormInputText(),
      'epg_update'    => new sfWidgetFormDateTime(),
      'epg_get'       => new sfWidgetFormInputText(),
      'recommend'     => new sfWidgetFormInputText(),
      'like_num'      => new sfWidgetFormInputText(),
      'dislike_num'   => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'code'          => new sfValidatorString(array('max_length' => 50)),
      'name'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'memo'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'province'      => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'city'          => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'logo'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'tv_station_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'))),
      'sort_id'       => new sfValidatorInteger(array('required' => false)),
      'publish'       => new sfValidatorInteger(array('required' => false)),
      'autosyn'       => new sfValidatorChoice(array('choices' => array(0 => 0, 1 => 1), 'required' => false)),
      'config'        => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'hot'           => new sfValidatorInteger(array('required' => false)),
      'tvsou_update'  => new sfValidatorDateTime(array('required' => false)),
      'editor_update' => new sfValidatorDateTime(array('required' => false)),
      'tvsou_get'     => new sfValidatorInteger(array('required' => false)),
      'epg_update'    => new sfValidatorDateTime(array('required' => false)),
      'epg_get'       => new sfValidatorInteger(array('required' => false)),
      'recommend'     => new sfValidatorInteger(array('required' => false)),
      'like_num'      => new sfValidatorInteger(array('required' => false)),
      'dislike_num'   => new sfValidatorInteger(array('required' => false)),
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
