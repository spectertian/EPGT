<?php

/**
 * Terminal Form.
 */
class TerminalForm extends BaseTerminalForm
{
    public function configure() {
        $this->disableCSRFProtection(); //关闭_csrf_token
        unset($this['updated_at'], $this['created_at']);
        $this->setWidget("brand", new sfWidgetFormInputText(array('label' => '品牌：'),array("size" => "50")));
        $this->setWidget("clienttype", new sfWidgetFormInputText(array('label' => '类型：'),array("size" => "50")));
        $this->setWidget("version", new sfWidgetFormTextarea(array('label' => '版本：'),array("cols" => "90", "rows" => "6", "style" => "width:100%")));
        
        $this->setValidator("brand", new sfValidatorString(array("required" => '请输入品牌')));
        $this->setValidator("clienttype", new sfValidatorString(array("required" => '请输入类型')));
        $this->setValidator("version", new sfValidatorString(array("required" => '请输入版本')));
    }    
}