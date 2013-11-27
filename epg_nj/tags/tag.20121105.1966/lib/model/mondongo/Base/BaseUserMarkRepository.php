<?php

/**
 * Base class of repository of UserMark document.
 */
abstract class BaseUserMarkRepository extends \Mondongo\Repository
{


    protected $documentClass = 'UserMark';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'user_mark';


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
            'type' => 1,
            'obj_id' => 1,
        ), array(
            'safe' => true,
        ));

    }
}