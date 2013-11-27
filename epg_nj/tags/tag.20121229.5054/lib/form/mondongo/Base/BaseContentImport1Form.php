<?php

/**
 * ContentImport1 Base Form.
 */
class BaseContentImport1Form extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'inject_id' => new sfWidgetFormInputText(array(), array()),
            'from' => new sfWidgetFormInputText(array(), array()),
            'from_id' => new sfWidgetFormInputText(array(), array()),
            'children_id' => new sfWidgetFormInputText(array(), array()),
            'from_title' => new sfWidgetFormInputText(array(), array()),
            'provider_id' => new sfWidgetFormInputText(array(), array()),
            'from_type' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'state_edit' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'inject_id' => new sfValidatorString(array(), array()),
            'from' => new sfValidatorString(array(), array()),
            'from_id' => new sfValidatorString(array(), array()),
            'children_id' => new sfValidatorString(array(), array()),
            'from_title' => new sfValidatorString(array(), array()),
            'provider_id' => new sfValidatorString(array(), array()),
            'from_type' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'state_edit' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('content_import1[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ContentImport1';
    }
}