<?php

/**
 * wiki people form
 */
class WikiTeamForm extends WikiForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();

        $this->setWidget("english_name", new sfWidgetFormInputText());
        $this->setWidget("founded", new sfWidgetFormInputText());
        $this->setWidget("arena", new sfWidgetFormInputText());
        $this->setWidget("city", new sfWidgetFormInputText());
        $this->setWidget("coach", new sfWidgetFormInputText());
        $this->setWidget("owner", new sfWidgetFormInputText());
        $this->setWidget("manager", new sfWidgetFormInputText());
        $this->setWidget("color", new sfWidgetFormInputText());
        
        $this->setValidator("english_name", new sfValidatorString(array("required" => false)));
        $this->setValidator("founded", new sfValidatorString(array("required" => false)));
        $this->setValidator("arena", new sfValidatorString(array("required" => false)));
        $this->setValidator("city", new sfValidatorString(array("required" => false)));
        $this->setValidator("coach", new sfValidatorString(array("required" => false)));
        $this->setValidator("owner", new sfValidatorString(array("required" => false)));
        $this->setValidator("manager", new sfValidatorString(array("required" => false)));
        $this->setValidator("color", new sfValidatorString(array("required" => false)));
    }
}

