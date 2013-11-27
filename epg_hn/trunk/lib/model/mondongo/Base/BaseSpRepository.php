<?php

/**
 * Base class of repository of Sp document.
 */
abstract class BaseSpRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Sp';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'sp';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'signal' => 1,
        ), array(
            'safe' => true,
        ));

    }
}