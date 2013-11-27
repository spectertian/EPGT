<?php
/**
 * MogileFS文件存储
 */
class MogilefsStorage {
    /**
     * @var MogileFS
     */
    protected $mogile_fs_client;
    protected $class;

    /**
     * 构造方法，与MogileFS Server连接
     * @param $domain String
     * @param $hosts Array
     * @param $root String
     */
    public function __construct($config) {
        $domain = $config['domain'];
        $hosts = $config['hosts'];
        if (!is_array($hosts)) {
            $hosts = array($hosts);
        }
        $this->class = $config['class'];
        $this->mogile_fs_client = MyMogileFS::NewMogileFS($domain, $hosts);
    }

    /**
     * 保存文件至服务器
     * @param $key String   关键字， 保证唯一
     * @param $class String  类别，对于服务器端主要设置保存份数
     * @param $filename String   要保存文件的实际地址
     * @return   boolean
     */
    public function save($key, $filename) {
        $class = $this->class;
        $this->mogile_fs_client->saveFile($key, $class, $filename);
    }
    
    /**
     * 获取文件内容
     * @param $key String 关键字，同保存文件时一致
     * @param $send boolean 是否立即发送加请求处
     * @return   mixed
     */
    public function get($key, $send = false) {
        if ($send) {
            $data = $this->mogile_fs_client->getFileDataAndSend($key);
        } else {
            $data = $this->mogile_fs_client->getFileData($key);
        }
        return $data;
    }
    
    /**
     * 获取文件可访问地址，内网使用，
     * 返回如 http://192.168.0.57:3341/dev1/0/000/0000123.fid 形式数组
     * @param $key String 关键字，同保存文件时一致
     * @return   array
     */
    public function getPath($key) {
        $paths = $this->mogile_fs_client->getPaths($key);
        return $paths;
    }
    
    /**
     * 删除文件
     * @param $key String 关键字，同保存文件时一致
     * @return   boolean
     */
    public function delete($key) {
        $this->mogile_fs_client->delete($key);
        return true;
    }
}

