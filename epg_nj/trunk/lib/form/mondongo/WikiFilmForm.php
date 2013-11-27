<?php

/**
 * WikiFilm Form.
 */
class WikiFilmForm extends WikiForm
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
        $this->setWidget("country", new sfWidgetFormInputText());
        $this->setWidget("runtime", new sfWidgetFormInputText());
        $this->setWidget("screenshots", new sfWidgetFormInputCheckbox());
        $this->setWidget("qiyi", new sfWidgetFormInputText());


        $this->setValidator("alias", new sfValidatorString(array("required" => false)));
        $this->setValidator("director", new sfValidatorString(array("required" => false)));
        $this->setValidator("starring", new sfValidatorString(array("required" => false)));
        $this->setValidator("produced", new sfValidatorString(array("required" => false)));
        $this->setValidator("released", new sfValidatorString(array("required" => false)));
        $this->setValidator("language", new sfValidatorString(array("required" => false)));
        $this->setValidator("country", new sfValidatorString(array("required" => false)));
        $this->setValidator("runtime", new sfValidatorString(array("required" => false)));
        $this->setValidator("writer", new sfValidatorString(array("required" => false)));
        $this->setValidator("distributor", new sfValidatorString(array("required" => false)));
        $this->setValidator("qiyi", new sfValidatorString(array("required" => false)));
        $this->setValidator("screenshots", new sfValidatorPass());
    }

    public function  getModelName() {
        return "Wiki_FlimTV_Film";
    }
}
