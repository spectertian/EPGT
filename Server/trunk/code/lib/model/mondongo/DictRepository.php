<?php

/**
 * Repository of Dict document.
 */
class DictRepository extends \BaseDictRepository
{
	
	public function getDictByName($name){
		//$memcache = tvCache::getInstance();
		//$memcache_key = 'Dict_name_'.$name;
		//$dict = $memcache->get($memcache_key);
		//if(!$dict){
			$dict =  $this->findOne(array("query"=>array("name"=>$name)));
			//if($dict){
			//	$memcache->set($memcache_key, $dict);
			//}
		//}
		return $dict;
	}
}