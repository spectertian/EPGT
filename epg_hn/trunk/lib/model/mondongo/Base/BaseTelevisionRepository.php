<?php

/**
 * Base class of repository of Television document.
 */
abstract class BaseTelevisionRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Television';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'television';


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