<?php

/**
 * Base class of repository of Programe_user document.
 */
abstract class BasePrograme_userRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Programe_user';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'programe_user';


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
            'channel_code' => 1,
            'start_time' => 1,
        ), array(
            'safe' => true,
        ));

    }
}