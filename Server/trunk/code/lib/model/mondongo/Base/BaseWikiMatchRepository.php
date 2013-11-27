<?php

/**
 * Base class of repository of WikiMatch document.
 */
abstract class BaseWikiMatchRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiMatch';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_match';


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