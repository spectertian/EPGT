<?php

/**
 * Repository of ShortMoviePackage document.
 */
class ShortMoviePackageRepository extends \BaseShortMoviePackageRepository
{
	
	/**
	 * 模糊查询NAME
	 * @param  string $short_movie_package_name
	 * @return Short_movie_package
	 * @author tianzhongsheng-ex@huan.tv
	 */
	public function likeShortMoviePackageName($short_movie_package_name)
	{
		$reg_str = "/.*".$short_movie_package_name.".*/i";
		$regex_obj = new MongoRegex($reg_str);
		return $this->find(array(
				'query' => array(
						'name' => $regex_obj,
						'state' => 1,
				),
				"limit" => 20,
				"sort" => array("updated_at",-1)
		)
		);
	}
}