<?php

/**
 * ProgramRecommend Base Form.
 */
class BaseProgramRecommendForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'tv_station_id' => new sfWidgetFormInputText(array(), array()),
            'channel_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'img' => new sfWidgetFormInputText(array(), array()),
            'play_time' => new sfWidgetFormInputText(array(), array()),
            'content' => new sfWidgetFormInputText(array(), array()),
            'sort' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'tv_station_id' => new sfValidatorInteger(array(), array()),
            'channel_id' => new sfValidatorInteger(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'img' => new sfValidatorString(array(), array()),
            'play_time' => new sfValidatorString(array(), array()),
            'content' => new sfValidatorString(array(), array()),
            'sort' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('program_recommend[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ProgramRecommend';
    }
}