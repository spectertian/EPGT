<?php

/**
 * WikiLiverecommend Base Form.
 */
class BaseWikiLiverecommendForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('wiki_liverecommend[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'WikiLiverecommend';
    }
}