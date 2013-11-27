<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QqtWeiboClient
 *
 * @author lizhi
 */
class QqtWeiboClient extends WeiboClient  {

    //put your code here

    public function  __construct($akey , $skey , $accecss_token , $accecss_token_secret) {
        $this->oauth = new QqtWeiboOAuth( $akey , $skey , $accecss_token , $accecss_token_secret);
    }

    public function users(array $params){
        return $this->oauth;
    }

    public function tags(array $params){
        
    }

    /**
     * 回复相应的信息
     * @access public
     * @param text 回复的信息
     * @param sid 回复信息的ID
     * @param $context 微博内容
     * @param clientip: 用户IP(以分析用户所在地)
     * @param jing: 经度（可以填空）
     * @param wei: 纬度（可以填空）
     * @p: 图片
     * @r: 父id
     * @return void
     */
    public function  reply($sid, $text, $cid) {
        $params = array();
        $params = array(
                'format' => parent::$_method,
                'content' => $text,
                'clientip' => $_SERVER['REMOTE_ADDR'],
                'jing' => '',
                'wei' => ''
        );
        $url = 'http://open.t.qq.com/api/t/reply?f=1';
        $params['reid'] = $sid;
        return $this->oauth->post($url,$params);
    }

    /**
     * 发布一条新weibo
     * @access public
     * @param string $text weibo的内容
     */
    public function update($text){
        $params = array(
                'format' => parent::$_method,
                'content' => $text,
                'clientip' => $_SERVER['REMOTE_ADDR'],
                'jing' => '',
                'wei' => ''
        );
        $url = 'http://open.t.qq.com/api/t/add?f=1';
	return $this->oauth->post($url,$params);
    }

    /**
     * 发布一张带图片的weibo
     * @access public
     * @param string $text weibo的内容
     * @param string $pic weibo所带的图片
     * @return void
     */
    public function upload($text, $pic){
        $params = array(
                'format' => parent::$_method,
                'content' => $text,
                'clientip' => $_SERVER['REMOTE_ADDR'],
                'jing' => '',
                'wei' => ''
        );
        if(empty($pic)){
            $url = 'http://open.t.qq.com/api/t/add?f=1';
            return $this->oauth->post($url,$params);
        }else{
            $url = 'http://open.t.qq.com/api/t/add_pic?f=1';
            $params['pic'] = $pic;
            return $this->oauth->post($url,$params,true);
        }
    }

    /**
     * 对一条微博信息进行评论
     *
     * @access public
     * @param mixed $sid 要评论的微博id
     * @param mixed $text 评论内容
     * @param bool $cid 要评论的评论id
     * @return array
     */
    public function  send_comment($sid, $text, $cid) {
        $params = array(
                'format' => parent::$_method,
                'content' => $text,
                'clientip' => $_SERVER['REMOTE_ADDR'],
                'jing' => '',
                'wei' => ''
        );
        $url = 'http://open.t.qq.com/api/t/comment?f=1';
        $params['reid'] = $sid;
        return $this->oauth->post($url,$params);
    }

    /**
     * 获取某一话题下的微博
     * @access public
     * @param string $tread
     *
     * @return array | void
     */
    public function  trends_statuses($trend) {
        $url = 'http://open.t.qq.com/api/trends/ht?f=1';
        $params = array(
                'format' => parent::$_method,
                'type' => 1,
                'reqnum' => 1, //表示请求的个数，最多为20个
                'pos' => 1 //请求位置，第一次请求时填0，继续填上次返回的POS
        );
        return $this->oauth->get($url,$params);
    }

    /**
     * 搜索
     */
    public  function search($keyword, $page, $count){
        $url = 'http://open.t.qq.com/api/search/ht?f=1';
        //$url = 'http://open.t.qq.com/api/search/t?f=1';
        $params = array(
                'format' => parent::$_method,
                'keyword' => $keyword,
                'pagesize' => $count,
                'page' => $page
        );
        return $this->oauth->get($url,$params);
    }

    /**
     * 获取自己的个人信息
     */
    public function getUserInfo(array $username){
        // $username['user'] at sina use user_id ,qq weibo use username
		if(!$username || !$username['user']){
			$url = 'http://open.t.qq.com/api/user/info?f=1';
			$params = array(
				'format' => parent::$_method
			);
		}else{
			$url = 'http://open.t.qq.com/api/user/other_info?f=1';
			$params = array(
				'format' => parent::$_method,
				'name' => $username['user']
			);
		}
	 	$userinfo = $this->oauth->get($url,$params);
                if($userinfo){
                    $user['name'] = $userinfo['Data']['Name'];
                    return $user;
                }
                return false;
    }


}

class QqtWeiboOAuth extends WeiboOAuth{

    public $host = "http://open.t.qq.com/";

    public function  __construct($akey , $skey , $accecss_token , $accecss_token_secret) {
        parent::__construct($akey , $skey , $accecss_token , $accecss_token_secret);
    }

    public function accessTokenURL()  { return 'https://open.t.qq.com/cgi-bin/access_token'; }

    public function authenticateURL() { return 'http://open.t.qq.com/cgi-bin/authenticate'; }

    public function authorizeURL()    { return 'http://open.t.qq.com/cgi-bin/authorize'; }

    public function requestTokenURL() { return 'https://open.t.qq.com/cgi-bin/request_token'; }
}
