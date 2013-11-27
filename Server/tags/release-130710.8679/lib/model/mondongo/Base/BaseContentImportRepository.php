<?php

/**
 * Base class of repository of ContentImport document.
 */
abstract class BaseContentImportRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ContentImport';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'content_import';


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