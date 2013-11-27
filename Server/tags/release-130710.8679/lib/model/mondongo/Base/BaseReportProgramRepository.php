<?php

/**
 * Base class of repository of ReportProgram document.
 */
abstract class BaseReportProgramRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ReportProgram';


    protected $connectionName = 'mondongosp';


    protected $collectionName = 'report_program';


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