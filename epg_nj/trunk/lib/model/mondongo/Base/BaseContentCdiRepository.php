<?php

/**
 * Base class of repository of ContentCdi document.
 */
abstract class BaseContentCdiRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ContentCdi';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'content_cdi';


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