<?php

/**
 * Base class of repository of ProgramTemp document.
 */
abstract class BaseProgramTempRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ProgramTemp';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'program_temp';


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
            'tags' => 1,
            'time' => 1,
            'date' => 1,
            'sort' => 1,
        ), array(
            'safe' => true,
        ));

    }
}