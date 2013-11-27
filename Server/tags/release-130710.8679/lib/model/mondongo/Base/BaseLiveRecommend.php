<?php

/**
 * Base class of LiveRecommend document.
 */
abstract class BaseLiveRecommend extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'date' => null,
            'start_time' => null,
            'endt_ime' => null,
            'list' => null,
            'user_name' => null,
            'user_id' => null,
            'state' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'date' => 'Date',
        'start_time' => 'StartTime',
        'endt_ime' => 'EndtIme',
        'list' => 'List',
        'user_name' => 'UserName',
        'user_id' => 'UserId',
        'state' => 'State',
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
        return \Mondongo\Container::getForDocumentClass('LiveRecommend');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('LiveRecommend');
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

        if (isset($data['date'])) {
            $this->data['fields']['date'] = (string) $data['date'];
        }
        if (isset($data['start_time'])) {
            $this->data['fields']['start_time'] = (string) $data['start_time'];
        }
        if (isset($data['endt_ime'])) {
            $this->data['fields']['endt_ime'] = (string) $data['endt_ime'];
        }
        if (isset($data['list'])) {
            $this->data['fields']['list'] = (string) $data['list'];
        }
        if (isset($data['user_name'])) {
            $this->data['fields']['user_name'] = (string) $data['user_name'];
        }
        if (isset($data['user_id'])) {
            $this->data['fields']['user_id'] = (string) $data['user_id'];
        }
        if (isset($data['state'])) {
            $this->data['fields']['state'] = (bool) $data['state'];
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
        if (isset($fields['date'])) {
            $fields['date'] = (string) $fields['date'];
        }
        if (isset($fields['start_time'])) {
            $fields['start_time'] = (string) $fields['start_time'];
        }
        if (isset($fields['endt_ime'])) {
            $fields['endt_ime'] = (string) $fields['endt_ime'];
        }
        if (isset($fields['list'])) {
            $fields['list'] = (string) $fields['list'];
        }
        if (isset($fields['user_name'])) {
            $fields['user_name'] = (string) $fields['user_name'];
        }
        if (isset($fields['user_id'])) {
            $fields['user_id'] = (string) $fields['user_id'];
        }
        if (isset($fields['state'])) {
            $fields['state'] = (bool) $fields['state'];
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
     * Set the "endt_ime" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setEndtIme($value)
    {
        if (!array_key_exists('endt_ime', $this->fieldsModified)) {
            $this->fieldsModified['endt_ime'] = $this->data['fields']['endt_ime'];
        } elseif ($value === $this->fieldsModified['endt_ime']) {
            unset($this->fieldsModified['endt_ime']);
        }

        $this->data['fields']['endt_ime'] = $value;
    }

    /**
     * Returns the "endt_ime" field.
     *
     * @return mixed The endt_ime field.
     */
    public function getEndtIme()
    {
        return $this->data['fields']['endt_ime'];
    }

    /**
     * Set the "list" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setList($value)
    {
        if (!array_key_exists('list', $this->fieldsModified)) {
            $this->fieldsModified['list'] = $this->data['fields']['list'];
        } elseif ($value === $this->fieldsModified['list']) {
            unset($this->fieldsModified['list']);
        }

        $this->data['fields']['list'] = $value;
    }

    /**
     * Returns the "list" field.
     *
     * @return mixed The list field.
     */
    public function getList()
    {
        return $this->data['fields']['list'];
    }

    /**
     * Set the "user_name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setUserName($value)
    {
        if (!array_key_exists('user_name', $this->fieldsModified)) {
            $this->fieldsModified['user_name'] = $this->data['fields']['user_name'];
        } elseif ($value === $this->fieldsModified['user_name']) {
            unset($this->fieldsModified['user_name']);
        }

        $this->data['fields']['user_name'] = $value;
    }

    /**
     * Returns the "user_name" field.
     *
     * @return mixed The user_name field.
     */
    public function getUserName()
    {
        return $this->data['fields']['user_name'];
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
     * Set the "state" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setState($value)
    {
        if (!array_key_exists('state', $this->fieldsModified)) {
            $this->fieldsModified['state'] = $this->data['fields']['state'];
        } elseif ($value === $this->fieldsModified['state']) {
            unset($this->fieldsModified['state']);
        }

        $this->data['fields']['state'] = $value;
    }

    /**
     * Returns the "state" field.
     *
     * @return mixed The state field.
     */
    public function getState()
    {
        return $this->data['fields']['state'];
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
        if (isset($array['date'])) {
            $this->setDate($array['date']);
        }
        if (isset($array['start_time'])) {
            $this->setStartTime($array['start_time']);
        }
        if (isset($array['endt_ime'])) {
            $this->setEndtIme($array['endt_ime']);
        }
        if (isset($array['list'])) {
            $this->setList($array['list']);
        }
        if (isset($array['user_name'])) {
            $this->setUserName($array['user_name']);
        }
        if (isset($array['user_id'])) {
            $this->setUserId($array['user_id']);
        }
        if (isset($array['state'])) {
            $this->setState($array['state']);
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

        if (null !== $this->data['fields']['date']) {
            $array['date'] = $this->data['fields']['date'];
        }
        if (null !== $this->data['fields']['start_time']) {
            $array['start_time'] = $this->data['fields']['start_time'];
        }
        if (null !== $this->data['fields']['endt_ime']) {
            $array['endt_ime'] = $this->data['fields']['endt_ime'];
        }
        if (null !== $this->data['fields']['list']) {
            $array['list'] = $this->data['fields']['list'];
        }
        if (null !== $this->data['fields']['user_name']) {
            $array['user_name'] = $this->data['fields']['user_name'];
        }
        if (null !== $this->data['fields']['user_id']) {
            $array['user_id'] = $this->data['fields']['user_id'];
        }
        if (null !== $this->data['fields']['state']) {
            $array['state'] = $this->data['fields']['state'];
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
                'date' => array(
                    'type' => 'string',
                ),
                'start_time' => array(
                    'type' => 'string',
                ),
                'endt_ime' => array(
                    'type' => 'string',
                ),
                'list' => array(
                    'type' => 'string',
                ),
                'user_name' => array(
                    'type' => 'string',
                ),
                'user_id' => array(
                    'type' => 'string',
                ),
                'state' => array(
                    'type' => 'boolean',
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