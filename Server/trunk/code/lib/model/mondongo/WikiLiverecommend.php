<?php

/**
 * WikiLiverecommend document.
 */
class WikiLiverecommend extends \BaseWikiLiverecommend
{
    /**
     * 数据保存后
     * @author zhigang
     */
    public function postSave() 
    {        
        $memcache = tvCache::getInstance();
        $memcache_key = "Wiki_findAllLiveRec";
        $memcache->delete($memcache_key);
    }
    
    /**
     * 数据更新后
     * @author wangnan
     */   
	public function postUpdate() 
	{     
        $memcache = tvCache::getInstance();
        $memcache_key = "Wiki_findAllLiveRec";
        $memcache->delete($memcache_key);
	}
    
    /**
     * 数据删除后
     */
    public function postDelete() 
    {         
        $memcache = tvCache::getInstance();
        $memcache_key = "Wiki_findAllLiveRec";
        $memcache->delete($memcache_key);
    }
}