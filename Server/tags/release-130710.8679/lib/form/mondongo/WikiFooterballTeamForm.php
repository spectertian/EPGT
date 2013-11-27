<?php

/**
 * WikiActor Form.
 */
class WikiFooterballTeamForm extends WikiTeamForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();

        $this->setWidget("nickname", new sfWidgetFormInputText());

        $this->setValidator("nickname", new sfValidatorString(array("required" => false)));
    }

    public function  getModelName() {
        return "Wiki_Team_FooterballTeam";
    }
}