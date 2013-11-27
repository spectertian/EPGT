<?php

/**
 * Base class of repository of ProgramRec document.
 */
abstract class BaseProgramRecRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ProgramRec';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'program_rec';


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