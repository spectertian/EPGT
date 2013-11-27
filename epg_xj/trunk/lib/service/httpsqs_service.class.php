<?php

/**
 * httpsqs工厂
 */
class HttpsqsService 
{
    static private $httpsqs = null;    
    static public function get() {
        if (empty(self::$httpsqs)) {
            $host = sfConfig::get("app_httpsqs_host");
            $port = sfConfig::get("app_httpsqs_port");
            $auth = sfConfig::get("app_httpsqs_auth");
            $charset = sfConfig::get("app_httpsqs_charset");
            self::$httpsqs = new httpsqs($host,$port,$auth,$charset);
        }        
        return self::$httpsqs;
    }
}

