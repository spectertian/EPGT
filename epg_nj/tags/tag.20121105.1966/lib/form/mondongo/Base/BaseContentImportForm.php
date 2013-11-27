<?php

/**
 * ContentImport Base Form.
 */
class BaseContentImportForm extends BaseFormMondongo
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
            'from_title' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'inject_id' => new sfValidatorString(array(), array()),
            'from' => new sfValidatorString(array(), array()),
            'from_id' => new sfValidatorString(array(), array()),
            'from_title' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('content_import[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ContentImport';
    }
}