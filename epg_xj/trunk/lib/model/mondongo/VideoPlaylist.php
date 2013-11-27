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
                    'qiyi'          =>  '奇艺',
                    'youku'         =>  '优酷',
                    'sina'          =>  '新浪',
                    'sohu'          =>  '搜狐',
                    'tps'           =>  'tps',
        			'baidu_qiyi'    =>  '百度-奇艺',
                    'baidu_youku'   =>  '百度-优酷',
                    'baidu_sina'    =>  '百度-新浪',
                    'baidu_sohu'    =>  '百度-搜狐',
                    'baidu_pptv'    =>  '百度-PPTV',
                    'baidu_pps'     =>  '百度-PPS',
                    'baidu_letv'    =>  '百度-乐视',
                    'baidu_tudou'   =>  '百度-土豆',
        			'baidu_tencent' =>  '百度-腾讯',
                    'cntv'          =>  'CNTV',
                    'wasu'          =>  'WASU',
                );

        return $referers[$this->getReferer()];
    }

    /**
     * 根据 play List ID 获取视频（老版本）
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
     * 根据 play List ID 获取视频（新添加）
     * @param <string> $list_id
     * @return <type>
     * @author wn
     */
    public function getTeleplayVideos() {
        $mongo = $this->getMondongo();
        $VideoRepository = $mongo->getRepository('Video');
        return $VideoRepository->getVideosByPlaylistIdFrontendTeleplay((string) $this->getId());
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