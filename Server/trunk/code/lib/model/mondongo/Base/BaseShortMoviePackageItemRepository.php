<?php

/**
 * Base class of repository of ShortMoviePackageItem document.
 */
abstract class BaseShortMoviePackageItemRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ShortMoviePackageItem';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'short_movie_package_item';


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