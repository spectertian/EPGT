<?php

/**
 * Repository of Programe_user document.
 */
class Programe_userRepository extends \BasePrograme_userRepository
{
	/*
     * 查询预约节目
     * @author lifucang 
     */
    public function SearchPrograme($user_id,$channel_code,$start_time) {
        $time=new MongoDate(strtotime($start_time));           
        return $this->findOne(
             array(
				       'query'=>array(
                            'user_id' => $user_id,
                            'channel_code' => $channel_code,
                            'start_time' => $time,
                        )
		           )
        );           

    }

	/*
     * 删除预约节目
     * @author lifucang 
     */
    public function del($user_id,$channel_code,$start_time) {
        $time=new MongoDate(strtotime($start_time));           
        return $this->remove(
                        array(
                            'user_id' => $user_id,
                            'channel_code' => $channel_code,
                            'start_time' => $time,
                        )
        );
    } 
	/*
     * 获取预约节目
     * @author lifucang 
     */    
    public function getProgrameByUser($user_id,$page=1,$size=10) 
    {
        $now = new MongoDate();
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
		return $this->find(
			array(
				'query'=>array(
					'user_id'=>$user_id,
                    'start_time' => array('$gte' => $now),
				),
				'limit' => intval($size),
				'skip'  => $offset,
                'sort'  => array('start_time' => 1)
		    )
		);
    }  
	/*
     * 获取预约节目总数
     * @author lifucang 
     */    
    public function getProgrameCountByUser($user_id) 
    {
        $now = new MongoDate();
    	$query=	array(
				'query'=>array(
					'user_id'=>$user_id,
                    'start_time' => array('$gte' => $now),
				)
		);
		return $this->find($query);
    }           
}