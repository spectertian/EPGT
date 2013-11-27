<?php

/**
 * Repository of WikiRecommend document.
 */
class WikiRecommendRepository extends \BaseWikiRecommendRepository
{
     /**
     * 根据 tags 返回 wiki
     * @param <string> $tag
     * @param <int> $limit 默认 999
     * @return <type>
     * @author luren
     */
    public function getWikiByTag($tag, $limit=999,$skip=null) {
    	if($skip)
    	{
	    	return $this->find(array(
	                        'query' => array('tags' => $tag),
	                        'sort'  => array('create_at' => -1),
	                        'limit' => intval($limit),
	    					'skip'  => $skip,
	                    )
	                );
    	}
    	else
    	{
	    	return $this->find(array(
	                        'query' => array('tags' => $tag),
	                        'sort' => array('create_at' => -1),
	                        'limit' => intval($limit)
	                    )
	                );
    	}
    }

    /**
     * 获取随机 recommendWiki
     * @param <integer> $limit
     */
    public function getRandWiki($limit = 4) {
        $max = $this->count() - $limit;
        
        if ($max > 0) {
            return $this->find(
                        array(
                            'limit' => $limit,
                            'skip' => rand(0, $max)
                        )
                    );
        }

        return array();
    }
    
    /**
     * 根据model获取recommendWiki
     * @param <type> $model
     * @param <integer> $limit
     * author:ly
     */
    public function getWikiByModel($model,$limit)
    {
        return $this->find(array("query"=>array("model"=>$model),"sort"=>array("created_at"=>-1),"limit"=>$limit));
    }
    
    /**
     * 获取推荐的wiki
     * @param array $model
     * @param int $limit
     * @param int skip
     * @author lizhi
     * @return void
     */
    public function getRecommendWiki($model, $limit, $skip) {
        return $this->find(
             array("query"=>array(
                 "model"=>array('$in'=>$model)
             ),
                 "sort"=>array("created_at"=>-1),
                 "limit"=>$limit,
                 "skip"=>$skip)
        );
    }
    /**
     * 根据wiki_id获取数据
     * @param array $model
     * @param  $wiki_id
     * @author wangnan
     * @return object
     */
    public function getDataByWikiId($wiki_id) 
    {
		return $this->find(
			array(
			'query' => array(
				'wiki_id' => $wiki_id,
					)
				)
		);
    }    

    public function getWikiByPageAndSize($page,$size ,$tag='') 
    {
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
    	if(empty($tag))
    	{
			return $this->find(
				array(
					'limit' => intval($size),
					'skip'  => $offset,
					)
			);
    	}
    	else
    	{
			return $this->find(
				array(
					'query'=>array(
						'tags'=>array('$in'=>array($tag)),
					),
					'limit' => intval($size),
					'skip'  => $offset,
					)
			);
    		
    	}
    } 

    public function getWiki() 
    {
    	$offset = $size * ($page-1);
		return $this->find();
    } 

    
}