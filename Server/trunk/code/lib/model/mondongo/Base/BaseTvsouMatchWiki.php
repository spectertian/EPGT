<?php

/**
 * Base class of TvsouMatchWiki document.
 */
abstract class BaseTvsouMatchWiki extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'tvsou_id' => null,
            'tvsou_title' => null,
            'wiki_id' => null,
            'wiki_title' => null,
            'compare' => null,
            'author' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'tvsou_id' => 'TvsouId',
        'tvsou_title' => 'TvsouTitle',
        'wiki_id' => 'WikiId',
        'wiki_title' => 'WikiTitle',
        'compare' => 'Compare',
        'author' => 'Author',
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
        return \Mondongo\Container::getForDocumentClass('TvsouMatchWiki');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('TvsouMatchWiki');
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

        if (isset($data['tvsou_id'])) {
            $this->data['fields']['tvsou_id'] = (string) $data['tvsou_id'];
        }
        if (isset($data['tvsou_title'])) {
            $this->data['fields']['tvsou_title'] = (string) $data['tvsou_title'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['wiki_title'])) {
            $this->data['fields']['wiki_title'] = (string) $data['wiki_title'];
        }
        if (isset($data['compare'])) {
            $this->data['fields']['compare'] = (bool) $data['compare'];
        }
        if (isset($data['author'])) {
            $this->data['fields']['author'] = $data['author'];
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
        if (isset($fields['tvsou_id'])) {
            $fields['tvsou_id'] = (string) $fields['tvsou_id'];
        }
        if (isset($fields['tvsou_title'])) {
            $fields['tvsou_title'] = (string) $fields['tvsou_title'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['wiki_title'])) {
            $fields['wiki_title'] = (string) $fields['wiki_title'];
        }
        if (isset($fields['compare'])) {
            $fields['compare'] = (bool) $fields['compare'];
        }
        if (isset($fields['author'])) {
            $fields['author'] = $fields['author'];
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
     * Set the "tvsou_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTvsouId($value)
    {
        if (!array_key_exists('tvsou_id', $this->fieldsModified)) {
            $this->fieldsModified['tvsou_id'] = $this->data['fields']['tvsou_id'];
        } elseif ($value === $this->fieldsModified['tvsou_id']) {
            unset($this->fieldsModified['tvsou_id']);
        }

        $this->data['fields']['tvsou_id'] = $value;
    }

    /**
     * Returns the "tvsou_id" field.
     *
     * @return mixed The tvsou_id field.
     */
    public function getTvsouId()
    {
        return $this->data['fields']['tvsou_id'];
    }

    /**
     * Set the "tvsou_title" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTvsouTitle($value)
    {
        if (!array_key_exists('tvsou_title', $this->fieldsModified)) {
            $this->fieldsModified['tvsou_title'] = $this->data['fields']['tvsou_title'];
        } elseif ($value === $this->fieldsModified['tvsou_title']) {
            unset($this->fieldsModified['tvsou_title']);
        }

        $this->data['fields']['tvsou_title'] = $value;
    }

    /**
     * Returns the "tvsou_title" field.
     *
     * @return mixed The tvsou_title field.
     */
    public function getTvsouTitle()
    {
        return $this->data['fields']['tvsou_title'];
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
     * Set the "wiki_title" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWikiTitle($value)
    {
        if (!array_key_exists('wiki_title', $this->fieldsModified)) {
            $this->fieldsModified['wiki_title'] = $this->data['fields']['wiki_title'];
        } elseif ($value === $this->fieldsModified['wiki_title']) {
            unset($this->fieldsModified['wiki_title']);
        }

        $this->data['fields']['wiki_title'] = $value;
    }

    /**
     * Returns the "wiki_title" field.
     *
     * @return mixed The wiki_title field.
     */
    public function getWikiTitle()
    {
        return $this->data['fields']['wiki_title'];
    }

    /**
     * Set the "compare" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCompare($value)
    {
        if (!array_key_exists('compare', $this->fieldsModified)) {
            $this->fieldsModified['compare'] = $this->data['fields']['compare'];
        } elseif ($value === $this->fieldsModified['compare']) {
            unset($this->fieldsModified['compare']);
        }

        $this->data['fields']['compare'] = $value;
    }

    /**
     * Returns the "compare" field.
     *
     * @return mixed The compare field.
     */
    public function getCompare()
    {
        return $this->data['fields']['compare'];
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
        if (isset($array['tvsou_id'])) {
            $this->setTvsouId($array['tvsou_id']);
        }
        if (isset($array['tvsou_title'])) {
            $this->setTvsouTitle($array['tvsou_title']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['wiki_title'])) {
            $this->setWikiTitle($array['wiki_title']);
        }
        if (isset($array['compare'])) {
            $this->setCompare($array['compare']);
        }
        if (isset($array['author'])) {
            $this->setAuthor($array['author']);
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

        if (null !== $this->data['fields']['tvsou_id']) {
            $array['tvsou_id'] = $this->data['fields']['tvsou_id'];
        }
        if (null !== $this->data['fields']['tvsou_title']) {
            $array['tvsou_title'] = $this->data['fields']['tvsou_title'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['wiki_title']) {
            $array['wiki_title'] = $this->data['fields']['wiki_title'];
        }
        if (null !== $this->data['fields']['compare']) {
            $array['compare'] = $this->data['fields']['compare'];
        }
        if (null !== $this->data['fields']['author']) {
            $array['author'] = $this->data['fields']['author'];
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
                'tvsou_id' => array(
                    'type' => 'string',
                ),
                'tvsou_title' => array(
                    'type' => 'string',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'wiki_title' => array(
                    'type' => 'string',
                ),
                'compare' => array(
                    'type' => 'boolean',
                ),
                'author' => array(
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