<?php

/**
 * CrontabLog Base Form.
 */
class BaseCrontabLogForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'title' => new sfWidgetFormInputText(array(), array()),
            'content' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormDateTime(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'title' => new sfValidatorString(array(), array()),
            'content' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorDateTime(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('crontab_log[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'CrontabLog';
    }
}