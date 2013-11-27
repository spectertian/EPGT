<?php

/**
 * Base class of repository of VodhitLog document.
 */
abstract class BaseVodhitLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'VodhitLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'vodhit_log';


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