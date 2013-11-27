<?php

/**
 * TvStation form.
 *
 * @package    epg
 * @subpackage form
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TvStationForm extends BaseTvStationForm
{
  public function configure()
  {
    
      unset(
          $this['created_at'], $this['updated_at']
      );

//      $pars = TvStationTable::getInstance()->getParentArray();
//      $parents = array('0' => '请选择或创建一个TvStation');
//      $parents = $this->getParents($pars, $parents);
//
//      $this->widgetSchema['parent_id'] = new sfWidgetFormSelect(array(
//          'choices' => $parents
//      ), array());
//
//      $this->validatorSchema['parent_id'] = new sfValidatorChoice(array(
//          'choices' => array_keys($parents)
//      ), array());

      $publishs = array(1 => '发布', 0 => '隐藏');
      $this->widgetSchema['publish'] = new sfWidgetFormSelectRadio(array(
          'choices' => $publishs
      ), array());

      $this->validatorSchema['publish'] = new sfValidatorChoice(array(
          'choices' => array_keys($publishs)
      ), array());

      $this->validatorSchema['name'] = new sfValidatorString(array('required' => true),array('required'=>'请填写名称','max_length'=>'不超过100字符'));
      $this->validatorSchema['code'] = new sfValidatorString(array('required' => true),array('required'=>'请填写代号','max_length'=>'不超过32字符'));
  }

  private function getParents($parents, $pars = array()) {
      foreach ($parents as $k => $v) {
          $pars[strval($v['id'])] = $v['name'];
      }
      return $pars;
  }
}
