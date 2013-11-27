<?php

/**
 * Base class of repository of EpgLog document.
 */
abstract class BaseEpgLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'EpgLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'epg_log';


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