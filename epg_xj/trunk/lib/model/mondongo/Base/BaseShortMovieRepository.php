<?php

/**
 * Base class of repository of ShortMovie document.
 */
abstract class BaseShortMovieRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ShortMovie';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'short_movie';


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