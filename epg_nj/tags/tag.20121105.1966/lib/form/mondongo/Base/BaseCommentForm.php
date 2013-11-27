<?php

/**
 * Comment Base Form.
 */
class BaseCommentForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'parent_id' => new sfWidgetFormInputText(array(), array()),
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'text' => new sfWidgetFormInputText(array(), array()),
            'mark' => new sfWidgetFormInputText(array(), array()),
            'is_publish' => new sfWidgetFormInputCheckbox(array(), array()),
            'type' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'parent_id' => new sfValidatorString(array(), array()),
            'user_id' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'text' => new sfValidatorString(array(), array()),
            'mark' => new sfValidatorInteger(array(), array()),
            'is_publish' => new sfValidatorBoolean(array(), array()),
            'type' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('comment[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Comment';
    }
}