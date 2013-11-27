<?php

/**
 * CategoryRecommend Base Form.
 */
class BaseCategoryRecommendForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'category' => new sfWidgetFormInputText(array(), array()),
            'template' => new sfWidgetFormInputText(array(), array()),
            'is_default' => new sfWidgetFormInputCheckbox(array(), array()),
            'start_time' => new sfWidgetFormInputText(array(), array()),
            'end_time' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'category' => new sfValidatorString(array(), array()),
            'template' => new sfValidatorString(array(), array()),
            'is_default' => new sfValidatorBoolean(array(), array()),
            'start_time' => new sfValidatorString(array(), array()),
            'end_time' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('category_recommend[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'CategoryRecommend';
    }
}