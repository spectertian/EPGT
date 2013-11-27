<?php

/**
 * YesterdayProgram Base Form.
 */
class BaseYesterdayProgramForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'program_name' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'date' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormDateTime(array(), array()),
            'end_time' => new sfWidgetFormDateTime(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'poster' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'aspect' => new sfWidgetFormInputText(array(), array()),
            'play_url' => new sfWidgetFormInputText(array(), array()),
            'sort' => new sfWidgetFormInputText(array(), array()),
            'style' => new sfWidgetFormInputText(array(), array()),
            'author' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputCheckbox(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'program_name' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'date' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorDateTime(array(), array()),
            'end_time' => new sfValidatorDateTime(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'poster' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'aspect' => new sfValidatorString(array(), array()),
            'play_url' => new sfValidatorString(array(), array()),
            'sort' => new sfValidatorInteger(array(), array()),
            'style' => new sfValidatorString(array(), array()),
            'author' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorBoolean(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('yesterday_program[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'YesterdayProgram';
    }
}