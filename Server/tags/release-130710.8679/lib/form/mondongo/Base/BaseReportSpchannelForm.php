<?php

/**
 * ReportSpchannel Base Form.
 */
class BaseReportSpchannelForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'spid' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'service_id' => new sfWidgetFormInputText(array(), array()),
            'frequency' => new sfWidgetFormInputText(array(), array()),
            'symbol_rate' => new sfWidgetFormInputText(array(), array()),
            'modulation' => new sfWidgetFormInputText(array(), array()),
            'on_id' => new sfWidgetFormInputText(array(), array()),
            'ts_id' => new sfWidgetFormInputText(array(), array()),
            'logic_number' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'num' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'spid' => new sfValidatorString(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'service_id' => new sfValidatorString(array(), array()),
            'frequency' => new sfValidatorString(array(), array()),
            'symbol_rate' => new sfValidatorString(array(), array()),
            'modulation' => new sfValidatorString(array(), array()),
            'on_id' => new sfValidatorString(array(), array()),
            'ts_id' => new sfValidatorString(array(), array()),
            'logic_number' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'num' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('report_spchannel[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ReportSpchannel';
    }
}