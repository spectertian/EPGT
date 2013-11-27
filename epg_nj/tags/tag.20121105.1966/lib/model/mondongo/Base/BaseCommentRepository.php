<?php

/**
 * Base class of repository of Comment document.
 */
abstract class BaseCommentRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Comment';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'comments';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'user_id' => 1,
            'wiki_id' => 1,
        ), array(
            'safe' => true,
        ));

    }
}