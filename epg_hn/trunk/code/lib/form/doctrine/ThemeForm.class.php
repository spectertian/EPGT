<?php

/**
 * Theme form.
 *
 * @package    epg
 * @subpackage form
 * @author     Mozi Tek
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ThemeForm extends BaseThemeForm
{
  public function configure()
  {
        $this->disableCSRFProtection(); //关闭_csrf_token
        unset($this['id'], $this['updated_at'], $this['created_at']);
        $this->setWidget("title", new sfWidgetFormInputText(array('label' => '专题名称：'),array("size" => "50")));
        $this->setWidget("img", new sfWidgetFormInputHidden(array('label' => ' ')));
        $this->setWidget("remark", new sfWidgetFormTextarea(array('label' => '专题简介：'),array("cols" => "90", "rows" => "6", "style" => "width:100%")));
        $this->setWidget("publish", new sfWidgetFormSelect(array('choices' => array('1'=>'发布', '0'=>'隐藏'))));
        $this->widgetSchema->setLabel('publish', '状态：');
        $this->setValidator("img", new sfValidatorString(array('required'=>false)));  //img不必须验证
  }
}