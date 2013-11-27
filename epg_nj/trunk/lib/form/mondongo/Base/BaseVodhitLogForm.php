<?php

/**
 * VodhitLog Base Form.
 */
class BaseVodhitLogForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'hits' => new sfWidgetFormInputText(array(), array()),
            'date' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'hits' => new sfValidatorInteger(array(), array()),
            'date' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('vodhit_log[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'VodhitLog';
    }
}