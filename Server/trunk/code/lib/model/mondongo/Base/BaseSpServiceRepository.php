<?php

/**
 * Base class of repository of SpService document.
 */
abstract class BaseSpServiceRepository extends \Mondongo\Repository
{


    protected $documentClass = 'SpService';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'sp_service';


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