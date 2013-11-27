<?php

/**
 * Sp document.
 */
class Sp extends \BaseSp
{
	/**
	 * 获取频道
	 * @return array
	 * @author wangnan
	 */
	public function getChannelObjs()
	{
        $memcache = tvCache::getInstance();
        $memcache_key = md5('getChannelObjs');
        $channels = $memcache->get($memcache_key);
        if(!$channels){ 
    		$channelCodes = $this->getChannels();
    		foreach($channelCodes as $channelCode)
    		{
    	        $channels[] = Doctrine::getTable('Channel')->findOneByCode($channelCode);
    		}
            $memcache->set($memcache_key,$channels);
        }
		return $channels;
	}
}