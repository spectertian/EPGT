<?php

/**
 * Base class of DoubanCelebrity document.
 */
abstract class BaseDoubanCelebrity extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'douban_id' => null,
            'name' => null,
            'name_en' => null,
            'avatars' => null,
            'summary' => null,
            'gender' => null,
            'birthday' => null,
            'country' => null,
            'born_place' => null,
            'professions' => null,
            'constellation' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'douban_id' => 'DoubanId',
        'name' => 'Name',
        'name_en' => 'NameEn',
        'avatars' => 'Avatars',
        'summary' => 'Summary',
        'gender' => 'Gender',
        'birthday' => 'Birthday',
        'country' => 'Country',
        'born_place' => 'BornPlace',
        'professions' => 'Professions',
        'constellation' => 'Constellation',
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
        return \Mondongo\Container::getForDocumentClass('DoubanCelebrity');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('DoubanCelebrity');
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

        if (isset($data['douban_id'])) {
            $this->data['fields']['douban_id'] = (int) $data['douban_id'];
        }
        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['name_en'])) {
            $this->data['fields']['name_en'] = (string) $data['name_en'];
        }
        if (isset($data['avatars'])) {
            $this->data['fields']['avatars'] = (string) $data['avatars'];
        }
        if (isset($data['summary'])) {
            $this->data['fields']['summary'] = (string) $data['summary'];
        }
        if (isset($data['gender'])) {
            $this->data['fields']['gender'] = (string) $data['gender'];
        }
        if (isset($data['birthday'])) {
            $this->data['fields']['birthday'] = (string) $data['birthday'];
        }
        if (isset($data['country'])) {
            $this->data['fields']['country'] = (string) $data['country'];
        }
        if (isset($data['born_place'])) {
            $this->data['fields']['born_place'] = (string) $data['born_place'];
        }
        if (isset($data['professions'])) {
            $this->data['fields']['professions'] = (string) $data['professions'];
        }
        if (isset($data['constellation'])) {
            $this->data['fields']['constellation'] = $data['constellation'];
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
        if (isset($fields['douban_id'])) {
            $fields['douban_id'] = (int) $fields['douban_id'];
        }
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['name_en'])) {
            $fields['name_en'] = (string) $fields['name_en'];
        }
        if (isset($fields['avatars'])) {
            $fields['avatars'] = (string) $fields['avatars'];
        }
        if (isset($fields['summary'])) {
            $fields['summary'] = (string) $fields['summary'];
        }
        if (isset($fields['gender'])) {
            $fields['gender'] = (string) $fields['gender'];
        }
        if (isset($fields['birthday'])) {
            $fields['birthday'] = (string) $fields['birthday'];
        }
        if (isset($fields['country'])) {
            $fields['country'] = (string) $fields['country'];
        }
        if (isset($fields['born_place'])) {
            $fields['born_place'] = (string) $fields['born_place'];
        }
        if (isset($fields['professions'])) {
            $fields['professions'] = (string) $fields['professions'];
        }
        if (isset($fields['constellation'])) {
            $fields['constellation'] = $fields['constellation'];
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
     * Set the "douban_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDoubanId($value)
    {
        if (!array_key_exists('douban_id', $this->fieldsModified)) {
            $this->fieldsModified['douban_id'] = $this->data['fields']['douban_id'];
        } elseif ($value === $this->fieldsModified['douban_id']) {
            unset($this->fieldsModified['douban_id']);
        }

        $this->data['fields']['douban_id'] = $value;
    }

    /**
     * Returns the "douban_id" field.
     *
     * @return mixed The douban_id field.
     */
    public function getDoubanId()
    {
        return $this->data['fields']['douban_id'];
    }

    /**
     * Set the "name" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setName($value)
    {
        if (!array_key_exists('name', $this->fieldsModified)) {
            $this->fieldsModified['name'] = $this->data['fields']['name'];
        } elseif ($value === $this->fieldsModified['name']) {
            unset($this->fieldsModified['name']);
        }

        $this->data['fields']['name'] = $value;
    }

    /**
     * Returns the "name" field.
     *
     * @return mixed The name field.
     */
    public function getName()
    {
        return $this->data['fields']['name'];
    }

    /**
     * Set the "name_en" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setNameEn($value)
    {
        if (!array_key_exists('name_en', $this->fieldsModified)) {
            $this->fieldsModified['name_en'] = $this->data['fields']['name_en'];
        } elseif ($value === $this->fieldsModified['name_en']) {
            unset($this->fieldsModified['name_en']);
        }

        $this->data['fields']['name_en'] = $value;
    }

    /**
     * Returns the "name_en" field.
     *
     * @return mixed The name_en field.
     */
    public function getNameEn()
    {
        return $this->data['fields']['name_en'];
    }

    /**
     * Set the "avatars" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAvatars($value)
    {
        if (!array_key_exists('avatars', $this->fieldsModified)) {
            $this->fieldsModified['avatars'] = $this->data['fields']['avatars'];
        } elseif ($value === $this->fieldsModified['avatars']) {
            unset($this->fieldsModified['avatars']);
        }

        $this->data['fields']['avatars'] = $value;
    }

    /**
     * Returns the "avatars" field.
     *
     * @return mixed The avatars field.
     */
    public function getAvatars()
    {
        return $this->data['fields']['avatars'];
    }

    /**
     * Set the "summary" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSummary($value)
    {
        if (!array_key_exists('summary', $this->fieldsModified)) {
            $this->fieldsModified['summary'] = $this->data['fields']['summary'];
        } elseif ($value === $this->fieldsModified['summary']) {
            unset($this->fieldsModified['summary']);
        }

        $this->data['fields']['summary'] = $value;
    }

    /**
     * Returns the "summary" field.
     *
     * @return mixed The summary field.
     */
    public function getSummary()
    {
        return $this->data['fields']['summary'];
    }

    /**
     * Set the "gender" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setGender($value)
    {
        if (!array_key_exists('gender', $this->fieldsModified)) {
            $this->fieldsModified['gender'] = $this->data['fields']['gender'];
        } elseif ($value === $this->fieldsModified['gender']) {
            unset($this->fieldsModified['gender']);
        }

        $this->data['fields']['gender'] = $value;
    }

    /**
     * Returns the "gender" field.
     *
     * @return mixed The gender field.
     */
    public function getGender()
    {
        return $this->data['fields']['gender'];
    }

    /**
     * Set the "birthday" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setBirthday($value)
    {
        if (!array_key_exists('birthday', $this->fieldsModified)) {
            $this->fieldsModified['birthday'] = $this->data['fields']['birthday'];
        } elseif ($value === $this->fieldsModified['birthday']) {
            unset($this->fieldsModified['birthday']);
        }

        $this->data['fields']['birthday'] = $value;
    }

    /**
     * Returns the "birthday" field.
     *
     * @return mixed The birthday field.
     */
    public function getBirthday()
    {
        return $this->data['fields']['birthday'];
    }

    /**
     * Set the "country" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCountry($value)
    {
        if (!array_key_exists('country', $this->fieldsModified)) {
            $this->fieldsModified['country'] = $this->data['fields']['country'];
        } elseif ($value === $this->fieldsModified['country']) {
            unset($this->fieldsModified['country']);
        }

        $this->data['fields']['country'] = $value;
    }

    /**
     * Returns the "country" field.
     *
     * @return mixed The country field.
     */
    public function getCountry()
    {
        return $this->data['fields']['country'];
    }

    /**
     * Set the "born_place" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setBornPlace($value)
    {
        if (!array_key_exists('born_place', $this->fieldsModified)) {
            $this->fieldsModified['born_place'] = $this->data['fields']['born_place'];
        } elseif ($value === $this->fieldsModified['born_place']) {
            unset($this->fieldsModified['born_place']);
        }

        $this->data['fields']['born_place'] = $value;
    }

    /**
     * Returns the "born_place" field.
     *
     * @return mixed The born_place field.
     */
    public function getBornPlace()
    {
        return $this->data['fields']['born_place'];
    }

    /**
     * Set the "professions" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setProfessions($value)
    {
        if (!array_key_exists('professions', $this->fieldsModified)) {
            $this->fieldsModified['professions'] = $this->data['fields']['professions'];
        } elseif ($value === $this->fieldsModified['professions']) {
            unset($this->fieldsModified['professions']);
        }

        $this->data['fields']['professions'] = $value;
    }

    /**
     * Returns the "professions" field.
     *
     * @return mixed The professions field.
     */
    public function getProfessions()
    {
        return $this->data['fields']['professions'];
    }

    /**
     * Set the "constellation" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setConstellation($value)
    {
        if (!array_key_exists('constellation', $this->fieldsModified)) {
            $this->fieldsModified['constellation'] = $this->data['fields']['constellation'];
        } elseif ($value === $this->fieldsModified['constellation']) {
            unset($this->fieldsModified['constellation']);
        }

        $this->data['fields']['constellation'] = $value;
    }

    /**
     * Returns the "constellation" field.
     *
     * @return mixed The constellation field.
     */
    public function getConstellation()
    {
        return $this->data['fields']['constellation'];
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
        if (isset($array['douban_id'])) {
            $this->setDoubanId($array['douban_id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['name_en'])) {
            $this->setNameEn($array['name_en']);
        }
        if (isset($array['avatars'])) {
            $this->setAvatars($array['avatars']);
        }
        if (isset($array['summary'])) {
            $this->setSummary($array['summary']);
        }
        if (isset($array['gender'])) {
            $this->setGender($array['gender']);
        }
        if (isset($array['birthday'])) {
            $this->setBirthday($array['birthday']);
        }
        if (isset($array['country'])) {
            $this->setCountry($array['country']);
        }
        if (isset($array['born_place'])) {
            $this->setBornPlace($array['born_place']);
        }
        if (isset($array['professions'])) {
            $this->setProfessions($array['professions']);
        }
        if (isset($array['constellation'])) {
            $this->setConstellation($array['constellation']);
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

        if (null !== $this->data['fields']['douban_id']) {
            $array['douban_id'] = $this->data['fields']['douban_id'];
        }
        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['name_en']) {
            $array['name_en'] = $this->data['fields']['name_en'];
        }
        if (null !== $this->data['fields']['avatars']) {
            $array['avatars'] = $this->data['fields']['avatars'];
        }
        if (null !== $this->data['fields']['summary']) {
            $array['summary'] = $this->data['fields']['summary'];
        }
        if (null !== $this->data['fields']['gender']) {
            $array['gender'] = $this->data['fields']['gender'];
        }
        if (null !== $this->data['fields']['birthday']) {
            $array['birthday'] = $this->data['fields']['birthday'];
        }
        if (null !== $this->data['fields']['country']) {
            $array['country'] = $this->data['fields']['country'];
        }
        if (null !== $this->data['fields']['born_place']) {
            $array['born_place'] = $this->data['fields']['born_place'];
        }
        if (null !== $this->data['fields']['professions']) {
            $array['professions'] = $this->data['fields']['professions'];
        }
        if (null !== $this->data['fields']['constellation']) {
            $array['constellation'] = $this->data['fields']['constellation'];
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
                'douban_id' => array(
                    'type' => 'integer',
                ),
                'name' => array(
                    'type' => 'string',
                ),
                'name_en' => array(
                    'type' => 'string',
                ),
                'avatars' => array(
                    'type' => 'string',
                ),
                'summary' => array(
                    'type' => 'string',
                ),
                'gender' => array(
                    'type' => 'string',
                ),
                'birthday' => array(
                    'type' => 'string',
                ),
                'country' => array(
                    'type' => 'string',
                ),
                'born_place' => array(
                    'type' => 'string',
                ),
                'professions' => array(
                    'type' => 'string',
                ),
                'constellation' => array(
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