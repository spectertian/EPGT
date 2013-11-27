<?php

/**
 * WikiMeta Base Form.
 */
class BaseWikiMetaForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'content' => new sfWidgetFormInputText(array(), array()),
            'html_cache' => new sfWidgetFormInputText(array(), array()),
            'mark' => new sfWidgetFormInputText(array(), array()),
            'screenshots' => new sfWidgetFormInputText(array(), array()),
            'guests' => new sfWidgetFormInputText(array(), array()),
            'year' => new sfWidgetFormInputText(array(), array()),
            'month' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'content' => new sfValidatorString(array(), array()),
            'html_cache' => new sfValidatorString(array(), array()),
            'mark' => new sfValidatorInteger(array(), array()),
            'screenshots' => new sfValidatorString(array(), array()),
            'guests' => new sfValidatorString(array(), array()),
            'year' => new sfValidatorString(array(), array()),
            'month' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('wiki_meta[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'WikiMeta';
    }
}