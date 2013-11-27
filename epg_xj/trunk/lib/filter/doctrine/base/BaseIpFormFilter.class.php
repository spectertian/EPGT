<?php

/**
 * Ip filter form base class.
 *
 * @package    epg2.0
 * @subpackage filter
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseIpFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ip1'        => new sfWidgetFormFilterInput(),
      'ip2'        => new sfWidgetFormFilterInput(),
      'province'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'city'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'code'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ip1'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ip2'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'province'   => new sfValidatorPass(array('required' => false)),
      'city'       => new sfValidatorPass(array('required' => false)),
      'code'       => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ip_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Ip';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'ip1'        => 'Number',
      'ip2'        => 'Number',
      'province'   => 'Text',
      'city'       => 'Text',
      'code'       => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
