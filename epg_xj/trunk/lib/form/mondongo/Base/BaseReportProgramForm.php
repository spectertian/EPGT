<?php

/**
 * ReportProgram Base Form.
 */
class BaseReportProgramForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'spid' => new sfWidgetFormInputText(array(), array()),
            'channel_name' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormDateTime(array(), array()),
            'end_time' => new sfWidgetFormDateTime(array(), array()),
            'tag' => new sfWidgetFormInputText(array(), array()),
            'num' => new sfWidgetFormInputText(array(), array()),
            'date' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'spid' => new sfValidatorString(array(), array()),
            'channel_name' => new sfValidatorString(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorDateTime(array(), array()),
            'end_time' => new sfValidatorDateTime(array(), array()),
            'tag' => new sfValidatorString(array(), array()),
            'num' => new sfValidatorInteger(array(), array()),
            'date' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('report_program[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ReportProgram';
    }
}