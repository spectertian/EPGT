<?php

/**
 * Base class of repository of RecommandFix document.
 */
abstract class BaseRecommandFixRepository extends \Mondongo\Repository
{


    protected $documentClass = 'RecommandFix';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'recommand_fix';


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