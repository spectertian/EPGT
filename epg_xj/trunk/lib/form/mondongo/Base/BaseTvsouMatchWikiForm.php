<?php

/**
 * TvsouMatchWiki Base Form.
 */
class BaseTvsouMatchWikiForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'tvsou_id' => new sfWidgetFormInputText(array(), array()),
            'tvsou_title' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_title' => new sfWidgetFormInputText(array(), array()),
            'compare' => new sfWidgetFormInputCheckbox(array(), array()),
            'author' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'tvsou_id' => new sfValidatorString(array(), array()),
            'tvsou_title' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'wiki_title' => new sfValidatorString(array(), array()),
            'compare' => new sfValidatorBoolean(array(), array()),
            'author' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('tvsou_match_wiki[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'TvsouMatchWiki';
    }
}