<?php

/**
 * WikiRecommend document.
 */
class WikiRecommend extends \BaseWikiRecommend
{
    protected $wiki;
    
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