<?php

/**
 * Base class of repository of TransferStatistics document.
 */
abstract class BaseTransferStatisticsRepository extends \Mondongo\Repository
{


    protected $documentClass = 'TransferStatistics';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'transfer_statistics';


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