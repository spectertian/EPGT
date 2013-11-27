<?php

/**
 * Repository of VideoPlaylist document.
 */
class VideoPlaylistRepository extends \BaseVideoPlaylistRepository
{
    /**
     * 根据WikiId 返回相应的全部视频列表
     * @param <type> $wiki_id
     * @return <type>
     * @author luren
     */
    public function getVideosByWikiId($wiki_id, $referer = '') {
        $query = array('wiki_id' => $wiki_id);
        if ($referer) $query['referer'] = $referer;
        return $this->find(
                    array(
                        'query' => $query
                        )
                );
    }
    
    /**
     * 删除相关的 playlist
     * @param type $wiki_id
     * @param type $referer 
     * @author luren
     */
    public function deleteVideos($wiki_id, $referer = '') {
        $play_list = $this->getVideosByWikiId($wiki_id, $referer);
        if ($play_list) {
            foreach($play_list as $play) {
                $play->delete();
            }
        }
    }
}