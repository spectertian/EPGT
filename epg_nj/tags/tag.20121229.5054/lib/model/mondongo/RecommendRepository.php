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
}