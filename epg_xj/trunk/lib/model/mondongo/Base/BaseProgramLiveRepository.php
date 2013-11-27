<?php

/**
 * Base class of repository of ProgramLive document.
 */
abstract class BaseProgramLiveRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ProgramLive';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'program_live';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'channel_code' => 1,
            'wiki_id' => 1,
            'start_time' => 1,
            'end_time' => 1,
        ), array(
            'safe' => true,
        ));

    }
}