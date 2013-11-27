<?php
/**
 * cache Singleton
 *
 * @package    epg
 * @subpackage cache
 * @author     superwen
 */
class tvCache
{
    private $memcache;
    private static $install;

    /**
     * __construct
     * 
     * @author ly
     * @date 2011-05-23
     */
    private function __construct()
    {
        $this->memcache = new Memcached();        
        $option = sfConfig::get("app_memcache_servers");
        foreach ($option as $value){
            $this->memcache->addserver($value['host'], $value['port']);
        }
    }

    /**
     *
     * @return <type> object
     * @author ly
     * @date 2011-05-23
     */
    public static function getInstance()
    {
        if (empty(self::$install)){
            self::$install = new tvCache();
        }
        return self::$install;
    }

    /**
     *
     * @param <type> $key
     * @param <type> $value
     * @param <type> $compression
     * @param <type> $leftTime
     * @return <type> bool
     * @author ly
     * @date 2011-05-23
     */
    public function set($key, $value, $leftTime = null, $compression = MEMCACHE_COMPRESSED)
    {        
        if(!isset ($leftTime) || is_null($leftTime)){
            $leftTime = sfConfig::get("app_memcache_lefttime") ? sfConfig::get("app_memcache_lefttime") : 7200;
        }
        return $this->memcache->set($key, $value, $leftTime);
        //$compression = sfConfig::get("app_memcache_compression");
        //return $this->memcache->set($key, $value, $compression, $losetime);
    }
    
    /**
     *
     * @param <type> $key
     * @return <type> bool
     * @author ly
     * @date 2011-05-23
     */
    public function get($key)
    {
        return $this->memcache->get($key);
    }

    /**
     *
     * @param <type> $key
     * @return <type> bool
     * @author ly
     * @date 2011-05-23
     */
    public function delete($key)
    {
        return $this->memcache->delete($key);
    }

    /**
     *
     * @param <type> $key
     * @return <type> bool
     * @author ly
     * @date 2011-05-23
     */
    public function has($key)
    {
        return !(false === $this->get($key));
    }

    /**
     * 
     */
    public function clear()
    {
        //$this->memcache->flush();
    }
}
