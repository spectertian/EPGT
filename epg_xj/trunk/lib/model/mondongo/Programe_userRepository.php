<?php

/**
 * Repository of Programe_user document.
 */
class Programe_userRepository extends \BasePrograme_userRepository
{
	/*
     * ��ѯԤԼ��Ŀ
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
     * ɾ��ԤԼ��Ŀ
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
     * ��ȡԤԼ��Ŀ
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
     * ��ȡԤԼ��Ŀ����
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
    /*
     *  获取某个时间段和某人的预约节目
     * @author qhm
     */
    public function getDateProgrameByUser($date,$user_id,$page=1,$size=10)
    {	
    	if(!$date){
    		$date=time();
    	} 
    	$starttime = new MongoDate(strtotime($date));
    	$starttime_m = new MongoDate(strtotime($date)+60*60*24);
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
    	return $this->find(
    			array(
    					'query'=>array(
    							'user_id'=>$user_id,
    							'start_time' => array('$gte' => $starttime,'$lte' => $starttime_m),
    					),
    					'limit' => intval($size),
    					'skip'  => $offset,
    					'sort'  => array('start_time' => 1)
    			)
    	);

    }         
}