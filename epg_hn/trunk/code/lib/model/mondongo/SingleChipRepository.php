<?php

/**
 * Repository of SingleChip document.
 */
class SingleChipRepository extends \BaseSingleChipRepository
{
    /**
     * 根据 user_id wiki_id 获取一条记录
     * @param <string> $user_id
     * @param <string> $wiki_id
     * @return array
     * @author luren
     */
    public function getOneChip($user_id, $wiki_id) {
        return $this->findOne(array(
                    'query' => array(
                            'user_id' => $user_id,
                            'wiki_id' => $wiki_id
                        )
        ));
    }
    
    /**
    * 获得用户的片单中所有的wiki列表
    * @param string user_id
    * @param int skip
    * @param int limit
    * @return void | array
    * @author lizhi
    */
    public function getUserChipByUserId($user_id, $skip, $limit) {
        $options['query'] = array(
              'user_id'=> $user_id,
              'is_public'=> true
        );
        $options['fields'] = array('wiki_id','user_id','created_at','is_public');
        if(!empty($skip)) {
            $options['skip'] = $skip;
        }
        if(!empty($limit)) {
            $options['limit'] = $limit;
        }
        $options['sort']= array('created_at' => 1);
        return $this->find($options);
        /*
        return $this->find(
            array(
                'query'=>array(
                    'user_id'=> $user_id,
                    'is_public'=> true
                ),
                'limit'=> $limit,
                'skip' => $skip,
                'sort' => array('created_at' => 1)
            )
         );*/
    }

}