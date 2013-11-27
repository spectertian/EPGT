<?php

/**
 * VideosZhui Base Form.
 */
class BaseVideosZhuiForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_name' => new sfWidgetFormInputText(array(), array()),
            'total' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'local' => new sfWidgetFormInputText(array(), array()),
            'source' => new sfWidgetFormInputText(array(), array()),
            'update_time' => new sfWidgetFormInputText(array(), array()),
            'success' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'wiki_name' => new sfValidatorString(array(), array()),
            'total' => new sfValidatorInteger(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'local' => new sfValidatorInteger(array(), array()),
            'source' => new sfValidatorString(array(), array()),
            'update_time' => new sfValidatorString(array(), array()),
            'success' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('videos_zhui[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'VideosZhui';
    }
}