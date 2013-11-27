<?php

/**
 * Base class of repository of OfflineLog document.
 */
abstract class BaseOfflineLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'OfflineLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'offline_log';


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