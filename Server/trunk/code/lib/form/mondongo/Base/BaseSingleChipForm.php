<?php

/**
 * SingleChip Base Form.
 */
class BaseSingleChipForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'is_public' => new sfWidgetFormInputCheckbox(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'user_id' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'is_public' => new sfValidatorBoolean(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('single_chip[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'SingleChip';
    }
}