<?php

/**
 * ContentCdi Base Form.
 */
class BaseContentCdiForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'from' => new sfWidgetFormInputText(array(), array()),
            'content' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'command' => new sfWidgetFormInputText(array(), array()),
            'subcontent_id' => new sfWidgetFormInputText(array(), array()),
            'page_id' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'from' => new sfValidatorString(array(), array()),
            'content' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'command' => new sfValidatorString(array(), array()),
            'subcontent_id' => new sfValidatorString(array(), array()),
            'page_id' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('content_cdi[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ContentCdi';
    }
}