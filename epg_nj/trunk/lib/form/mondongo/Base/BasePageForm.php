<?php

/**
 * Page Base Form.
 */
class BasePageForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'pagename' => new sfWidgetFormInputText(array(), array()),
            'content' => new sfWidgetFormInputText(array(), array()),
            'version' => new sfWidgetFormInputText(array(), array()),
            'author' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'pagename' => new sfValidatorString(array(), array()),
            'content' => new sfValidatorString(array(), array()),
            'version' => new sfValidatorInteger(array(), array()),
            'author' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('page[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Page';
    }
}