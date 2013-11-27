<?php

/**
 * Base class of repository of ContentTemp document.
 */
abstract class BaseContentTempRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ContentTemp';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'content_temp';


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