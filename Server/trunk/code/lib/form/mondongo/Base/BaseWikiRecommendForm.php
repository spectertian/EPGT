<?php

/**
 * WikiRecommend Base Form.
 */
class BaseWikiRecommendForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'model' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'model' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('wiki_recommend[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'WikiRecommend';
    }
}