<?php

/**
 * VideoPlaylist Base Form.
 */
class BaseVideoPlaylistForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'url' => new sfWidgetFormInputText(array(), array()),
            'referer' => new sfWidgetFormInputText(array(), array()),
            'vc_id' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'wiki_id' => new sfValidatorString(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'url' => new sfValidatorString(array(), array()),
            'referer' => new sfValidatorString(array(), array()),
            'vc_id' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('video_playlist[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'VideoPlaylist';
    }
}