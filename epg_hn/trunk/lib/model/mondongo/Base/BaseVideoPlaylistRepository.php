<?php

/**
 * Base class of repository of VideoPlaylist document.
 */
abstract class BaseVideoPlaylistRepository extends \Mondongo\Repository
{


    protected $documentClass = 'VideoPlaylist';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'video_playlist';


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