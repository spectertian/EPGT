<?php

/**
 * Base class of ContentImport document.
 */
abstract class BaseContentImport extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'inject_id' => null,
            'from' => null,
            'provider_id' => null,
            'from_type' => null,
            'from_id' => null,
            'from_title' => null,
            'children_id' => null,
            'wiki_id' => null,
            'state' => null,
            'state_edit' => null,
            'state_match' => null,
            'state_check' => null,
            'state_error' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'inject_id' => 'InjectId',
        'from' => 'From',
        'provider_id' => 'ProviderId',
        'from_type' => 'FromType',
        'from_id' => 'FromId',
        'from_title' => 'FromTitle',
        'children_id' => 'ChildrenId',
        'wiki_id' => 'WikiId',
        'state' => 'State',
        'state_edit' => 'StateEdit',
        'state_match' => 'StateMatch',
        'state_check' => 'StateCheck',
        'state_error' => 'StateError',
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
        return \Mondongo\Container::getForDocumentClass('ContentImport');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('ContentImport');
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

        if (isset($data['inject_id'])) {
            $this->data['fields']['inject_id'] = (string) $data['inject_id'];
        }
        if (isset($data['from'])) {
            $this->data['fields']['from'] = (string) $data['from'];
        }
        if (isset($data['provider_id'])) {
            $this->data['fields']['provider_id'] = (string) $data['provider_id'];
        }
        if (isset($data['from_type'])) {
            $this->data['fields']['from_type'] = (string) $data['from_type'];
        }
        if (isset($data['from_id'])) {
            $this->data['fields']['from_id'] = (string) $data['from_id'];
        }
        if (isset($data['from_title'])) {
            $this->data['fields']['from_title'] = (string) $data['from_title'];
        }
        if (isset($data['children_id'])) {
            $this->data['fields']['children_id'] = $data['children_id'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['state'])) {
            $this->data['fields']['state'] = (int) $data['state'];
        }
        if (isset($data['state_edit'])) {
            $this->data['fields']['state_edit'] = (int) $data['state_edit'];
        }
        if (isset($data['state_match'])) {
            $this->data['fields']['state_match'] = (int) $data['state_match'];
        }
        if (isset($data['state_check'])) {
            $this->data['fields']['state_check'] = (int) $data['state_check'];
        }
        if (isset($data['state_error'])) {
            $this->data['fields']['state_error'] = (int) $data['state_error'];
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
        if (isset($fields['inject_id'])) {
            $fields['inject_id'] = (string) $fields['inject_id'];
        }
        if (isset($fields['from'])) {
            $fields['from'] = (string) $fields['from'];
        }
        if (isset($fields['provider_id'])) {
            $fields['provider_id'] = (string) $fields['provider_id'];
        }
        if (isset($fields['from_type'])) {
            $fields['from_type'] = (string) $fields['from_type'];
        }
        if (isset($fields['from_id'])) {
            $fields['from_id'] = (string) $fields['from_id'];
        }
        if (isset($fields['from_title'])) {
            $fields['from_title'] = (string) $fields['from_title'];
        }
        if (isset($fields['children_id'])) {
            $fields['children_id'] = $fields['children_id'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['state'])) {
            $fields['state'] = (int) $fields['state'];
        }
        if (isset($fields['state_edit'])) {
            $fields['state_edit'] = (int) $fields['state_edit'];
        }
        if (isset($fields['state_match'])) {
            $fields['state_match'] = (int) $fields['state_match'];
        }
        if (isset($fields['state_check'])) {
            $fields['state_check'] = (int) $fields['state_check'];
        }
        if (isset($fields['state_error'])) {
            $fields['state_error'] = (int) $fields['state_error'];
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
     * Set the "inject_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setInjectId($value)
    {
        if (!array_key_exists('inject_id', $this->fieldsModified)) {
            $this->fieldsModified['inject_id'] = $this->data['fields']['inject_id'];
        } elseif ($value === $this->fieldsModified['inject_id']) {
            unset($this->fieldsModified['inject_id']);
        }

        $this->data['fields']['inject_id'] = $value;
    }

    /**
     * Returns the "inject_id" field.
     *
     * @return mixed The inject_id field.
     */
    public function getInjectId()
    {
        return $this->data['fields']['inject_id'];
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
     * Set the "provider_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setProviderId($value)
    {
        if (!array_key_exists('provider_id', $this->fieldsModified)) {
            $this->fieldsModified['provider_id'] = $this->data['fields']['provider_id'];
        } elseif ($value === $this->fieldsModified['provider_id']) {
            unset($this->fieldsModified['provider_id']);
        }

        $this->data['fields']['provider_id'] = $value;
    }

    /**
     * Returns the "provider_id" field.
     *
     * @return mixed The provider_id field.
     */
    public function getProviderId()
    {
        return $this->data['fields']['provider_id'];
    }

    /**
     * Set the "from_type" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setFromType($value)
    {
        if (!array_key_exists('from_type', $this->fieldsModified)) {
            $this->fieldsModified['from_type'] = $this->data['fields']['from_type'];
        } elseif ($value === $this->fieldsModified['from_type']) {
            unset($this->fieldsModified['from_type']);
        }

        $this->data['fields']['from_type'] = $value;
    }

    /**
     * Returns the "from_type" field.
     *
     * @return mixed The from_type field.
     */
    public function getFromType()
    {
        return $this->data['fields']['from_type'];
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
     * Set the "from_title" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setFromTitle($value)
    {
        if (!array_key_exists('from_title', $this->fieldsModified)) {
            $this->fieldsModified['from_title'] = $this->data['fields']['from_title'];
        } elseif ($value === $this->fieldsModified['from_title']) {
            unset($this->fieldsModified['from_title']);
        }

        $this->data['fields']['from_title'] = $value;
    }

    /**
     * Returns the "from_title" field.
     *
     * @return mixed The from_title field.
     */
    public function getFromTitle()
    {
        return $this->data['fields']['from_title'];
    }

    /**
     * Set the "children_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChildrenId($value)
    {
        if (!array_key_exists('children_id', $this->fieldsModified)) {
            $this->fieldsModified['children_id'] = $this->data['fields']['children_id'];
        } elseif ($value === $this->fieldsModified['children_id']) {
            unset($this->fieldsModified['children_id']);
        }

        $this->data['fields']['children_id'] = $value;
    }

    /**
     * Returns the "children_id" field.
     *
     * @return mixed The children_id field.
     */
    public function getChildrenId()
    {
        return $this->data['fields']['children_id'];
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
     * Set the "state_edit" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStateEdit($value)
    {
        if (!array_key_exists('state_edit', $this->fieldsModified)) {
            $this->fieldsModified['state_edit'] = $this->data['fields']['state_edit'];
        } elseif ($value === $this->fieldsModified['state_edit']) {
            unset($this->fieldsModified['state_edit']);
        }

        $this->data['fields']['state_edit'] = $value;
    }

    /**
     * Returns the "state_edit" field.
     *
     * @return mixed The state_edit field.
     */
    public function getStateEdit()
    {
        return $this->data['fields']['state_edit'];
    }

    /**
     * Set the "state_match" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStateMatch($value)
    {
        if (!array_key_exists('state_match', $this->fieldsModified)) {
            $this->fieldsModified['state_match'] = $this->data['fields']['state_match'];
        } elseif ($value === $this->fieldsModified['state_match']) {
            unset($this->fieldsModified['state_match']);
        }

        $this->data['fields']['state_match'] = $value;
    }

    /**
     * Returns the "state_match" field.
     *
     * @return mixed The state_match field.
     */
    public function getStateMatch()
    {
        return $this->data['fields']['state_match'];
    }

    /**
     * Set the "state_check" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStateCheck($value)
    {
        if (!array_key_exists('state_check', $this->fieldsModified)) {
            $this->fieldsModified['state_check'] = $this->data['fields']['state_check'];
        } elseif ($value === $this->fieldsModified['state_check']) {
            unset($this->fieldsModified['state_check']);
        }

        $this->data['fields']['state_check'] = $value;
    }

    /**
     * Returns the "state_check" field.
     *
     * @return mixed The state_check field.
     */
    public function getStateCheck()
    {
        return $this->data['fields']['state_check'];
    }

    /**
     * Set the "state_error" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setStateError($value)
    {
        if (!array_key_exists('state_error', $this->fieldsModified)) {
            $this->fieldsModified['state_error'] = $this->data['fields']['state_error'];
        } elseif ($value === $this->fieldsModified['state_error']) {
            unset($this->fieldsModified['state_error']);
        }

        $this->data['fields']['state_error'] = $value;
    }

    /**
     * Returns the "state_error" field.
     *
     * @return mixed The state_error field.
     */
    public function getStateError()
    {
        return $this->data['fields']['state_error'];
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
        if (isset($array['inject_id'])) {
            $this->setInjectId($array['inject_id']);
        }
        if (isset($array['from'])) {
            $this->setFrom($array['from']);
        }
        if (isset($array['provider_id'])) {
            $this->setProviderId($array['provider_id']);
        }
        if (isset($array['from_type'])) {
            $this->setFromType($array['from_type']);
        }
        if (isset($array['from_id'])) {
            $this->setFromId($array['from_id']);
        }
        if (isset($array['from_title'])) {
            $this->setFromTitle($array['from_title']);
        }
        if (isset($array['children_id'])) {
            $this->setChildrenId($array['children_id']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['state'])) {
            $this->setState($array['state']);
        }
        if (isset($array['state_edit'])) {
            $this->setStateEdit($array['state_edit']);
        }
        if (isset($array['state_match'])) {
            $this->setStateMatch($array['state_match']);
        }
        if (isset($array['state_check'])) {
            $this->setStateCheck($array['state_check']);
        }
        if (isset($array['state_error'])) {
            $this->setStateError($array['state_error']);
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

        if (null !== $this->data['fields']['inject_id']) {
            $array['inject_id'] = $this->data['fields']['inject_id'];
        }
        if (null !== $this->data['fields']['from']) {
            $array['from'] = $this->data['fields']['from'];
        }
        if (null !== $this->data['fields']['provider_id']) {
            $array['provider_id'] = $this->data['fields']['provider_id'];
        }
        if (null !== $this->data['fields']['from_type']) {
            $array['from_type'] = $this->data['fields']['from_type'];
        }
        if (null !== $this->data['fields']['from_id']) {
            $array['from_id'] = $this->data['fields']['from_id'];
        }
        if (null !== $this->data['fields']['from_title']) {
            $array['from_title'] = $this->data['fields']['from_title'];
        }
        if (null !== $this->data['fields']['children_id']) {
            $array['children_id'] = $this->data['fields']['children_id'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['state']) {
            $array['state'] = $this->data['fields']['state'];
        }
        if (null !== $this->data['fields']['state_edit']) {
            $array['state_edit'] = $this->data['fields']['state_edit'];
        }
        if (null !== $this->data['fields']['state_match']) {
            $array['state_match'] = $this->data['fields']['state_match'];
        }
        if (null !== $this->data['fields']['state_check']) {
            $array['state_check'] = $this->data['fields']['state_check'];
        }
        if (null !== $this->data['fields']['state_error']) {
            $array['state_error'] = $this->data['fields']['state_error'];
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
                'inject_id' => array(
                    'type' => 'string',
                ),
                'from' => array(
                    'type' => 'string',
                ),
                'provider_id' => array(
                    'type' => 'string',
                ),
                'from_type' => array(
                    'type' => 'string',
                ),
                'from_id' => array(
                    'type' => 'string',
                ),
                'from_title' => array(
                    'type' => 'string',
                ),
                'children_id' => array(
                    'type' => 'raw',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'state' => array(
                    'type' => 'integer',
                ),
                'state_edit' => array(
                    'type' => 'integer',
                ),
                'state_match' => array(
                    'type' => 'integer',
                ),
                'state_check' => array(
                    'type' => 'integer',
                ),
                'state_error' => array(
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