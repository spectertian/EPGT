<?php

/**
 * programLog Base Form.
 */
class BaseprogramLogForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'date' => new sfWidgetFormInputText(array(), array()),
            'nums' => new sfWidgetFormInputText(array(), array()),
            'wikinums' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'date' => new sfValidatorString(array(), array()),
            'nums' => new sfValidatorInteger(array(), array()),
            'wikinums' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('program_log[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'programLog';
    }
}