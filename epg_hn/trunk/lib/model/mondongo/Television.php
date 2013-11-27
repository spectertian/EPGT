<?php

/**
 * Television document.
 */
class Television extends \BaseTelevision
{
    protected $wiki = null;
     /**
     * 获取关联的wiki对象
     * @return <obj>
     * @author hmy
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

}