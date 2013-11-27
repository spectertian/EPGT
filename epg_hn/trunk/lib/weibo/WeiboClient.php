<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Weibo
 *
 * @author lizhi
 */
require_once 'Exception.php';
require_once 'OAuth.php';
require_once 'WeiboOAuth.php';

abstract class WeiboClient {

    /**
     * 所支持的网站的类型
     * Sina http://open.weibo.com
     * Qq http://opensns.qq.com
     * Qqt http://open.t.qq.com/
     */
    protected static  $_types = array('Sina','Qq','Qqt');

    /**
     * 返回的相应格式
     * string $_method = json | xml
     */
    protected static $_method = 'json';

    /**
     * @param string $type 类型
     * @param string akey api-key
     * @param string $skey api secret
     * @param string access_token
     * @param string access_token secret
     * @return void
     */

    public static function factory($type, $akey , $skey , $accecss_token , $accecss_token_secret){
        if(!in_array($type, self::$_types)){
            throw new Weibo_Exception($type."is not in my types");
        }
        require_once $type.'WeiboClient.php';
        $className = $type.'WeiboClient';
        if(class_exists($className)){
            return new $className($akey , $skey , $accecss_token , $accecss_token_secret);
        }
        return false;
    }

    /**
     * 帐户相关
     * @param array $param
     */
    abstract function users(array $param);

    /**
     * Tags 用户标签接口
     * @param array $param
     */
    abstract function tags(array $param);

    /**
     * 回复相应的信息
     * @param float $sid 父ID
     * @param string $text 内容
     * @param float 消息的Id信息，子ID（必填）
     */
    abstract function reply($sid , $text ,$cid);

    /**
     * 获取自己的信息
     * @param array $user
     * @param $user['n'] 是用于qqt 中的用户名
     * @param $user['user_id'] 是用于sina中的user_id
     * #因为现在所两种平台所采用的用户机制不一样。
     */
    abstract function getUserInfo(array $user);

    /**
     * 发布一条新weibo
     * @param $text 消息内容
     */
    abstract function update($text);

    /**
     * 发布一张带图的weibo
     * @param $text 消息
     * @param $pic 图片地址（注）enctype="multipart/form-data"
     */
    abstract function upload($text, $pic);

    /**
     * 发送一条评论
     * @param $sid 父ID
     * @param $text 信息
     * @param $cid 子ID 可以为空
     */
    abstract function send_comment($sid , $text , $cid);

    /**
     * 获取某一话题下的微博
     */
    abstract function trends_statuses($trend);

    /**
     * 搜索相应的关键字
     * @param string $keyword
     * @param $page 页码
     * @param $count 数量
     */
    abstract function search($keyword, $page, $count);
}
?>
