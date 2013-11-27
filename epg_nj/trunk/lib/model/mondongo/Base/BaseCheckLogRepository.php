<?php

/**
 * Base class of repository of CheckLog document.
 */
abstract class BaseCheckLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'CheckLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'check_log';


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