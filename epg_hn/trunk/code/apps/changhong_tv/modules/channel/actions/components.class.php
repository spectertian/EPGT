<?php

class channelComponents extends sfComponents {

    public function executeShow_tags_list(sfWebRequest $request) {
        $this->tags = Doctrine::getTable('Tags')->findAll();
    }

    public function executeShow_weekdays_list(sfWebRequest $request) {
        
    }

}
?>