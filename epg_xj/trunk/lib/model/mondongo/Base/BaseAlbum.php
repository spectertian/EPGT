<?php

/**
 * Base class of Album document.
 */
abstract class BaseAlbum extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'name' => null,
            'desc' => null,
            'author' => null,
            'user_id' => null,
            'is_public' => null,
            'rec_num' => null,
            'list' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'name' => 'Name',
        'desc' => 'Desc',
        'author' => 'Author',
        'user_id' => 'UserId',
        'is_public' => 'IsPublic',
        'rec_num' => 'RecNum',
        'list' => 'List',
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
        return \Mondongo\Container::getForDocumentClass('Album');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Album');
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
        if (isset($data['desc'])) {
            $this->data['fields']['desc'] = (string) $data['desc'];
        }
        if (isset($data['author'])) {
            $this->data['fields']['author'] = (string) $data['author'];
        }
        if (isset($data['user_id'])) {
            $this->data['fields']['user_id'] = (string) $data['user_id'];
        }
        if (isset($data['is_public'])) {
            $this->data['fields']['is_public'] = (bool) $data['is_public'];
        }
        if (isset($data['rec_num'])) {
            $this->data['fields']['rec_num'] = (int) $data['rec_num'];
        }
        if (isset($data['list'])) {
            $this->data['fields']['list'] = $data['list'];
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
        if (isset($fields['desc'])) {
            $fields['desc'] = (string) $fields['desc'];
        }
        if (isset($fields['author'])) {
            $fields['author'] = (string) $fields['author'];
        }
        if (isset($fields['user_id'])) {
            $fields['user_id'] = (string) $fields['user_id'];
        }
        if (isset($fields['is_public'])) {
            $fields['is_public'] = (bool) $fields['is_public'];
        }
        if (isset($fields['rec_num'])) {
            $fields['rec_num'] = (int) $fields['rec_num'];
        }
        if (isset($fields['list'])) {
            $fields['list'] = $fields['list'];
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
     * Set the "desc" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDesc($value)
    {
        if (!array_key_exists('desc', $this->fieldsModified)) {
            $this->fieldsModified['desc'] = $this->data['fields']['desc'];
        } elseif ($value === $this->fieldsModified['desc']) {
            unset($this->fieldsModified['desc']);
        }

        $this->data['fields']['desc'] = $value;
    }

    /**
     * Returns the "desc" field.
     *
     * @return mixed The desc field.
     */
    public function getDesc()
    {
        return $this->data['fields']['desc'];
    }

    /**
     * Set the "author" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAuthor($value)
    {
        if (!array_key_exists('author', $this->fieldsModified)) {
            $this->fieldsModified['author'] = $this->data['fields']['author'];
        } elseif ($value === $this->fieldsModified['author']) {
            unset($this->fieldsModified['author']);
        }

        $this->data['fields']['author'] = $value;
    }

    /**
     * Returns the "author" field.
     *
     * @return mixed The author field.
     */
    public function getAuthor()
    {
        return $this->data['fields']['author'];
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
     * Set the "is_public" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setIsPublic($value)
    {
        if (!array_key_exists('is_public', $this->fieldsModified)) {
            $this->fieldsModified['is_public'] = $this->data['fields']['is_public'];
        } elseif ($value === $this->fieldsModified['is_public']) {
            unset($this->fieldsModified['is_public']);
        }

        $this->data['fields']['is_public'] = $value;
    }

    /**
     * Returns the "is_public" field.
     *
     * @return mixed The is_public field.
     */
    public function getIsPublic()
    {
        return $this->data['fields']['is_public'];
    }

    /**
     * Set the "rec_num" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setRecNum($value)
    {
        if (!array_key_exists('rec_num', $this->fieldsModified)) {
            $this->fieldsModified['rec_num'] = $this->data['fields']['rec_num'];
        } elseif ($value === $this->fieldsModified['rec_num']) {
            unset($this->fieldsModified['rec_num']);
        }

        $this->data['fields']['rec_num'] = $value;
    }

    /**
     * Returns the "rec_num" field.
     *
     * @return mixed The rec_num field.
     */
    public function getRecNum()
    {
        return $this->data['fields']['rec_num'];
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
        if (isset($array['desc'])) {
            $this->setDesc($array['desc']);
        }
        if (isset($array['author'])) {
            $this->setAuthor($array['author']);
        }
        if (isset($array['user_id'])) {
            $this->setUserId($array['user_id']);
        }
        if (isset($array['is_public'])) {
            $this->setIsPublic($array['is_public']);
        }
        if (isset($array['rec_num'])) {
            $this->setRecNum($array['rec_num']);
        }
        if (isset($array['list'])) {
            $this->setList($array['list']);
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
        if (null !== $this->data['fields']['desc']) {
            $array['desc'] = $this->data['fields']['desc'];
        }
        if (null !== $this->data['fields']['author']) {
            $array['author'] = $this->data['fields']['author'];
        }
        if (null !== $this->data['fields']['user_id']) {
            $array['user_id'] = $this->data['fields']['user_id'];
        }
        if (null !== $this->data['fields']['is_public']) {
            $array['is_public'] = $this->data['fields']['is_public'];
        }
        if (null !== $this->data['fields']['rec_num']) {
            $array['rec_num'] = $this->data['fields']['rec_num'];
        }
        if (null !== $this->data['fields']['list']) {
            $array['list'] = $this->data['fields']['list'];
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
                'desc' => array(
                    'type' => 'string',
                ),
                'author' => array(
                    'type' => 'string',
                ),
                'user_id' => array(
                    'type' => 'string',
                ),
                'is_public' => array(
                    'type' => 'boolean',
                ),
                'rec_num' => array(
                    'type' => 'integer',
                ),
                'list' => array(
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