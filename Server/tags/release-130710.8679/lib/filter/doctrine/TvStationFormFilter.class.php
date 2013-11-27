<?php

/**
 * TvStation filter form.
 *
 * @package    epg
 * @subpackage filter
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TvStationFormFilter extends BaseTvStationFormFilter
{
  public function configure()
  {
      $tv_station       = Doctrine::getTable('TvStation')->getParentArray(0);
      $this->widgetSchema['name'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
      $this->widgetSchema['publish'] = new sfWidgetFormChoice(array('choices' => array('' => '', '1' => '已发布', '0' => '未发布')));
      $this->widgetSchema['parent_id'] = new sfWidgetFormChoice(array('choices' => $tv_station));

      $this->validatorSchema['publish'] = new sfValidatorChoice(array('required' => false, 'choices' => array('', '1', '0')));
      $this->validatorSchema['parent_id'] = new sfValidatorChoice(array('required' => false, 'choices' => array_keys($tv_station)));
  }

  public function getFields() {
      $fields = parent::getFields();
      $fields['publish'] = 'Enum';

      return $fields;
  }
}
