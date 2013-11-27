<?php

/**
 * UserWikiStatus Base Form.
 */
class BaseUserWikiStatusForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'type' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'user_id' => new sfValidatorString(array(), array()),
            'type' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('user_wiki_status[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'UserWikiStatus';
    }
}