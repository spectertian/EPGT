<?php

/**
 * CheckLog Base Form.
 */
class BaseCheckLogForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'log' => new sfWidgetFormInputText(array(), array()),
            'time' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'log' => new sfValidatorString(array(), array()),
            'time' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('check_log[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'CheckLog';
    }
}