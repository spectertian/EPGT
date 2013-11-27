<?php

/**
 * Base class of SpService document.
 */
abstract class BaseSpService extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'channelType' => null,
            'name' => null,
            'serviceId' => null,
            'channelNetworkId' => null,
            'logicNumber' => null,
            'tags' => null,
            'channel_code' => null,
            'channel_logo' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'channelType' => 'ChannelType',
        'name' => 'Name',
        'serviceId' => 'ServiceId',
        'channelNetworkId' => 'ChannelNetworkId',
        'logicNumber' => 'LogicNumber',
        'tags' => 'Tags',
        'channel_code' => 'ChannelCode',
        'channel_logo' => 'ChannelLogo',
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
        return \Mondongo\Container::getForDocumentClass('SpService');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('SpService');
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

        if (isset($data['channelType'])) {
            $this->data['fields']['channelType'] = (int) $data['channelType'];
        }
        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['serviceId'])) {
            $this->data['fields']['serviceId'] = (string) $data['serviceId'];
        }
        if (isset($data['channelNetworkId'])) {
            $this->data['fields']['channelNetworkId'] = (int) $data['channelNetworkId'];
        }
        if (isset($data['logicNumber'])) {
            $this->data['fields']['logicNumber'] = (int) $data['logicNumber'];
        }
        if (isset($data['tags'])) {
            $this->data['fields']['tags'] = $data['tags'];
        }
        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['channel_logo'])) {
            $this->data['fields']['channel_logo'] = (string) $data['channel_logo'];
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
        if (isset($fields['channelType'])) {
            $fields['channelType'] = (int) $fields['channelType'];
        }
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['serviceId'])) {
            $fields['serviceId'] = (string) $fields['serviceId'];
        }
        if (isset($fields['channelNetworkId'])) {
            $fields['channelNetworkId'] = (int) $fields['channelNetworkId'];
        }
        if (isset($fields['logicNumber'])) {
            $fields['logicNumber'] = (int) $fields['logicNumber'];
        }
        if (isset($fields['tags'])) {
            $fields['tags'] = $fields['tags'];
        }
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['channel_logo'])) {
            $fields['channel_logo'] = (string) $fields['channel_logo'];
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
     * Set the "channelType" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelType($value)
    {
        if (!array_key_exists('channelType', $this->fieldsModified)) {
            $this->fieldsModified['channelType'] = $this->data['fields']['channelType'];
        } elseif ($value === $this->fieldsModified['channelType']) {
            unset($this->fieldsModified['channelType']);
        }

        $this->data['fields']['channelType'] = $value;
    }

    /**
     * Returns the "channelType" field.
     *
     * @return mixed The channelType field.
     */
    public function getChannelType()
    {
        return $this->data['fields']['channelType'];
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
     * Set the "serviceId" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setServiceId($value)
    {
        if (!array_key_exists('serviceId', $this->fieldsModified)) {
            $this->fieldsModified['serviceId'] = $this->data['fields']['serviceId'];
        } elseif ($value === $this->fieldsModified['serviceId']) {
            unset($this->fieldsModified['serviceId']);
        }

        $this->data['fields']['serviceId'] = $value;
    }

    /**
     * Returns the "serviceId" field.
     *
     * @return mixed The serviceId field.
     */
    public function getServiceId()
    {
        return $this->data['fields']['serviceId'];
    }

    /**
     * Set the "channelNetworkId" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelNetworkId($value)
    {
        if (!array_key_exists('channelNetworkId', $this->fieldsModified)) {
            $this->fieldsModified['channelNetworkId'] = $this->data['fields']['channelNetworkId'];
        } elseif ($value === $this->fieldsModified['channelNetworkId']) {
            unset($this->fieldsModified['channelNetworkId']);
        }

        $this->data['fields']['channelNetworkId'] = $value;
    }

    /**
     * Returns the "channelNetworkId" field.
     *
     * @return mixed The channelNetworkId field.
     */
    public function getChannelNetworkId()
    {
        return $this->data['fields']['channelNetworkId'];
    }

    /**
     * Set the "logicNumber" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLogicNumber($value)
    {
        if (!array_key_exists('logicNumber', $this->fieldsModified)) {
            $this->fieldsModified['logicNumber'] = $this->data['fields']['logicNumber'];
        } elseif ($value === $this->fieldsModified['logicNumber']) {
            unset($this->fieldsModified['logicNumber']);
        }

        $this->data['fields']['logicNumber'] = $value;
    }

    /**
     * Returns the "logicNumber" field.
     *
     * @return mixed The logicNumber field.
     */
    public function getLogicNumber()
    {
        return $this->data['fields']['logicNumber'];
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
     * Set the "channel_logo" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelLogo($value)
    {
        if (!array_key_exists('channel_logo', $this->fieldsModified)) {
            $this->fieldsModified['channel_logo'] = $this->data['fields']['channel_logo'];
        } elseif ($value === $this->fieldsModified['channel_logo']) {
            unset($this->fieldsModified['channel_logo']);
        }

        $this->data['fields']['channel_logo'] = $value;
    }

    /**
     * Returns the "channel_logo" field.
     *
     * @return mixed The channel_logo field.
     */
    public function getChannelLogo()
    {
        return $this->data['fields']['channel_logo'];
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
        if (isset($array['channelType'])) {
            $this->setChannelType($array['channelType']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['serviceId'])) {
            $this->setServiceId($array['serviceId']);
        }
        if (isset($array['channelNetworkId'])) {
            $this->setChannelNetworkId($array['channelNetworkId']);
        }
        if (isset($array['logicNumber'])) {
            $this->setLogicNumber($array['logicNumber']);
        }
        if (isset($array['tags'])) {
            $this->setTags($array['tags']);
        }
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['channel_logo'])) {
            $this->setChannelLogo($array['channel_logo']);
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

        if (null !== $this->data['fields']['channelType']) {
            $array['channelType'] = $this->data['fields']['channelType'];
        }
        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['serviceId']) {
            $array['serviceId'] = $this->data['fields']['serviceId'];
        }
        if (null !== $this->data['fields']['channelNetworkId']) {
            $array['channelNetworkId'] = $this->data['fields']['channelNetworkId'];
        }
        if (null !== $this->data['fields']['logicNumber']) {
            $array['logicNumber'] = $this->data['fields']['logicNumber'];
        }
        if (null !== $this->data['fields']['tags']) {
            $array['tags'] = $this->data['fields']['tags'];
        }
        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['channel_logo']) {
            $array['channel_logo'] = $this->data['fields']['channel_logo'];
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
                'channelType' => array(
                    'type' => 'integer',
                ),
                'name' => array(
                    'type' => 'string',
                ),
                'serviceId' => array(
                    'type' => 'string',
                ),
                'channelNetworkId' => array(
                    'type' => 'integer',
                ),
                'logicNumber' => array(
                    'type' => 'integer',
                ),
                'tags' => array(
                    'type' => 'raw',
                ),
                'channel_code' => array(
                    'type' => 'string',
                ),
                'channel_logo' => array(
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