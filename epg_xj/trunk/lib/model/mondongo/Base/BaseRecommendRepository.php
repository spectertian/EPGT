<?php

/**
 * Base class of repository of Recommend document.
 */
abstract class BaseRecommendRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Recommend';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'recommend';


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
            'sort' => 1,
        ), array(
            'safe' => true,
        ));

    }
}