<?php

/**
 * Base class of ProgramLive document.
 */
abstract class BaseProgramLive extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'name' => null,
            'type' => null,
            'next_name' => null,
            'channel_code' => null,
            'start_time' => null,
            'end_time' => null,
            'wiki_id' => null,
            'wiki_cover' => null,
            'wiki_title' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'name' => 'Name',
        'type' => 'Type',
        'next_name' => 'NextName',
        'channel_code' => 'ChannelCode',
        'start_time' => 'StartTime',
        'end_time' => 'EndTime',
        'wiki_id' => 'WikiId',
        'wiki_cover' => 'WikiCover',
        'wiki_title' => 'WikiTitle',
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
        return \Mondongo\Container::getForDocumentClass('ProgramLive');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('ProgramLive');
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

        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['type'])) {
            $this->data['fields']['type'] = (string) $data['type'];
        }
        if (isset($data['next_name'])) {
            $this->data['fields']['next_name'] = (string) $data['next_name'];
        }
        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['start_time'])) {
            $date = new \DateTime(); $date->setTimestamp($data['start_time']->sec); $this->data['fields']['start_time'] = $date;
        }
        if (isset($data['end_time'])) {
            $date = new \DateTime(); $date->setTimestamp($data['end_time']->sec); $this->data['fields']['end_time'] = $date;
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['wiki_cover'])) {
            $this->data['fields']['wiki_cover'] = (string) $data['wiki_cover'];
        }
        if (isset($data['wiki_title'])) {
            $this->data['fields']['wiki_title'] = (string) $data['wiki_title'];
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
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['type'])) {
            $fields['type'] = (string) $fields['type'];
        }
        if (isset($fields['next_name'])) {
            $fields['next_name'] = (string) $fields['next_name'];
        }
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['start_time'])) {
            if ($fields['start_time'] instanceof \DateTime) { $fields['start_time'] = $fields['start_time']->getTimestamp(); } elseif (is_string($fields['start_time'])) { $fields['start_time'] = strtotime($fields['start_time']); } $fields['start_time'] = new \MongoDate($fields['start_time']);
        }
        if (isset($fields['end_time'])) {
            if ($fields['end_time'] instanceof \DateTime) { $fields['end_time'] = $fields['end_time']->getTimestamp(); } elseif (is_string($fields['end_time'])) { $fields['end_time'] = strtotime($fields['end_time']); } $fields['end_time'] = new \MongoDate($fields['end_time']);
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['wiki_cover'])) {
            $fields['wiki_cover'] = (string) $fields['wiki_cover'];
        }
        if (isset($fields['wiki_title'])) {
            $fields['wiki_title'] = (string) $fields['wiki_title'];
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
     * Set the "type" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setType($value)
    {
        if (!array_key_exists('type', $this->fieldsModified)) {
            $this->fieldsModified['type'] = $this->data['fields']['type'];
        } elseif ($value === $this->fieldsModified['type']) {
            unset($this->fieldsModified['type']);
        }

        $this->data['fields']['type'] = $value;
    }

    /**
     * Returns the "type" field.
     *
     * @return mixed The type field.
     */
    public function getType()
    {
        return $this->data['fields']['type'];
    }

    /**
     * Set the "next_name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setNextName($value)
    {
        if (!array_key_exists('next_name', $this->fieldsModified)) {
            $this->fieldsModified['next_name'] = $this->data['fields']['next_name'];
        } elseif ($value === $this->fieldsModified['next_name']) {
            unset($this->fieldsModified['next_name']);
        }

        $this->data['fields']['next_name'] = $value;
    }

    /**
     * Returns the "next_name" field.
     *
     * @return mixed The next_name field.
     */
    public function getNextName()
    {
        return $this->data['fields']['next_name'];
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
     * Set the "wiki_cover" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWikiCover($value)
    {
        if (!array_key_exists('wiki_cover', $this->fieldsModified)) {
            $this->fieldsModified['wiki_cover'] = $this->data['fields']['wiki_cover'];
        } elseif ($value === $this->fieldsModified['wiki_cover']) {
            unset($this->fieldsModified['wiki_cover']);
        }

        $this->data['fields']['wiki_cover'] = $value;
    }

    /**
     * Returns the "wiki_cover" field.
     *
     * @return mixed The wiki_cover field.
     */
    public function getWikiCover()
    {
        return $this->data['fields']['wiki_cover'];
    }

    /**
     * Set the "wiki_title" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWikiTitle($value)
    {
        if (!array_key_exists('wiki_title', $this->fieldsModified)) {
            $this->fieldsModified['wiki_title'] = $this->data['fields']['wiki_title'];
        } elseif ($value === $this->fieldsModified['wiki_title']) {
            unset($this->fieldsModified['wiki_title']);
        }

        $this->data['fields']['wiki_title'] = $value;
    }

    /**
     * Returns the "wiki_title" field.
     *
     * @return mixed The wiki_title field.
     */
    public function getWikiTitle()
    {
        return $this->data['fields']['wiki_title'];
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
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
        if (isset($array['next_name'])) {
            $this->setNextName($array['next_name']);
        }
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['start_time'])) {
            $this->setStartTime($array['start_time']);
        }
        if (isset($array['end_time'])) {
            $this->setEndTime($array['end_time']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['wiki_cover'])) {
            $this->setWikiCover($array['wiki_cover']);
        }
        if (isset($array['wiki_title'])) {
            $this->setWikiTitle($array['wiki_title']);
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

        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['type']) {
            $array['type'] = $this->data['fields']['type'];
        }
        if (null !== $this->data['fields']['next_name']) {
            $array['next_name'] = $this->data['fields']['next_name'];
        }
        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['start_time']) {
            $array['start_time'] = $this->data['fields']['start_time'];
        }
        if (null !== $this->data['fields']['end_time']) {
            $array['end_time'] = $this->data['fields']['end_time'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['wiki_cover']) {
            $array['wiki_cover'] = $this->data['fields']['wiki_cover'];
        }
        if (null !== $this->data['fields']['wiki_title']) {
            $array['wiki_title'] = $this->data['fields']['wiki_title'];
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
                'name' => array(
                    'type' => 'string',
                ),
                'type' => array(
                    'type' => 'string',
                ),
                'next_name' => array(
                    'type' => 'string',
                ),
                'channel_code' => array(
                    'type' => 'string',
                ),
                'start_time' => array(
                    'type' => 'date',
                ),
                'end_time' => array(
                    'type' => 'date',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'wiki_cover' => array(
                    'type' => 'string',
                ),
                'wiki_title' => array(
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