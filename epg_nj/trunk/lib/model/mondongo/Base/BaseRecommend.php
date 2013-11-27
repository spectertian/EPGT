<?php

/**
 * Base class of Recommend document.
 */
abstract class BaseRecommend extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'title' => null,
            'is_public' => null,
            'scene' => null,
            'sort' => null,
            'pic' => null,
            'smallpic' => null,
            'desc' => null,
            'url' => null,
            'isdesc_display' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'title' => 'Title',
        'is_public' => 'IsPublic',
        'scene' => 'Scene',
        'sort' => 'Sort',
        'pic' => 'Pic',
        'smallpic' => 'Smallpic',
        'desc' => 'Desc',
        'url' => 'Url',
        'isdesc_display' => 'IsdescDisplay',
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
        return \Mondongo\Container::getForDocumentClass('Recommend');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Recommend');
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

        if (isset($data['title'])) {
            $this->data['fields']['title'] = (string) $data['title'];
        }
        if (isset($data['is_public'])) {
            $this->data['fields']['is_public'] = (bool) $data['is_public'];
        }
        if (isset($data['scene'])) {
            $this->data['fields']['scene'] = (string) $data['scene'];
        }
        if (isset($data['sort'])) {
            $this->data['fields']['sort'] = (int) $data['sort'];
        }
        if (isset($data['pic'])) {
            $this->data['fields']['pic'] = (string) $data['pic'];
        }
        if (isset($data['smallpic'])) {
            $this->data['fields']['smallpic'] = (string) $data['smallpic'];
        }
        if (isset($data['desc'])) {
            $this->data['fields']['desc'] = (string) $data['desc'];
        }
        if (isset($data['url'])) {
            $this->data['fields']['url'] = (string) $data['url'];
        }
        if (isset($data['isdesc_display'])) {
            $this->data['fields']['isdesc_display'] = (bool) $data['isdesc_display'];
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
        if (isset($fields['title'])) {
            $fields['title'] = (string) $fields['title'];
        }
        if (isset($fields['is_public'])) {
            $fields['is_public'] = (bool) $fields['is_public'];
        }
        if (isset($fields['scene'])) {
            $fields['scene'] = (string) $fields['scene'];
        }
        if (isset($fields['sort'])) {
            $fields['sort'] = (int) $fields['sort'];
        }
        if (isset($fields['pic'])) {
            $fields['pic'] = (string) $fields['pic'];
        }
        if (isset($fields['smallpic'])) {
            $fields['smallpic'] = (string) $fields['smallpic'];
        }
        if (isset($fields['desc'])) {
            $fields['desc'] = (string) $fields['desc'];
        }
        if (isset($fields['url'])) {
            $fields['url'] = (string) $fields['url'];
        }
        if (isset($fields['isdesc_display'])) {
            $fields['isdesc_display'] = (bool) $fields['isdesc_display'];
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
     * Set the "scene" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setScene($value)
    {
        if (!array_key_exists('scene', $this->fieldsModified)) {
            $this->fieldsModified['scene'] = $this->data['fields']['scene'];
        } elseif ($value === $this->fieldsModified['scene']) {
            unset($this->fieldsModified['scene']);
        }

        $this->data['fields']['scene'] = $value;
    }

    /**
     * Returns the "scene" field.
     *
     * @return mixed The scene field.
     */
    public function getScene()
    {
        return $this->data['fields']['scene'];
    }

    /**
     * Set the "sort" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSort($value)
    {
        if (!array_key_exists('sort', $this->fieldsModified)) {
            $this->fieldsModified['sort'] = $this->data['fields']['sort'];
        } elseif ($value === $this->fieldsModified['sort']) {
            unset($this->fieldsModified['sort']);
        }

        $this->data['fields']['sort'] = $value;
    }

    /**
     * Returns the "sort" field.
     *
     * @return mixed The sort field.
     */
    public function getSort()
    {
        return $this->data['fields']['sort'];
    }

    /**
     * Set the "pic" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPic($value)
    {
        if (!array_key_exists('pic', $this->fieldsModified)) {
            $this->fieldsModified['pic'] = $this->data['fields']['pic'];
        } elseif ($value === $this->fieldsModified['pic']) {
            unset($this->fieldsModified['pic']);
        }

        $this->data['fields']['pic'] = $value;
    }

    /**
     * Returns the "pic" field.
     *
     * @return mixed The pic field.
     */
    public function getPic()
    {
        return $this->data['fields']['pic'];
    }

    /**
     * Set the "smallpic" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSmallpic($value)
    {
        if (!array_key_exists('smallpic', $this->fieldsModified)) {
            $this->fieldsModified['smallpic'] = $this->data['fields']['smallpic'];
        } elseif ($value === $this->fieldsModified['smallpic']) {
            unset($this->fieldsModified['smallpic']);
        }

        $this->data['fields']['smallpic'] = $value;
    }

    /**
     * Returns the "smallpic" field.
     *
     * @return mixed The smallpic field.
     */
    public function getSmallpic()
    {
        return $this->data['fields']['smallpic'];
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
     * Set the "isdesc_display" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setIsdescDisplay($value)
    {
        if (!array_key_exists('isdesc_display', $this->fieldsModified)) {
            $this->fieldsModified['isdesc_display'] = $this->data['fields']['isdesc_display'];
        } elseif ($value === $this->fieldsModified['isdesc_display']) {
            unset($this->fieldsModified['isdesc_display']);
        }

        $this->data['fields']['isdesc_display'] = $value;
    }

    /**
     * Returns the "isdesc_display" field.
     *
     * @return mixed The isdesc_display field.
     */
    public function getIsdescDisplay()
    {
        return $this->data['fields']['isdesc_display'];
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
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['is_public'])) {
            $this->setIsPublic($array['is_public']);
        }
        if (isset($array['scene'])) {
            $this->setScene($array['scene']);
        }
        if (isset($array['sort'])) {
            $this->setSort($array['sort']);
        }
        if (isset($array['pic'])) {
            $this->setPic($array['pic']);
        }
        if (isset($array['smallpic'])) {
            $this->setSmallpic($array['smallpic']);
        }
        if (isset($array['desc'])) {
            $this->setDesc($array['desc']);
        }
        if (isset($array['url'])) {
            $this->setUrl($array['url']);
        }
        if (isset($array['isdesc_display'])) {
            $this->setIsdescDisplay($array['isdesc_display']);
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

        if (null !== $this->data['fields']['title']) {
            $array['title'] = $this->data['fields']['title'];
        }
        if (null !== $this->data['fields']['is_public']) {
            $array['is_public'] = $this->data['fields']['is_public'];
        }
        if (null !== $this->data['fields']['scene']) {
            $array['scene'] = $this->data['fields']['scene'];
        }
        if (null !== $this->data['fields']['sort']) {
            $array['sort'] = $this->data['fields']['sort'];
        }
        if (null !== $this->data['fields']['pic']) {
            $array['pic'] = $this->data['fields']['pic'];
        }
        if (null !== $this->data['fields']['smallpic']) {
            $array['smallpic'] = $this->data['fields']['smallpic'];
        }
        if (null !== $this->data['fields']['desc']) {
            $array['desc'] = $this->data['fields']['desc'];
        }
        if (null !== $this->data['fields']['url']) {
            $array['url'] = $this->data['fields']['url'];
        }
        if (null !== $this->data['fields']['isdesc_display']) {
            $array['isdesc_display'] = $this->data['fields']['isdesc_display'];
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
                'title' => array(
                    'type' => 'string',
                ),
                'is_public' => array(
                    'type' => 'boolean',
                ),
                'scene' => array(
                    'type' => 'string',
                ),
                'sort' => array(
                    'type' => 'integer',
                ),
                'pic' => array(
                    'type' => 'string',
                ),
                'smallpic' => array(
                    'type' => 'string',
                ),
                'desc' => array(
                    'type' => 'string',
                ),
                'url' => array(
                    'type' => 'string',
                ),
                'isdesc_display' => array(
                    'type' => 'boolean',
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