<?php

/**
 * Television Base Form.
 */
class BaseTelevisionForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_title' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'play_time' => new sfWidgetFormInputText(array(), array()),
            'week_day' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_title' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'play_time' => new sfValidatorString(array(), array()),
            'week_day' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('television[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Television';
    }
}