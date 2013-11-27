<?php

/**
 * WikiMeta document.
 */
class WikiMeta extends \BaseWikiMeta
{
    /**
     * 设置内容时，自动设置 HTML Cache
     * @param string $value
     * @author zhigang
     */
    public function setContent($text) {
        parent::setContent($text);
        $html_cache = WikiPasers::render($text);
        $this->setHtmlCache($text);
    }

    /**
     * getHtmlCache
     * @param <type> $lengh
     * @return <type>
     */
    public function  getHtmlCache($lengh = 0) {
        $html_cache = trim($this->data['fields']['html_cache']);
        if ($lengh != 0) {
            $html_cache = mb_strimwidth(strip_tags($html_cache), 0, $lengh, '...', 'utf-8');
        }
        return $html_cache;
    }

    /**
     * 根据 wiki_mata_id 获取相关联的视频
     * @author luren
     */
    public function getVideos() {
        $mongo = $this->getMondongo();
        $video_repository = $mongo->getRepository('Video');
        return $video_repository->find(array(
                                'query' => array(
                                    'wiki_mata_id' => (string) $this->getId()
                                )
                            )
                    );
    }

    /**
     * 获取一张剧照用来做前台封面显示 传递高度 宽度
     * @param $width
     * @param $height
     * @return <type>
     * @author luren
     */
    public function getOneScreenshot($width, $height) {
        $Screenshots = $this->getScreenshots();

        return (!empty($Screenshots)) ? thumb_url(current($Screenshots), $width, $height) : '';
    }

    /**
     * 计算分期剧照数量
     * @return <type>
     */
    public function getScreenshotsCount() {
        $screenshots = $this->getScreenshots();

        return count($screenshots);
    }
}