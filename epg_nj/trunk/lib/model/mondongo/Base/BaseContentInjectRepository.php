<?php

/**
 * Base class of repository of ContentInject document.
 */
abstract class BaseContentInjectRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ContentInject';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'content_inject';


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