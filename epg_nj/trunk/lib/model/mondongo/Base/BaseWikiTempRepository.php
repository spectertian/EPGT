<?php

/**
 * Base class of repository of WikiTemp document.
 */
abstract class BaseWikiTempRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiTemp';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_temp';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'wiki_id' => 1,
            'model' => 1,
        ), array(
            'safe' => true,
        ));

    }
}