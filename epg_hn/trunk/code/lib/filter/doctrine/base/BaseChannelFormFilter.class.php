<?php

/**
 * Channel filter form base class.
 *
 * @package    epg2.0
 * @subpackage filter
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseChannelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tv_station_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'), 'add_empty' => true)),
      'sort_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'publish'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'autosyn'       => new sfWidgetFormChoice(array('choices' => array('' => '', 0 => 0, 1 => 1))),
      'code'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'          => new sfWidgetFormFilterInput(),
      'memo'          => new sfWidgetFormFilterInput(),
      'type'          => new sfWidgetFormFilterInput(),
      'config'        => new sfWidgetFormFilterInput(),
      'logo'          => new sfWidgetFormFilterInput(),
      'live'          => new sfWidgetFormChoice(array('choices' => array('' => '', 0 => 0, 1 => 1))),
      'live_config'   => new sfWidgetFormFilterInput(),
      'hot'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'tv_station_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TvStation'), 'column' => 'id')),
      'sort_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'autosyn'       => new sfValidatorChoice(array('required' => false, 'choices' => array(0 => 0, 1 => 1))),
      'code'          => new sfValidatorPass(array('required' => false)),
      'name'          => new sfValidatorPass(array('required' => false)),
      'memo'          => new sfValidatorPass(array('required' => false)),
      'type'          => new sfValidatorPass(array('required' => false)),
      'config'        => new sfValidatorPass(array('required' => false)),
      'logo'          => new sfValidatorPass(array('required' => false)),
      'live'          => new sfValidatorChoice(array('required' => false, 'choices' => array(0 => 0, 1 => 1))),
      'live_config'   => new sfValidatorPass(array('required' => false)),
      'hot'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('channel_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Channel';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'tv_station_id' => 'ForeignKey',
      'sort_id'       => 'Number',
      'publish'       => 'Number',
      'autosyn'       => 'Enum',
      'code'          => 'Text',
      'name'          => 'Text',
      'memo'          => 'Text',
      'type'          => 'Text',
      'config'        => 'Text',
      'logo'          => 'Text',
      'live'          => 'Enum',
      'live_config'   => 'Text',
      'hot'           => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
