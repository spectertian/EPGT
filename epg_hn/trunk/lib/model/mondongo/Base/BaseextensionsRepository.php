<?php

/**
 * Base class of repository of extensions document.
 */
abstract class BaseextensionsRepository extends \Mondongo\Repository
{


    protected $documentClass = 'extensions';


    protected $connectionName = NULL;


    protected $collectionName = 'extensions';


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