<?php

/**
 * Repository of Sp document.
 */
class SpRepository extends \BaseSpRepository
{
	public function getOneSpByName($spname)
	{
        $memcache = tvCache::getInstance();
        $memcache_key = 'getOneSpByName'.','.$spname;	//Modify by 缓存的key不经过md5加密 tianzhongsheng-ex@huan.tv Time 2013-04-22 17:40:00
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
        $memcache_key = 'getSpByProvince'.','.$province;	//Modify by 缓存的key不经过md5加密 tianzhongsheng-ex@huan.tv Time 2013-04-22 17:43:00
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