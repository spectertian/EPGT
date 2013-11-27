<?php

/**
 * Base class of YesterdayProgram document.
 */
abstract class BaseYesterdayProgram extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'program_name' => null,
            'channel_code' => null,
            'date' => null,
            'start_time' => null,
            'end_time' => null,
            'wiki_id' => null,
            'poster' => null,
            'tags' => null,
            'aspect' => null,
            'play_url' => null,
            'sort' => null,
            'style' => null,
            'author' => null,
            'state' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'program_name' => 'ProgramName',
        'channel_code' => 'ChannelCode',
        'date' => 'Date',
        'start_time' => 'StartTime',
        'end_time' => 'EndTime',
        'wiki_id' => 'WikiId',
        'poster' => 'Poster',
        'tags' => 'Tags',
        'aspect' => 'Aspect',
        'play_url' => 'PlayUrl',
        'sort' => 'Sort',
        'style' => 'Style',
        'author' => 'Author',
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
        return \Mondongo\Container::getForDocumentClass('YesterdayProgram');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('YesterdayProgram');
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

        if (isset($data['program_name'])) {
            $this->data['fields']['program_name'] = (string) $data['program_name'];
        }
        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['date'])) {
            $this->data['fields']['date'] = (string) $data['date'];
        }
        if (isset($data['start_time'])) {
            $date = new \DateTime(); $date->setTimestamp($data['start_time']->sec); $this->data['fields']['start_time'] = $date;
        }
        if (isset($data['end_time'])) {
            $date = new \DateTime(); $date->setTimestamp($data['end_time']->sec); $this->data['fields']['end_time'] = $date;
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['poster'])) {
            $this->data['fields']['poster'] = (string) $data['poster'];
        }
        if (isset($data['tags'])) {
            $this->data['fields']['tags'] = $data['tags'];
        }
        if (isset($data['aspect'])) {
            $this->data['fields']['aspect'] = (string) $data['aspect'];
        }
        if (isset($data['play_url'])) {
            $this->data['fields']['play_url'] = (string) $data['play_url'];
        }
        if (isset($data['sort'])) {
            $this->data['fields']['sort'] = (int) $data['sort'];
        }
        if (isset($data['style'])) {
            $this->data['fields']['style'] = (string) $data['style'];
        }
        if (isset($data['author'])) {
            $this->data['fields']['author'] = $data['author'];
        }
        if (isset($data['state'])) {
            $this->data['fields']['state'] = (bool) $data['state'];
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
        if (isset($fields['program_name'])) {
            $fields['program_name'] = (string) $fields['program_name'];
        }
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['date'])) {
            $fields['date'] = (string) $fields['date'];
        }
        if (isset($fields['start_time'])) {
            if ($fields['start_time'] instanceof \DateTime) { $fields['start_time'] = $fields['start_time']->getTimestamp(); } elseif (is_string($fields['start_time'])) { $fields['start_time'] = strtotime($fields['start_time']); } $fields['start_time'] = new \MongoDate($fields['start_time']);
        }
        if (isset($fields['end_time'])) {
            if ($fields['end_time'] instanceof \DateTime) { $fields['end_time'] = $fields['end_time']->getTimestamp(); } elseif (is_string($fields['end_time'])) { $fields['end_time'] = strtotime($fields['end_time']); } $fields['end_time'] = new \MongoDate($fields['end_time']);
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['poster'])) {
            $fields['poster'] = (string) $fields['poster'];
        }
        if (isset($fields['tags'])) {
            $fields['tags'] = $fields['tags'];
        }
        if (isset($fields['aspect'])) {
            $fields['aspect'] = (string) $fields['aspect'];
        }
        if (isset($fields['play_url'])) {
            $fields['play_url'] = (string) $fields['play_url'];
        }
        if (isset($fields['sort'])) {
            $fields['sort'] = (int) $fields['sort'];
        }
        if (isset($fields['style'])) {
            $fields['style'] = (string) $fields['style'];
        }
        if (isset($fields['author'])) {
            $fields['author'] = $fields['author'];
        }
        if (isset($fields['state'])) {
            $fields['state'] = (bool) $fields['state'];
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
     * Set the "program_name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setProgramName($value)
    {
        if (!array_key_exists('program_name', $this->fieldsModified)) {
            $this->fieldsModified['program_name'] = $this->data['fields']['program_name'];
        } elseif ($value === $this->fieldsModified['program_name']) {
            unset($this->fieldsModified['program_name']);
        }

        $this->data['fields']['program_name'] = $value;
    }

    /**
     * Returns the "program_name" field.
     *
     * @return mixed The program_name field.
     */
    public function getProgramName()
    {
        return $this->data['fields']['program_name'];
    }

    /**
     * Set the "channel_code" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelCode($value)
    {
        if (!array_key_exists('channel_code', $this->fieldsModified)) {
            $this->fieldsModified['channel_code'] = $this->data['fields']['channel_code'];
        } elseif ($value === $this->fieldsModified['channel_code']) {
            unset($this->fieldsModified['channel_code']);
        }

        $this->data['fields']['channel_code'] = $value;
    }

    /**
     * Returns the "channel_code" field.
     *
     * @return mixed The channel_code field.
     */
    public function getChannelCode()
    {
        return $this->data['fields']['channel_code'];
    }

    /**
     * Set the "date" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDate($value)
    {
        if (!array_key_exists('date', $this->fieldsModified)) {
            $this->fieldsModified['date'] = $this->data['fields']['date'];
        } elseif ($value === $this->fieldsModified['date']) {
            unset($this->fieldsModified['date']);
        }

        $this->data['fields']['date'] = $value;
    }

    /**
     * Returns the "date" field.
     *
     * @return mixed The date field.
     */
    public function getDate()
    {
        return $this->data['fields']['date'];
    }

    /**
     * Set the "start_time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStartTime($value)
    {
        if (!array_key_exists('start_time', $this->fieldsModified)) {
            $this->fieldsModified['start_time'] = $this->data['fields']['start_time'];
        } elseif ($value === $this->fieldsModified['start_time']) {
            unset($this->fieldsModified['start_time']);
        }

        $this->data['fields']['start_time'] = $value;
    }

    /**
     * Returns the "start_time" field.
     *
     * @return mixed The start_time field.
     */
    public function getStartTime()
    {
        return $this->data['fields']['start_time'];
    }

    /**
     * Set the "end_time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setEndTime($value)
    {
        if (!array_key_exists('end_time', $this->fieldsModified)) {
            $this->fieldsModified['end_time'] = $this->data['fields']['end_time'];
        } elseif ($value === $this->fieldsModified['end_time']) {
            unset($this->fieldsModified['end_time']);
        }

        $this->data['fields']['end_time'] = $value;
    }

    /**
     * Returns the "end_time" field.
     *
     * @return mixed The end_time field.
     */
    public function getEndTime()
    {
        return $this->data['fields']['end_time'];
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
     * Set the "poster" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPoster($value)
    {
        if (!array_key_exists('poster', $this->fieldsModified)) {
            $this->fieldsModified['poster'] = $this->data['fields']['poster'];
        } elseif ($value === $this->fieldsModified['poster']) {
            unset($this->fieldsModified['poster']);
        }

        $this->data['fields']['poster'] = $value;
    }

    /**
     * Returns the "poster" field.
     *
     * @return mixed The poster field.
     */
    public function getPoster()
    {
        return $this->data['fields']['poster'];
    }

    /**
     * Set the "tags" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTags($value)
    {
        if (!array_key_exists('tags', $this->fieldsModified)) {
            $this->fieldsModified['tags'] = $this->data['fields']['tags'];
        } elseif ($value === $this->fieldsModified['tags']) {
            unset($this->fieldsModified['tags']);
        }

        $this->data['fields']['tags'] = $value;
    }

    /**
     * Returns the "tags" field.
     *
     * @return mixed The tags field.
     */
    public function getTags()
    {
        return $this->data['fields']['tags'];
    }

    /**
     * Set the "aspect" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAspect($value)
    {
        if (!array_key_exists('aspect', $this->fieldsModified)) {
            $this->fieldsModified['aspect'] = $this->data['fields']['aspect'];
        } elseif ($value === $this->fieldsModified['aspect']) {
            unset($this->fieldsModified['aspect']);
        }

        $this->data['fields']['aspect'] = $value;
    }

    /**
     * Returns the "aspect" field.
     *
     * @return mixed The aspect field.
     */
    public function getAspect()
    {
        return $this->data['fields']['aspect'];
    }

    /**
     * Set the "play_url" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPlayUrl($value)
    {
        if (!array_key_exists('play_url', $this->fieldsModified)) {
            $this->fieldsModified['play_url'] = $this->data['fields']['play_url'];
        } elseif ($value === $this->fieldsModified['play_url']) {
            unset($this->fieldsModified['play_url']);
        }

        $this->data['fields']['play_url'] = $value;
    }

    /**
     * Returns the "play_url" field.
     *
     * @return mixed The play_url field.
     */
    public function getPlayUrl()
    {
        return $this->data['fields']['play_url'];
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
     * Set the "style" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStyle($value)
    {
        if (!array_key_exists('style', $this->fieldsModified)) {
            $this->fieldsModified['style'] = $this->data['fields']['style'];
        } elseif ($value === $this->fieldsModified['style']) {
            unset($this->fieldsModified['style']);
        }

        $this->data['fields']['style'] = $value;
    }

    /**
     * Returns the "style" field.
     *
     * @return mixed The style field.
     */
    public function getStyle()
    {
        return $this->data['fields']['style'];
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
        if (isset($array['program_name'])) {
            $this->setProgramName($array['program_name']);
        }
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['date'])) {
            $this->setDate($array['date']);
        }
        if (isset($array['start_time'])) {
            $this->setStartTime($array['start_time']);
        }
        if (isset($array['end_time'])) {
            $this->setEndTime($array['end_time']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['poster'])) {
            $this->setPoster($array['poster']);
        }
        if (isset($array['tags'])) {
            $this->setTags($array['tags']);
        }
        if (isset($array['aspect'])) {
            $this->setAspect($array['aspect']);
        }
        if (isset($array['play_url'])) {
            $this->setPlayUrl($array['play_url']);
        }
        if (isset($array['sort'])) {
            $this->setSort($array['sort']);
        }
        if (isset($array['style'])) {
            $this->setStyle($array['style']);
        }
        if (isset($array['author'])) {
            $this->setAuthor($array['author']);
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

        if (null !== $this->data['fields']['program_name']) {
            $array['program_name'] = $this->data['fields']['program_name'];
        }
        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['date']) {
            $array['date'] = $this->data['fields']['date'];
        }
        if (null !== $this->data['fields']['start_time']) {
            $array['start_time'] = $this->data['fields']['start_time'];
        }
        if (null !== $this->data['fields']['end_time']) {
            $array['end_time'] = $this->data['fields']['end_time'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['poster']) {
            $array['poster'] = $this->data['fields']['poster'];
        }
        if (null !== $this->data['fields']['tags']) {
            $array['tags'] = $this->data['fields']['tags'];
        }
        if (null !== $this->data['fields']['aspect']) {
            $array['aspect'] = $this->data['fields']['aspect'];
        }
        if (null !== $this->data['fields']['play_url']) {
            $array['play_url'] = $this->data['fields']['play_url'];
        }
        if (null !== $this->data['fields']['sort']) {
            $array['sort'] = $this->data['fields']['sort'];
        }
        if (null !== $this->data['fields']['style']) {
            $array['style'] = $this->data['fields']['style'];
        }
        if (null !== $this->data['fields']['author']) {
            $array['author'] = $this->data['fields']['author'];
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
                'program_name' => array(
                    'type' => 'string',
                ),
                'channel_code' => array(
                    'type' => 'string',
                ),
                'date' => array(
                    'type' => 'string',
                ),
                'start_time' => array(
                    'type' => 'date',
                ),
                'end_time' => array(
                    'type' => 'date',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'poster' => array(
                    'type' => 'string',
                ),
                'tags' => array(
                    'type' => 'raw',
                ),
                'aspect' => array(
                    'type' => 'string',
                ),
                'play_url' => array(
                    'type' => 'string',
                ),
                'sort' => array(
                    'type' => 'integer',
                ),
                'style' => array(
                    'type' => 'string',
                ),
                'author' => array(
                    'type' => 'raw',
                ),
                'state' => array(
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