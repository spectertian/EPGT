<?php

/**
 * Base class of repository of ProgramArchiver document.
 */
abstract class BaseProgramArchiverRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ProgramArchiver';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'program_archiver';


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