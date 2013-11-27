<?php

/**
 * WikiMatch Base Form.
 */
class BaseWikiMatchForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'pattern' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'pattern' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('wiki_match[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'WikiMatch';
    }
}