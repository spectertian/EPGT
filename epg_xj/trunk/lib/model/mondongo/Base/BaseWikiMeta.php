<?php

/**
 * Base class of WikiMeta document.
 */
abstract class BaseWikiMeta extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'wiki_id' => null,
            'title' => null,
            'content' => null,
            'html_cache' => null,
            'mark' => null,
            'screenshots' => null,
            'guests' => null,
            'year' => null,
            'month' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'wiki_id' => 'WikiId',
        'title' => 'Title',
        'content' => 'Content',
        'html_cache' => 'HtmlCache',
        'mark' => 'Mark',
        'screenshots' => 'Screenshots',
        'guests' => 'Guests',
        'year' => 'Year',
        'month' => 'Month',
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
        return \Mondongo\Container::getForDocumentClass('WikiMeta');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('WikiMeta');
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
        if (isset($data['title'])) {
            $this->data['fields']['title'] = (string) $data['title'];
        }
        if (isset($data['content'])) {
            $this->data['fields']['content'] = (string) $data['content'];
        }
        if (isset($data['html_cache'])) {
            $this->data['fields']['html_cache'] = (string) $data['html_cache'];
        }
        if (isset($data['mark'])) {
            $this->data['fields']['mark'] = (int) $data['mark'];
        }
        if (isset($data['screenshots'])) {
            $this->data['fields']['screenshots'] = $data['screenshots'];
        }
        if (isset($data['guests'])) {
            $this->data['fields']['guests'] = $data['guests'];
        }
        if (isset($data['year'])) {
            $this->data['fields']['year'] = (string) $data['year'];
        }
        if (isset($data['month'])) {
            $this->data['fields']['month'] = (string) $data['month'];
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
        if (isset($fields['title'])) {
            $fields['title'] = (string) $fields['title'];
        }
        if (isset($fields['content'])) {
            $fields['content'] = (string) $fields['content'];
        }
        if (isset($fields['html_cache'])) {
            $fields['html_cache'] = (string) $fields['html_cache'];
        }
        if (isset($fields['mark'])) {
            $fields['mark'] = (int) $fields['mark'];
        }
        if (isset($fields['screenshots'])) {
            $fields['screenshots'] = $fields['screenshots'];
        }
        if (isset($fields['guests'])) {
            $fields['guests'] = $fields['guests'];
        }
        if (isset($fields['year'])) {
            $fields['year'] = (string) $fields['year'];
        }
        if (isset($fields['month'])) {
            $fields['month'] = (string) $fields['month'];
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
     * Set the "title" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTitle($value)
    {
        if (!array_key_exists('title', $this->fieldsModified)) {
            $this->fieldsModified['title'] = $this->data['fields']['title'];
        } elseif ($value === $this->fieldsModified['title']) {
            unset($this->fieldsModified['title']);
        }

        $this->data['fields']['title'] = $value;
    }

    /**
     * Returns the "title" field.
     *
     * @return mixed The title field.
     */
    public function getTitle()
    {
        return $this->data['fields']['title'];
    }

    /**
     * Set the "content" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setContent($value)
    {
        if (!array_key_exists('content', $this->fieldsModified)) {
            $this->fieldsModified['content'] = $this->data['fields']['content'];
        } elseif ($value === $this->fieldsModified['content']) {
            unset($this->fieldsModified['content']);
        }

        $this->data['fields']['content'] = $value;
    }

    /**
     * Returns the "content" field.
     *
     * @return mixed The content field.
     */
    public function getContent()
    {
        return $this->data['fields']['content'];
    }

    /**
     * Set the "html_cache" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setHtmlCache($value)
    {
        if (!array_key_exists('html_cache', $this->fieldsModified)) {
            $this->fieldsModified['html_cache'] = $this->data['fields']['html_cache'];
        } elseif ($value === $this->fieldsModified['html_cache']) {
            unset($this->fieldsModified['html_cache']);
        }

        $this->data['fields']['html_cache'] = $value;
    }

    /**
     * Returns the "html_cache" field.
     *
     * @return mixed The html_cache field.
     */
    public function getHtmlCache()
    {
        return $this->data['fields']['html_cache'];
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
     * Set the "screenshots" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setScreenshots($value)
    {
        if (!array_key_exists('screenshots', $this->fieldsModified)) {
            $this->fieldsModified['screenshots'] = $this->data['fields']['screenshots'];
        } elseif ($value === $this->fieldsModified['screenshots']) {
            unset($this->fieldsModified['screenshots']);
        }

        $this->data['fields']['screenshots'] = $value;
    }

    /**
     * Returns the "screenshots" field.
     *
     * @return mixed The screenshots field.
     */
    public function getScreenshots()
    {
        return $this->data['fields']['screenshots'];
    }

    /**
     * Set the "guests" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setGuests($value)
    {
        if (!array_key_exists('guests', $this->fieldsModified)) {
            $this->fieldsModified['guests'] = $this->data['fields']['guests'];
        } elseif ($value === $this->fieldsModified['guests']) {
            unset($this->fieldsModified['guests']);
        }

        $this->data['fields']['guests'] = $value;
    }

    /**
     * Returns the "guests" field.
     *
     * @return mixed The guests field.
     */
    public function getGuests()
    {
        return $this->data['fields']['guests'];
    }

    /**
     * Set the "year" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setYear($value)
    {
        if (!array_key_exists('year', $this->fieldsModified)) {
            $this->fieldsModified['year'] = $this->data['fields']['year'];
        } elseif ($value === $this->fieldsModified['year']) {
            unset($this->fieldsModified['year']);
        }

        $this->data['fields']['year'] = $value;
    }

    /**
     * Returns the "year" field.
     *
     * @return mixed The year field.
     */
    public function getYear()
    {
        return $this->data['fields']['year'];
    }

    /**
     * Set the "month" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setMonth($value)
    {
        if (!array_key_exists('month', $this->fieldsModified)) {
            $this->fieldsModified['month'] = $this->data['fields']['month'];
        } elseif ($value === $this->fieldsModified['month']) {
            unset($this->fieldsModified['month']);
        }

        $this->data['fields']['month'] = $value;
    }

    /**
     * Returns the "month" field.
     *
     * @return mixed The month field.
     */
    public function getMonth()
    {
        return $this->data['fields']['month'];
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
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['content'])) {
            $this->setContent($array['content']);
        }
        if (isset($array['html_cache'])) {
            $this->setHtmlCache($array['html_cache']);
        }
        if (isset($array['mark'])) {
            $this->setMark($array['mark']);
        }
        if (isset($array['screenshots'])) {
            $this->setScreenshots($array['screenshots']);
        }
        if (isset($array['guests'])) {
            $this->setGuests($array['guests']);
        }
        if (isset($array['year'])) {
            $this->setYear($array['year']);
        }
        if (isset($array['month'])) {
            $this->setMonth($array['month']);
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
        if (null !== $this->data['fields']['title']) {
            $array['title'] = $this->data['fields']['title'];
        }
        if (null !== $this->data['fields']['content']) {
            $array['content'] = $this->data['fields']['content'];
        }
        if (null !== $this->data['fields']['html_cache']) {
            $array['html_cache'] = $this->data['fields']['html_cache'];
        }
        if (null !== $this->data['fields']['mark']) {
            $array['mark'] = $this->data['fields']['mark'];
        }
        if (null !== $this->data['fields']['screenshots']) {
            $array['screenshots'] = $this->data['fields']['screenshots'];
        }
        if (null !== $this->data['fields']['guests']) {
            $array['guests'] = $this->data['fields']['guests'];
        }
        if (null !== $this->data['fields']['year']) {
            $array['year'] = $this->data['fields']['year'];
        }
        if (null !== $this->data['fields']['month']) {
            $array['month'] = $this->data['fields']['month'];
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
                'title' => array(
                    'type' => 'string',
                ),
                'content' => array(
                    'type' => 'string',
                ),
                'html_cache' => array(
                    'type' => 'string',
                ),
                'mark' => array(
                    'type' => 'integer',
                ),
                'screenshots' => array(
                    'type' => 'raw',
                ),
                'guests' => array(
                    'type' => 'raw',
                ),
                'year' => array(
                    'type' => 'string',
                ),
                'month' => array(
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