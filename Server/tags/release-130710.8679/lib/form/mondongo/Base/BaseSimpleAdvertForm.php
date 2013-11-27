<?php

/**
 * SimpleAdvert Base Form.
 */
class BaseSimpleAdvertForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'image' => new sfWidgetFormInputText(array(), array()),
            'url' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormInputText(array(), array()),
            'end_time' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'image' => new sfValidatorString(array(), array()),
            'url' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorString(array(), array()),
            'end_time' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('simple_advert[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'SimpleAdvert';
    }
}