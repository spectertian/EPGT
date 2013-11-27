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
            'channel_code' => 1,
            'program_name' => 1,
        ), array(
            'safe' => true,
        ));

    }
}