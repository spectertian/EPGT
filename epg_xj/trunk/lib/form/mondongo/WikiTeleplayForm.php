<?php

/**
 * WikiFilm Form.
 */
class WikiTeleplayForm extends WikiForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();

        $this->setWidget("alias", new WidgetFormInputTextArray());
        $this->setWidget("director", new WidgetFormInputTextArray());
        $this->setWidget("starring", new WidgetFormInputTextArray());
        $this->setWidget("writer", new WidgetFormInputTextArray());
        $this->setWidget("distributor", new WidgetFormInputTextArray());
        $this->setWidget("produced", new sfWidgetFormInputText());
        $this->setWidget("released", new sfWidgetFormInputText());
        $this->setWidget("language", new sfWidgetFormInputText());
        $this->setWidget("screenshots", new sfWidgetFormInputCheckbox());
        $this->setWidget("episodes", new sfWidgetFormInputText());
        $this->setWidget("update_episodes", new sfWidgetFormInputText());
        $this->setWidget("country", new sfWidgetFormInputText());
        $this->setWidget("qiyi", new sfWidgetFormInputText());
        $this->setWidget("imdb", new sfWidgetFormInputText());
        $this->setWidget("douban_id", new sfWidgetFormInputText());

        $this->setValidator("alias", new sfValidatorString(array("required" => false)));
        $this->setValidator("writer", new sfValidatorString(array("required" => false)));
        $this->setValidator("distributor", new sfValidatorString(array("required" => false)));
        $this->setValidator("director", new sfValidatorString(array("required" => false)));
        $this->setValidator("starring", new sfValidatorString(array("required" => false)));
        $this->setValidator("produced", new sfValidatorString(array("required" => false)));
        $this->setValidator("released", new sfValidatorString(array("required" => false)));
        $this->setValidator("language", new sfValidatorString(array("required" => false)));
        $this->setValidator("screenshots", new sfValidatorPass());
        $this->setValidator("episodes", new sfValidatorString(array("required" => false)));
        $this->setValidator("update_episodes", new sfValidatorString(array("required" => false)));
        $this->setValidator("country", new sfValidatorString(array("required" => false)));
        $this->setValidator("qiyi", new sfValidatorString(array("required" => false)));
        $this->setValidator("imdb", new sfValidatorString(array("required" => false)));
        $this->setValidator("douban_id", new sfValidatorString(array("required" => false)));
    }

    public function  getModelName() {
        return "Wiki_FilmTV_Teleplay";
    }
}
