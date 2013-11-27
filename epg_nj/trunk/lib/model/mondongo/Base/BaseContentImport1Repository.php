<?php

/**
 * Base class of repository of ContentImport1 document.
 */
abstract class BaseContentImport1Repository extends \Mondongo\Repository
{


    protected $documentClass = 'ContentImport1';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'content_import1';


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