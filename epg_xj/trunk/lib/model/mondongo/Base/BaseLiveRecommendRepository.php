<?php

/**
 * Base class of repository of LiveRecommend document.
 */
abstract class BaseLiveRecommendRepository extends \Mondongo\Repository
{


    protected $documentClass = 'LiveRecommend';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'live_recommend';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'date' => 1,
        ), array(
            'safe' => true,
        ));

    }
}