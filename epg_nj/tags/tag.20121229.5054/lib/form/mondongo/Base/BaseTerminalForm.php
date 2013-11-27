<?php

/**
 * Terminal Base Form.
 */
class BaseTerminalForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'brand' => new sfWidgetFormInputText(array(), array()),
            'clienttype' => new sfWidgetFormInputText(array(), array()),
            'version' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'brand' => new sfValidatorString(array(), array()),
            'clienttype' => new sfValidatorString(array(), array()),
            'version' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('terminal[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Terminal';
    }
}