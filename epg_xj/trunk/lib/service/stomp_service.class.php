<?php

/**
 * httpsqs工厂
 */
class StompService 
{
    static private $stomp = null; 
    
    static public function get() 
    {
        if (empty(self::$stomp)) {
            $host = sfConfig::get("app_stomp_host");
            self::$stomp = new Stomp($host);
        }        
        return self::$stomp;
    }
}

