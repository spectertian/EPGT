<?php

/**
 * Repository of WikiMeta document.
 */
class WikiMetaRepository extends \BaseWikiMetaRepository
{
    /**
     * 根据标题获取一条 wikimeta
     * @param <type> $title
     * @return <type>
     * @author luren
     */
    public function getMetaByTitle($title) {
        return $this->findOne(
                    array('query' => array(
                                'title' => $title
                            )
                        )
                );
    }

    /**
     * 根据 wiki_id 获取相关的 wikimeta
     * @param <type> $wiki_id
     * @param <type> $skip
     * @param <type> $limit
     * @return <type>
     * @author luren
     */
    public function getMetasByWikiId($wiki_id, $skip =0, $limit = 0) {
    	if(empty($wiki_id))
    		return false;
    	else
    	{
	        return $this->find(
	                    array(
	                        'query' => array( 'wiki_id' => $wiki_id),
	                        'sort' => array('mark' => 1),
	                        'skip' => $skip,
	                        'limit' => $limit
	                    )
	                );
    	}
    }

    /**
     *获取栏目的mark
     * @return <type>
     */
    public function getMatesMarkByWikiId($wiki_id, $skip =0, $limit = 0){
        return $this->find(
            array(
                'query' => array( 'wiki_id' => $wiki_id),
                "fields" => array("mark"),
                'sort' => array('mark' => 1),
                'skip' => $skip,
                'limit' => $limit
            )
        );

    }

    /**
     * 传递一个查询语句返回一条 meta 数据
     * @param <type> $wiki_id
     * @param <type> $mark
     * @author luren
     */
    public function getMetesByQurey($query) {
        return $this->findOne($query);
    }

}