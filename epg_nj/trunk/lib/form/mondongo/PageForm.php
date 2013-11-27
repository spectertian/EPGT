<?php

/**
 * Page Form.
 */
class PageForm extends BasePageForm
{
    public function  configure() {
        unset($this['created_at'], $this['updated_at'], $this['author'], $this['version']);

        $this->widgetSchema['content'] = new sfWidgetFormTextarea(array(), array());
        $this->validatorSchema['pagename'] = new sfValidatorString(
                            array(
                                'required' => true,
                            ),
                            array(
                                'required' => '请输入模板名称'
                            )
                );
        
        $this->validatorSchema['content'] = new sfValidatorString(
                            array(
                                'required' => true,
                            ),
                            array(
                                'required' => '请输入模板内容'
                            )
                );

    }


}