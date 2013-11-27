<?php

/**
 * Base class of VideoCrawler document.
 */
abstract class BaseVideoCrawler extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'site' => null,
            'title' => null,
            'model' => null,
            'url' => null,
            'wiki_id' => null,
            'state' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'site' => 'Site',
        'title' => 'Title',
        'model' => 'Model',
        'url' => 'Url',
        'wiki_id' => 'WikiId',
        'state' => 'State',
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
        return \Mondongo\Container::getForDocumentClass('VideoCrawler');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('VideoCrawler');
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

        if (isset($data['site'])) {
            $this->data['fields']['site'] = (string) $data['site'];
        }
        if (isset($data['title'])) {
            $this->data['fields']['title'] = (string) $data['title'];
        }
        if (isset($data['model'])) {
            $this->data['fields']['model'] = (string) $data['model'];
        }
        if (isset($data['url'])) {
            $this->data['fields']['url'] = (string) $data['url'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['state'])) {
            $this->data['fields']['state'] = (int) $data['state'];
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
        if (isset($fields['site'])) {
            $fields['site'] = (string) $fields['site'];
        }
        if (isset($fields['title'])) {
            $fields['title'] = (string) $fields['title'];
        }
        if (isset($fields['model'])) {
            $fields['model'] = (string) $fields['model'];
        }
        if (isset($fields['url'])) {
            $fields['url'] = (string) $fields['url'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['state'])) {
            $fields['state'] = (int) $fields['state'];
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
     * Set the "site" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSite($value)
    {
        if (!array_key_exists('site', $this->fieldsModified)) {
            $this->fieldsModified['site'] = $this->data['fields']['site'];
        } elseif ($value === $this->fieldsModified['site']) {
            unset($this->fieldsModified['site']);
        }

        $this->data['fields']['site'] = $value;
    }

    /**
     * Returns the "site" field.
     *
     * @return mixed The site field.
     */
    public function getSite()
    {
        return $this->data['fields']['site'];
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
     * Set the "model" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setModel($value)
    {
        if (!array_key_exists('model', $this->fieldsModified)) {
            $this->fieldsModified['model'] = $this->data['fields']['model'];
        } elseif ($value === $this->fieldsModified['model']) {
            unset($this->fieldsModified['model']);
        }

        $this->data['fields']['model'] = $value;
    }

    /**
     * Returns the "model" field.
     *
     * @return mixed The model field.
     */
    public function getModel()
    {
        return $this->data['fields']['model'];
    }

    /**
     * Set the "url" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setUrl($value)
    {
        if (!array_key_exists('url', $this->fieldsModified)) {
            $this->fieldsModified['url'] = $this->data['fields']['url'];
        } elseif ($value === $this->fieldsModified['url']) {
            unset($this->fieldsModified['url']);
        }

        $this->data['fields']['url'] = $value;
    }

    /**
     * Returns the "url" field.
     *
     * @return mixed The url field.
     */
    public function getUrl()
    {
        return $this->data['fields']['url'];
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
        if (isset($array['site'])) {
            $this->setSite($array['site']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['model'])) {
            $this->setModel($array['model']);
        }
        if (isset($array['url'])) {
            $this->setUrl($array['url']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['state'])) {
            $this->setState($array['state']);
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

        if (null !== $this->data['fields']['site']) {
            $array['site'] = $this->data['fields']['site'];
        }
        if (null !== $this->data['fields']['title']) {
            $array['title'] = $this->data['fields']['title'];
        }
        if (null !== $this->data['fields']['model']) {
            $array['model'] = $this->data['fields']['model'];
        }
        if (null !== $this->data['fields']['url']) {
            $array['url'] = $this->data['fields']['url'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['state']) {
            $array['state'] = $this->data['fields']['state'];
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
                'site' => array(
                    'type' => 'string',
                ),
                'title' => array(
                    'type' => 'string',
                ),
                'model' => array(
                    'type' => 'string',
                ),
                'url' => array(
                    'type' => 'string',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'state' => array(
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