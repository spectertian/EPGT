<?php

/**
 * Base class of repository of ShortMoviePackage document.
 */
abstract class BaseShortMoviePackageRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ShortMoviePackage';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'short_movie_package';


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