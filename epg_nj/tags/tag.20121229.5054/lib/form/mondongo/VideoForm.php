<?php

/**
 * Video Form.
 */
class VideoForm extends BaseVideoForm
{
    public function configure() {
        unset($this['created_at'], $this['updated_at'], $this['config']);
        
        $this->setWidget("title", new sfWidgetFormInputText());
        $this->setWidget("wiki_title", new sfWidgetFormInputText());
        $this->setWidget("wiki_id", new sfWidgetFormInputHidden());
        $this->setWidget("url", new sfWidgetFormInputText()); 

        $this->setValidator("wiki_id", new sfValidatorString(array("required" => true)));
        $this->setValidator("wiki_title", new sfValidatorString(array("required" => true)));
        $this->setValidator("url", new sfValidatorString(array("required" => false)));
        $this->setValidator("default", new sfValidatorString(array("required" => true)));
    }

}