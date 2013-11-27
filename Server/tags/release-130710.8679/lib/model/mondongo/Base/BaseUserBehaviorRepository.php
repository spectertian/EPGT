<?php

/**
 * Base class of repository of UserBehavior document.
 */
abstract class BaseUserBehaviorRepository extends \Mondongo\Repository
{


    protected $documentClass = 'UserBehavior';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'user_behavior';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {

    }
}