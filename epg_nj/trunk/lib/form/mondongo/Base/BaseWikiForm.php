<?php

/**
 * Wiki Base Form.
 */
class BaseWikiForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'rev' => new sfWidgetFormInputText(array(), array()),
            'cover' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'html_cache' => new sfWidgetFormInputText(array(), array()),
            'content' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'comment_tags' => new sfWidgetFormInputText(array(), array()),
            'model' => new sfWidgetFormInputText(array(), array()),
            'has_video' => new sfWidgetFormInputText(array(), array()),
            'like_num' => new sfWidgetFormInputText(array(), array()),
            'dislike_num' => new sfWidgetFormInputText(array(), array()),
            'watched_num' => new sfWidgetFormInputText(array(), array()),
            'admin_id' => new sfWidgetFormInputText(array(), array()),
            'do_date' => new sfWidgetFormDateTime(array(), array()),
            'source' => new sfWidgetFormInputText(array(), array()),
            'tvsou_id' => new sfWidgetFormInputText(array(), array()),
            'first_letter' => new sfWidgetFormInputText(array(), array()),
            'douban_id' => new sfWidgetFormInputText(array(), array()),
            'verify' => new sfWidgetFormInputText(array(), array()),
            'video_update' => new sfWidgetFormDateTime(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),
            'slug' => new sfWidgetFormInputText(array(), array()),

        ));

        $this->setValidators(array(
            'rev' => new sfValidatorInteger(array(), array()),
            'cover' => new sfValidatorString(array(), array()),
            'wiki_id' => new sfValidatorInteger(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'html_cache' => new sfValidatorString(array(), array()),
            'content' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'comment_tags' => new sfValidatorString(array(), array()),
            'model' => new sfValidatorString(array(), array()),
            'has_video' => new sfValidatorInteger(array(), array()),
            'like_num' => new sfValidatorInteger(array(), array()),
            'dislike_num' => new sfValidatorInteger(array(), array()),
            'watched_num' => new sfValidatorInteger(array(), array()),
            'admin_id' => new sfValidatorInteger(array(), array()),
            'do_date' => new sfValidatorDateTime(array(), array()),
            'source' => new sfValidatorString(array(), array()),
            'tvsou_id' => new sfValidatorString(array(), array()),
            'first_letter' => new sfValidatorString(array(), array()),
            'douban_id' => new sfValidatorString(array(), array()),
            'verify' => new sfValidatorInteger(array(), array()),
            'video_update' => new sfValidatorDateTime(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),
            'slug' => new sfValidatorString(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('wiki[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Wiki';
    }
}