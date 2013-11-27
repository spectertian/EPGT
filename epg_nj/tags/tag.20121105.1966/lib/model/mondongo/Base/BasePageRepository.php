<?php

/**
 * Base class of repository of Page document.
 */
abstract class BasePageRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Page';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'page';


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