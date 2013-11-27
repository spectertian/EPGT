<?php

/**
 * Album Base Form.
 */
class BaseAlbumForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'desc' => new sfWidgetFormInputText(array(), array()),
            'author' => new sfWidgetFormInputText(array(), array()),
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'is_public' => new sfWidgetFormInputCheckbox(array(), array()),
            'rec_num' => new sfWidgetFormInputText(array(), array()),
            'list' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'desc' => new sfValidatorString(array(), array()),
            'author' => new sfValidatorString(array(), array()),
            'user_id' => new sfValidatorString(array(), array()),
            'is_public' => new sfValidatorBoolean(array(), array()),
            'rec_num' => new sfValidatorInteger(array(), array()),
            'list' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('album[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Album';
    }
}