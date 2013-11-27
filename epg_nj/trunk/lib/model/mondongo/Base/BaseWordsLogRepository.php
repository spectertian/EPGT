<?php

/**
 * Base class of repository of WordsLog document.
 */
abstract class BaseWordsLogRepository extends \Mondongo\Repository
{


    protected $documentClass = 'WordsLog';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'words_log';


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