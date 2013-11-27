<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SinaWeiboClient
 *
 * @author lizhi
 */
class SinaWeiboClient extends WeiboClient{
    //put your code here

    /**
     * 构造函数
     *
     * @access public
     * @param mixed $akey 微博开放平台应用APP KEY
     * @param mixed $skey 微博开放平台应用APP SECRET
     * @param mixed $accecss_token OAuth认证返回的token
     * @param mixed $accecss_token_secret OAuth认证返回的token secret
     * @return void
     */
    function __construct( $akey , $skey , $accecss_token , $accecss_token_secret )
    {
        //$this->oauth = new WeiboOAuth( $akey , $skey , $accecss_token , $accecss_token_secret );
        $this->oauth = new SinaWeiboOAuth($akey , $skey , $accecss_token , $accecss_token_secret);
    }

    /**
     * 处理sina weibo 用户情况
     * @param $param['method'] = users_show 根据用户ID获取用户资料（授权用户）
     * @param $param['method'] = users_hot 获取系统推荐用户
     * @param $param['method'] = user_friends_update_remark 更新当前登录用户所关注的某个好友的备注信息
     * @param $param['method'] = users_suggestions 返回当前用户可能感兴趣的用户
     * @param $param['uid_or_name'] 用户的ID或者用户名字
     */
    public function  users(array $params) {
        if(empty($params) || !isset ($params['method'])){
            throw new Weibo_Exception('users param error');
        }
        if(!isset ($params['uid_or_name'])){
            throw new Weibo_Exception("users uid_or_name is empty");
        }
        switch(trim($params['method'])){
            case 'users_show';
                return $this->request_with_uid( 'http://api.t.sina.com.cn/users/show.'.parent::$_method ,  $params['uid_or_name']);
                break;
            case 'users_hot';
                return $this->request_with_uid('http://api.t.sina.com.cn/users/hot.'.parent::$_method, $params['uid_or_name']);
                break;
            case 'user_friends_update_remark';
                $param = array();
                /**
                 * @see http://open.weibo.com/wiki/index.php/User/friends/update_remark
                 */
                $param['user_id'] = $params['uid_or_name'];
                $param['remark'] = $param['remark']; //备注
                return $this->oauth->post( 'http://api.t.sina.com.cn/user/friends/update_remark.'.parent::$_method , $param);
                break;
            case 'users_suggestions':
                return $this->request_with_uid( 'http://api.t.sina.com.cn/users/suggestions.'.parent::$_method ,  $params['uid_or_name'], false, false, false, 'get');
                return $this->oauth->get('http://api.t.sina.com.cn/users/suggestions.'.parent::$_method);
                break;
        }

    }

    /**
     * 获取自己的个人信息
     */
    public function  getUserInfo(array $user) {
        // $user['user'] at sina use user_id ,qq weibo use username
        $userinfo = $this->request_with_uid( 'http://api.t.sina.com.cn/users/show.'.parent::$_method ,  $user['user']);
        if($userinfo){
            $user['name'] = $userinfo['name'];
            return $user;
        }
        return FALSE;
    }

    public function tags(array $param) {
        
    }

    /**
     * 对一条微博评论信息进行回复。
     *
     * @access public
     * @param mixed $sid 微博id
     * @param mixed $text 评论内容。
     * @param mixed $cid 评论id
     * @return array
     */ 
    public function reply($sid , $text , $cid) {
        $param = array();
        $param['id'] = $sid;
        $param['comment'] = $text;
        $param['cid '] = $cid;

        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/reply.json', $param);
    }

    /**
     * 发布条新的weibo信息
     * @access public
     * @param string text weibo 消息
     * @return void
     */
    public function update($text){
        /**
         * @see http://api.t.sina.com.cn/statuses/update.json
         */
        $param = array();
        $param['status'] = $text;

        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/update.'.parent::$_method , $param );
    }

    /**
     * 发布一张带图片的weibo
     * @access public
     * @param string $text weibo的内容
     * @param string $pic weibo所带的图片
     * @return void
     */
    public function upload($text, $pic){
        //  http://api.t.sina.com.cn/statuses/update.json
        $param = array();
        $param['status'] = $text;
        $param['pic'] = '@'.$pic;
        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/upload.'.parent::$_method , $param , true );
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
    public function send_comment($sid , $text , $cid = false )
    {
        $param = array();
        $param['id'] = $sid;
        $param['comment'] = $text;
        if( $cid ) $param['cid '] = $cid;

        return $this->oauth->post( 'http://api.t.sina.com.cn/statuses/comment.'.parent::$_method , $param  );

    }
    /**
     * 获取某一话题下的微博
     * @access public
     * @param string $treads
     * @return array
     */
    public function  trends_statuses($trend) {
	$param = array();
	$param['trend_name'] = $trend;
	return $this->oauth->get('http://api.t.sina.com.cn/trends/statuses.'.parent::$_method, $param);
    }

    /**
     * 返回与关键字相匹配的微博用户。
     * @param string $keywork
     * @param int $page
     * @param int count
     * @return array | void
     */
    public function search($keyword, $page, $count){
        $param = array();
        $param['q'] = urlencode($keyword);
        $param['page'] = $page;
        $param['count'] = $count;
        
        return $this->oauth->get('http://api.t.sina.com.cn/trends/statuses.'.parent::$_method, $param);
    }

    /**
     * 
     */
    protected function request_with_uid( $url , $uid_or_name , $page = false , $count = false , $cursor = false , $post = false )
    {
        $param = array();
        if( $page ) $param['page'] = $page;
        if( $count ) $param['count'] = $count;
        if( $cursor )$param['cursor'] =  $cursor;

        if( $post ) $method = 'post';
        else $method = 'get';

        if( is_numeric( $uid_or_name ) )
        {
            $param['user_id'] = $uid_or_name;
            return $this->oauth->$method($url , $param );

        }elseif( $uid_or_name !== null )
        {
            $param['screen_name'] = $uid_or_name;
            return $this->oauth->$method($url , $param );
        }
        else
        {
            return $this->oauth->$method($url , $param );
        }

    }

}

class SinaWeiboOAuth extends WeiboOAuth{

    public $host = "http://api.t.sina.com.cn/";

    public function  __construct($akey , $skey , $accecss_token , $accecss_token_secret) {
        parent::__construct($akey , $skey , $accecss_token , $accecss_token_secret);
    }

    public function accessTokenURL()  { return 'http://api.t.sina.com.cn/oauth/access_token'; }

    public function authenticateURL() { return 'http://api.t.sina.com.cn/oauth/authenticate'; }

    public function authorizeURL()    { return 'http://api.t.sina.com.cn/oauth/authorize'; }

    public function requestTokenURL() { return 'http://api.t.sina.com.cn/oauth/request_token'; }
    
}