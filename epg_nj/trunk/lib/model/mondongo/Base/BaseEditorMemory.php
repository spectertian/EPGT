<?php

/**
 * Base class of EditorMemory document.
 */
abstract class BaseEditorMemory extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'channel_code' => null,
            'program_id' => null,
            'program_name' => null,
            'tags' => null,
            'wiki_id' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'channel_code' => 'ChannelCode',
        'program_id' => 'ProgramId',
        'program_name' => 'ProgramName',
        'tags' => 'Tags',
        'wiki_id' => 'WikiId',
        'created_at' => 'CreatedAt',
        'updated_at' => 'UpdatedAt',
    );

    /**
     * Returns the Mondongo of the document.
     *
     * @return Mondongo\Mondongo The Mondongo of the document.
     */
    public function getMondongo()
    {
        return \Mondongo\Container::getForDocumentClass('EditorMemory');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('EditorMemory');
    }


    protected function updateTimestampableCreated()
    {
        $this->setCreatedAt(new \DateTime());
    }


    protected function updateTimestampableUpdated()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Set the data in the document (hydrate).
     *
     * @return void
     */
    public function setDocumentData($data)
    {
        $this->id = $data['_id'];

        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['program_id'])) {
            $this->data['fields']['program_id'] = (string) $data['program_id'];
        }
        if (isset($data['program_name'])) {
            $this->data['fields']['program_name'] = (string) $data['program_name'];
        }
        if (isset($data['tags'])) {
            $this->data['fields']['tags'] = $data['tags'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['created_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['created_at']->sec); $this->data['fields']['created_at'] = $date;
        }
        if (isset($data['updated_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['updated_at']->sec); $this->data['fields']['updated_at'] = $date;
        }


        
    }

    /**
     * Convert an array of fields with data to Mongo values.
     *
     * @param array $fields An array of fields with data.
     *
     * @return array The fields with data in Mongo values.
     */
    public function fieldsToMongo($fields)
    {
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['program_id'])) {
            $fields['program_id'] = (string) $fields['program_id'];
        }
        if (isset($fields['program_name'])) {
            $fields['program_name'] = (string) $fields['program_name'];
        }
        if (isset($fields['tags'])) {
            $fields['tags'] = $fields['tags'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['created_at'])) {
            if ($fields['created_at'] instanceof \DateTime) { $fields['created_at'] = $fields['created_at']->getTimestamp(); } elseif (is_string($fields['created_at'])) { $fields['created_at'] = strtotime($fields['created_at']); } $fields['created_at'] = new \MongoDate($fields['created_at']);
        }
        if (isset($fields['updated_at'])) {
            if ($fields['updated_at'] instanceof \DateTime) { $fields['updated_at'] = $fields['updated_at']->getTimestamp(); } elseif (is_string($fields['updated_at'])) { $fields['updated_at'] = strtotime($fields['updated_at']); } $fields['updated_at'] = new \MongoDate($fields['updated_at']);
        }


        return $fields;
    }

    /**
     * Set the "channel_code" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelCode($value)
    {
        if (!array_key_exists('channel_code', $this->fieldsModified)) {
            $this->fieldsModified['channel_code'] = $this->data['fields']['channel_code'];
        } elseif ($value === $this->fieldsModified['channel_code']) {
            unset($this->fieldsModified['channel_code']);
        }

        $this->data['fields']['channel_code'] = $value;
    }

    /**
     * Returns the "channel_code" field.
     *
     * @return mixed The channel_code field.
     */
    public function getChannelCode()
    {
        return $this->data['fields']['channel_code'];
    }

    /**
     * Set the "program_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setProgramId($value)
    {
        if (!array_key_exists('program_id', $this->fieldsModified)) {
            $this->fieldsModified['program_id'] = $this->data['fields']['program_id'];
        } elseif ($value === $this->fieldsModified['program_id']) {
            unset($this->fieldsModified['program_id']);
        }

        $this->data['fields']['program_id'] = $value;
    }

    /**
     * Returns the "program_id" field.
     *
     * @return mixed The program_id field.
     */
    public function getProgramId()
    {
        return $this->data['fields']['program_id'];
    }

    /**
     * Set the "program_name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setProgramName($value)
    {
        if (!array_key_exists('program_name', $this->fieldsModified)) {
            $this->fieldsModified['program_name'] = $this->data['fields']['program_name'];
        } elseif ($value === $this->fieldsModified['program_name']) {
            unset($this->fieldsModified['program_name']);
        }

        $this->data['fields']['program_name'] = $value;
    }

    /**
     * Returns the "program_name" field.
     *
     * @return mixed The program_name field.
     */
    public function getProgramName()
    {
        return $this->data['fields']['program_name'];
    }

    /**
     * Set the "tags" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTags($value)
    {
        if (!array_key_exists('tags', $this->fieldsModified)) {
            $this->fieldsModified['tags'] = $this->data['fields']['tags'];
        } elseif ($value === $this->fieldsModified['tags']) {
            unset($this->fieldsModified['tags']);
        }

        $this->data['fields']['tags'] = $value;
    }

    /**
     * Returns the "tags" field.
     *
     * @return mixed The tags field.
     */
    public function getTags()
    {
        return $this->data['fields']['tags'];
    }

    /**
     * Set the "wiki_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWikiId($value)
    {
        if (!array_key_exists('wiki_id', $this->fieldsModified)) {
            $this->fieldsModified['wiki_id'] = $this->data['fields']['wiki_id'];
        } elseif ($value === $this->fieldsModified['wiki_id']) {
            unset($this->fieldsModified['wiki_id']);
        }

        $this->data['fields']['wiki_id'] = $value;
    }

    /**
     * Returns the "wiki_id" field.
     *
     * @return mixed The wiki_id field.
     */
    public function getWikiId()
    {
        return $this->data['fields']['wiki_id'];
    }

    /**
     * Set the "created_at" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCreatedAt($value)
    {
        if (!array_key_exists('created_at', $this->fieldsModified)) {
            $this->fieldsModified['created_at'] = $this->data['fields']['created_at'];
        } elseif ($value === $this->fieldsModified['created_at']) {
            unset($this->fieldsModified['created_at']);
        }

        $this->data['fields']['created_at'] = $value;
    }

    /**
     * Returns the "created_at" field.
     *
     * @return mixed The created_at field.
     */
    public function getCreatedAt()
    {
        return $this->data['fields']['created_at'];
    }

    /**
     * Set the "updated_at" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setUpdatedAt($value)
    {
        if (!array_key_exists('updated_at', $this->fieldsModified)) {
            $this->fieldsModified['updated_at'] = $this->data['fields']['updated_at'];
        } elseif ($value === $this->fieldsModified['updated_at']) {
            unset($this->fieldsModified['updated_at']);
        }

        $this->data['fields']['updated_at'] = $value;
    }

    /**
     * Returns the "updated_at" field.
     *
     * @return mixed The updated_at field.
     */
    public function getUpdatedAt()
    {
        return $this->data['fields']['updated_at'];
    }


    public function preInsertExtensions()
    {
        $this->updateTimestampableCreated();

    }


    public function postInsertExtensions()
    {

    }


    public function preUpdateExtensions()
    {
        $this->updateTimestampableUpdated();

    }


    public function postUpdateExtensions()
    {

    }


    public function preSaveExtensions()
    {

    }


    public function postSaveExtensions()
    {

    }


    public function preDeleteExtensions()
    {

    }


    public function postDeleteExtensions()
    {

    }

    /**
     * Returns the data CamelCase map.
     *
     * @return array The data CamelCase map.
     */
    static public function getDataCamelCaseMap()
    {
        return self::$dataCamelCaseMap;
    }

    /**
     * Import data from an array.
     *
     * @param array $array An array.
     *
     * @return void
     */
    public function fromArray($array)
    {
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['program_id'])) {
            $this->setProgramId($array['program_id']);
        }
        if (isset($array['program_name'])) {
            $this->setProgramName($array['program_name']);
        }
        if (isset($array['tags'])) {
            $this->setTags($array['tags']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['created_at'])) {
            $this->setCreatedAt($array['created_at']);
        }
        if (isset($array['updated_at'])) {
            $this->setUpdatedAt($array['updated_at']);
        }

    }

    /**
     * Export the document data to array.
     *
     * @param bool $withEmbeddeds If export embeddeds or not.
     *
     * @return array An array with the document data.
     */
    public function toArray($withEmbeddeds = true)
    {
        $array = array();

        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['program_id']) {
            $array['program_id'] = $this->data['fields']['program_id'];
        }
        if (null !== $this->data['fields']['program_name']) {
            $array['program_name'] = $this->data['fields']['program_name'];
        }
        if (null !== $this->data['fields']['tags']) {
            $array['tags'] = $this->data['fields']['tags'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['created_at']) {
            $array['created_at'] = $this->data['fields']['created_at'];
        }
        if (null !== $this->data['fields']['updated_at']) {
            $array['updated_at'] = $this->data['fields']['updated_at'];
        }


        if ($withEmbeddeds) {

        }

        return $array;
    }

    /**
     * Throws an \LogicException because you cannot check if data exists.
     *
     * @throws \LogicException
     */
    public function offsetExists($name)
    {
        throw new \LogicException('You cannot check if data exists in a document.');
    }

    /**
     * Set data in the document.
     *
     * @param string $name  The data name.
     * @param mixed  $value The value.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the data name does not exists.
     */
    public function offsetSet($name, $value)
    {
        if (!isset(self::$dataCamelCaseMap[$name])) {
            throw new \InvalidArgumentException(sprintf('The name "%s" does not exists.', $name));
        }

        $method = 'set'.self::$dataCamelCaseMap[$name];

        $this->$method($value);
    }

    /**
     * Returns data of the document.
     *
     * @param string $name The data name.
     *
     * @return mixed Some data.
     *
     * @throws \InvalidArgumentException If the data name does not exists.
     */
    public function offsetGet($name)
    {
        if (!isset(self::$dataCamelCaseMap[$name])) {
            throw new \InvalidArgumentException(sprintf('The data "%s" does not exists.', $name));
        }

        $method = 'get'.self::$dataCamelCaseMap[$name];

        return $this->$method();
    }

    /**
     * Throws a \LogicException because you cannot unset data in the document.
     *
     * @throws \LogicException
     */
    public function offsetUnset($name)
    {
        throw new \LogicException('You cannot unset data in the document.');
    }

    /**
     * Set data in the document.
     *
     * @param string $name  The data name.
     * @param mixed  $value The value.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the data name does not exists.
     */
    public function __set($name, $value)
    {
        if (!isset(self::$dataCamelCaseMap[$name])) {
            throw new \InvalidArgumentException(sprintf('The name "%s" does not exists.', $name));
        }

        $method = 'set'.self::$dataCamelCaseMap[$name];

        $this->$method($value);
    }

    /**
     * Returns data of the document.
     *
     * @param string $name The data name.
     *
     * @return mixed Some data.
     *
     * @throws \InvalidArgumentException If the data name does not exists.
     */
    public function __get($name)
    {
        if (!isset(self::$dataCamelCaseMap[$name])) {
            throw new \InvalidArgumentException(sprintf('The data "%s" does not exists.', $name));
        }

        $method = 'get'.self::$dataCamelCaseMap[$name];

        return $this->$method();
    }

    /**
     * Returns the data map.
     *
     * @return array The data map.
     */
    static public function getDataMap()
    {
        return array(
            'fields' => array(
                'channel_code' => array(
                    'type' => 'string',
                ),
                'program_id' => array(
                    'type' => 'string',
                ),
                'program_name' => array(
                    'type' => 'string',
                ),
                'tags' => array(
                    'type' => 'raw',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'created_at' => array(
                    'type' => 'date',
                ),
                'updated_at' => array(
                    'type' => 'date',
                ),
            ),
            'references' => array(

            ),
            'embeddeds' => array(

            ),
            'relations' => array(

            ),
        );
    }
}