<?php

/**
 * RecommandFix Form.
 */
class RecommandFixForm extends BaseRecommandFixForm
{
    public function configure() {
        $this->disableCSRFProtection(); //关闭_csrf_token
        unset($this['updated_at'], $this['created_at']);
        $this->setWidget("title", new sfWidgetFormInputText(array('label' => '标题：'),array("size" => "50")));
        $this->setWidget("poster", new sfWidgetFormTextarea(array('label' => '海报地址：'),array("cols" => "90", "rows" => "3", "style" => "width:100%")));
        $this->setWidget("url", new sfWidgetFormTextarea(array('label' => '播放地址：'),array("cols" => "90", "rows" => "3", "style" => "width:100%")));
        $this->setWidget("type", new sfWidgetFormSelect(array('choices' => array('vod'=>'新上线','Series'=>'电视剧','Movie'=>'电影','Sports'=>'体育','Entertainment'=>'综艺','Cartoon'=>'动漫','Culture'=>'文化','News'=>'综合'))));
        $this->widgetSchema->setLabel('type', '类型：');
    }
}