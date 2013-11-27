<?php

/**
 * Base class of repository of ReportChannel document.
 */
abstract class BaseReportChannelRepository extends \Mondongo\Repository
{


    protected $documentClass = 'ReportChannel';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'reportchannel';


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