<?php

/**
 * WikiPackage Base Form.
 */
class BaseWikiPackageForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'scene' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'is_public' => new sfWidgetFormInputCheckbox(array(), array()),
            'sort' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormInputText(array(), array()),
            'end_time' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'scene' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'is_public' => new sfValidatorBoolean(array(), array()),
            'sort' => new sfValidatorInteger(array(), array()),
            'start_time' => new sfValidatorString(array(), array()),
            'end_time' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('wiki_package[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'WikiPackage';
    }
}