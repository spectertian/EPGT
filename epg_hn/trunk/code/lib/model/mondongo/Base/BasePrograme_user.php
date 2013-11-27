<?php

/**
 * Base class of Programe_user document.
 */
abstract class BasePrograme_user extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'user_id' => null,
            'channel_code' => null,
            'name' => null,
            'start_time' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'user_id' => 'UserId',
        'channel_code' => 'ChannelCode',
        'name' => 'Name',
        'start_time' => 'StartTime',
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
        return \Mondongo\Container::getForDocumentClass('Programe_user');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Programe_user');
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

        if (isset($data['user_id'])) {
            $this->data['fields']['user_id'] = (string) $data['user_id'];
        }
        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['start_time'])) {
            $date = new \DateTime(); $date->setTimestamp($data['start_time']->sec); $this->data['fields']['start_time'] = $date;
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
        if (isset($fields['user_id'])) {
            $fields['user_id'] = (string) $fields['user_id'];
        }
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['start_time'])) {
            if ($fields['start_time'] instanceof \DateTime) { $fields['start_time'] = $fields['start_time']->getTimestamp(); } elseif (is_string($fields['start_time'])) { $fields['start_time'] = strtotime($fields['start_time']); } $fields['start_time'] = new \MongoDate($fields['start_time']);
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
     * Set the "user_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setUserId($value)
    {
        if (!array_key_exists('user_id', $this->fieldsModified)) {
            $this->fieldsModified['user_id'] = $this->data['fields']['user_id'];
        } elseif ($value === $this->fieldsModified['user_id']) {
            unset($this->fieldsModified['user_id']);
        }

        $this->data['fields']['user_id'] = $value;
    }

    /**
     * Returns the "user_id" field.
     *
     * @return mixed The user_id field.
     */
    public function getUserId()
    {
        return $this->data['fields']['user_id'];
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
        if (isset($array['user_id'])) {
            $this->setUserId($array['user_id']);
        }
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['start_time'])) {
            $this->setStartTime($array['start_time']);
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

        if (null !== $this->data['fields']['user_id']) {
            $array['user_id'] = $this->data['fields']['user_id'];
        }
        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['start_time']) {
            $array['start_time'] = $this->data['fields']['start_time'];
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
                'user_id' => array(
                    'type' => 'string',
                ),
                'channel_code' => array(
                    'type' => 'string',
                ),
                'name' => array(
                    'type' => 'string',
                ),
                'start_time' => array(
                    'type' => 'date',
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