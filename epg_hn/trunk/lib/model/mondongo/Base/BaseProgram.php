<?php

/**
 * Base class of Program document.
 */
abstract class BaseProgram extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'program_id' => null,
            'name' => null,
            'publish' => null,
            'channel_code' => null,
            'tags' => null,
            'start_time' => null,
            'end_time' => null,
            'time' => null,
            'date' => null,
            'wiki_id' => null,
            'admin' => null,
            'sort' => null,
            'tvsou_id' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'program_id' => 'ProgramId',
        'name' => 'Name',
        'publish' => 'Publish',
        'channel_code' => 'ChannelCode',
        'tags' => 'Tags',
        'start_time' => 'StartTime',
        'end_time' => 'EndTime',
        'time' => 'Time',
        'date' => 'Date',
        'wiki_id' => 'WikiId',
        'admin' => 'Admin',
        'sort' => 'Sort',
        'tvsou_id' => 'TvsouId',
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
        return \Mondongo\Container::getForDocumentClass('Program');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Program');
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

        if (isset($data['program_id'])) {
            $this->data['fields']['program_id'] = (string) $data['program_id'];
        }
        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['publish'])) {
            $this->data['fields']['publish'] = (bool) $data['publish'];
        }
        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['tags'])) {
            $this->data['fields']['tags'] = $data['tags'];
        }
        if (isset($data['start_time'])) {
            $date = new \DateTime(); $date->setTimestamp($data['start_time']->sec); $this->data['fields']['start_time'] = $date;
        }
        if (isset($data['end_time'])) {
            $date = new \DateTime(); $date->setTimestamp($data['end_time']->sec); $this->data['fields']['end_time'] = $date;
        }
        if (isset($data['time'])) {
            $this->data['fields']['time'] = (string) $data['time'];
        }
        if (isset($data['date'])) {
            $this->data['fields']['date'] = (string) $data['date'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['admin'])) {
            $this->data['fields']['admin'] = (string) $data['admin'];
        }
        if (isset($data['sort'])) {
            $this->data['fields']['sort'] = (int) $data['sort'];
        }
        if (isset($data['tvsou_id'])) {
            $this->data['fields']['tvsou_id'] = (int) $data['tvsou_id'];
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
        if (isset($fields['program_id'])) {
            $fields['program_id'] = (string) $fields['program_id'];
        }
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['publish'])) {
            $fields['publish'] = (bool) $fields['publish'];
        }
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['tags'])) {
            $fields['tags'] = $fields['tags'];
        }
        if (isset($fields['start_time'])) {
            if ($fields['start_time'] instanceof \DateTime) { $fields['start_time'] = $fields['start_time']->getTimestamp(); } elseif (is_string($fields['start_time'])) { $fields['start_time'] = strtotime($fields['start_time']); } $fields['start_time'] = new \MongoDate($fields['start_time']);
        }
        if (isset($fields['end_time'])) {
            if ($fields['end_time'] instanceof \DateTime) { $fields['end_time'] = $fields['end_time']->getTimestamp(); } elseif (is_string($fields['end_time'])) { $fields['end_time'] = strtotime($fields['end_time']); } $fields['end_time'] = new \MongoDate($fields['end_time']);
        }
        if (isset($fields['time'])) {
            $fields['time'] = (string) $fields['time'];
        }
        if (isset($fields['date'])) {
            $fields['date'] = (string) $fields['date'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['admin'])) {
            $fields['admin'] = (string) $fields['admin'];
        }
        if (isset($fields['sort'])) {
            $fields['sort'] = (int) $fields['sort'];
        }
        if (isset($fields['tvsou_id'])) {
            $fields['tvsou_id'] = (int) $fields['tvsou_id'];
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
     * Set the "name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setName($value)
    {
        if (!array_key_exists('name', $this->fieldsModified)) {
            $this->fieldsModified['name'] = $this->data['fields']['name'];
        } elseif ($value === $this->fieldsModified['name']) {
            unset($this->fieldsModified['name']);
        }

        $this->data['fields']['name'] = $value;
    }

    /**
     * Returns the "name" field.
     *
     * @return mixed The name field.
     */
    public function getName()
    {
        return $this->data['fields']['name'];
    }

    /**
     * Set the "publish" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPublish($value)
    {
        if (!array_key_exists('publish', $this->fieldsModified)) {
            $this->fieldsModified['publish'] = $this->data['fields']['publish'];
        } elseif ($value === $this->fieldsModified['publish']) {
            unset($this->fieldsModified['publish']);
        }

        $this->data['fields']['publish'] = $value;
    }

    /**
     * Returns the "publish" field.
     *
     * @return mixed The publish field.
     */
    public function getPublish()
    {
        return $this->data['fields']['publish'];
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
     * Set the "start_time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStartTime($value)
    {
        if (!array_key_exists('start_time', $this->fieldsModified)) {
            $this->fieldsModified['start_time'] = $this->data['fields']['start_time'];
        } elseif ($value === $this->fieldsModified['start_time']) {
            unset($this->fieldsModified['start_time']);
        }

        $this->data['fields']['start_time'] = $value;
    }

    /**
     * Returns the "start_time" field.
     *
     * @return mixed The start_time field.
     */
    public function getStartTime()
    {
        return $this->data['fields']['start_time'];
    }

    /**
     * Set the "end_time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setEndTime($value)
    {
        if (!array_key_exists('end_time', $this->fieldsModified)) {
            $this->fieldsModified['end_time'] = $this->data['fields']['end_time'];
        } elseif ($value === $this->fieldsModified['end_time']) {
            unset($this->fieldsModified['end_time']);
        }

        $this->data['fields']['end_time'] = $value;
    }

    /**
     * Returns the "end_time" field.
     *
     * @return mixed The end_time field.
     */
    public function getEndTime()
    {
        return $this->data['fields']['end_time'];
    }

    /**
     * Set the "time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTime($value)
    {
        if (!array_key_exists('time', $this->fieldsModified)) {
            $this->fieldsModified['time'] = $this->data['fields']['time'];
        } elseif ($value === $this->fieldsModified['time']) {
            unset($this->fieldsModified['time']);
        }

        $this->data['fields']['time'] = $value;
    }

    /**
     * Returns the "time" field.
     *
     * @return mixed The time field.
     */
    public function getTime()
    {
        return $this->data['fields']['time'];
    }

    /**
     * Set the "date" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDate($value)
    {
        if (!array_key_exists('date', $this->fieldsModified)) {
            $this->fieldsModified['date'] = $this->data['fields']['date'];
        } elseif ($value === $this->fieldsModified['date']) {
            unset($this->fieldsModified['date']);
        }

        $this->data['fields']['date'] = $value;
    }

    /**
     * Returns the "date" field.
     *
     * @return mixed The date field.
     */
    public function getDate()
    {
        return $this->data['fields']['date'];
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
     * Set the "admin" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAdmin($value)
    {
        if (!array_key_exists('admin', $this->fieldsModified)) {
            $this->fieldsModified['admin'] = $this->data['fields']['admin'];
        } elseif ($value === $this->fieldsModified['admin']) {
            unset($this->fieldsModified['admin']);
        }

        $this->data['fields']['admin'] = $value;
    }

    /**
     * Returns the "admin" field.
     *
     * @return mixed The admin field.
     */
    public function getAdmin()
    {
        return $this->data['fields']['admin'];
    }

    /**
     * Set the "sort" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSort($value)
    {
        if (!array_key_exists('sort', $this->fieldsModified)) {
            $this->fieldsModified['sort'] = $this->data['fields']['sort'];
        } elseif ($value === $this->fieldsModified['sort']) {
            unset($this->fieldsModified['sort']);
        }

        $this->data['fields']['sort'] = $value;
    }

    /**
     * Returns the "sort" field.
     *
     * @return mixed The sort field.
     */
    public function getSort()
    {
        return $this->data['fields']['sort'];
    }

    /**
     * Set the "tvsou_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTvsouId($value)
    {
        if (!array_key_exists('tvsou_id', $this->fieldsModified)) {
            $this->fieldsModified['tvsou_id'] = $this->data['fields']['tvsou_id'];
        } elseif ($value === $this->fieldsModified['tvsou_id']) {
            unset($this->fieldsModified['tvsou_id']);
        }

        $this->data['fields']['tvsou_id'] = $value;
    }

    /**
     * Returns the "tvsou_id" field.
     *
     * @return mixed The tvsou_id field.
     */
    public function getTvsouId()
    {
        return $this->data['fields']['tvsou_id'];
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
        if (isset($array['program_id'])) {
            $this->setProgramId($array['program_id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['publish'])) {
            $this->setPublish($array['publish']);
        }
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['tags'])) {
            $this->setTags($array['tags']);
        }
        if (isset($array['start_time'])) {
            $this->setStartTime($array['start_time']);
        }
        if (isset($array['end_time'])) {
            $this->setEndTime($array['end_time']);
        }
        if (isset($array['time'])) {
            $this->setTime($array['time']);
        }
        if (isset($array['date'])) {
            $this->setDate($array['date']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['admin'])) {
            $this->setAdmin($array['admin']);
        }
        if (isset($array['sort'])) {
            $this->setSort($array['sort']);
        }
        if (isset($array['tvsou_id'])) {
            $this->setTvsouId($array['tvsou_id']);
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

        if (null !== $this->data['fields']['program_id']) {
            $array['program_id'] = $this->data['fields']['program_id'];
        }
        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['publish']) {
            $array['publish'] = $this->data['fields']['publish'];
        }
        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['tags']) {
            $array['tags'] = $this->data['fields']['tags'];
        }
        if (null !== $this->data['fields']['start_time']) {
            $array['start_time'] = $this->data['fields']['start_time'];
        }
        if (null !== $this->data['fields']['end_time']) {
            $array['end_time'] = $this->data['fields']['end_time'];
        }
        if (null !== $this->data['fields']['time']) {
            $array['time'] = $this->data['fields']['time'];
        }
        if (null !== $this->data['fields']['date']) {
            $array['date'] = $this->data['fields']['date'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['admin']) {
            $array['admin'] = $this->data['fields']['admin'];
        }
        if (null !== $this->data['fields']['sort']) {
            $array['sort'] = $this->data['fields']['sort'];
        }
        if (null !== $this->data['fields']['tvsou_id']) {
            $array['tvsou_id'] = $this->data['fields']['tvsou_id'];
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
                'program_id' => array(
                    'type' => 'string',
                ),
                'name' => array(
                    'type' => 'string',
                ),
                'publish' => array(
                    'type' => 'boolean',
                ),
                'channel_code' => array(
                    'type' => 'string',
                ),
                'tags' => array(
                    'type' => 'raw',
                ),
                'start_time' => array(
                    'type' => 'date',
                ),
                'end_time' => array(
                    'type' => 'date',
                ),
                'time' => array(
                    'type' => 'string',
                ),
                'date' => array(
                    'type' => 'string',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'admin' => array(
                    'type' => 'string',
                ),
                'sort' => array(
                    'type' => 'integer',
                ),
                'tvsou_id' => array(
                    'type' => 'integer',
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