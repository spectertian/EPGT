<?php

/**
 * Base class of repository of SimpleAdvert document.
 */
abstract class BaseSimpleAdvertRepository extends \Mondongo\Repository
{


    protected $documentClass = 'SimpleAdvert';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'simple_advert';


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