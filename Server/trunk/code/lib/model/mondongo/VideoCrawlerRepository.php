<?php

/**
 * Repository of VideoCrawler document.
 */
class VideoCrawlerRepository extends \BaseVideoCrawlerRepository
{
    /**
     * 根据类型获取数据
     * @param <type> $model  类型
     * @return <type>
     * @author wn
     */    
    public function getObjectsByModel($model) {
        return $this->find(
                    array(
                        'query' => array(
                            'model'=>$model,
                    		'wiki_id'=>array('$exists'=>true,'$ne'=>''),
                    		'state'=>0,
                            )
                     )
                );
    } 	
}