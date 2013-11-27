<?php

/**
 * Base class of repository of Terminal document.
 */
abstract class BaseTerminalRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Terminal';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'terminal';


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