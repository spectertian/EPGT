<?php

/**
 * Video Base Form.
 */
class BaseVideoForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_mata_id' => new sfWidgetFormInputText(array(), array()),
            'video_playlist_id' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'url' => new sfWidgetFormInputText(array(), array()),
            'config' => new sfWidgetFormInputText(array(), array()),
            'time' => new sfWidgetFormInputText(array(), array()),
            'mark' => new sfWidgetFormInputText(array(), array()),
            'model' => new sfWidgetFormInputText(array(), array()),
            'referer' => new sfWidgetFormInputText(array(), array()),
            'publish' => new sfWidgetFormInputCheckbox(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'wiki_mata_id' => new sfValidatorString(array(), array()),
            'video_playlist_id' => new sfValidatorString(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'url' => new sfValidatorString(array(), array()),
            'config' => new sfValidatorString(array(), array()),
            'time' => new sfValidatorString(array(), array()),
            'mark' => new sfValidatorInteger(array(), array()),
            'model' => new sfValidatorString(array(), array()),
            'referer' => new sfValidatorString(array(), array()),
            'publish' => new sfValidatorBoolean(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('video[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Video';
    }
}