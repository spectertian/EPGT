<?php

/**
 * Base class of WordsLog document.
 */
abstract class BaseWordsLog extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'word' => null,
            'reword' => null,
            'sensitive' => null,
            'resensitive' => null,
            'from' => null,
            'from_id' => null,
            'status' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'word' => 'Word',
        'reword' => 'Reword',
        'sensitive' => 'Sensitive',
        'resensitive' => 'Resensitive',
        'from' => 'From',
        'from_id' => 'FromId',
        'status' => 'Status',
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
        return \Mondongo\Container::getForDocumentClass('WordsLog');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('WordsLog');
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

        if (isset($data['word'])) {
            $this->data['fields']['word'] = (string) $data['word'];
        }
        if (isset($data['reword'])) {
            $this->data['fields']['reword'] = (string) $data['reword'];
        }
        if (isset($data['sensitive'])) {
            $this->data['fields']['sensitive'] = (string) $data['sensitive'];
        }
        if (isset($data['resensitive'])) {
            $this->data['fields']['resensitive'] = (string) $data['resensitive'];
        }
        if (isset($data['from'])) {
            $this->data['fields']['from'] = (string) $data['from'];
        }
        if (isset($data['from_id'])) {
            $this->data['fields']['from_id'] = (string) $data['from_id'];
        }
        if (isset($data['status'])) {
            $this->data['fields']['status'] = (int) $data['status'];
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
        if (isset($fields['word'])) {
            $fields['word'] = (string) $fields['word'];
        }
        if (isset($fields['reword'])) {
            $fields['reword'] = (string) $fields['reword'];
        }
        if (isset($fields['sensitive'])) {
            $fields['sensitive'] = (string) $fields['sensitive'];
        }
        if (isset($fields['resensitive'])) {
            $fields['resensitive'] = (string) $fields['resensitive'];
        }
        if (isset($fields['from'])) {
            $fields['from'] = (string) $fields['from'];
        }
        if (isset($fields['from_id'])) {
            $fields['from_id'] = (string) $fields['from_id'];
        }
        if (isset($fields['status'])) {
            $fields['status'] = (int) $fields['status'];
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
     * Set the "word" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWord($value)
    {
        if (!array_key_exists('word', $this->fieldsModified)) {
            $this->fieldsModified['word'] = $this->data['fields']['word'];
        } elseif ($value === $this->fieldsModified['word']) {
            unset($this->fieldsModified['word']);
        }

        $this->data['fields']['word'] = $value;
    }

    /**
     * Returns the "word" field.
     *
     * @return mixed The word field.
     */
    public function getWord()
    {
        return $this->data['fields']['word'];
    }

    /**
     * Set the "reword" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setReword($value)
    {
        if (!array_key_exists('reword', $this->fieldsModified)) {
            $this->fieldsModified['reword'] = $this->data['fields']['reword'];
        } elseif ($value === $this->fieldsModified['reword']) {
            unset($this->fieldsModified['reword']);
        }

        $this->data['fields']['reword'] = $value;
    }

    /**
     * Returns the "reword" field.
     *
     * @return mixed The reword field.
     */
    public function getReword()
    {
        return $this->data['fields']['reword'];
    }

    /**
     * Set the "sensitive" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSensitive($value)
    {
        if (!array_key_exists('sensitive', $this->fieldsModified)) {
            $this->fieldsModified['sensitive'] = $this->data['fields']['sensitive'];
        } elseif ($value === $this->fieldsModified['sensitive']) {
            unset($this->fieldsModified['sensitive']);
        }

        $this->data['fields']['sensitive'] = $value;
    }

    /**
     * Returns the "sensitive" field.
     *
     * @return mixed The sensitive field.
     */
    public function getSensitive()
    {
        return $this->data['fields']['sensitive'];
    }

    /**
     * Set the "resensitive" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setResensitive($value)
    {
        if (!array_key_exists('resensitive', $this->fieldsModified)) {
            $this->fieldsModified['resensitive'] = $this->data['fields']['resensitive'];
        } elseif ($value === $this->fieldsModified['resensitive']) {
            unset($this->fieldsModified['resensitive']);
        }

        $this->data['fields']['resensitive'] = $value;
    }

    /**
     * Returns the "resensitive" field.
     *
     * @return mixed The resensitive field.
     */
    public function getResensitive()
    {
        return $this->data['fields']['resensitive'];
    }

    /**
     * Set the "from" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setFrom($value)
    {
        if (!array_key_exists('from', $this->fieldsModified)) {
            $this->fieldsModified['from'] = $this->data['fields']['from'];
        } elseif ($value === $this->fieldsModified['from']) {
            unset($this->fieldsModified['from']);
        }

        $this->data['fields']['from'] = $value;
    }

    /**
     * Returns the "from" field.
     *
     * @return mixed The from field.
     */
    public function getFrom()
    {
        return $this->data['fields']['from'];
    }

    /**
     * Set the "from_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setFromId($value)
    {
        if (!array_key_exists('from_id', $this->fieldsModified)) {
            $this->fieldsModified['from_id'] = $this->data['fields']['from_id'];
        } elseif ($value === $this->fieldsModified['from_id']) {
            unset($this->fieldsModified['from_id']);
        }

        $this->data['fields']['from_id'] = $value;
    }

    /**
     * Returns the "from_id" field.
     *
     * @return mixed The from_id field.
     */
    public function getFromId()
    {
        return $this->data['fields']['from_id'];
    }

    /**
     * Set the "status" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStatus($value)
    {
        if (!array_key_exists('status', $this->fieldsModified)) {
            $this->fieldsModified['status'] = $this->data['fields']['status'];
        } elseif ($value === $this->fieldsModified['status']) {
            unset($this->fieldsModified['status']);
        }

        $this->data['fields']['status'] = $value;
    }

    /**
     * Returns the "status" field.
     *
     * @return mixed The status field.
     */
    public function getStatus()
    {
        return $this->data['fields']['status'];
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
        if (isset($array['word'])) {
            $this->setWord($array['word']);
        }
        if (isset($array['reword'])) {
            $this->setReword($array['reword']);
        }
        if (isset($array['sensitive'])) {
            $this->setSensitive($array['sensitive']);
        }
        if (isset($array['resensitive'])) {
            $this->setResensitive($array['resensitive']);
        }
        if (isset($array['from'])) {
            $this->setFrom($array['from']);
        }
        if (isset($array['from_id'])) {
            $this->setFromId($array['from_id']);
        }
        if (isset($array['status'])) {
            $this->setStatus($array['status']);
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

        if (null !== $this->data['fields']['word']) {
            $array['word'] = $this->data['fields']['word'];
        }
        if (null !== $this->data['fields']['reword']) {
            $array['reword'] = $this->data['fields']['reword'];
        }
        if (null !== $this->data['fields']['sensitive']) {
            $array['sensitive'] = $this->data['fields']['sensitive'];
        }
        if (null !== $this->data['fields']['resensitive']) {
            $array['resensitive'] = $this->data['fields']['resensitive'];
        }
        if (null !== $this->data['fields']['from']) {
            $array['from'] = $this->data['fields']['from'];
        }
        if (null !== $this->data['fields']['from_id']) {
            $array['from_id'] = $this->data['fields']['from_id'];
        }
        if (null !== $this->data['fields']['status']) {
            $array['status'] = $this->data['fields']['status'];
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
                'word' => array(
                    'type' => 'string',
                ),
                'reword' => array(
                    'type' => 'string',
                ),
                'sensitive' => array(
                    'type' => 'string',
                ),
                'resensitive' => array(
                    'type' => 'string',
                ),
                'from' => array(
                    'type' => 'string',
                ),
                'from_id' => array(
                    'type' => 'string',
                ),
                'status' => array(
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