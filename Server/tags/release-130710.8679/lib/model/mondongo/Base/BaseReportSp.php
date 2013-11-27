<?php

/**
 * Base class of ReportSp document.
 */
abstract class BaseReportSp extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'newwork_id' => null,
            'newwork_name' => null,
            'version' => null,
            'city' => null,
            'num' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'newwork_id' => 'NewworkId',
        'newwork_name' => 'NewworkName',
        'version' => 'Version',
        'city' => 'City',
        'num' => 'Num',
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
        return \Mondongo\Container::getForDocumentClass('ReportSp');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('ReportSp');
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

        if (isset($data['newwork_id'])) {
            $this->data['fields']['newwork_id'] = (string) $data['newwork_id'];
        }
        if (isset($data['newwork_name'])) {
            $this->data['fields']['newwork_name'] = (string) $data['newwork_name'];
        }
        if (isset($data['version'])) {
            $this->data['fields']['version'] = (string) $data['version'];
        }
        if (isset($data['city'])) {
            $this->data['fields']['city'] = (string) $data['city'];
        }
        if (isset($data['num'])) {
            $this->data['fields']['num'] = (int) $data['num'];
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
        if (isset($fields['newwork_id'])) {
            $fields['newwork_id'] = (string) $fields['newwork_id'];
        }
        if (isset($fields['newwork_name'])) {
            $fields['newwork_name'] = (string) $fields['newwork_name'];
        }
        if (isset($fields['version'])) {
            $fields['version'] = (string) $fields['version'];
        }
        if (isset($fields['city'])) {
            $fields['city'] = (string) $fields['city'];
        }
        if (isset($fields['num'])) {
            $fields['num'] = (int) $fields['num'];
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
     * Set the "newwork_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setNewworkId($value)
    {
        if (!array_key_exists('newwork_id', $this->fieldsModified)) {
            $this->fieldsModified['newwork_id'] = $this->data['fields']['newwork_id'];
        } elseif ($value === $this->fieldsModified['newwork_id']) {
            unset($this->fieldsModified['newwork_id']);
        }

        $this->data['fields']['newwork_id'] = $value;
    }

    /**
     * Returns the "newwork_id" field.
     *
     * @return mixed The newwork_id field.
     */
    public function getNewworkId()
    {
        return $this->data['fields']['newwork_id'];
    }

    /**
     * Set the "newwork_name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setNewworkName($value)
    {
        if (!array_key_exists('newwork_name', $this->fieldsModified)) {
            $this->fieldsModified['newwork_name'] = $this->data['fields']['newwork_name'];
        } elseif ($value === $this->fieldsModified['newwork_name']) {
            unset($this->fieldsModified['newwork_name']);
        }

        $this->data['fields']['newwork_name'] = $value;
    }

    /**
     * Returns the "newwork_name" field.
     *
     * @return mixed The newwork_name field.
     */
    public function getNewworkName()
    {
        return $this->data['fields']['newwork_name'];
    }

    /**
     * Set the "version" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setVersion($value)
    {
        if (!array_key_exists('version', $this->fieldsModified)) {
            $this->fieldsModified['version'] = $this->data['fields']['version'];
        } elseif ($value === $this->fieldsModified['version']) {
            unset($this->fieldsModified['version']);
        }

        $this->data['fields']['version'] = $value;
    }

    /**
     * Returns the "version" field.
     *
     * @return mixed The version field.
     */
    public function getVersion()
    {
        return $this->data['fields']['version'];
    }

    /**
     * Set the "city" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCity($value)
    {
        if (!array_key_exists('city', $this->fieldsModified)) {
            $this->fieldsModified['city'] = $this->data['fields']['city'];
        } elseif ($value === $this->fieldsModified['city']) {
            unset($this->fieldsModified['city']);
        }

        $this->data['fields']['city'] = $value;
    }

    /**
     * Returns the "city" field.
     *
     * @return mixed The city field.
     */
    public function getCity()
    {
        return $this->data['fields']['city'];
    }

    /**
     * Set the "num" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setNum($value)
    {
        if (!array_key_exists('num', $this->fieldsModified)) {
            $this->fieldsModified['num'] = $this->data['fields']['num'];
        } elseif ($value === $this->fieldsModified['num']) {
            unset($this->fieldsModified['num']);
        }

        $this->data['fields']['num'] = $value;
    }

    /**
     * Returns the "num" field.
     *
     * @return mixed The num field.
     */
    public function getNum()
    {
        return $this->data['fields']['num'];
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
        if (isset($array['newwork_id'])) {
            $this->setNewworkId($array['newwork_id']);
        }
        if (isset($array['newwork_name'])) {
            $this->setNewworkName($array['newwork_name']);
        }
        if (isset($array['version'])) {
            $this->setVersion($array['version']);
        }
        if (isset($array['city'])) {
            $this->setCity($array['city']);
        }
        if (isset($array['num'])) {
            $this->setNum($array['num']);
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

        if (null !== $this->data['fields']['newwork_id']) {
            $array['newwork_id'] = $this->data['fields']['newwork_id'];
        }
        if (null !== $this->data['fields']['newwork_name']) {
            $array['newwork_name'] = $this->data['fields']['newwork_name'];
        }
        if (null !== $this->data['fields']['version']) {
            $array['version'] = $this->data['fields']['version'];
        }
        if (null !== $this->data['fields']['city']) {
            $array['city'] = $this->data['fields']['city'];
        }
        if (null !== $this->data['fields']['num']) {
            $array['num'] = $this->data['fields']['num'];
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
                'newwork_id' => array(
                    'type' => 'string',
                ),
                'newwork_name' => array(
                    'type' => 'string',
                ),
                'version' => array(
                    'type' => 'string',
                ),
                'city' => array(
                    'type' => 'string',
                ),
                'num' => array(
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