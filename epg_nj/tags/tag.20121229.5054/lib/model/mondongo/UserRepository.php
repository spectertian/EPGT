<?php

/**
 * Repository of User document.
 */
class UserRepository extends \BaseUserRepository
{
    public function check_value($field,$value) {
        $user = $this->findOne(array(
                        'query'=>array(
                            $field => $value
                        )
                 ));
        if($user) {
            return false;
        }
        return true;
    }

    /**
     * 用户登录操作
     * @param <type> $username / email
     * @param <type> $password
     * @return <type>
     * @author luren
     */

    public function login($username,$password) {
        if (preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', $username)) {
            $query = array('email' => $username);
        } else {
            $query = array('username' => $username);
        }
        
        $user = $this->findOne(array('query' => $query));
        
        if( $user ) {
            if ($user->getPassword() == $password) {
                return $user;
            }
        }    
        return false;
    }

    /*
     * 通过device_id来验证用户
     * @param int $device_id
     * @return int $device_id
     * @author guoqiang.zhang
     */
    public function device_user($device_id){
        $query = array('device_id'=>(int)$device_id);
        $user = $this->findOne(array('query'=>$query));
        if(!$user){
            $UserInfo = new User();
            $user_op = $UserInfo->saveUserByDeviceId($device_id);
        }
        return (int)$device_id;

    }

    /*
     * 通过device_id 来返回user_id
     * @param int $device_id
     * @return string $user_id
     * @author guoqiang.zhang
     */
    public function getUserIdByDeviceId($device_id){
        $query = array('device_id'=>(int)$device_id);
        return $this->findOne(array('query'=>$query));
    }


    /*
     * 通过device_id 加入片单
     * @param int $device_id
     * @param obj
     * @author guoqiang.zhang
     */
    public function getUserByDeviceId($device_id,$wiki_id){
        $user = $this->getUserIdByDeviceId($device_id);
        if($user){
            $chip = $this->addChipByDevice((string)$user->getId(),$wiki_id);
        }else{
            $this->device_user($device_id);
            $newUser = $this->getUserIdByDeviceId($device_id);
            $chip = $this->addChipByDevice((string)$newUser->getId(),$wiki_id);
        }
    }

    /*
     * 加入片单
     * @param int $device_id
     * @param int $wiki_id
     * @return obj
     * @author guoqiang.zhang
     */
    public function addChipByDevice($user_id,$wiki_id){
        $mongo = $this->getMondongo();
        $singleChipRepository = $mongo->getRepository("singleChip");
        $chip = $singleChipRepository->getOneChip($user_id,$wiki_id);
        if(!$chip){
            $singleChip = new SingleChip();
            $singleChip->setWikiId($wiki_id);
            $singleChip->setUserId($user_id);
            $singleChip->setIsPublic(true);           
            $singleChip->save();
            //加入评论
            $comment = new Comment();
            $comment->setUserId($user_id);
            $comment->setWikiId($wiki_id);
            $comment->setParentId(0);
            $comment->setType('queue');
            $comment->setText('');
            $comment->save();
        }      
    }
    /*
     * 根据用户id获取用户
     * @param int $device_id
     * @return int $device_id
     * @author guoqiang.zhang
     */
    public function find_user($userid){
        $user = $this->findOneById(new MongoId($userid));
        if(!$user){
           return false;
        }
        return true;

    }    
}