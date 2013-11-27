<?php

/**
 * ProgramLive Base Form.
 */
class BaseProgramLiveForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'type' => new sfWidgetFormInputText(array(), array()),
            'next_name' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormDateTime(array(), array()),
            'end_time' => new sfWidgetFormDateTime(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_cover' => new sfWidgetFormInputText(array(), array()),
            'wiki_title' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'type' => new sfValidatorString(array(), array()),
            'next_name' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorDateTime(array(), array()),
            'end_time' => new sfValidatorDateTime(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'wiki_cover' => new sfValidatorString(array(), array()),
            'wiki_title' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('program_live[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ProgramLive';
    }
}