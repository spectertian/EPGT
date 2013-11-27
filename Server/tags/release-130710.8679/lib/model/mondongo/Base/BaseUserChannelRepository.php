<?php

/**
 * Base class of repository of UserChannel document.
 */
abstract class BaseUserChannelRepository extends \Mondongo\Repository
{


    protected $documentClass = 'UserChannel';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'user_channel';


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
        ), array(
            'safe' => true,
        ));

    }
}