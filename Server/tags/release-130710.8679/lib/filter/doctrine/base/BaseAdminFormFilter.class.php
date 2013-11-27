<?php

/**
 * Admin filter form base class.
 *
 * @package    epg2.0
 * @subpackage filter
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseAdminFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'          => new sfWidgetFormFilterInput(),
      'phone'         => new sfWidgetFormFilterInput(),
      'status'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'email'         => new sfWidgetFormFilterInput(),
      'last_login_ip' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_login_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'username'      => new sfValidatorPass(array('required' => false)),
      'password'      => new sfValidatorPass(array('required' => false)),
      'name'          => new sfValidatorPass(array('required' => false)),
      'phone'         => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'email'         => new sfValidatorPass(array('required' => false)),
      'last_login_ip' => new sfValidatorPass(array('required' => false)),
      'last_login_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('admin_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Admin';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'username'      => 'Text',
      'password'      => 'Text',
      'name'          => 'Text',
      'phone'         => 'Text',
      'status'        => 'Boolean',
      'email'         => 'Text',
      'last_login_ip' => 'Text',
      'last_login_at' => 'Date',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
