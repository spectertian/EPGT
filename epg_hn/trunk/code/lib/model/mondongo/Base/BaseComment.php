<?php

/**
 * Base class of Comment document.
 */
abstract class BaseComment extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'parent_id' => null,
            'user_id' => null,
            'wiki_id' => null,
            'text' => null,
            'mark' => null,
            'is_publish' => true,
            'type' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(
        'is_publish' => null,
    );


    static protected $dataCamelCaseMap = array(
        'parent_id' => 'ParentId',
        'user_id' => 'UserId',
        'wiki_id' => 'WikiId',
        'text' => 'Text',
        'mark' => 'Mark',
        'is_publish' => 'IsPublish',
        'type' => 'Type',
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
        return \Mondongo\Container::getForDocumentClass('Comment');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Comment');
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

        if (isset($data['parent_id'])) {
            $this->data['fields']['parent_id'] = (string) $data['parent_id'];
        }
        if (isset($data['user_id'])) {
            $this->data['fields']['user_id'] = (string) $data['user_id'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['text'])) {
            $this->data['fields']['text'] = (string) $data['text'];
        }
        if (isset($data['mark'])) {
            $this->data['fields']['mark'] = (int) $data['mark'];
        }
        if (isset($data['is_publish'])) {
            $this->data['fields']['is_publish'] = (bool) $data['is_publish'];
        }
        if (isset($data['type'])) {
            $this->data['fields']['type'] = (string) $data['type'];
        }
        if (isset($data['created_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['created_at']->sec); $this->data['fields']['created_at'] = $date;
        }
        if (isset($data['updated_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['updated_at']->sec); $this->data['fields']['updated_at'] = $date;
        }


        $this->fieldsModified = array();
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
        if (isset($fields['parent_id'])) {
            $fields['parent_id'] = (string) $fields['parent_id'];
        }
        if (isset($fields['user_id'])) {
            $fields['user_id'] = (string) $fields['user_id'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['text'])) {
            $fields['text'] = (string) $fields['text'];
        }
        if (isset($fields['mark'])) {
            $fields['mark'] = (int) $fields['mark'];
        }
        if (isset($fields['is_publish'])) {
            $fields['is_publish'] = (bool) $fields['is_publish'];
        }
        if (isset($fields['type'])) {
            $fields['type'] = (string) $fields['type'];
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
     * Set the "parent_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setParentId($value)
    {
        if (!array_key_exists('parent_id', $this->fieldsModified)) {
            $this->fieldsModified['parent_id'] = $this->data['fields']['parent_id'];
        } elseif ($value === $this->fieldsModified['parent_id']) {
            unset($this->fieldsModified['parent_id']);
        }

        $this->data['fields']['parent_id'] = $value;
    }

    /**
     * Returns the "parent_id" field.
     *
     * @return mixed The parent_id field.
     */
    public function getParentId()
    {
        return $this->data['fields']['parent_id'];
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
     * Set the "text" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setText($value)
    {
        if (!array_key_exists('text', $this->fieldsModified)) {
            $this->fieldsModified['text'] = $this->data['fields']['text'];
        } elseif ($value === $this->fieldsModified['text']) {
            unset($this->fieldsModified['text']);
        }

        $this->data['fields']['text'] = $value;
    }

    /**
     * Returns the "text" field.
     *
     * @return mixed The text field.
     */
    public function getText()
    {
        return $this->data['fields']['text'];
    }

    /**
     * Set the "mark" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setMark($value)
    {
        if (!array_key_exists('mark', $this->fieldsModified)) {
            $this->fieldsModified['mark'] = $this->data['fields']['mark'];
        } elseif ($value === $this->fieldsModified['mark']) {
            unset($this->fieldsModified['mark']);
        }

        $this->data['fields']['mark'] = $value;
    }

    /**
     * Returns the "mark" field.
     *
     * @return mixed The mark field.
     */
    public function getMark()
    {
        return $this->data['fields']['mark'];
    }

    /**
     * Set the "is_publish" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setIsPublish($value)
    {
        if (!array_key_exists('is_publish', $this->fieldsModified)) {
            $this->fieldsModified['is_publish'] = $this->data['fields']['is_publish'];
        } elseif ($value === $this->fieldsModified['is_publish']) {
            unset($this->fieldsModified['is_publish']);
        }

        $this->data['fields']['is_publish'] = $value;
    }

    /**
     * Returns the "is_publish" field.
     *
     * @return mixed The is_publish field.
     */
    public function getIsPublish()
    {
        return $this->data['fields']['is_publish'];
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
        if (isset($array['parent_id'])) {
            $this->setParentId($array['parent_id']);
        }
        if (isset($array['user_id'])) {
            $this->setUserId($array['user_id']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['text'])) {
            $this->setText($array['text']);
        }
        if (isset($array['mark'])) {
            $this->setMark($array['mark']);
        }
        if (isset($array['is_publish'])) {
            $this->setIsPublish($array['is_publish']);
        }
        if (isset($array['type'])) {
            $this->setType($array['type']);
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

        if (null !== $this->data['fields']['parent_id']) {
            $array['parent_id'] = $this->data['fields']['parent_id'];
        }
        if (null !== $this->data['fields']['user_id']) {
            $array['user_id'] = $this->data['fields']['user_id'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['text']) {
            $array['text'] = $this->data['fields']['text'];
        }
        if (null !== $this->data['fields']['mark']) {
            $array['mark'] = $this->data['fields']['mark'];
        }
        if (null !== $this->data['fields']['is_publish']) {
            $array['is_publish'] = $this->data['fields']['is_publish'];
        }
        if (null !== $this->data['fields']['type']) {
            $array['type'] = $this->data['fields']['type'];
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
                'parent_id' => array(
                    'type' => 'string',
                ),
                'user_id' => array(
                    'type' => 'string',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'text' => array(
                    'type' => 'string',
                ),
                'mark' => array(
                    'type' => 'integer',
                ),
                'is_publish' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'type' => array(
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