<?php

/**
 * Repository of DoubanCelebrity document.
 */
class DoubanCelebrityRepository extends \BaseDoubanCelebrityRepository
{
	/**
	 * 根据doubanid获取当前正在播放的节目
	 * @return <type>
	 * @author wn
	 */
	public function getCelebrityInfoOne($douban_id)
	{

		return $this->findOne(
				array(
						'query' => array( "douban_id" => $douban_id),
				));
	}
}