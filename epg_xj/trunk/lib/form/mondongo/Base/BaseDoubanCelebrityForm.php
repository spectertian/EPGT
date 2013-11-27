<?php

/**
 * DoubanCelebrity Base Form.
 */
class BaseDoubanCelebrityForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'douban_id' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'name_en' => new sfWidgetFormInputText(array(), array()),
            'avatars' => new sfWidgetFormInputText(array(), array()),
            'summary' => new sfWidgetFormInputText(array(), array()),
            'gender' => new sfWidgetFormInputText(array(), array()),
            'birthday' => new sfWidgetFormInputText(array(), array()),
            'country' => new sfWidgetFormInputText(array(), array()),
            'born_place' => new sfWidgetFormInputText(array(), array()),
            'professions' => new sfWidgetFormInputText(array(), array()),
            'constellation' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'douban_id' => new sfValidatorInteger(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'name_en' => new sfValidatorString(array(), array()),
            'avatars' => new sfValidatorString(array(), array()),
            'summary' => new sfValidatorString(array(), array()),
            'gender' => new sfValidatorString(array(), array()),
            'birthday' => new sfValidatorString(array(), array()),
            'country' => new sfValidatorString(array(), array()),
            'born_place' => new sfValidatorString(array(), array()),
            'professions' => new sfValidatorString(array(), array()),
            'constellation' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('douban_celebrity[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'DoubanCelebrity';
    }
}