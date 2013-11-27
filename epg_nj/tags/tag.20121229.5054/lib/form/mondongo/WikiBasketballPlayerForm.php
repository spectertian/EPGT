<?php

/**
 * WikiActor Form.
 */
class WikiBasketballPlayerForm extends WikiPeopleForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();
        $this->setWidget("team", new sfWidgetFormInputText());
        $this->setWidget("position", new sfWidgetFormInputText());
        $this->setWidget("number", new sfWidgetFormInputText());

        $this->setValidator("team", new sfValidatorString(array("required" => false)));
        $this->setValidator("position", new sfValidatorString(array("required" => false)));
        $this->setValidator("number", new sfValidatorString(array("required" => false)));
    }

    public function  getModelName() {
        return "Wiki_People_BasketballPlayer";
    }
}
