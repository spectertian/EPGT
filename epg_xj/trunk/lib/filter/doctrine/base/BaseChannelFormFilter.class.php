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
      'code'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'          => new sfWidgetFormFilterInput(),
      'memo'          => new sfWidgetFormFilterInput(),
      'type'          => new sfWidgetFormFilterInput(),
      'province'      => new sfWidgetFormFilterInput(),
      'city'          => new sfWidgetFormFilterInput(),
      'logo'          => new sfWidgetFormFilterInput(),
      'tv_station_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TvStation'), 'add_empty' => true)),
      'sort_id'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'publish'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'autosyn'       => new sfWidgetFormChoice(array('choices' => array('' => '', 0 => 0, 1 => 1))),
      'config'        => new sfWidgetFormFilterInput(),
      'hot'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tvsou_update'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'editor_update' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'tvsou_get'     => new sfWidgetFormFilterInput(),
      'epg_update'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'epg_get'       => new sfWidgetFormFilterInput(),
      'recommend'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'like_num'      => new sfWidgetFormFilterInput(),
      'dislike_num'   => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'code'          => new sfValidatorPass(array('required' => false)),
      'name'          => new sfValidatorPass(array('required' => false)),
      'memo'          => new sfValidatorPass(array('required' => false)),
      'type'          => new sfValidatorPass(array('required' => false)),
      'province'      => new sfValidatorPass(array('required' => false)),
      'city'          => new sfValidatorPass(array('required' => false)),
      'logo'          => new sfValidatorPass(array('required' => false)),
      'tv_station_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TvStation'), 'column' => 'id')),
      'sort_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'autosyn'       => new sfValidatorChoice(array('required' => false, 'choices' => array(0 => 0, 1 => 1))),
      'config'        => new sfValidatorPass(array('required' => false)),
      'hot'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tvsou_update'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'editor_update' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'tvsou_get'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'epg_update'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'epg_get'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'recommend'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'like_num'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dislike_num'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
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
      'code'          => 'Text',
      'name'          => 'Text',
      'memo'          => 'Text',
      'type'          => 'Text',
      'province'      => 'Text',
      'city'          => 'Text',
      'logo'          => 'Text',
      'tv_station_id' => 'ForeignKey',
      'sort_id'       => 'Number',
      'publish'       => 'Number',
      'autosyn'       => 'Enum',
      'config'        => 'Text',
      'hot'           => 'Number',
      'tvsou_update'  => 'Date',
      'editor_update' => 'Date',
      'tvsou_get'     => 'Number',
      'epg_update'    => 'Date',
      'epg_get'       => 'Number',
      'recommend'     => 'Number',
      'like_num'      => 'Number',
      'dislike_num'   => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
