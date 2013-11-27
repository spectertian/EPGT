<?php

/**
 * Base class of repository of VideoCrawler document.
 */
abstract class BaseVideoCrawlerRepository extends \Mondongo\Repository
{


    protected $documentClass = 'VideoCrawler';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'video_crawler';


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