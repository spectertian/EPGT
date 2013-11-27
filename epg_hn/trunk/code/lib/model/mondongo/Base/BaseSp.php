<?php

/**
 * Base class of Sp document.
 */
abstract class BaseSp extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'signal' => null,
            'name' => null,
            'remark' => null,
            'logo' => null,
            'type' => null,
            'channels' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'signal' => 'Signal',
        'name' => 'Name',
        'remark' => 'Remark',
        'logo' => 'Logo',
        'type' => 'Type',
        'channels' => 'Channels',
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
        return \Mondongo\Container::getForDocumentClass('Sp');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Sp');
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

        if (isset($data['signal'])) {
            $this->data['fields']['signal'] = (string) $data['signal'];
        }
        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['remark'])) {
            $this->data['fields']['remark'] = (string) $data['remark'];
        }
        if (isset($data['logo'])) {
            $this->data['fields']['logo'] = (string) $data['logo'];
        }
        if (isset($data['type'])) {
            $this->data['fields']['type'] = (string) $data['type'];
        }
        if (isset($data['channels'])) {
            $this->data['fields']['channels'] = $data['channels'];
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
        if (isset($fields['signal'])) {
            $fields['signal'] = (string) $fields['signal'];
        }
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['remark'])) {
            $fields['remark'] = (string) $fields['remark'];
        }
        if (isset($fields['logo'])) {
            $fields['logo'] = (string) $fields['logo'];
        }
        if (isset($fields['type'])) {
            $fields['type'] = (string) $fields['type'];
        }
        if (isset($fields['channels'])) {
            $fields['channels'] = $fields['channels'];
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
     * Set the "signal" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSignal($value)
    {
        if (!array_key_exists('signal', $this->fieldsModified)) {
            $this->fieldsModified['signal'] = $this->data['fields']['signal'];
        } elseif ($value === $this->fieldsModified['signal']) {
            unset($this->fieldsModified['signal']);
        }

        $this->data['fields']['signal'] = $value;
    }

    /**
     * Returns the "signal" field.
     *
     * @return mixed The signal field.
     */
    public function getSignal()
    {
        return $this->data['fields']['signal'];
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
     * Set the "remark" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setRemark($value)
    {
        if (!array_key_exists('remark', $this->fieldsModified)) {
            $this->fieldsModified['remark'] = $this->data['fields']['remark'];
        } elseif ($value === $this->fieldsModified['remark']) {
            unset($this->fieldsModified['remark']);
        }

        $this->data['fields']['remark'] = $value;
    }

    /**
     * Returns the "remark" field.
     *
     * @return mixed The remark field.
     */
    public function getRemark()
    {
        return $this->data['fields']['remark'];
    }

    /**
     * Set the "logo" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLogo($value)
    {
        if (!array_key_exists('logo', $this->fieldsModified)) {
            $this->fieldsModified['logo'] = $this->data['fields']['logo'];
        } elseif ($value === $this->fieldsModified['logo']) {
            unset($this->fieldsModified['logo']);
        }

        $this->data['fields']['logo'] = $value;
    }

    /**
     * Returns the "logo" field.
     *
     * @return mixed The logo field.
     */
    public function getLogo()
    {
        return $this->data['fields']['logo'];
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
     * Set the "channels" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannels($value)
    {
        if (!array_key_exists('channels', $this->fieldsModified)) {
            $this->fieldsModified['channels'] = $this->data['fields']['channels'];
        } elseif ($value === $this->fieldsModified['channels']) {
            unset($this->fieldsModified['channels']);
        }

        $this->data['fields']['channels'] = $value;
    }

    /**
     * Returns the "channels" field.
     *
     * @return mixed The channels field.
     */
    public function getChannels()
    {
        return $this->data['fields']['channels'];
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
        if (isset($array['signal'])) {
            $this->setSignal($array['signal']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['remark'])) {
            $this->setRemark($array['remark']);
        }
        if (isset($array['logo'])) {
            $this->setLogo($array['logo']);
        }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
        if (isset($array['channels'])) {
            $this->setChannels($array['channels']);
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

        if (null !== $this->data['fields']['signal']) {
            $array['signal'] = $this->data['fields']['signal'];
        }
        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['remark']) {
            $array['remark'] = $this->data['fields']['remark'];
        }
        if (null !== $this->data['fields']['logo']) {
            $array['logo'] = $this->data['fields']['logo'];
        }
        if (null !== $this->data['fields']['type']) {
            $array['type'] = $this->data['fields']['type'];
        }
        if (null !== $this->data['fields']['channels']) {
            $array['channels'] = $this->data['fields']['channels'];
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
                'signal' => array(
                    'type' => 'string',
                ),
                'name' => array(
                    'type' => 'string',
                ),
                'remark' => array(
                    'type' => 'string',
                ),
                'logo' => array(
                    'type' => 'string',
                ),
                'type' => array(
                    'type' => 'string',
                ),
                'channels' => array(
                    'type' => 'raw',
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