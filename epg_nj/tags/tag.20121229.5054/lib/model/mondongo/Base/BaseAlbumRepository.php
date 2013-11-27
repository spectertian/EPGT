<?php

/**
 * Base class of repository of Album document.
 */
abstract class BaseAlbumRepository extends \Mondongo\Repository
{


    protected $documentClass = 'Album';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'album';


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
        ), array(
            'safe' => true,
        ));

    }
}