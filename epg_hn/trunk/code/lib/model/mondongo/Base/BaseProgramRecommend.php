<?php

/**
 * Base class of ProgramRecommend document.
 */
abstract class BaseProgramRecommend extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'tv_station_id' => null,
            'channel_id' => null,
            'wiki_id' => null,
            'title' => null,
            'img' => null,
            'play_time' => null,
            'content' => null,
            'sort' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'tv_station_id' => 'TvStationId',
        'channel_id' => 'ChannelId',
        'wiki_id' => 'WikiId',
        'title' => 'Title',
        'img' => 'Img',
        'play_time' => 'PlayTime',
        'content' => 'Content',
        'sort' => 'Sort',
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
        return \Mondongo\Container::getForDocumentClass('ProgramRecommend');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('ProgramRecommend');
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

        if (isset($data['tv_station_id'])) {
            $this->data['fields']['tv_station_id'] = (int) $data['tv_station_id'];
        }
        if (isset($data['channel_id'])) {
            $this->data['fields']['channel_id'] = (int) $data['channel_id'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['title'])) {
            $this->data['fields']['title'] = (string) $data['title'];
        }
        if (isset($data['img'])) {
            $this->data['fields']['img'] = (string) $data['img'];
        }
        if (isset($data['play_time'])) {
            $this->data['fields']['play_time'] = (string) $data['play_time'];
        }
        if (isset($data['content'])) {
            $this->data['fields']['content'] = (string) $data['content'];
        }
        if (isset($data['sort'])) {
            $this->data['fields']['sort'] = (int) $data['sort'];
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
        if (isset($fields['tv_station_id'])) {
            $fields['tv_station_id'] = (int) $fields['tv_station_id'];
        }
        if (isset($fields['channel_id'])) {
            $fields['channel_id'] = (int) $fields['channel_id'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['title'])) {
            $fields['title'] = (string) $fields['title'];
        }
        if (isset($fields['img'])) {
            $fields['img'] = (string) $fields['img'];
        }
        if (isset($fields['play_time'])) {
            $fields['play_time'] = (string) $fields['play_time'];
        }
        if (isset($fields['content'])) {
            $fields['content'] = (string) $fields['content'];
        }
        if (isset($fields['sort'])) {
            $fields['sort'] = (int) $fields['sort'];
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
     * Set the "tv_station_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTvStationId($value)
    {
        if (!array_key_exists('tv_station_id', $this->fieldsModified)) {
            $this->fieldsModified['tv_station_id'] = $this->data['fields']['tv_station_id'];
        } elseif ($value === $this->fieldsModified['tv_station_id']) {
            unset($this->fieldsModified['tv_station_id']);
        }

        $this->data['fields']['tv_station_id'] = $value;
    }

    /**
     * Returns the "tv_station_id" field.
     *
     * @return mixed The tv_station_id field.
     */
    public function getTvStationId()
    {
        return $this->data['fields']['tv_station_id'];
    }

    /**
     * Set the "channel_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelId($value)
    {
        if (!array_key_exists('channel_id', $this->fieldsModified)) {
            $this->fieldsModified['channel_id'] = $this->data['fields']['channel_id'];
        } elseif ($value === $this->fieldsModified['channel_id']) {
            unset($this->fieldsModified['channel_id']);
        }

        $this->data['fields']['channel_id'] = $value;
    }

    /**
     * Returns the "channel_id" field.
     *
     * @return mixed The channel_id field.
     */
    public function getChannelId()
    {
        return $this->data['fields']['channel_id'];
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
     * Set the "img" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setImg($value)
    {
        if (!array_key_exists('img', $this->fieldsModified)) {
            $this->fieldsModified['img'] = $this->data['fields']['img'];
        } elseif ($value === $this->fieldsModified['img']) {
            unset($this->fieldsModified['img']);
        }

        $this->data['fields']['img'] = $value;
    }

    /**
     * Returns the "img" field.
     *
     * @return mixed The img field.
     */
    public function getImg()
    {
        return $this->data['fields']['img'];
    }

    /**
     * Set the "play_time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPlayTime($value)
    {
        if (!array_key_exists('play_time', $this->fieldsModified)) {
            $this->fieldsModified['play_time'] = $this->data['fields']['play_time'];
        } elseif ($value === $this->fieldsModified['play_time']) {
            unset($this->fieldsModified['play_time']);
        }

        $this->data['fields']['play_time'] = $value;
    }

    /**
     * Returns the "play_time" field.
     *
     * @return mixed The play_time field.
     */
    public function getPlayTime()
    {
        return $this->data['fields']['play_time'];
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
        if (isset($array['tv_station_id'])) {
            $this->setTvStationId($array['tv_station_id']);
        }
        if (isset($array['channel_id'])) {
            $this->setChannelId($array['channel_id']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['img'])) {
            $this->setImg($array['img']);
        }
        if (isset($array['play_time'])) {
            $this->setPlayTime($array['play_time']);
        }
        if (isset($array['content'])) {
            $this->setContent($array['content']);
        }
        if (isset($array['sort'])) {
            $this->setSort($array['sort']);
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

        if (null !== $this->data['fields']['tv_station_id']) {
            $array['tv_station_id'] = $this->data['fields']['tv_station_id'];
        }
        if (null !== $this->data['fields']['channel_id']) {
            $array['channel_id'] = $this->data['fields']['channel_id'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['title']) {
            $array['title'] = $this->data['fields']['title'];
        }
        if (null !== $this->data['fields']['img']) {
            $array['img'] = $this->data['fields']['img'];
        }
        if (null !== $this->data['fields']['play_time']) {
            $array['play_time'] = $this->data['fields']['play_time'];
        }
        if (null !== $this->data['fields']['content']) {
            $array['content'] = $this->data['fields']['content'];
        }
        if (null !== $this->data['fields']['sort']) {
            $array['sort'] = $this->data['fields']['sort'];
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
                'tv_station_id' => array(
                    'type' => 'integer',
                ),
                'channel_id' => array(
                    'type' => 'integer',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'title' => array(
                    'type' => 'string',
                ),
                'img' => array(
                    'type' => 'string',
                ),
                'play_time' => array(
                    'type' => 'string',
                ),
                'content' => array(
                    'type' => 'string',
                ),
                'sort' => array(
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