<?php

/**
 * Program form base class.
 *
 * @method Program getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProgramForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'publish'    => new sfWidgetFormInputText(),
      'channel_id' => new sfWidgetFormInputText(),
      'time'       => new sfWidgetFormTime(),
      'date'       => new sfWidgetFormDate(),
      'wiki_id'    => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'fulltime'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'publish'    => new sfValidatorInteger(array('required' => false)),
      'channel_id' => new sfValidatorInteger(array('required' => false)),
      'time'       => new sfValidatorTime(array('required' => false)),
      'date'       => new sfValidatorDate(array('required' => false)),
      'wiki_id'    => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'fulltime'   => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('program[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Program';
  }

}
