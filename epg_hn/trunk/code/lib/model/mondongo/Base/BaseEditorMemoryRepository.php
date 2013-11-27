<?php

/**
 * Base class of repository of EditorMemory document.
 */
abstract class BaseEditorMemoryRepository extends \Mondongo\Repository
{


    protected $documentClass = 'EditorMemory';


    protected $connectionName = 'mondongo';


    protected $collectionName = 'editor_memory';


    protected $isFile = false;

    /**
     * Ensure indexes.
     *
     * @return void
     */
    public function ensureIndexes()
    {
        $this->getCollection()->ensureIndex(array(
            'wiki_id' => 1,
        ), array(
            'safe' => true,
        ));

    }
}