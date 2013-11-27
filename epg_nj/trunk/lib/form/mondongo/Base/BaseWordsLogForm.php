<?php

/**
 * WordsLog Base Form.
 */
class BaseWordsLogForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'word' => new sfWidgetFormInputText(array(), array()),
            'reword' => new sfWidgetFormInputText(array(), array()),
            'sensitive' => new sfWidgetFormInputText(array(), array()),
            'resensitive' => new sfWidgetFormInputText(array(), array()),
            'from' => new sfWidgetFormInputText(array(), array()),
            'from_id' => new sfWidgetFormInputText(array(), array()),
            'status' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'word' => new sfValidatorString(array(), array()),
            'reword' => new sfValidatorString(array(), array()),
            'sensitive' => new sfValidatorString(array(), array()),
            'resensitive' => new sfValidatorString(array(), array()),
            'from' => new sfValidatorString(array(), array()),
            'from_id' => new sfValidatorString(array(), array()),
            'status' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('words_log[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'WordsLog';
    }
}