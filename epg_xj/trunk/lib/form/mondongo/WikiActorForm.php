<?php

/**
 * WikiActor Form.
 */
class WikiActorForm extends WikiPeopleForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();
        $this->setWidget("faith", new sfWidgetFormInputText()); 
        $this->setWidget("region", new sfWidgetFormInputText());
        $this->setWidget("debut", new sfWidgetFormInputText()); //出道日期
        $this->setWidget("screenshots", new sfWidgetFormInputCheckbox());

        $this->setValidator("faith", new sfValidatorString(array("required" => false)));
        $this->setValidator("region", new sfValidatorString(array("required" => false)));
        $this->setValidator("debut", new sfValidatorString(array("required" => false)));
        $this->setValidator("screenshots", new sfValidatorPass());

    }

    public function  getModelName() {
        return "Wiki_People_Actor";
    }
}
