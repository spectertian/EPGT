<?php

/**
 * Sp Form.
 */
class SpForm extends BaseSpForm
{
    public function configure() {
        $this->disableCSRFProtection(); //关闭_csrf_token
        unset($this['updated_at'], $this['created_at'], $this['channels']);
        $this->setWidget("signal", new sfWidgetFormInputText(array('label' => '运营商标识：'),array("size" => "50")));
        $this->setWidget("name", new sfWidgetFormInputText(array('label' => '名称：'),array("size" => "50")));
        $this->setWidget("logo", new sfWidgetFormInputHidden(array('label' => ' ')));
        $this->setWidget("remark", new sfWidgetFormTextarea(array('label' => '描述：'),array("cols" => "90", "rows" => "6", "style" => "width:100%")));
        $this->setWidget("type", new sfWidgetFormSelect(array('choices' => array('vod'=>'点播', 'live'=>'直播'))));
        /*
        $this->widgetSchema->setLabels(
            array('signal'    => '运营商标识：', 'name'   => '名称：','logo' => ' ','remark' => '描述：','type' => '类型：')
        );
        */
        $this->widgetSchema->setLabel('type', '类型：');
        $this->setValidator("logo", new sfValidatorString(array('required'=>false)));  //img不必须验证
    }
}