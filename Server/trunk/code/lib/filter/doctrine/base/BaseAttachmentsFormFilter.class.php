<?php

/**
 * Attachments filter form base class.
 *
 * @package    epg2.0
 * @subpackage filter
 * @author     Huan Tek
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseAttachmentsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'source_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'file_name'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'file_key'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'category_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'thumb'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'source_name' => new sfValidatorPass(array('required' => false)),
      'file_name'   => new sfValidatorPass(array('required' => false)),
      'file_key'    => new sfValidatorPass(array('required' => false)),
      'category_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'thumb'       => new sfValidatorPass(array('required' => false)),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('attachments_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Attachments';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'source_name' => 'Text',
      'file_name'   => 'Text',
      'file_key'    => 'Text',
      'category_id' => 'Number',
      'thumb'       => 'Text',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
