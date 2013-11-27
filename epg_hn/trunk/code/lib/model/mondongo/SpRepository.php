<?php

/**
 * Repository of Sp document.
 */
class SpRepository extends \BaseSpRepository
{
	public function getOneSpByName($spname)
	{
        $memcache = tvCache::getInstance();
        $memcache_key = md5('getOneSpByName'.','.$spname);
        $splist = $memcache->get($memcache_key);
        if(!$splist){   	   
    		$splist=$this->findOne(array(
    					'query'=>array(
    						'name'=>$spname,
    					)
    				));
            $memcache->set($memcache_key,$splist);
        }
        return $splist;
	}

	public function getSpByProvince($province='')
	{
        $memcache = tvCache::getInstance();
        $memcache_key = md5('getSpByProvince'.','.$province);
        $splist = $memcache->get($memcache_key);
        if(!$splist){   	   
            if($province!='')
                $query=array(
    					'query'=>array(
    						'province'=>$province,
    					)
    				);
            else
                $query=array();                
    		$splist=$this->find($query);
            $memcache->set($memcache_key,$splist);
        }
        return $splist;
	}    
}