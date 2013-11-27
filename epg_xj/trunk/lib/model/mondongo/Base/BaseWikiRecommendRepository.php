<?php

/**
 * Base class of repository of WikiRecommend document.
 */
abstract class BaseWikiRecommendRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiRecommend';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_recommend';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {

    }
}