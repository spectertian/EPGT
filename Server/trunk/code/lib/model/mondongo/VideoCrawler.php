<?php

/**
 * VideoCrawler document.
 */
class VideoCrawler extends \BaseVideoCrawler
{
    protected $wiki = null;
    /**
     * 获取关联的wiki对象
     * @return <obj>
     * @author pjl
     */
    public function getWiki() {
        if (!isset($this->wiki)) {
            $wiki_id = $this->getWikiId();
            if($wiki_id) {
                $mondongo = $this->getMondongo();
                $wiki_repository = $mondongo->getRepository('Wiki');
                $this->wiki = $wiki_repository->getWikiById($wiki_id);
            }
        }
        
        return $this->wiki;
    }

    /**
     * 获取wiki标题
     * @return <string>
     * @author pjl
     */
    public function getWikiTitle() {
        $wiki_title = '';
        $wiki = $this->getWiki();
        if($wiki) {
            $wiki_title = $wiki->getTitle();
        }

        return $wiki_title;
    }

    /**
     * 获取wiki封面图片
     * @return <string>
     * @author pjl
     */
    public function getWikiCoverUrl() {
        $wiki_url = '';
        $wiki = $this->getWiki();
        if($wiki) {
            $wiki_url = $wiki->getCoverUrl();
        }

        return $wiki_url;
    } 
    /**
     * 获取wiki封面图片
     * @return <string>
     * @author pjl
     */
    public function getContentId() {
		$url = $this->getUrl();
		$contentID = preg_replace("/(.*id=)(.*)(&page.*)/","\${2}",$url);

        return $contentID;
    }
    
    public function deleteVideo() 
    {
		$mondongo = $this->getMondongo();
		//删videoplaylist
		$vpl_repository = $mondongo->getRepository('videoPlaylist');
		$vpls = $vpl_repository->find(array('query' => array('vc_id' => (string)$this->getId())));
        if($vpls)
        {
			foreach ($vpls as $vpl) 
				$vpl->delete();
        }
        //删video
        $video_repository = $mondongo->getRepository('video');
    	$videos = $video_repository->find(array('query' => array('vc_id' => (string)$this->getId())));
        if($videos)
        {
			foreach ($videos as $video) 
				$video->delete();
        }        
    }    
    
}