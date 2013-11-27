<?php

/**
 * Base class of repository of Wiki document.
 */
abstract class BaseWikiRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Wiki';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'wiki';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'tags' => 1,
            'wiki_id' => 1,
            'model' => 1,
            'admin_id' => 1,
        ), array(
            'safe' => true,
        ));
        $this->getCollection()->ensureIndex(array(
            'slug' => 1,
        ), array(
            'unique' => 1,
            'safe' => true,
        ));

    }
}