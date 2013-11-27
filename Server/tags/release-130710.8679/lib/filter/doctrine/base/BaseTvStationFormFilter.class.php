<?php

/**
 * TvStation filter form base class.
 *
 * @package    epg2.0
 * @subpackage filter
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTvStationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'), 'add_empty' => true)),
      'name'       => new sfWidgetFormFilterInput(),
      'sort'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'publish'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'code'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'province'   => new sfWidgetFormFilterInput(),
      'city'       => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'parent_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TvStation'), 'column' => 'id')),
      'name'       => new sfValidatorPass(array('required' => false)),
      'sort'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'code'       => new sfValidatorPass(array('required' => false)),
      'province'   => new sfValidatorPass(array('required' => false)),
      'city'       => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('tv_station_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TvStation';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'parent_id'  => 'ForeignKey',
      'name'       => 'Text',
      'sort'       => 'Number',
      'publish'    => 'Number',
      'code'       => 'Text',
      'province'   => 'Text',
      'city'       => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
