<?php

/**
 * ProgramTemplate form base class.
 *
 * @method ProgramTemplate getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProgramTemplateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'p_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ProgramIndex'), 'add_empty' => true)),
      'wiki_id'    => new sfWidgetFormInputText(),
      'name'       => new sfWidgetFormInputText(),
      'time'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'p_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ProgramIndex'), 'required' => false)),
      'wiki_id'    => new sfValidatorInteger(array('required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'time'       => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('program_template[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProgramTemplate';
  }

}
