<?php

/**
 * Repository of ChannelFavorites document.
 */
class ChannelFavoritesRepository extends \BaseChannelFavoritesRepository
{
    /**
    * 通过user_id 获得相应收藏的channel
    * @param string user_id
    * @return obj
    * @author lizhi
    */
    public function getChannelByUserId($user_id, $skip=0, $limit = 20) {
        $options['query'] = array(
              'user_id'=> $user_id,
        );
        //$options['fields'] = array('wiki_id','user_id','created_at','is_public');
        if(!empty($skip)) {
            $options['skip'] = $skip;
        }
        if(!empty($limit)) {
            $options['limit'] = $limit;
        }
        $options['sort']= array('created_at' => 1);
        return $this->find($options);
    }
    
    /**
    * 通过user_id,channel_type 获得相应收藏的channel
    * @param string user_id
    * @param string channel_type
    * @return obj
    * @author lizhi
    */
    public function getChannelByUserChannelType($user_id, $channel_type) {
        if($channel_type=='default') {
            $options['query'] = array(
                  'user_id'=> $user_id,
            );
        }else{
            $options['query'] = array(
                'user_id'=> $user_id,
                'channel_type'=>$channel_type,
            );
        }
        $options['sort']= array('created_at' => 1);
        return $this->find($options); 
    }
    
    /**
    * 通过user_id code 获得唯一的一条数据
    * @param string user_id
    * @param string code
    * @return obj
    * @author lizhi
    */
    public function getOneChannelByUCode($user_id, $code) {
        return $this->findOne(array(
                        'query'=>array(
                            'user_id' => $user_id,
                            'channel_code' => $code
                        )
                 )
        );
    }
    
    /**
    * 通过user_id, channel_id 来获得唯一条数据
    * @param string user_id
    * @param int channel_id
    * @return obj
    * @author lizhi
    */
    public function getOneChannelByUCid($user_id, $channel_id) {
        return $this->findOne(array(
                        'query'=>array(
                            'user_id' => $user_id,
                            'channel_id' => $channel_id
                        )
                 )
        );        
    }
    
}