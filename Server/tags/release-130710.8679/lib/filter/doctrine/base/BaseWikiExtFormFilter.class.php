<?php

/**
 * WikiExt filter form base class.
 *
 * @package    epg
 * @subpackage filter
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseWikiExtFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'      => new sfWidgetFormFilterInput(),
      'sort'       => new sfWidgetFormFilterInput(),
      'wiki_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Wiki'), 'add_empty' => true)),
      'wiki_key'   => new sfWidgetFormFilterInput(),
      'wiki_value' => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'      => new sfValidatorPass(array('required' => false)),
      'sort'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'wiki_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Wiki'), 'column' => 'id')),
      'wiki_key'   => new sfValidatorPass(array('required' => false)),
      'wiki_value' => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('wiki_ext_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'WikiExt';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'title'      => 'Text',
      'sort'       => 'Number',
      'wiki_id'    => 'ForeignKey',
      'wiki_key'   => 'Text',
      'wiki_value' => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
