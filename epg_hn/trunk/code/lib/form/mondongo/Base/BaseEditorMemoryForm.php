<?php

/**
 * EditorMemory Base Form.
 */
class BaseEditorMemoryForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'program_id' => new sfWidgetFormInputText(array(), array()),
            'program_name' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'channel_code' => new sfValidatorString(array(), array()),
            'program_id' => new sfValidatorString(array(), array()),
            'program_name' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('editor_memory[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'EditorMemory';
    }
}