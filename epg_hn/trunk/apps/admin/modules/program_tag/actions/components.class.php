<?php

class program_tagComponents extends sfComponents {

    public function executeTags(sfWebRequest $request) {
        $program_id = $this->getVar('program_id');
        $tag_datas = Doctrine::getTable('ProgramTag')->getTagsWhereProgramId($program_id);
        $this->tag_datas = $tag_datas;
    }
}