<?php

/**
 * WikiActor Form.
 */
class WikiBasketballTeamForm extends WikiTeamForm
{
    public function configure() {
        parent::configure();
        $this->unset_fildes();
    }

    public function  getModelName() {
        return "Wiki_Team_BasketballTeam";
    }
}
