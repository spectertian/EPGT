<?php

/**
 * ProgramRec Base Form.
 */
class BaseProgramRecForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormDateTime(array(), array()),
            'end_time' => new sfWidgetFormDateTime(array(), array()),
            'date' => new sfWidgetFormInputText(array(), array()),
            'time_area' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'episode' => new sfWidgetFormInputText(array(), array()),
            'author' => new sfWidgetFormInputText(array(), array()),
            'sort' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorDateTime(array(), array()),
            'end_time' => new sfValidatorDateTime(array(), array()),
            'date' => new sfValidatorString(array(), array()),
            'time_area' => new sfValidatorInteger(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'episode' => new sfValidatorInteger(array(), array()),
            'author' => new sfValidatorString(array(), array()),
            'sort' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('program_rec[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ProgramRec';
    }
}