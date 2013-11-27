<?php

/**
 * Channel filter form.
 *
 * @package    epg
 * @subpackage filter
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ChannelFormFilter extends BaseChannelFormFilter
{
  public function configure()
  {
      
      $tv_station       = Doctrine::getTable('TvStation')->getParentArray(0);
      $result = array(''=>'');  
		foreach($tv_station as $k=>$v)
		{
			$result[$k]=$v;
		}      
      $this->widgetSchema['name'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
      $this->widgetSchema['memo'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
      $this->widgetSchema['publish'] = new sfWidgetFormChoice(array('choices' => array('' => '', '1' => '已发布', '0' => '未发布')));//new sfWidgetFormFilterInput(array('template' => '%input%'));
      $this->widgetSchema['tv_station_id'] = new sfWidgetFormChoice(array('choices' => $result));
      $this->widgetSchema['has_logo'] = new sfWidgetFormChoice(array('label'=>'台标','choices' => array('' => '', '1' => '是', '2' => '否')));
	  
      $this->validatorSchema['has_logo'] = new sfValidatorChoice(array('required' => false, 'choices' => array('', '1', '2')));
      $this->validatorSchema['publish'] = new sfValidatorChoice(array('required' => false, 'choices' => array('', '1', '0')));
      $this->validatorSchema['tv_station_id'] = new sfValidatorChoice(array('required' => false, 'choices' => array_keys($result)));
  }

  public function getFields() {
      $fields = parent::getFields();
      $fields['publish'] = 'Enum';
      //$fields['logo'] = 'Enum';

      return $fields;
  }
}
