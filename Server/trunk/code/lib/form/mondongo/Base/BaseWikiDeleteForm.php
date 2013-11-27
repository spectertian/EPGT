<?php

/**
 * WikiDelete Base Form.
 */
class BaseWikiDeleteForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'admin' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'admin' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('wiki_delete[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'WikiDelete';
    }
}