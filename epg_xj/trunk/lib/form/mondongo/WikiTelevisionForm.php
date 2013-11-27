<?php

/**
 * Television Form.
 * 电视栏目表单
 * @author zhigang
 */
class WikiTelevisionForm extends WikiForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();

        $this->widgetSchema['channel'] = new sfWidgetFormInputText();  // 播出频道
        $this->widgetSchema['play_time'] = new sfWidgetFormInputText(); // 播出时间
        $this->widgetSchema['host'] = new WidgetFormInputTextArray();  // 主持人
        $this->widgetSchema['guest'] = new WidgetFormInputTextArray();  // 嘉宾
        $this->widgetSchema['runtime'] = new sfWidgetFormInputText();
        $this->widgetSchema['language'] = new sfWidgetFormInputText();
        $this->widgetSchema['country'] = new sfWidgetFormInputText();
        $this->widgetSchema['producer'] = new WidgetFormInputTextArray();
        $this->widgetSchema['alias'] = new WidgetFormInputTextArray();
        $this->setWidget("screenshots", new sfWidgetFormInputCheckbox());

        
        $this->validatorSchema['channel'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['play_time'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['host'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['guest'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['runtime'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['language'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['country'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['producer'] = new sfValidatorString(array('required' => false));
        $this->validatorSchema['alias'] = new sfValidatorString(array('required' => false));
        $this->setValidator("screenshots", new sfValidatorPass());
    }

    public function getModelName()
    {
        return 'Wiki_Television';
    }
}
