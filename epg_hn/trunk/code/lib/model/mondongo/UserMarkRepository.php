<?php

/**
 * Repository of UserMark document.
 */
class UserMarkRepository extends \BaseUserMarkRepository
{
	/*
     * 根据分集id获取标记信息
     * @param $eid 分集id(video id)
     * @return xml
     * @author wangnan
     */	
	public function getUserMarkByObyId($eid)
	{
		return $this->find(array(
				'query'=>array(
					'obj_id' => $eid,
				)
			));
	}

	/*
     * 根据用户id获取标记列表
     * @param user_id 用户id
     * @return xml
     * @author wangnan
     */	
	public function getUserMarksByUserId($user_id)
	{
		return $this->find(array(
				'query'=>array(
					'user_id' => $user_id,
				),
			));
	}	
	/*
     * 根据用户id type获取标记列表
     * @param user_id 用户id
     * @param type 类型
     * @return xml
     * @author wangnan
     */	
	public function getUserMarksByUserIdAndType($user_id,$type)
	{
		return $this->find(array(
				'query'=>array(
					'user_id' => $user_id,
					'type' => $type
				),
			));
	}	
	
	/*
     * 根据用户id,page,size获取标记列表
     * @param user_id 用户id
     * @param page    页码
     * @param size    每页条数
     * @return xml
     * @author wangnan
     */	
	public function getUserMarksByUserIdAndPageAndSize($user_id,$page,$size)
	{
		return $this->find(array(
				'query'=>array(
					'user_id' => $user_id,
				),
				'skip' => ($page - 1)*$size,
				'limit'=> $size,
			));
	}
	/*
     * 根据用户id,type,page,size获取标记列表
     * @param user_id 用户id
     * @param page    页码
     * @param size    每页条数
     * @return xml
     * @author wangnan
     */	
	public function getUserMarksByUserIdTypePageSize($user_id,$type,$page,$size)
	{
		return $this->find(array(
				'query'=>array(
					'user_id' => $user_id,
					'type' => $type
				),
				'skip' => ($page - 1)*$size,
				'limit'=> $size,
			));
	}
	/*
     * 创建新的mark记录
     * @param $user_id 用户id
     * @param $type    类型1，观看 2，标记
     * @param $wiki_id   
     * @param $obj_id   video_id
     * @param $extra   标记秒数
     * @return void
     * @author wangnan
     */	
	public function createOneMark($user_id,$type,$wiki_id,$obj_id,$extra)
	{
		$user_mark = new UserMark();
		$user_mark->setUserId($user_id);
		$user_mark->setType($type);
		$user_mark->setWikiId($wiki_id);
		$user_mark->setObjId($obj_id);
		$user_mark->setExtra($extra);
		$user_mark->save();
	}

		
}