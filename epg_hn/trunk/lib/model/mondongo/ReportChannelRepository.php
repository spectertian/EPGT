<?php

/**
 * Repository of ReportChannel document.
 */
class ReportChannelRepository extends \BaseReportChannelRepository
{
	/*
     * ²éÑ¯ÆµµÀÃû³Æ
     * @author lifucang 
     */
    public function SearchByDtvspAndName($dtvsp,$name) {          
        return $this->findOne(
             array(
				       'query'=>array(
                            'dtvsp' => $dtvsp,
                            'name' => $name,
                        )
		           )
        );           

    }  
}