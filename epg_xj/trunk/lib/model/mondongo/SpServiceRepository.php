<?php

/**
 * Repository of SpService document.
 */
class SpServiceRepository extends \BaseSpServiceRepository
{

    function getServicesBySpCode($sp_code = 'hncatv', $type = '', $sort = '', $page = 1, $pagesize = 200) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'SpService_BySpCode_'.$sp_code.'_'.$type.'_'.$sort.'_'.$page.'_'.$pagesize;
        echo $memcache_key;
        $services = $memcache->get($memcache_key);
        if(!$services) {
            $query = array("sp_code" => $sp_code);
            $sort = array("logicNumber" => 1);
            $services = $this->find(array("query" => $query,
                                          "sort" => $sort,
                                          "skip" => ($page-1)*$pagesize,
                                          "limit" => $pagesize));
            $memcache->set($memcache_key,$services);                       
        }
        return $services;
    }
    
    function getServiceCodesBySpCode($sp_code = 'hncatv', $type = '', $sort = '', $page = 1, $pagesize = 200) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'SpServiceCode_BySpCode_'.$sp_code.'_'.$type.'_'.$sort.'_'.$page.'_'.$pagesize;
        $servicecodes = $memcache->get($memcache_key);
        if(!$servicess) {
            $query = array("sp_code" => $sp_code);
            if($type != "") $query['tags'] = $type; 
            $sort = array("logicNumber" => 1);            
            $services = $this->find(array("query" => $query,
                                          "sort" => $sort,
                                          "fields" => array("channel_code" => 1),
                                          "skip" => ($page-1)*$pagesize,
                                          "limit" => $pagesize));
            foreach($services as $service) {
                $servicecodes[] = $service->getChannelCode();
            }
            $memcache->set($memcache_key,$servicecodes);                       
        }
        return $servicecodes;
    }
    /**
     * @desc 获取频道表
     * @param <type>
     * @author jhm
     */
    function getServiceChannelsList($type = 'all', $sort = '', $page = 1, $pagesize = 200)
    {
    	 $memcache = tvCache::getInstance();
    	 $memcache_key = 'SpService_ChannelsList_'.$type.'_'.$page.'_'.$pagesize.'_'.$sort;
    	 $services = $memcache->get($memcache_key);
    	 if(!$services) {
    	 	if($type != 'all'){
   	    		$query = array("tags" => $type);
	    		$sort = array("logicNumber" => 1);
	    		$services = $this->find(array("query" => $query,
	    				"sort" => $sort,
	    				"skip" => ($page-1)*$pagesize,
	    				"limit" => $pagesize));
    	 	}else{
    	 		$sort = array("logicNumber" => 1);
    	 		$services = $this->find(array(
    	 				"sort" => $sort,
    	 				"skip" => ($page-1)*$pagesize,
    	 				"limit" => $pagesize));
    	 	}
    	 	$memcache->set($memcache_key,$services);
    	  }
    	return $services;
    }
    
    
    
    
    
}