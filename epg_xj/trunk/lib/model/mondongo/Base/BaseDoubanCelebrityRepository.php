<?php

/**
 * Base class of repository of DoubanCelebrity document.
 */
abstract class BaseDoubanCelebrityRepository extends \Mondongo\Repository
{


    protected $documentClass = 'DoubanCelebrity';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'douban_celebrity';


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