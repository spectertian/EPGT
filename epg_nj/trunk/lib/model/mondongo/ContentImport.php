<?php

/**
 * ContentImport document.
 */
class ContentImport extends \BaseContentImport
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
                $this->wiki = $wiki_repository->findOneByid(new MongoId($wiki_id));
            }
        }
        return $this->wiki;
    }
 	/**
     * 获取关联的inject对象
     * @return <obj>
     * @author lifucang 2013-06-04
     */
    public function getInject() {
        $inject_id = $this->getInjectId();
        if($inject_id) {
            $mondongo = $this->getMondongo();
            $inject_repository = $mondongo->getRepository('ContentInject');
            $inject = $inject_repository->findOneById(new MongoId($inject_id));
        }
        return $inject->getContent();
    }    
    /**
     * 获取wiki标题
     * @return <string>
     * @author pjl
     */
    public function getWikiTitle() {
        $wiki_title = '';
        $this->wiki = $this->getWiki();
        if($this->wiki) {
            $wiki_title = $this->wiki->getTitle();
        }
        return $wiki_title;
    }
}