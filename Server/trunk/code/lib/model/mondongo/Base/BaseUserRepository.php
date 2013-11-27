<?php

/**
 * Base class of repository of User document.
 */
abstract class BaseUserRepository extends \Mondongo\Repository
{


    protected $documentClass = 'User';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'user';


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
            'username' => 1,
        ), array(
            'safe' => true,
        ));

    }
}