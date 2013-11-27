<?php

class myUser extends sfBasicSecurityUser
{
    /**
     * 用户登陆成功设置
     * @param <Array> $user
     */
    function user_login($user)
    {
        $this->setAuthenticated(true);
        $this->setAttribute('user_id', $user['id']);
        $this->setAttribute('user_city', $user['city']);
        $this->setAttribute('user_from', $user['user_from']);
        $this->setAttribute('user_key', $user['user_key']);
        $this->setAttribute('email', $user['email']);
        $this->setAttribute('username', $user['username']);
        $this->setAttribute('province', $user['province']);
        $this->setFlash('user_notice', '初始化成功');
        unset($user);
    }

    /**
     * 用户退出
     */
    function user_login_out()
    {
        $this->setAuthenticated(false);
        $this->getAttributeHolder()->remove('user_id');
        $this->getAttributeHolder()->remove('user_city');
        $this->getAttributeHolder()->remove('user_from');
        $this->getAttributeHolder()->remove('username');
        $this->getAttributeHolder()->remove('email');
        $this->getAttributeHolder()->remove('user_key');
        $this->getAttributeHolder()->remove('province');
        $this->shutdown();
    }
}
