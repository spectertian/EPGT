<?php

/**
 * 文件存储工厂
 */
class StorageService {
    static private $cache = array();
    
    static public function get($name) {
        if (empty(self::$cache[$name])) {
            $class = sfConfig::get("app_{$name}_type");
            $config = sfConfig::get("app_{$name}_config");
//            sfContext::getInstance()->getLogger()->info($config['domain']);
            self::$cache[$name] = new $class($config);
            // self::$cache[$name]->setName($name);
        }
        
        return self::$cache[$name];
    }
}

