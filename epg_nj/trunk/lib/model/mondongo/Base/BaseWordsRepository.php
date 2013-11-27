<?php

/**
 * Base class of repository of Words document.
 */
abstract class BaseWordsRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Words';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'words';


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