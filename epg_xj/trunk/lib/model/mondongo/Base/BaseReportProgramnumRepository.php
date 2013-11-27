<?php

/**
 * Base class of repository of ReportProgramnum document.
 */
abstract class BaseReportProgramnumRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ReportProgramnum';


    protected $connectionName = 'mondongosp';


    protected $collectionName = 'report_programnum';


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