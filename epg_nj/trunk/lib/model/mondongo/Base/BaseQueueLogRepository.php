<?php

/**
 * Base class of repository of QueueLog document.
 */
abstract class BaseQueueLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'QueueLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'queue_log';


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