<?php

/**
 * Repository of WikiLiverecommend document.
 */
class WikiLiverecommendRepository extends \BaseWikiLiverecommendRepository
{
    
    /**
     * 获取全部直播推荐到数组里面
     * @param <type> $id
     * @return <type>
     * @author ly
     */
    public function findAllToArray()
    {
        $memcache = tvCache::getInstance();
        $memcache_key = "Wiki_findAllLiveRec";
        $wikis = $memcache->get($memcache_key);
        if(!$wikis){
            $wikilist = $this->find();
            foreach($wikilist as $wiki) {
                $wikis[] = (string)$wiki->getId();
            }
            $memcache->set($memcache_key,$wikis);
        }
        return $wikis;
    }
}