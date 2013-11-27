<?php

/**
 * Base class of repository of Video document.
 */
abstract class BaseVideoRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Video';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'video';


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