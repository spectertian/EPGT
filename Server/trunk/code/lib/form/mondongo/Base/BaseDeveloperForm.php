<?php

/**
 * Developer Base Form.
 */
class BaseDeveloperForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'desc' => new sfWidgetFormInputText(array(), array()),
            'apikey' => new sfWidgetFormInputText(array(), array()),
            'secretkey' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'sources' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'desc' => new sfValidatorString(array(), array()),
            'apikey' => new sfValidatorString(array(), array()),
            'secretkey' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'sources' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('developer[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Developer';
    }
}