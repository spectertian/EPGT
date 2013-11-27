<?php

/**
 * Base class of repository of ChannelFavorites document.
 */
abstract class BaseChannelFavoritesRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ChannelFavorites';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'channel_favorites';


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
            'channel_id' => 1,
        ), array(
            'safe' => true,
        ));

    }
}