<?php

/**
 * LiveRecommend Base Form.
 */
class BaseLiveRecommendForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'date' => new sfWidgetFormInputText(array(), array()),
            'start_time' => new sfWidgetFormInputText(array(), array()),
            'endt_ime' => new sfWidgetFormInputText(array(), array()),
            'list' => new sfWidgetFormInputText(array(), array()),
            'user_name' => new sfWidgetFormInputText(array(), array()),
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputCheckbox(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'date' => new sfValidatorString(array(), array()),
            'start_time' => new sfValidatorString(array(), array()),
            'endt_ime' => new sfValidatorString(array(), array()),
            'list' => new sfValidatorString(array(), array()),
            'user_name' => new sfValidatorString(array(), array()),
            'user_id' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorBoolean(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('live_recommend[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'LiveRecommend';
    }
}