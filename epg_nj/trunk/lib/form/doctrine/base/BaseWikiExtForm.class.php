<?php

/**
 * WikiExt form base class.
 *
 * @method WikiExt getObject() Returns the current form's model object
 *
 * @package    epg
 * @subpackage form
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseWikiExtForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'title'      => new sfWidgetFormInputText(),
      'sort'       => new sfWidgetFormInputText(),
      'wiki_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Wiki'), 'add_empty' => true)),
      'wiki_key'   => new sfWidgetFormInputText(),
      'wiki_value' => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'sort'       => new sfValidatorInteger(array('required' => false)),
      'wiki_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Wiki'), 'required' => false)),
      'wiki_key'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'wiki_value' => new sfValidatorString(array('max_length' => 10000, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('wiki_ext[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WikiExt';
  }

}
