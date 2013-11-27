<?php

/**
 * Recommend Base Form.
 */
class BaseRecommendForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'title' => new sfWidgetFormInputText(array(), array()),
            'is_public' => new sfWidgetFormInputCheckbox(array(), array()),
            'scene' => new sfWidgetFormInputText(array(), array()),
            'sort' => new sfWidgetFormInputText(array(), array()),
            'pic' => new sfWidgetFormInputText(array(), array()),
            'smallpic' => new sfWidgetFormInputText(array(), array()),
            'desc' => new sfWidgetFormInputText(array(), array()),
            'url' => new sfWidgetFormInputText(array(), array()),
            'isdesc_display' => new sfWidgetFormInputCheckbox(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'title' => new sfValidatorString(array(), array()),
            'is_public' => new sfValidatorBoolean(array(), array()),
            'scene' => new sfValidatorString(array(), array()),
            'sort' => new sfValidatorInteger(array(), array()),
            'pic' => new sfValidatorString(array(), array()),
            'smallpic' => new sfValidatorString(array(), array()),
            'desc' => new sfValidatorString(array(), array()),
            'url' => new sfValidatorString(array(), array()),
            'isdesc_display' => new sfValidatorBoolean(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('recommend[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Recommend';
    }
}