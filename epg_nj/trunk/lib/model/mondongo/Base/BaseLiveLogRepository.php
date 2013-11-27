<?php

/**
 * Base class of repository of LiveLog document.
 */
abstract class BaseLiveLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'LiveLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'live_log';


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