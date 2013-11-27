<?php

/**
 * Base class of repository of VideosZhui document.
 */
abstract class BaseVideosZhuiRepository extends \Mondongo\Repository
{


    protected $documentClass = 'VideosZhui';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'videos_zhui';


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