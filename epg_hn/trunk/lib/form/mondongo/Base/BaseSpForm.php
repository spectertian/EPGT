<?php

/**
 * Sp Base Form.
 */
class BaseSpForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'signal' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'remark' => new sfWidgetFormInputText(array(), array()),
            'logo' => new sfWidgetFormInputText(array(), array()),
            'type' => new sfWidgetFormInputText(array(), array()),
            'channels' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'signal' => new sfValidatorString(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'remark' => new sfValidatorString(array(), array()),
            'logo' => new sfValidatorString(array(), array()),
            'type' => new sfValidatorString(array(), array()),
            'channels' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('sp[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Sp';
    }
}