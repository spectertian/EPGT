<?php

/**
 * Base class of repository of ReportSp document.
 */
abstract class BaseReportSpRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ReportSp';


    protected $connectionName = 'mondongosp';


    protected $collectionName = 'report_sp';


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