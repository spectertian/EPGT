<?php

/**
 * Attachments form base class.
 *
 * @method Attachments getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseAttachmentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'source_name' => new sfWidgetFormInputText(),
      'file_name'   => new sfWidgetFormInputText(),
      'file_key'    => new sfWidgetFormInputText(),
      'category_id' => new sfWidgetFormInputText(),
      'thumb'       => new sfWidgetFormTextarea(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'source_name' => new sfValidatorString(array('max_length' => 250)),
      'file_name'   => new sfValidatorString(array('max_length' => 250)),
      'file_key'    => new sfValidatorString(array('max_length' => 250)),
      'category_id' => new sfValidatorInteger(array('required' => false)),
      'thumb'       => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('attachments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Attachments';
  }

}
