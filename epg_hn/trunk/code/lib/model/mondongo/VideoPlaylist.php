<?php

/**
 * VideoPlaylist document.
 */
class VideoPlaylist extends \BaseVideoPlaylist
{
    public $wiki = null;

    /**
     * 获取视频关联维基
     * @return <type>
     * @author luren
     */
    public function getWiki() {
        if(!$this->wiki) {
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('wiki');
            $this->wiki = $repository->getWikiById($this->getWikiId());
        }

        return $this->wiki;
    }

    /**
     * 显示来源中文名称
     * @return string
     * @author luren
     */
    public function  getRefererZhcn() {
        $referers = array(
                    'qiyi' => '奇艺',
                    'youku' => '优酷',
                    'sina' => '新浪',
                    'sohu' => '搜狐',
                    'tps' => 'tps',
                );

        return $referers[$this->getReferer()];
    }

    /**
     * 根据 play List ID 获取视频
     * @param <string> $list_id
     * @return <type>
     * @author luren
     */
    public function getVideos() {
        $mongo = $this->getMondongo();
        $VideoRepository = $mongo->getRepository('Video');
        return $VideoRepository->getVideosByPlaylistId((string) $this->getId());
    }

    /**
     * 根据 play List ID 计算视频数量
     * @return <type>
     * @author luren
     */
    public function countVideo() {
        $mongo = $this->getMondongo();
        $VideoRepository = $mongo->getRepository('Video');
        return $VideoRepository->count(array(
                                'video_playlist_id' => (string) $this->getId(),
                                'publish' => true
                            )
                        );
    }

    /**
     * 列表删除时删除相关的视频
     * @author luren
     */
    public function postDelete(){
        $mongo = $this->getMondongo();
        $video_repository = $mongo->getRepository('Video');
        $videos = $video_repository->find(array(
                                'query' => array(
                                    'video_playlist_id' => (string) $this->getId()
                                )
                            )
                    );
        if ($videos) {
            foreach ($videos as $video) {
                $video->delete();
            }
        }
    }
}