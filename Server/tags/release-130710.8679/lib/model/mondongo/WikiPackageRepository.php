<?php

/**
 * Repository of WikiPackage document.
 */
class WikiPackageRepository extends \BaseWikiPackageRepository
{
	public function getWikiByPageAndSize($page,$size ,$scene='') 
    {
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
    	if(empty($scene))
    	{
			return $this->find(
				array(
					"query"=>array(
                 		"is_public"=>true
             		),
					'limit' => intval($size),
					'skip'  => $offset,
					'sort' => array('created_at' => -1),
					)
			);
    	}
    	else
    	{
			return $this->find(
				array(
					'query'=>array(
						'scene'=>$scene,
						"is_public"=>true
					),
					'limit' => intval($size),
					'skip'  => $offset,
					'sort' => array('created_at' => -1),
					)
			);
    		
    	}
    } 
    public function getWikiBySceneNoLimit($scene) {
		return $this->find(
			array(
				'query'=>array(
					'scene'=>$scene,
					"is_public"=>true
					),
				)
		);
    }
}