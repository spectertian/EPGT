<?php

/**
 * UserShare Base Form.
 */
class BaseUserShareForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'stype' => new sfWidgetFormInputText(array(), array()),
            'sname' => new sfWidgetFormInputText(array(), array()),
            'accecss_token' => new sfWidgetFormInputText(array(), array()),
            'accecss_token_secret' => new sfWidgetFormInputText(array(), array()),
            'userinfo' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'user_id' => new sfValidatorString(array(), array()),
            'stype' => new sfValidatorInteger(array(), array()),
            'sname' => new sfValidatorString(array(), array()),
            'accecss_token' => new sfValidatorString(array(), array()),
            'accecss_token_secret' => new sfValidatorString(array(), array()),
            'userinfo' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('user_share[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'UserShare';
    }
}