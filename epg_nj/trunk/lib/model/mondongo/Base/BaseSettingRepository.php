<?php

/**
 * Base class of repository of Setting document.
 */
abstract class BaseSettingRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Setting';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'setting';


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