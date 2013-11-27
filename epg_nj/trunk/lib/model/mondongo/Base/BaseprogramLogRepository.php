<?php

/**
 * Base class of repository of programLog document.
 */
abstract class BaseprogramLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'programLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'program_log';


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