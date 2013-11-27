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
    public function getWikiByTag($tag, $limit=999,$skip=null,$model=null) {
        $query_arr=array();
        $query_arr['tags']=$tag;

        if($model){
            $query_arr['model']=$model;
        }   
        if($skip){
        	return $this->find(array(
                            'query' => $query_arr,
                            'sort' => array('create_at' => -1),
                            'limit' => intval($limit),
                            'skip'  => $skip
                        )
                    ); 
        }else{
        	return $this->find(array(
                            'query' => $query_arr,
                            'sort' => array('create_at' => -1),
                            'limit' => intval($limit)
                        )
                    ); 
        }

        /*        
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
        */
    }
    public function getWikiByTagNoLimit($tag) {
        $query_arr=array();
        $query_arr['tags']=$tag;

        return $this->find(array(
                            'query' => $query_arr,
                            'sort' => array('create_at' => -1),
                        )
                    ); 
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
					'sort'  => array('created_at'=>-1)
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
					'sort'  => array('created_at'=>-1)
					)
			);
    		
    	}
    } 
    public function getRandWikiBySize($size ,$tag='') 
    {
    	$num = rand(0,70);    	
    	if(empty($tag))
    	{
			return $this->find(
				array(
					'limit' => intval($size),
					'skip'  => $num,
					'sort'  => array('created_at'=>-1)
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
					'skip'  => $num,
					'sort'  => array('created_at'=>-1)
					)
			);
    		
    	}
    } 

    public function getWiki() 
    {
    	$offset = $size * ($page-1);
		return $this->find();
    } 

    /**
     * 从欢网智能推荐系统获取用户喜欢的wiki
     *
     * @param int $userid 用户ID
     * @param int $size 返回的wiki数量
     * @return array $wikis
     */
    public function getWikiByHuanIrs($userid,$size)
    {
        $size = 100;
        $url = sfConfig::get("app_huan_irs_url");
        $userid = $userid ? $userid : "CH_78cfb72afedf324d9e83d1a76cef55b5746415dd";
        $key = "irs_".$userid."_".$size;
        $memcache = tvCache::getInstance();
        $wikis = $memcache->get($key);
        if(!$wikis) {
            $content = Common::get_url_content($url."/recommender/ItemCFRecommenderAction?userID=".$userid."&howMany=".$size);
            $jsoncontent = json_decode($content,true);
            $wikis = $jsoncontent['itemIDs'];
			foreach($wikis as $key => $wikiRec){
			  $wikiR[$key]['wiki_id'] = $wikiRec;	
            }
            $wikis=$wikiR;
            $memcache->set($key,$wikis); 
        }
        return $wikis;
    }
    
}