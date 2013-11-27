<?php

/**
 * Repository of SimpleAdvert document.
 */
class SimpleAdvertRepository extends \BaseSimpleAdvertRepository
{
	
	/**
	* 模糊查询NAME
	* @param  string $ad_name
	* @return Ad
	* @author tianzhongsheng-ex@huan.tv
	*/
	public function likeSimpleAdvertName($ad_name)
	{
		$reg_str = "/.*".$ad_name.".*/i";
		$regex_obj = new MongoRegex($reg_str);
		return $this->find(array(
				'query' => array(
				'name' => $regex_obj,
				),
				"limit" => 20,
				"sort" => array("updated_at",-1)
				)
			);
	}
}