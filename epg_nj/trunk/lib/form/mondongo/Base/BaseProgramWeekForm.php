<?php

/**
 * ProgramWeek Base Form.
 */
class BaseProgramWeekForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'program_id' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'publish' => new sfWidgetFormInputCheckbox(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'channel_type' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormDateTime(array(), array()),
            'end_time' => new sfWidgetFormDateTime(array(), array()),
            'time' => new sfWidgetFormInputText(array(), array()),
            'date' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'admin' => new sfWidgetFormInputText(array(), array()),
            'sort' => new sfWidgetFormInputText(array(), array()),
            'tvsou_id' => new sfWidgetFormInputText(array(), array()),
            'cpg_content_id' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'program_id' => new sfValidatorString(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'publish' => new sfValidatorBoolean(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'channel_type' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorDateTime(array(), array()),
            'end_time' => new sfValidatorDateTime(array(), array()),
            'time' => new sfValidatorString(array(), array()),
            'date' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'admin' => new sfValidatorString(array(), array()),
            'sort' => new sfValidatorInteger(array(), array()),
            'tvsou_id' => new sfValidatorInteger(array(), array()),
            'cpg_content_id' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('program_week[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ProgramWeek';
    }
}