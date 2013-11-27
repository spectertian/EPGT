<?php

/**
 * WikiPlay document.
 */
class WikiPlay extends \BaseWikiPlay
{
   protected $wiki;
    /**
     * 获取想关联的 WIKI
     * @return <type>
     */
    public function getWiki() {

        if (! isset($this->wiki) ) {
            $mongo = $this->getMondongo();
            $wikiRepository = $mongo->getRepository('Wiki');
            $this->wiki = $wikiRepository->getWikiById($this->getWikiId());
        }

        return $this->wiki;
    }
}