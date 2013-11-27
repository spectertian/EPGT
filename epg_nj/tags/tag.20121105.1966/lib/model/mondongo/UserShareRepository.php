<?php

/**
 * Repository of UserShare document.
 */
class UserShareRepository extends \BaseUserShareRepository
{
    /**
     * 检查用户是否有存放分享信息
     * @param string user_id
     * @param int type 类型
     * @return boolean|array
     * @author lizhi
     */
    public function checkShare($user_id, $type){
        $user = $this->findOne(array(
                    'query'=>array(
                        'user_id'=>$user_id,
                        'stype'=>$type
                    )
                ));
        if( !$user ) {
            return false;
        }
        return $user;
    }
    /**
     * 获得所分享API级信息
     * @param string user_id
     * @return boolean|array
     * @author lizhi
     */
    public function getAllShareByUserId($user_id){
        $user = $this->find(
                     array(
                        'query' => array(
                            'user_id' => $user_id
                        )
                    )               
        
         );
        if(!$user) return false;
        return $user;
    }
    
    /**
    * 通过Sname  获得相应的_id
    * @param string user_id
    * @param string sname
    * @author lizhi
    * @return void
    */
    public function getShareBySname($user_id, $sname='Sina') {
        $user = $this->findOne(array(
                    'query'=>array(
                        'user_id'=>$user_id,
                        'sname'=>$sname
                    )
        ));
        if( !$user ) {
            return false;
        }
        return $user;
    }
    
    /**
     * 通过userinfo,type来获得他是否已经设置了分享
     * @param string userinfo
     * @param string sname
     * @return obj
     * @author lizhi
     */
    public function getShareByUserinfo($userinfo, $sname='Sina') {
        $user = $this->findOne(
                array(
                    'query'=>array(
                        'userinfo' => $userinfo,
                        'sname' => $sname
                    )
                )
        );
        if(!$user) {
            return false;
        }
        return $user;
    }
}