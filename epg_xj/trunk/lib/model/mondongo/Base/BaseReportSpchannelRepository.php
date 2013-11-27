<?php

/**
 * Base class of repository of ReportSpchannel document.
 */
abstract class BaseReportSpchannelRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ReportSpchannel';


    protected $connectionName = 'mondongosp';


    protected $collectionName = 'report_spchannel';


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