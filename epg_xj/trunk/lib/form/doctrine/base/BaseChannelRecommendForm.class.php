<?php

/**
 * ChannelRecommend form base class.
 *
 * @method ChannelRecommend getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseChannelRecommendForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'channel_code' => new sfWidgetFormInputText(),
      'wiki_id'      => new sfWidgetFormInputText(),
      'title'        => new sfWidgetFormInputText(),
      'pic'          => new sfWidgetFormInputText(),
      'playtime'     => new sfWidgetFormInputText(),
      'remark'       => new sfWidgetFormInputText(),
      'sort'         => new sfWidgetFormInputText(),
      'publish'      => new sfWidgetFormInputText(),
      'tongbu_id'    => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'channel_code' => new sfValidatorString(array('max_length' => 32)),
      'wiki_id'      => new sfValidatorString(array('max_length' => 32)),
      'title'        => new sfValidatorString(array('max_length' => 128)),
      'pic'          => new sfValidatorString(array('max_length' => 128)),
      'playtime'     => new sfValidatorString(array('max_length' => 32)),
      'remark'       => new sfValidatorString(array('max_length' => 255)),
      'sort'         => new sfValidatorInteger(array('required' => false)),
      'publish'      => new sfValidatorInteger(array('required' => false)),
      'tongbu_id'    => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('channel_recommend[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ChannelRecommend';
  }

}
