<?php

/**
 * ThemeItem form base class.
 *
 * @method ThemeItem getObject() Returns the current form's model object
 *
 * @package    epg2.0
 * @subpackage form
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseThemeItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'theme_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Theme'), 'add_empty' => false)),
      'wiki_id'    => new sfWidgetFormInputText(),
      'remark'     => new sfWidgetFormInputText(),
      'img'        => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'theme_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Theme'), 'required' => false)),
      'wiki_id'    => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'remark'     => new sfValidatorString(array('max_length' => 250)),
      'img'        => new sfValidatorString(array('max_length' => 250)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('theme_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ThemeItem';
  }

}
