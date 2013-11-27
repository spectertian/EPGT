<?php

/**
 * Base class of repository of Program document.
 */
abstract class BaseProgramRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Program';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'program';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'channel_code' => 1,
            'wiki_id' => 1,
            'tags' => 1,
            'time' => 1,
            'date' => 1,
            'sort' => 1,
        ), array(
            'safe' => true,
        ));

    }
}