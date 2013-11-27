<?php

/**
 * Base class of repository of SingleChip document.
 */
abstract class BaseSingleChipRepository extends \Mondongo\Repository
{


    protected $documentClass = 'SingleChip';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'single_chip';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'user_id' => 1,
            'wiki_id' => 1,
        ), array(
            'safe' => true,
        ));

    }
}