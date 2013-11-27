<?php

/**
 * Repository of Video document.
 */
class VideoRepository extends \BaseVideoRepository
{
    /**
     * 根据WikiId 返回相应的全部视频列表
     * @param <type> $wiki_id
     * @return <type>
     * @author luren
     * @update lifucang 2013-06-30
     */
    public function getVideosByWikiId($wiki_id,$referer=null,$hd=null) {
        $query=array();
        $query['wiki_id']=$wiki_id;
        if($referer&&$referer!=''){
            $query['referer']=$referer;
        }
        if($hd&&$hd!=''){
            $query['config.hd_content']=$hd;
        }
        return $this->find(
                    array(
                        'query' => $query,
                        'sort' => array('mark' => 1)
                        )
                );
    }
    /**
     * 根据wiki_id,视频来源,是否高清统计视频数量
     * @param <string> $wiki_id
     * @return <integer>
     * @author lifucang
     * @update 2013-06-30
     */
    public function countVideosByWikiId($wiki_id,$referer=null,$hd=null) {
        $query=array();
        $query['wiki_id']=$wiki_id;
        if($referer){
            $query['referer']=$referer;
        }
        if($hd){
            $query['config.hd_content']=$hd;
        }
        return $this->count($query);
    }
    /**
     * 根据wiki_meta获取视频
     * @param <type> $wiki_id
     * @return <type>
	 * @author ly
     */
    public function getVideosByWikiMetaId($wiki_meta_id) {
        return $this->find(
                    array(
                        'query' => array(
                                'wiki_mata_id' => $wiki_meta_id
                                )
                        )
                );
    }

    
    /**
     * 根据 play List ID 获取视频
     * @param <string> $list_id
     * @return <type>
     * @author luren
     */
    public function getVideosByPlaylistId($list_id,$hd_content='all') {
        if($hd_content!='all'){
            $query=array(
                                    'video_playlist_id' => $list_id,
                                    'publish' => true,
                                    'config.hd_content' => $hd_content,
                         );
        }else{
            $query=array(
                                    'video_playlist_id' => $list_id,
                                    'publish' => true
                         );
        }
        return $this->find(array(
                                'query' => $query,
                                'sort' => array('mark' => 1)
                            )
                       );
    }
}