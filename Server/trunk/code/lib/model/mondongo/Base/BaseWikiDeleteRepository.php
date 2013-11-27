<?php

/**
 * Base class of repository of WikiDelete document.
 */
abstract class BaseWikiDeleteRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WikiDelete';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki_delete';


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
            'admin_id' => 1,
        ), array(
            'safe' => true,
        ));

    }
}