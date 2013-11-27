<?php

/**
 * Repository of Recommend document.
 */
class RecommendRepository extends \BaseRecommendRepository
{
    /**
     * 获得相应的区域推荐图片
     * @param string scene 区域 如index
     * @param int num 个数 
     * @return void|obj
     * @author lizhi
     */
    public function getRecommendByScene($scene='index', $num=10) {
        return $this->find(array(
            'query' => array(
                'scene' => $scene,
                'is_public' => true,
            ),
            "limit" => $num,
            "sort" => array("sort"=> 1),
        )
       );
    }
	public function getRecommendByPageAndSize($page,$size ,$scene='') 
    {
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
    	if(empty($scene))
    	{
			return $this->find(
				array(
					"query"=>array(
                 		"is_public"=>true
             		),				
					'limit' => intval($size),
					'skip'  => $offset,
					'sort' => array('created_at' => -1),
					)
			);
    	}
    	else
    	{
			return $this->find(
				array(
					'query'=>array(
						'scene'=>$scene,
						"is_public"=>true
					),
					'limit' => intval($size),
					'skip'  => $offset,
					'sort' => array('created_at' => -1),
					)
			);
    		
    	}
    } 
    public function getRecommendBySceneNoLimit($scene) {
        $query_arr=array();
        $query_arr['scene']=$scene;
		if(empty($scene))
    	{
		        return $this->find(); 
    	}	                    
    	else
    	{
			return $this->find(
				array(
					'query'=>array(
						'scene'=>$scene,
						"is_public"=>true
					),
					)
			);
    	}                 
    }    
}