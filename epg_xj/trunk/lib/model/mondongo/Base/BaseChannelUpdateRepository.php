<?php

/**
 * Base class of repository of ChannelUpdate document.
 */
abstract class BaseChannelUpdateRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ChannelUpdate';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'channel_update';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'channel_code' => 1,
        ), array(
            'safe' => true,
        ));

    }
}