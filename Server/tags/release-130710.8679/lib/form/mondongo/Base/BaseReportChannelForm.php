<?php

/**
 * ReportChannel Base Form.
 */
class BaseReportChannelForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'dtvsp' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputCheckbox(array(), array()),
            'user' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'dtvsp' => new sfValidatorString(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorBoolean(array(), array()),
            'user' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('report_channel[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ReportChannel';
    }
}