<?php

/**
 * Base class of repository of Cpg document.
 */
abstract class BaseCpgRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Cpg';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'cpg';


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