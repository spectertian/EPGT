<?php

/**
 * Base class of repository of ProgramRecommend document.
 */
abstract class BaseProgramRecommendRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ProgramRecommend';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'program_recommend';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'tv_station_id' => 1,
            'channel_id' => 1,
            'content' => 1,
        ), array(
            'safe' => true,
        ));

    }
}