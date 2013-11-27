<?php

/**
 * Base class of UserShare document.
 */
abstract class BaseUserShare extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'user_id' => null,
            'stype' => null,
            'sname' => null,
            'accecss_token' => null,
            'accecss_token_secret' => null,
            'userinfo' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'user_id' => 'UserId',
        'stype' => 'Stype',
        'sname' => 'Sname',
        'accecss_token' => 'AccecssToken',
        'accecss_token_secret' => 'AccecssTokenSecret',
        'userinfo' => 'Userinfo',
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
        return \Mondongo\Container::getForDocumentClass('UserShare');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('UserShare');
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
        if (isset($data['stype'])) {
            $this->data['fields']['stype'] = (int) $data['stype'];
        }
        if (isset($data['sname'])) {
            $this->data['fields']['sname'] = (string) $data['sname'];
        }
        if (isset($data['accecss_token'])) {
            $this->data['fields']['accecss_token'] = (string) $data['accecss_token'];
        }
        if (isset($data['accecss_token_secret'])) {
            $this->data['fields']['accecss_token_secret'] = (string) $data['accecss_token_secret'];
        }
        if (isset($data['userinfo'])) {
            $this->data['fields']['userinfo'] = (string) $data['userinfo'];
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
        if (isset($fields['stype'])) {
            $fields['stype'] = (int) $fields['stype'];
        }
        if (isset($fields['sname'])) {
            $fields['sname'] = (string) $fields['sname'];
        }
        if (isset($fields['accecss_token'])) {
            $fields['accecss_token'] = (string) $fields['accecss_token'];
        }
        if (isset($fields['accecss_token_secret'])) {
            $fields['accecss_token_secret'] = (string) $fields['accecss_token_secret'];
        }
        if (isset($fields['userinfo'])) {
            $fields['userinfo'] = (string) $fields['userinfo'];
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
     * Set the "stype" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStype($value)
    {
        if (!array_key_exists('stype', $this->fieldsModified)) {
            $this->fieldsModified['stype'] = $this->data['fields']['stype'];
        } elseif ($value === $this->fieldsModified['stype']) {
            unset($this->fieldsModified['stype']);
        }

        $this->data['fields']['stype'] = $value;
    }

    /**
     * Returns the "stype" field.
     *
     * @return mixed The stype field.
     */
    public function getStype()
    {
        return $this->data['fields']['stype'];
    }

    /**
     * Set the "sname" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSname($value)
    {
        if (!array_key_exists('sname', $this->fieldsModified)) {
            $this->fieldsModified['sname'] = $this->data['fields']['sname'];
        } elseif ($value === $this->fieldsModified['sname']) {
            unset($this->fieldsModified['sname']);
        }

        $this->data['fields']['sname'] = $value;
    }

    /**
     * Returns the "sname" field.
     *
     * @return mixed The sname field.
     */
    public function getSname()
    {
        return $this->data['fields']['sname'];
    }

    /**
     * Set the "accecss_token" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAccecssToken($value)
    {
        if (!array_key_exists('accecss_token', $this->fieldsModified)) {
            $this->fieldsModified['accecss_token'] = $this->data['fields']['accecss_token'];
        } elseif ($value === $this->fieldsModified['accecss_token']) {
            unset($this->fieldsModified['accecss_token']);
        }

        $this->data['fields']['accecss_token'] = $value;
    }

    /**
     * Returns the "accecss_token" field.
     *
     * @return mixed The accecss_token field.
     */
    public function getAccecssToken()
    {
        return $this->data['fields']['accecss_token'];
    }

    /**
     * Set the "accecss_token_secret" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAccecssTokenSecret($value)
    {
        if (!array_key_exists('accecss_token_secret', $this->fieldsModified)) {
            $this->fieldsModified['accecss_token_secret'] = $this->data['fields']['accecss_token_secret'];
        } elseif ($value === $this->fieldsModified['accecss_token_secret']) {
            unset($this->fieldsModified['accecss_token_secret']);
        }

        $this->data['fields']['accecss_token_secret'] = $value;
    }

    /**
     * Returns the "accecss_token_secret" field.
     *
     * @return mixed The accecss_token_secret field.
     */
    public function getAccecssTokenSecret()
    {
        return $this->data['fields']['accecss_token_secret'];
    }

    /**
     * Set the "userinfo" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setUserinfo($value)
    {
        if (!array_key_exists('userinfo', $this->fieldsModified)) {
            $this->fieldsModified['userinfo'] = $this->data['fields']['userinfo'];
        } elseif ($value === $this->fieldsModified['userinfo']) {
            unset($this->fieldsModified['userinfo']);
        }

        $this->data['fields']['userinfo'] = $value;
    }

    /**
     * Returns the "userinfo" field.
     *
     * @return mixed The userinfo field.
     */
    public function getUserinfo()
    {
        return $this->data['fields']['userinfo'];
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
        if (isset($array['stype'])) {
            $this->setStype($array['stype']);
        }
        if (isset($array['sname'])) {
            $this->setSname($array['sname']);
        }
        if (isset($array['accecss_token'])) {
            $this->setAccecssToken($array['accecss_token']);
        }
        if (isset($array['accecss_token_secret'])) {
            $this->setAccecssTokenSecret($array['accecss_token_secret']);
        }
        if (isset($array['userinfo'])) {
            $this->setUserinfo($array['userinfo']);
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
        if (null !== $this->data['fields']['stype']) {
            $array['stype'] = $this->data['fields']['stype'];
        }
        if (null !== $this->data['fields']['sname']) {
            $array['sname'] = $this->data['fields']['sname'];
        }
        if (null !== $this->data['fields']['accecss_token']) {
            $array['accecss_token'] = $this->data['fields']['accecss_token'];
        }
        if (null !== $this->data['fields']['accecss_token_secret']) {
            $array['accecss_token_secret'] = $this->data['fields']['accecss_token_secret'];
        }
        if (null !== $this->data['fields']['userinfo']) {
            $array['userinfo'] = $this->data['fields']['userinfo'];
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
                'stype' => array(
                    'type' => 'integer',
                ),
                'sname' => array(
                    'type' => 'string',
                ),
                'accecss_token' => array(
                    'type' => 'string',
                ),
                'accecss_token_secret' => array(
                    'type' => 'string',
                ),
                'userinfo' => array(
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