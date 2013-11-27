<?php

/**
 * Base class of UserBehavior document.
 */
abstract class BaseUserBehavior extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'user_id' => null,
            'user_name' => null,
            'access' => null,
            'values' => null,
            'date' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'user_id' => 'UserId',
        'user_name' => 'UserName',
        'access' => 'Access',
        'values' => 'Values',
        'date' => 'Date',
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
        return \Mondongo\Container::getForDocumentClass('UserBehavior');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('UserBehavior');
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
        if (isset($data['user_name'])) {
            $this->data['fields']['user_name'] = (string) $data['user_name'];
        }
        if (isset($data['access'])) {
            $this->data['fields']['access'] = (string) $data['access'];
        }
        if (isset($data['values'])) {
            $this->data['fields']['values'] = (string) $data['values'];
        }
        if (isset($data['date'])) {
            $date = new \DateTime(); $date->setTimestamp($data['date']->sec); $this->data['fields']['date'] = $date;
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
        if (isset($fields['user_name'])) {
            $fields['user_name'] = (string) $fields['user_name'];
        }
        if (isset($fields['access'])) {
            $fields['access'] = (string) $fields['access'];
        }
        if (isset($fields['values'])) {
            $fields['values'] = (string) $fields['values'];
        }
        if (isset($fields['date'])) {
            if ($fields['date'] instanceof \DateTime) { $fields['date'] = $fields['date']->getTimestamp(); } elseif (is_string($fields['date'])) { $fields['date'] = strtotime($fields['date']); } $fields['date'] = new \MongoDate($fields['date']);
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
     * Set the "access" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAccess($value)
    {
        if (!array_key_exists('access', $this->fieldsModified)) {
            $this->fieldsModified['access'] = $this->data['fields']['access'];
        } elseif ($value === $this->fieldsModified['access']) {
            unset($this->fieldsModified['access']);
        }

        $this->data['fields']['access'] = $value;
    }

    /**
     * Returns the "access" field.
     *
     * @return mixed The access field.
     */
    public function getAccess()
    {
        return $this->data['fields']['access'];
    }

    /**
     * Set the "values" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setValues($value)
    {
        if (!array_key_exists('values', $this->fieldsModified)) {
            $this->fieldsModified['values'] = $this->data['fields']['values'];
        } elseif ($value === $this->fieldsModified['values']) {
            unset($this->fieldsModified['values']);
        }

        $this->data['fields']['values'] = $value;
    }

    /**
     * Returns the "values" field.
     *
     * @return mixed The values field.
     */
    public function getValues()
    {
        return $this->data['fields']['values'];
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
        if (isset($array['user_name'])) {
            $this->setUserName($array['user_name']);
        }
        if (isset($array['access'])) {
            $this->setAccess($array['access']);
        }
        if (isset($array['values'])) {
            $this->setValues($array['values']);
        }
        if (isset($array['date'])) {
            $this->setDate($array['date']);
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
        if (null !== $this->data['fields']['user_name']) {
            $array['user_name'] = $this->data['fields']['user_name'];
        }
        if (null !== $this->data['fields']['access']) {
            $array['access'] = $this->data['fields']['access'];
        }
        if (null !== $this->data['fields']['values']) {
            $array['values'] = $this->data['fields']['values'];
        }
        if (null !== $this->data['fields']['date']) {
            $array['date'] = $this->data['fields']['date'];
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
                'user_name' => array(
                    'type' => 'string',
                ),
                'access' => array(
                    'type' => 'string',
                ),
                'values' => array(
                    'type' => 'string',
                ),
                'date' => array(
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