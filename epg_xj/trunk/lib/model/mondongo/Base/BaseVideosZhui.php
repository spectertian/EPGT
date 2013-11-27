<?php

/**
 * Base class of VideosZhui document.
 */
abstract class BaseVideosZhui extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'wiki_id' => null,
            'wiki_name' => null,
            'total' => null,
            'state' => null,
            'local' => null,
            'source' => null,
            'update_time' => null,
            'success' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'wiki_id' => 'WikiId',
        'wiki_name' => 'WikiName',
        'total' => 'Total',
        'state' => 'State',
        'local' => 'Local',
        'source' => 'Source',
        'update_time' => 'UpdateTime',
        'success' => 'Success',
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
        return \Mondongo\Container::getForDocumentClass('VideosZhui');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('VideosZhui');
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

        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['wiki_name'])) {
            $this->data['fields']['wiki_name'] = (string) $data['wiki_name'];
        }
        if (isset($data['total'])) {
            $this->data['fields']['total'] = (int) $data['total'];
        }
        if (isset($data['state'])) {
            $this->data['fields']['state'] = (int) $data['state'];
        }
        if (isset($data['local'])) {
            $this->data['fields']['local'] = (int) $data['local'];
        }
        if (isset($data['source'])) {
            $this->data['fields']['source'] = $data['source'];
        }
        if (isset($data['update_time'])) {
            $this->data['fields']['update_time'] = (string) $data['update_time'];
        }
        if (isset($data['success'])) {
            $this->data['fields']['success'] = (int) $data['success'];
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
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['wiki_name'])) {
            $fields['wiki_name'] = (string) $fields['wiki_name'];
        }
        if (isset($fields['total'])) {
            $fields['total'] = (int) $fields['total'];
        }
        if (isset($fields['state'])) {
            $fields['state'] = (int) $fields['state'];
        }
        if (isset($fields['local'])) {
            $fields['local'] = (int) $fields['local'];
        }
        if (isset($fields['source'])) {
            $fields['source'] = $fields['source'];
        }
        if (isset($fields['update_time'])) {
            $fields['update_time'] = (string) $fields['update_time'];
        }
        if (isset($fields['success'])) {
            $fields['success'] = (int) $fields['success'];
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
     * Set the "wiki_name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWikiName($value)
    {
        if (!array_key_exists('wiki_name', $this->fieldsModified)) {
            $this->fieldsModified['wiki_name'] = $this->data['fields']['wiki_name'];
        } elseif ($value === $this->fieldsModified['wiki_name']) {
            unset($this->fieldsModified['wiki_name']);
        }

        $this->data['fields']['wiki_name'] = $value;
    }

    /**
     * Returns the "wiki_name" field.
     *
     * @return mixed The wiki_name field.
     */
    public function getWikiName()
    {
        return $this->data['fields']['wiki_name'];
    }

    /**
     * Set the "total" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTotal($value)
    {
        if (!array_key_exists('total', $this->fieldsModified)) {
            $this->fieldsModified['total'] = $this->data['fields']['total'];
        } elseif ($value === $this->fieldsModified['total']) {
            unset($this->fieldsModified['total']);
        }

        $this->data['fields']['total'] = $value;
    }

    /**
     * Returns the "total" field.
     *
     * @return mixed The total field.
     */
    public function getTotal()
    {
        return $this->data['fields']['total'];
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
     * Set the "local" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLocal($value)
    {
        if (!array_key_exists('local', $this->fieldsModified)) {
            $this->fieldsModified['local'] = $this->data['fields']['local'];
        } elseif ($value === $this->fieldsModified['local']) {
            unset($this->fieldsModified['local']);
        }

        $this->data['fields']['local'] = $value;
    }

    /**
     * Returns the "local" field.
     *
     * @return mixed The local field.
     */
    public function getLocal()
    {
        return $this->data['fields']['local'];
    }

    /**
     * Set the "source" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSource($value)
    {
        if (!array_key_exists('source', $this->fieldsModified)) {
            $this->fieldsModified['source'] = $this->data['fields']['source'];
        } elseif ($value === $this->fieldsModified['source']) {
            unset($this->fieldsModified['source']);
        }

        $this->data['fields']['source'] = $value;
    }

    /**
     * Returns the "source" field.
     *
     * @return mixed The source field.
     */
    public function getSource()
    {
        return $this->data['fields']['source'];
    }

    /**
     * Set the "update_time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setUpdateTime($value)
    {
        if (!array_key_exists('update_time', $this->fieldsModified)) {
            $this->fieldsModified['update_time'] = $this->data['fields']['update_time'];
        } elseif ($value === $this->fieldsModified['update_time']) {
            unset($this->fieldsModified['update_time']);
        }

        $this->data['fields']['update_time'] = $value;
    }

    /**
     * Returns the "update_time" field.
     *
     * @return mixed The update_time field.
     */
    public function getUpdateTime()
    {
        return $this->data['fields']['update_time'];
    }

    /**
     * Set the "success" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSuccess($value)
    {
        if (!array_key_exists('success', $this->fieldsModified)) {
            $this->fieldsModified['success'] = $this->data['fields']['success'];
        } elseif ($value === $this->fieldsModified['success']) {
            unset($this->fieldsModified['success']);
        }

        $this->data['fields']['success'] = $value;
    }

    /**
     * Returns the "success" field.
     *
     * @return mixed The success field.
     */
    public function getSuccess()
    {
        return $this->data['fields']['success'];
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
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['wiki_name'])) {
            $this->setWikiName($array['wiki_name']);
        }
        if (isset($array['total'])) {
            $this->setTotal($array['total']);
        }
        if (isset($array['state'])) {
            $this->setState($array['state']);
        }
        if (isset($array['local'])) {
            $this->setLocal($array['local']);
        }
        if (isset($array['source'])) {
            $this->setSource($array['source']);
        }
        if (isset($array['update_time'])) {
            $this->setUpdateTime($array['update_time']);
        }
        if (isset($array['success'])) {
            $this->setSuccess($array['success']);
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

        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['wiki_name']) {
            $array['wiki_name'] = $this->data['fields']['wiki_name'];
        }
        if (null !== $this->data['fields']['total']) {
            $array['total'] = $this->data['fields']['total'];
        }
        if (null !== $this->data['fields']['state']) {
            $array['state'] = $this->data['fields']['state'];
        }
        if (null !== $this->data['fields']['local']) {
            $array['local'] = $this->data['fields']['local'];
        }
        if (null !== $this->data['fields']['source']) {
            $array['source'] = $this->data['fields']['source'];
        }
        if (null !== $this->data['fields']['update_time']) {
            $array['update_time'] = $this->data['fields']['update_time'];
        }
        if (null !== $this->data['fields']['success']) {
            $array['success'] = $this->data['fields']['success'];
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
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'wiki_name' => array(
                    'type' => 'string',
                ),
                'total' => array(
                    'type' => 'integer',
                ),
                'state' => array(
                    'type' => 'integer',
                ),
                'local' => array(
                    'type' => 'integer',
                ),
                'source' => array(
                    'type' => 'raw',
                ),
                'update_time' => array(
                    'type' => 'string',
                ),
                'success' => array(
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