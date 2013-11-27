<?php

/**
 * User Base Form.
 */
class BaseUserForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'email' => new sfWidgetFormInputText(array(), array()),
            'username' => new sfWidgetFormInputText(array(), array()),
            'password' => new sfWidgetFormInputText(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'textpass' => new sfWidgetFormInputText(array(), array()),
            'avatar' => new sfWidgetFormInputText(array(), array()),
            'original_avatar' => new sfWidgetFormInputText(array(), array()),
            'nickname' => new sfWidgetFormInputText(array(), array()),
            'desc' => new sfWidgetFormInputText(array(), array()),
            'province' => new sfWidgetFormInputText(array(), array()),
            'city' => new sfWidgetFormInputText(array(), array()),
            'dtvsp' => new sfWidgetFormInputText(array(), array()),
            'device_id' => new sfWidgetFormInputText(array(), array()),
            'referer' => new sfWidgetFormInputText(array(), array()),
            'type' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'email' => new sfValidatorString(array(), array()),
            'username' => new sfValidatorString(array(), array()),
            'password' => new sfValidatorString(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'textpass' => new sfValidatorString(array(), array()),
            'avatar' => new sfValidatorString(array(), array()),
            'original_avatar' => new sfValidatorString(array(), array()),
            'nickname' => new sfValidatorString(array(), array()),
            'desc' => new sfValidatorString(array(), array()),
            'province' => new sfValidatorString(array(), array()),
            'city' => new sfValidatorString(array(), array()),
            'dtvsp' => new sfValidatorString(array(), array()),
            'device_id' => new sfValidatorInteger(array(), array()),
            'referer' => new sfValidatorString(array(), array()),
            'type' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('user[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'User';
    }
}