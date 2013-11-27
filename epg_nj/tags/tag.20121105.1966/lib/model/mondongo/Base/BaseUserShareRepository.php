<?php

/**
 * Base class of repository of UserShare document.
 */
abstract class BaseUserShareRepository extends \Mondongo\Repository
{


    protected $documentClass = 'UserShare';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'user_share';


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
            'stype' => 1,
        ), array(
            'safe' => true,
        ));

    }
}