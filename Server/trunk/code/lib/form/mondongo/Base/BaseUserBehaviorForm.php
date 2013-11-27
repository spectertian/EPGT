<?php

/**
 * UserBehavior Base Form.
 */
class BaseUserBehaviorForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'user_name' => new sfWidgetFormInputText(array(), array()),
            'access' => new sfWidgetFormInputText(array(), array()),
            'values' => new sfWidgetFormInputText(array(), array()),
            'date' => new sfWidgetFormDateTime(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'user_id' => new sfValidatorString(array(), array()),
            'user_name' => new sfValidatorString(array(), array()),
            'access' => new sfValidatorString(array(), array()),
            'values' => new sfValidatorString(array(), array()),
            'date' => new sfValidatorDateTime(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('user_behavior[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'UserBehavior';
    }
}