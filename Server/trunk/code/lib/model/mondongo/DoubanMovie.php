<?php

/**
 * DoubanMovie document.
 */
class DoubanMovie extends \BaseDoubanMovie
{
    protected $wiki = null;
    
    /**
     * 获取wiki标题
     * @return <string>
     * @author gaobo
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
     * 获取wiki导演
     * @return <string>
     * @author gaobo
     */
    public function getWikiDirector() {
        $wiki_title = '';
        $wiki = $this->getWiki();
        if($wiki) {
            $wikiDirector = $wiki->getDirector();
        }else{
            return array();
        }
        return $wikiDirector;
    }
    
    /**
     * 获取wiki演员
     * @return <string>
     * @author gaobo
     */
    public function getWikiStarring() {
        $wiki_title = '';
        $wiki = $this->getWiki();
        if($wiki) {
            $wikiStarring = $wiki->getStarring();
        }else{
            return array();
        }
        return $wikiStarring;
    }
    
    /**
     * 获取关联的wiki对象
     * @return <obj>
     * @author gaobo
     */
    public function getWiki()
    {
        $wiki_id = $this->getWikiId();
        if($wiki_id) {
            $mondongo = $this->getMondongo();
            $wiki_repository = $mondongo->getRepository('Wiki');
            $this->wiki = $wiki_repository->getWikiById($wiki_id);
            return $this->wiki;
        } else {
            return null;
        }
    }
}