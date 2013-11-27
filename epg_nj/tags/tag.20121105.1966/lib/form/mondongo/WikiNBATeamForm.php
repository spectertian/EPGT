<?php

/**
 * WikiActor Form.
 */
class WikiNBATeamForm extends WikiTeamForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();
        
        $this->setWidget("conference", new sfWidgetFormInputText());
        $this->setWidget("division", new sfWidgetFormInputText());

        $this->setValidator("conference", new sfValidatorString(array("required" => false)));
        $this->setValidator("division", new sfValidatorString(array("required" => false)));
    }

    public function  getModelName() {
        return "Wiki_Team_NBATeam";
    }
}
