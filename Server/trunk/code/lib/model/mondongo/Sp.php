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
	    $signal=$this->getSignal();
        $memcache = tvCache::getInstance();
        $memcache_key = 'getChannelObjs_'.$signal;	//Modify by 缓存的key不经过md5加密 tianzhongsheng-ex@huan.tv Time 2013-04-22 17:46:00
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