<?php

/**
 * Base class of repository of WikiMeta document.
 */
abstract class BaseWikiMetaRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiMeta';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_meta';


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