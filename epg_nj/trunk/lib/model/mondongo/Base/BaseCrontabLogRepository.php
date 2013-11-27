<?php

/**
 * Base class of repository of CrontabLog document.
 */
abstract class BaseCrontabLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'CrontabLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'crontab_log';


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