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
     */
    public function getVideosByWikiId($wiki_id) {
        return $this->find(
                    array(
                        'query' => array(
                                'wiki_id' => $wiki_id
                                )
                        )
                );
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
    public function getVideosByPlaylistId($list_id) {
        return $this->find(array(
                                'query' => array(
                                    'video_playlist_id' => $list_id,
                                    'publish' => true
                                ),
                                'sort' => array('mark' => 1)
                            )
                       );
    }
}