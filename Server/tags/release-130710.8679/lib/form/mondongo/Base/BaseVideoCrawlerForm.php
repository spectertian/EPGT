<?php

/**
 * VideoCrawler Base Form.
 */
class BaseVideoCrawlerForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'site' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'model' => new sfWidgetFormInputText(array(), array()),
            'url' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'site' => new sfValidatorString(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'model' => new sfValidatorString(array(), array()),
            'url' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('video_crawler[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'VideoCrawler';
    }
}