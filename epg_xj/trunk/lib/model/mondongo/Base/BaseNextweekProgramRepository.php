<?php

/**
 * Base class of repository of NextweekProgram document.
 */
abstract class BaseNextweekProgramRepository extends \Mondongo\Repository
{


    protected $documentClass = 'NextweekProgram';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'nextweek_program';


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