<?php

/**
 * Base class of repository of TvsouMatchWiki document.
 */
abstract class BaseTvsouMatchWikiRepository extends \Mondongo\Repository
{


    protected $documentClass = 'TvsouMatchWiki';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'tvsou_match_wiki';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'tvsou_id' => 1,
        ), array(
            'safe' => true,
        ));

    }
}