<?php

/**
 * Base class of repository of WikiPackage document.
 */
abstract class BaseWikiPackageRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiPackage';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_package';


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