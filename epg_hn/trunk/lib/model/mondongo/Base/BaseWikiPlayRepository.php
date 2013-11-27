<?php

/**
 * Base class of repository of WikiPlay document.
 */
abstract class BaseWikiPlayRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiPlay';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_play';


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