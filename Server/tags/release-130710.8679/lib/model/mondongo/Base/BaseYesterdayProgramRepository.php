<?php

/**
 * Base class of repository of YesterdayProgram document.
 */
abstract class BaseYesterdayProgramRepository extends \Mondongo\Repository
{


    protected $documentClass = 'YesterdayProgram';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'yesterday_program';


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