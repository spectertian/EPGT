<?php

/**
 * User document.
 */
class User extends \BaseUser
{
    private $baseAvatar = '1313572883180.png'; //默认头像
    
    public function getAvatar(){
        if(parent::getAvatar()==NULL) {
            return $this->baseAvatar;
        }else{
            return parent::getAvatar();
        }
    }


    /*
     * 通过device_id来保存用户
     * @param int $device_id
     * @return bool
     * @author guoqiang.zhang
     */
    public function saveUserByDeviceId($device_id){
        $this->setNickname($device_id);
        $this->setReferer("huanwang");
        $this->setDeviceId($device_id);
        parent::save();
    }
}