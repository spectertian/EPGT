<?php

/**
 * Base class of Video document.
 */
abstract class BaseVideo extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'wiki_id' => null,
            'wiki_mata_id' => null,
            'video_playlist_id' => null,
            'title' => null,
            'url' => null,
            'config' => null,
            'time' => null,
            'mark' => null,
            'model' => null,
            'referer' => null,
            'publish' => null,
            'vc_id' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'wiki_id' => 'WikiId',
        'wiki_mata_id' => 'WikiMataId',
        'video_playlist_id' => 'VideoPlaylistId',
        'title' => 'Title',
        'url' => 'Url',
        'config' => 'Config',
        'time' => 'Time',
        'mark' => 'Mark',
        'model' => 'Model',
        'referer' => 'Referer',
        'publish' => 'Publish',
        'vc_id' => 'VcId',
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
        return \Mondongo\Container::getForDocumentClass('Video');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Video');
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
        if (isset($data['wiki_mata_id'])) {
            $this->data['fields']['wiki_mata_id'] = (string) $data['wiki_mata_id'];
        }
        if (isset($data['video_playlist_id'])) {
            $this->data['fields']['video_playlist_id'] = (string) $data['video_playlist_id'];
        }
        if (isset($data['title'])) {
            $this->data['fields']['title'] = (string) $data['title'];
        }
        if (isset($data['url'])) {
            $this->data['fields']['url'] = (string) $data['url'];
        }
        if (isset($data['config'])) {
            $this->data['fields']['config'] = $data['config'];
        }
        if (isset($data['time'])) {
            $this->data['fields']['time'] = (string) $data['time'];
        }
        if (isset($data['mark'])) {
            $this->data['fields']['mark'] = (int) $data['mark'];
        }
        if (isset($data['model'])) {
            $this->data['fields']['model'] = (string) $data['model'];
        }
        if (isset($data['referer'])) {
            $this->data['fields']['referer'] = (string) $data['referer'];
        }
        if (isset($data['publish'])) {
            $this->data['fields']['publish'] = (bool) $data['publish'];
        }
        if (isset($data['vc_id'])) {
            $this->data['fields']['vc_id'] = (string) $data['vc_id'];
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
        if (isset($fields['wiki_mata_id'])) {
            $fields['wiki_mata_id'] = (string) $fields['wiki_mata_id'];
        }
        if (isset($fields['video_playlist_id'])) {
            $fields['video_playlist_id'] = (string) $fields['video_playlist_id'];
        }
        if (isset($fields['title'])) {
            $fields['title'] = (string) $fields['title'];
        }
        if (isset($fields['url'])) {
            $fields['url'] = (string) $fields['url'];
        }
        if (isset($fields['config'])) {
            $fields['config'] = $fields['config'];
        }
        if (isset($fields['time'])) {
            $fields['time'] = (string) $fields['time'];
        }
        if (isset($fields['mark'])) {
            $fields['mark'] = (int) $fields['mark'];
        }
        if (isset($fields['model'])) {
            $fields['model'] = (string) $fields['model'];
        }
        if (isset($fields['referer'])) {
            $fields['referer'] = (string) $fields['referer'];
        }
        if (isset($fields['publish'])) {
            $fields['publish'] = (bool) $fields['publish'];
        }
        if (isset($fields['vc_id'])) {
            $fields['vc_id'] = (string) $fields['vc_id'];
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
     * Set the "wiki_mata_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWikiMataId($value)
    {
        if (!array_key_exists('wiki_mata_id', $this->fieldsModified)) {
            $this->fieldsModified['wiki_mata_id'] = $this->data['fields']['wiki_mata_id'];
        } elseif ($value === $this->fieldsModified['wiki_mata_id']) {
            unset($this->fieldsModified['wiki_mata_id']);
        }

        $this->data['fields']['wiki_mata_id'] = $value;
    }

    /**
     * Returns the "wiki_mata_id" field.
     *
     * @return mixed The wiki_mata_id field.
     */
    public function getWikiMataId()
    {
        return $this->data['fields']['wiki_mata_id'];
    }

    /**
     * Set the "video_playlist_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setVideoPlaylistId($value)
    {
        if (!array_key_exists('video_playlist_id', $this->fieldsModified)) {
            $this->fieldsModified['video_playlist_id'] = $this->data['fields']['video_playlist_id'];
        } elseif ($value === $this->fieldsModified['video_playlist_id']) {
            unset($this->fieldsModified['video_playlist_id']);
        }

        $this->data['fields']['video_playlist_id'] = $value;
    }

    /**
     * Returns the "video_playlist_id" field.
     *
     * @return mixed The video_playlist_id field.
     */
    public function getVideoPlaylistId()
    {
        return $this->data['fields']['video_playlist_id'];
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
     * Set the "config" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setConfig($value)
    {
        if (!array_key_exists('config', $this->fieldsModified)) {
            $this->fieldsModified['config'] = $this->data['fields']['config'];
        } elseif ($value === $this->fieldsModified['config']) {
            unset($this->fieldsModified['config']);
        }

        $this->data['fields']['config'] = $value;
    }

    /**
     * Returns the "config" field.
     *
     * @return mixed The config field.
     */
    public function getConfig()
    {
        return $this->data['fields']['config'];
    }

    /**
     * Set the "time" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTime($value)
    {
        if (!array_key_exists('time', $this->fieldsModified)) {
            $this->fieldsModified['time'] = $this->data['fields']['time'];
        } elseif ($value === $this->fieldsModified['time']) {
            unset($this->fieldsModified['time']);
        }

        $this->data['fields']['time'] = $value;
    }

    /**
     * Returns the "time" field.
     *
     * @return mixed The time field.
     */
    public function getTime()
    {
        return $this->data['fields']['time'];
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
     * Set the "referer" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setReferer($value)
    {
        if (!array_key_exists('referer', $this->fieldsModified)) {
            $this->fieldsModified['referer'] = $this->data['fields']['referer'];
        } elseif ($value === $this->fieldsModified['referer']) {
            unset($this->fieldsModified['referer']);
        }

        $this->data['fields']['referer'] = $value;
    }

    /**
     * Returns the "referer" field.
     *
     * @return mixed The referer field.
     */
    public function getReferer()
    {
        return $this->data['fields']['referer'];
    }

    /**
     * Set the "publish" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPublish($value)
    {
        if (!array_key_exists('publish', $this->fieldsModified)) {
            $this->fieldsModified['publish'] = $this->data['fields']['publish'];
        } elseif ($value === $this->fieldsModified['publish']) {
            unset($this->fieldsModified['publish']);
        }

        $this->data['fields']['publish'] = $value;
    }

    /**
     * Returns the "publish" field.
     *
     * @return mixed The publish field.
     */
    public function getPublish()
    {
        return $this->data['fields']['publish'];
    }

    /**
     * Set the "vc_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setVcId($value)
    {
        if (!array_key_exists('vc_id', $this->fieldsModified)) {
            $this->fieldsModified['vc_id'] = $this->data['fields']['vc_id'];
        } elseif ($value === $this->fieldsModified['vc_id']) {
            unset($this->fieldsModified['vc_id']);
        }

        $this->data['fields']['vc_id'] = $value;
    }

    /**
     * Returns the "vc_id" field.
     *
     * @return mixed The vc_id field.
     */
    public function getVcId()
    {
        return $this->data['fields']['vc_id'];
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
        if (isset($array['wiki_mata_id'])) {
            $this->setWikiMataId($array['wiki_mata_id']);
        }
        if (isset($array['video_playlist_id'])) {
            $this->setVideoPlaylistId($array['video_playlist_id']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['url'])) {
            $this->setUrl($array['url']);
        }
        if (isset($array['config'])) {
            $this->setConfig($array['config']);
        }
        if (isset($array['time'])) {
            $this->setTime($array['time']);
        }
        if (isset($array['mark'])) {
            $this->setMark($array['mark']);
        }
        if (isset($array['model'])) {
            $this->setModel($array['model']);
        }
        if (isset($array['referer'])) {
            $this->setReferer($array['referer']);
        }
        if (isset($array['publish'])) {
            $this->setPublish($array['publish']);
        }
        if (isset($array['vc_id'])) {
            $this->setVcId($array['vc_id']);
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
        if (null !== $this->data['fields']['wiki_mata_id']) {
            $array['wiki_mata_id'] = $this->data['fields']['wiki_mata_id'];
        }
        if (null !== $this->data['fields']['video_playlist_id']) {
            $array['video_playlist_id'] = $this->data['fields']['video_playlist_id'];
        }
        if (null !== $this->data['fields']['title']) {
            $array['title'] = $this->data['fields']['title'];
        }
        if (null !== $this->data['fields']['url']) {
            $array['url'] = $this->data['fields']['url'];
        }
        if (null !== $this->data['fields']['config']) {
            $array['config'] = $this->data['fields']['config'];
        }
        if (null !== $this->data['fields']['time']) {
            $array['time'] = $this->data['fields']['time'];
        }
        if (null !== $this->data['fields']['mark']) {
            $array['mark'] = $this->data['fields']['mark'];
        }
        if (null !== $this->data['fields']['model']) {
            $array['model'] = $this->data['fields']['model'];
        }
        if (null !== $this->data['fields']['referer']) {
            $array['referer'] = $this->data['fields']['referer'];
        }
        if (null !== $this->data['fields']['publish']) {
            $array['publish'] = $this->data['fields']['publish'];
        }
        if (null !== $this->data['fields']['vc_id']) {
            $array['vc_id'] = $this->data['fields']['vc_id'];
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
                'wiki_mata_id' => array(
                    'type' => 'string',
                ),
                'video_playlist_id' => array(
                    'type' => 'string',
                ),
                'title' => array(
                    'type' => 'string',
                ),
                'url' => array(
                    'type' => 'string',
                ),
                'config' => array(
                    'type' => 'raw',
                ),
                'time' => array(
                    'type' => 'string',
                ),
                'mark' => array(
                    'type' => 'integer',
                ),
                'model' => array(
                    'type' => 'string',
                ),
                'referer' => array(
                    'type' => 'string',
                ),
                'publish' => array(
                    'type' => 'boolean',
                ),
                'vc_id' => array(
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