<?php

/**
 * Programe_user document.
 */
class Programe_user extends \BasePrograme_user
{
	/*
     * 添加预约节目
     * @author lifucang 
     */
    public function add($user_id,$channel_code,$name,$start_time,$channel_name='') {
        
    	$this->setUserId($user_id);
        $this->setChannelCode($channel_code);
        $this->setName($name);
        $this->setStartTime($start_time);
        $this->setChannelName($channel_name);
        parent::save();

    }
}