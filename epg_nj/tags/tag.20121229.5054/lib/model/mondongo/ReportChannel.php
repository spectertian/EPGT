<?php

/**
 * ReportChannel document.
 */
class ReportChannel extends \BaseReportChannel
{
	/*
     * Ìí¼ÓÆµµÀÃû³Æ
     * @author lifucang 
     */
    public function add($dtvsp,$name) {
    	$this->setDtvsp($dtvsp);
        $this->setName($name);
        $this->setState(false);
        parent::save();

    }  
}