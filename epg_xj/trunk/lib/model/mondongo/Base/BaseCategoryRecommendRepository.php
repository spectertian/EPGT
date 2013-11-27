<?php

/**
 * Base class of repository of CategoryRecommend document.
 */
abstract class BaseCategoryRecommendRepository extends \Mondongo\Repository
{


    protected $documentClass = 'CategoryRecommend';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'category_recommend';


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