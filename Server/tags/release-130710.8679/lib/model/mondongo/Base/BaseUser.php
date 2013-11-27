<?php

/**
 * Base class of User document.
 */
abstract class BaseUser extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'email' => null,
            'username' => null,
            'password' => null,
            'updated_at' => null,
            'tags' => null,
            'textpass' => null,
            'avatar' => null,
            'original_avatar' => null,
            'nickname' => null,
            'desc' => null,
            'province' => null,
            'city' => null,
            'dtvsp' => null,
            'device_id' => null,
            'referer' => null,
            'type' => null,
            'created_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'email' => 'Email',
        'username' => 'Username',
        'password' => 'Password',
        'updated_at' => 'UpdatedAt',
        'tags' => 'Tags',
        'textpass' => 'Textpass',
        'avatar' => 'Avatar',
        'original_avatar' => 'OriginalAvatar',
        'nickname' => 'Nickname',
        'desc' => 'Desc',
        'province' => 'Province',
        'city' => 'City',
        'dtvsp' => 'Dtvsp',
        'device_id' => 'DeviceId',
        'referer' => 'Referer',
        'type' => 'Type',
        'created_at' => 'CreatedAt',
    );

    /**
     * Returns the Mondongo of the document.
     *
     * @return Mondongo\Mondongo The Mondongo of the document.
     */
    public function getMondongo()
    {
        return \Mondongo\Container::getForDocumentClass('User');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('User');
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

        if (isset($data['email'])) {
            $this->data['fields']['email'] = (string) $data['email'];
        }
        if (isset($data['username'])) {
            $this->data['fields']['username'] = (string) $data['username'];
        }
        if (isset($data['password'])) {
            $this->data['fields']['password'] = (string) $data['password'];
        }
        if (isset($data['updated_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['updated_at']->sec); $this->data['fields']['updated_at'] = $date;
        }
        if (isset($data['tags'])) {
            $this->data['fields']['tags'] = $data['tags'];
        }
        if (isset($data['textpass'])) {
            $this->data['fields']['textpass'] = (string) $data['textpass'];
        }
        if (isset($data['avatar'])) {
            $this->data['fields']['avatar'] = (string) $data['avatar'];
        }
        if (isset($data['original_avatar'])) {
            $this->data['fields']['original_avatar'] = (string) $data['original_avatar'];
        }
        if (isset($data['nickname'])) {
            $this->data['fields']['nickname'] = (string) $data['nickname'];
        }
        if (isset($data['desc'])) {
            $this->data['fields']['desc'] = (string) $data['desc'];
        }
        if (isset($data['province'])) {
            $this->data['fields']['province'] = (string) $data['province'];
        }
        if (isset($data['city'])) {
            $this->data['fields']['city'] = (string) $data['city'];
        }
        if (isset($data['dtvsp'])) {
            $this->data['fields']['dtvsp'] = (string) $data['dtvsp'];
        }
        if (isset($data['device_id'])) {
            $this->data['fields']['device_id'] = (string) $data['device_id'];
        }
        if (isset($data['referer'])) {
            $this->data['fields']['referer'] = (string) $data['referer'];
        }
        if (isset($data['type'])) {
            $this->data['fields']['type'] = (int) $data['type'];
        }
        if (isset($data['created_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['created_at']->sec); $this->data['fields']['created_at'] = $date;
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
        if (isset($fields['email'])) {
            $fields['email'] = (string) $fields['email'];
        }
        if (isset($fields['username'])) {
            $fields['username'] = (string) $fields['username'];
        }
        if (isset($fields['password'])) {
            $fields['password'] = (string) $fields['password'];
        }
        if (isset($fields['updated_at'])) {
            if ($fields['updated_at'] instanceof \DateTime) { $fields['updated_at'] = $fields['updated_at']->getTimestamp(); } elseif (is_string($fields['updated_at'])) { $fields['updated_at'] = strtotime($fields['updated_at']); } $fields['updated_at'] = new \MongoDate($fields['updated_at']);
        }
        if (isset($fields['tags'])) {
            $fields['tags'] = $fields['tags'];
        }
        if (isset($fields['textpass'])) {
            $fields['textpass'] = (string) $fields['textpass'];
        }
        if (isset($fields['avatar'])) {
            $fields['avatar'] = (string) $fields['avatar'];
        }
        if (isset($fields['original_avatar'])) {
            $fields['original_avatar'] = (string) $fields['original_avatar'];
        }
        if (isset($fields['nickname'])) {
            $fields['nickname'] = (string) $fields['nickname'];
        }
        if (isset($fields['desc'])) {
            $fields['desc'] = (string) $fields['desc'];
        }
        if (isset($fields['province'])) {
            $fields['province'] = (string) $fields['province'];
        }
        if (isset($fields['city'])) {
            $fields['city'] = (string) $fields['city'];
        }
        if (isset($fields['dtvsp'])) {
            $fields['dtvsp'] = (string) $fields['dtvsp'];
        }
        if (isset($fields['device_id'])) {
            $fields['device_id'] = (string) $fields['device_id'];
        }
        if (isset($fields['referer'])) {
            $fields['referer'] = (string) $fields['referer'];
        }
        if (isset($fields['type'])) {
            $fields['type'] = (int) $fields['type'];
        }
        if (isset($fields['created_at'])) {
            if ($fields['created_at'] instanceof \DateTime) { $fields['created_at'] = $fields['created_at']->getTimestamp(); } elseif (is_string($fields['created_at'])) { $fields['created_at'] = strtotime($fields['created_at']); } $fields['created_at'] = new \MongoDate($fields['created_at']);
        }


        return $fields;
    }

    /**
     * Set the "email" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setEmail($value)
    {
        if (!array_key_exists('email', $this->fieldsModified)) {
            $this->fieldsModified['email'] = $this->data['fields']['email'];
        } elseif ($value === $this->fieldsModified['email']) {
            unset($this->fieldsModified['email']);
        }

        $this->data['fields']['email'] = $value;
    }

    /**
     * Returns the "email" field.
     *
     * @return mixed The email field.
     */
    public function getEmail()
    {
        return $this->data['fields']['email'];
    }

    /**
     * Set the "username" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setUsername($value)
    {
        if (!array_key_exists('username', $this->fieldsModified)) {
            $this->fieldsModified['username'] = $this->data['fields']['username'];
        } elseif ($value === $this->fieldsModified['username']) {
            unset($this->fieldsModified['username']);
        }

        $this->data['fields']['username'] = $value;
    }

    /**
     * Returns the "username" field.
     *
     * @return mixed The username field.
     */
    public function getUsername()
    {
        return $this->data['fields']['username'];
    }

    /**
     * Set the "password" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPassword($value)
    {
        if (!array_key_exists('password', $this->fieldsModified)) {
            $this->fieldsModified['password'] = $this->data['fields']['password'];
        } elseif ($value === $this->fieldsModified['password']) {
            unset($this->fieldsModified['password']);
        }

        $this->data['fields']['password'] = $value;
    }

    /**
     * Returns the "password" field.
     *
     * @return mixed The password field.
     */
    public function getPassword()
    {
        return $this->data['fields']['password'];
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
     * Set the "textpass" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTextpass($value)
    {
        if (!array_key_exists('textpass', $this->fieldsModified)) {
            $this->fieldsModified['textpass'] = $this->data['fields']['textpass'];
        } elseif ($value === $this->fieldsModified['textpass']) {
            unset($this->fieldsModified['textpass']);
        }

        $this->data['fields']['textpass'] = $value;
    }

    /**
     * Returns the "textpass" field.
     *
     * @return mixed The textpass field.
     */
    public function getTextpass()
    {
        return $this->data['fields']['textpass'];
    }

    /**
     * Set the "avatar" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAvatar($value)
    {
        if (!array_key_exists('avatar', $this->fieldsModified)) {
            $this->fieldsModified['avatar'] = $this->data['fields']['avatar'];
        } elseif ($value === $this->fieldsModified['avatar']) {
            unset($this->fieldsModified['avatar']);
        }

        $this->data['fields']['avatar'] = $value;
    }

    /**
     * Returns the "avatar" field.
     *
     * @return mixed The avatar field.
     */
    public function getAvatar()
    {
        return $this->data['fields']['avatar'];
    }

    /**
     * Set the "original_avatar" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setOriginalAvatar($value)
    {
        if (!array_key_exists('original_avatar', $this->fieldsModified)) {
            $this->fieldsModified['original_avatar'] = $this->data['fields']['original_avatar'];
        } elseif ($value === $this->fieldsModified['original_avatar']) {
            unset($this->fieldsModified['original_avatar']);
        }

        $this->data['fields']['original_avatar'] = $value;
    }

    /**
     * Returns the "original_avatar" field.
     *
     * @return mixed The original_avatar field.
     */
    public function getOriginalAvatar()
    {
        return $this->data['fields']['original_avatar'];
    }

    /**
     * Set the "nickname" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setNickname($value)
    {
        if (!array_key_exists('nickname', $this->fieldsModified)) {
            $this->fieldsModified['nickname'] = $this->data['fields']['nickname'];
        } elseif ($value === $this->fieldsModified['nickname']) {
            unset($this->fieldsModified['nickname']);
        }

        $this->data['fields']['nickname'] = $value;
    }

    /**
     * Returns the "nickname" field.
     *
     * @return mixed The nickname field.
     */
    public function getNickname()
    {
        return $this->data['fields']['nickname'];
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
     * Set the "province" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setProvince($value)
    {
        if (!array_key_exists('province', $this->fieldsModified)) {
            $this->fieldsModified['province'] = $this->data['fields']['province'];
        } elseif ($value === $this->fieldsModified['province']) {
            unset($this->fieldsModified['province']);
        }

        $this->data['fields']['province'] = $value;
    }

    /**
     * Returns the "province" field.
     *
     * @return mixed The province field.
     */
    public function getProvince()
    {
        return $this->data['fields']['province'];
    }

    /**
     * Set the "city" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCity($value)
    {
        if (!array_key_exists('city', $this->fieldsModified)) {
            $this->fieldsModified['city'] = $this->data['fields']['city'];
        } elseif ($value === $this->fieldsModified['city']) {
            unset($this->fieldsModified['city']);
        }

        $this->data['fields']['city'] = $value;
    }

    /**
     * Returns the "city" field.
     *
     * @return mixed The city field.
     */
    public function getCity()
    {
        return $this->data['fields']['city'];
    }

    /**
     * Set the "dtvsp" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDtvsp($value)
    {
        if (!array_key_exists('dtvsp', $this->fieldsModified)) {
            $this->fieldsModified['dtvsp'] = $this->data['fields']['dtvsp'];
        } elseif ($value === $this->fieldsModified['dtvsp']) {
            unset($this->fieldsModified['dtvsp']);
        }

        $this->data['fields']['dtvsp'] = $value;
    }

    /**
     * Returns the "dtvsp" field.
     *
     * @return mixed The dtvsp field.
     */
    public function getDtvsp()
    {
        return $this->data['fields']['dtvsp'];
    }

    /**
     * Set the "device_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDeviceId($value)
    {
        if (!array_key_exists('device_id', $this->fieldsModified)) {
            $this->fieldsModified['device_id'] = $this->data['fields']['device_id'];
        } elseif ($value === $this->fieldsModified['device_id']) {
            unset($this->fieldsModified['device_id']);
        }

        $this->data['fields']['device_id'] = $value;
    }

    /**
     * Returns the "device_id" field.
     *
     * @return mixed The device_id field.
     */
    public function getDeviceId()
    {
        return $this->data['fields']['device_id'];
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
        if (isset($array['email'])) {
            $this->setEmail($array['email']);
        }
        if (isset($array['username'])) {
            $this->setUsername($array['username']);
        }
        if (isset($array['password'])) {
            $this->setPassword($array['password']);
        }
        if (isset($array['updated_at'])) {
            $this->setUpdatedAt($array['updated_at']);
        }
        if (isset($array['tags'])) {
            $this->setTags($array['tags']);
        }
        if (isset($array['textpass'])) {
            $this->setTextpass($array['textpass']);
        }
        if (isset($array['avatar'])) {
            $this->setAvatar($array['avatar']);
        }
        if (isset($array['original_avatar'])) {
            $this->setOriginalAvatar($array['original_avatar']);
        }
        if (isset($array['nickname'])) {
            $this->setNickname($array['nickname']);
        }
        if (isset($array['desc'])) {
            $this->setDesc($array['desc']);
        }
        if (isset($array['province'])) {
            $this->setProvince($array['province']);
        }
        if (isset($array['city'])) {
            $this->setCity($array['city']);
        }
        if (isset($array['dtvsp'])) {
            $this->setDtvsp($array['dtvsp']);
        }
        if (isset($array['device_id'])) {
            $this->setDeviceId($array['device_id']);
        }
        if (isset($array['referer'])) {
            $this->setReferer($array['referer']);
        }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
        if (isset($array['created_at'])) {
            $this->setCreatedAt($array['created_at']);
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

        if (null !== $this->data['fields']['email']) {
            $array['email'] = $this->data['fields']['email'];
        }
        if (null !== $this->data['fields']['username']) {
            $array['username'] = $this->data['fields']['username'];
        }
        if (null !== $this->data['fields']['password']) {
            $array['password'] = $this->data['fields']['password'];
        }
        if (null !== $this->data['fields']['updated_at']) {
            $array['updated_at'] = $this->data['fields']['updated_at'];
        }
        if (null !== $this->data['fields']['tags']) {
            $array['tags'] = $this->data['fields']['tags'];
        }
        if (null !== $this->data['fields']['textpass']) {
            $array['textpass'] = $this->data['fields']['textpass'];
        }
        if (null !== $this->data['fields']['avatar']) {
            $array['avatar'] = $this->data['fields']['avatar'];
        }
        if (null !== $this->data['fields']['original_avatar']) {
            $array['original_avatar'] = $this->data['fields']['original_avatar'];
        }
        if (null !== $this->data['fields']['nickname']) {
            $array['nickname'] = $this->data['fields']['nickname'];
        }
        if (null !== $this->data['fields']['desc']) {
            $array['desc'] = $this->data['fields']['desc'];
        }
        if (null !== $this->data['fields']['province']) {
            $array['province'] = $this->data['fields']['province'];
        }
        if (null !== $this->data['fields']['city']) {
            $array['city'] = $this->data['fields']['city'];
        }
        if (null !== $this->data['fields']['dtvsp']) {
            $array['dtvsp'] = $this->data['fields']['dtvsp'];
        }
        if (null !== $this->data['fields']['device_id']) {
            $array['device_id'] = $this->data['fields']['device_id'];
        }
        if (null !== $this->data['fields']['referer']) {
            $array['referer'] = $this->data['fields']['referer'];
        }
        if (null !== $this->data['fields']['type']) {
            $array['type'] = $this->data['fields']['type'];
        }
        if (null !== $this->data['fields']['created_at']) {
            $array['created_at'] = $this->data['fields']['created_at'];
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
                'email' => array(
                    'type' => 'string',
                ),
                'username' => array(
                    'type' => 'string',
                ),
                'password' => array(
                    'type' => 'string',
                ),
                'updated_at' => array(
                    'type' => 'date',
                ),
                'tags' => array(
                    'type' => 'raw',
                ),
                'textpass' => array(
                    'type' => 'string',
                ),
                'avatar' => array(
                    'type' => 'string',
                ),
                'original_avatar' => array(
                    'type' => 'string',
                ),
                'nickname' => array(
                    'type' => 'string',
                ),
                'desc' => array(
                    'type' => 'string',
                ),
                'province' => array(
                    'type' => 'string',
                ),
                'city' => array(
                    'type' => 'string',
                ),
                'dtvsp' => array(
                    'type' => 'string',
                ),
                'device_id' => array(
                    'type' => 'string',
                ),
                'referer' => array(
                    'type' => 'string',
                ),
                'type' => array(
                    'type' => 'integer',
                ),
                'created_at' => array(
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