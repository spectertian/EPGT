<?php

/**
 * ChannelRecommend filter form base class.
 *
 * @package    epg2.0
 * @subpackage filter
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseChannelRecommendFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'channel_code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'wiki_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pic'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'playtime'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'remark'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sort'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'publish'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'channel_code' => new sfValidatorPass(array('required' => false)),
      'wiki_id'      => new sfValidatorPass(array('required' => false)),
      'title'        => new sfValidatorPass(array('required' => false)),
      'pic'          => new sfValidatorPass(array('required' => false)),
      'playtime'     => new sfValidatorPass(array('required' => false)),
      'remark'       => new sfValidatorPass(array('required' => false)),
      'sort'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'publish'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('channel_recommend_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ChannelRecommend';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'channel_code' => 'Text',
      'wiki_id'      => 'Text',
      'title'        => 'Text',
      'pic'          => 'Text',
      'playtime'     => 'Text',
      'remark'       => 'Text',
      'sort'         => 'Number',
      'publish'      => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
