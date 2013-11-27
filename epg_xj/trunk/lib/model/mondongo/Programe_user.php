<?php

/**
 * Programe_user document.
 */
class Programe_user extends \BasePrograme_user
{
	/*
     * ���ԤԼ��Ŀ
     * @author lifucang 
     */
    public function add($user_id,$channel_code,$name,$start_time,$wiki_id='',$wiki_title='',$wiki_cover='') {
        
    	$this->setUserId($user_id);
        $this->setChannelCode($channel_code);
        $this->setName($name);
        $this->setWikiId($wiki_id);
        $this->setWikiTitle($wiki_title);
        $this->setWikiCover($wiki_cover);
        $this->setStartTime($start_time);
        parent::save();

    }
}