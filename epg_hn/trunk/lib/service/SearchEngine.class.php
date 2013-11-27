<?php

class SearchEngine {
    static protected $databases = array();
    static protected $writable_databases = array();
 
    /**
     * 静态方法，目前只获取 xapian 数据库
     * @return XapianDatabase
     * @author zhigang
     */
    static public function getDatabase($name) {
        if (! isset(self::$databases[$name])) {
            $app_search = sfConfig::get("app_search_{$name}");
            $config = $app_search['config'];

            if (isset($config['path'])) {
                self::$databases[$name] = new XapianDatabase($config['path']); //Xapian::auto_open_stub($config['path']);
            } elseif (isset($config['host'])) {
                self::$databases[$name] = Xapian::remote_open($config['host'], (int)$config['port']);
            } else {
                self::$databases[$name] = null;
            }
        }         
        return self::$databases[$name];
    }

    /**
     * 静态方法， 获取可写数据库
     * @return XapianWritableDatabase
     * @author zhigang
     */
    static public function getWritableDatabase($name) {
        if (! isset(self::$writable_databases[$name])) {
            $app_search = sfConfig::get("app_search_{$name}");
            $config = $app_search['config'];

            if (isset($config['path'])) {
                self::$writable_databases[$name] = new XapianWritableDatabase($config['path'], Xapian::DB_CREATE_OR_OPEN);
            } elseif (isset($config['host'])) {
                self::$writable_databases[$name] = Xapian::remote_open_writable($config['host'], (int)$config['port']);
            } else {
                self::$writable_databases[$name] = null;
            }
        }
        return self::$writable_databases[$name];
    }
}
