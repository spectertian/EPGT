<?php

/**
 * Base class of repository of WikiLiverecommend document.
 */
abstract class BaseWikiLiverecommendRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiLiverecommend';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_liverecommend';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'wiki_id' => -1,
        ), array(
            'safe' => true,
        ));

    }
}