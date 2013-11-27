<?php

/**
 * Base class of repository of Dict document.
 */
abstract class BaseDictRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Dict';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'dict';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'keyword' => 1,
        ), array(
            'safe' => true,
        ));

    }
}