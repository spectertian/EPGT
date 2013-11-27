<?php
class tvCache{
    private  $option = array();
    private  $memcache;
    private static $install;

    private function __construct(){
         $this->memcache = new memcache;
         $this->option = $this->_getOption();
         foreach ($this->option as $value){
             $this->memcache->addserver($value['host'], $value['port']);
         }
    }

    /**
     *
     * @return <type> object
     * @author ly
     * @date 2011-05-23
     */
    public static function getInstance(){
            if (empty(self::$install)){
                    self::$install = new tvCache();
            }
            return self::$install;
    }

    /**
     *
     * @return <type> array
     * @author ly
     * @date 2011-09-5-23
     */
    private function _getOption(){
        return $this->option = sfConfig::get("app_memcache_servers");
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
    public function set($key, $value, $leftTime = null, $compression = MEMCACHE_COMPRESSED){
        $compression = sfConfig::get("app_memcache_compression");
        $losetime = sfConfig::get("app_memcache_lefttime")?sfConfig::get("app_memcache_lefttime"):7200;
        if(!isset ($leftTime) || is_null($leftTime)){
            return $this->memcache->set($key, $value, $compression, $losetime);
        }else{
            return $this->memcache->set($key, $value, $compression, $leftTime);
        }
        
    }
    /**
     *
     * @param <type> $key
     * @return <type> bool
     * @author ly
     * @date 2011-05-23
     */
    public function get($key){
        return $this->memcache->get($key);
    }

    /**
     *
     * @param <type> $key
     * @return <type> bool
     * @author ly
     * @date 2011-05-23
     */
    public function delete($key){
        return $this->memcache->delete($key);
    }

    /**
     *
     * @param <type> $key
     * @return <type> bool
     * @author ly
     * @date 2011-05-23
     */
    public function has($key){
        return !(false === $this->get($key));
    }

    /**
     * clear cache
     */
    public function clear(){
        $this->memcache->flush();
    }

}
