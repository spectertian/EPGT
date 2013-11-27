<?php

/**
 * Repository of UserChannel document.
 */
class UserChannelRepository extends \BaseUserChannelRepository
{
    public function findOneByChannelCode($user_id,$channel_code) {
        return $this->findOne(
                    array(
                        'query' => array(
                    		'user_id'=>$user_id,
                            'channel_code' => $channel_code,
                        ),
                    )
                );

    }
    public function getUserChannelsByPS($user_id,$page=1,$size=10) 
    {
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
		return $this->find(
			array(
				'query'=>array(
					'user_id'=>$user_id,
				),
				'limit' => intval($size),
				'skip'  => $offset,
				'sort'  => array('created_at'=> -1)
		    )
		);
    }
    public function getUserChannels($user_id) 
    {
		return $this->find(
			array(
				'query'=>array(
					'user_id'=>$user_id,
				),
		    )
		);
    }         
}