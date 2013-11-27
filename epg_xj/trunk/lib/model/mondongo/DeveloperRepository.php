<?php

/**
 * Repository of Developer document.
 */
class DeveloperRepository extends \BaseDeveloperRepository
{
    public function updateCahceByApikey($apikey)
    {
        $developer = $this->findOne(array('query' => array('apikey'=> $apikey)));
        if($developer) {
            tvCache::getInstance()->set("developer_".$apikey, $developer, time() +  60*60*24*180);
        }
        return $developer;
    }
}