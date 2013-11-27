<?php

/**
 * Base class of repository of Developer document.
 */
abstract class BaseDeveloperRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Developer';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'developer';


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