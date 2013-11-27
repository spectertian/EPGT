<?php

/**
 * Base class of repository of DoubanMovie document.
 */
abstract class BaseDoubanMovieRepository extends \Mondongo\Repository
{


    protected $documentClass = 'DoubanMovie';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'douban_movie';


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