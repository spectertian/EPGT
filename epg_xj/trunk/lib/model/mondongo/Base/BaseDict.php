<?php

/**
 * Base class of Dict document.
 */
abstract class BaseDict extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'name' => null,
            'tf' => null,
            'idf' => null,
            'attr' => null,
            'state' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'name' => 'Name',
        'tf' => 'Tf',
        'idf' => 'Idf',
        'attr' => 'Attr',
        'state' => 'State',
    );

    /**
     * Returns the Mondongo of the document.
     *
     * @return Mondongo\Mondongo The Mondongo of the document.
     */
    public function getMondongo()
    {
        return \Mondongo\Container::getForDocumentClass('Dict');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Dict');
    }

    /**
     * Set the data in the document (hydrate).
     *
     * @return void
     */
    public function setDocumentData($data)
    {
        $this->id = $data['_id'];

        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['tf'])) {
            $this->data['fields']['tf'] = (string) $data['tf'];
        }
        if (isset($data['idf'])) {
            $this->data['fields']['idf'] = (string) $data['idf'];
        }
        if (isset($data['attr'])) {
            $this->data['fields']['attr'] = (string) $data['attr'];
        }
        if (isset($data['state'])) {
            $this->data['fields']['state'] = (int) $data['state'];
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
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['tf'])) {
            $fields['tf'] = (string) $fields['tf'];
        }
        if (isset($fields['idf'])) {
            $fields['idf'] = (string) $fields['idf'];
        }
        if (isset($fields['attr'])) {
            $fields['attr'] = (string) $fields['attr'];
        }
        if (isset($fields['state'])) {
            $fields['state'] = (int) $fields['state'];
        }


        return $fields;
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
     * Set the "tf" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTf($value)
    {
        if (!array_key_exists('tf', $this->fieldsModified)) {
            $this->fieldsModified['tf'] = $this->data['fields']['tf'];
        } elseif ($value === $this->fieldsModified['tf']) {
            unset($this->fieldsModified['tf']);
        }

        $this->data['fields']['tf'] = $value;
    }

    /**
     * Returns the "tf" field.
     *
     * @return mixed The tf field.
     */
    public function getTf()
    {
        return $this->data['fields']['tf'];
    }

    /**
     * Set the "idf" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setIdf($value)
    {
        if (!array_key_exists('idf', $this->fieldsModified)) {
            $this->fieldsModified['idf'] = $this->data['fields']['idf'];
        } elseif ($value === $this->fieldsModified['idf']) {
            unset($this->fieldsModified['idf']);
        }

        $this->data['fields']['idf'] = $value;
    }

    /**
     * Returns the "idf" field.
     *
     * @return mixed The idf field.
     */
    public function getIdf()
    {
        return $this->data['fields']['idf'];
    }

    /**
     * Set the "attr" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAttr($value)
    {
        if (!array_key_exists('attr', $this->fieldsModified)) {
            $this->fieldsModified['attr'] = $this->data['fields']['attr'];
        } elseif ($value === $this->fieldsModified['attr']) {
            unset($this->fieldsModified['attr']);
        }

        $this->data['fields']['attr'] = $value;
    }

    /**
     * Returns the "attr" field.
     *
     * @return mixed The attr field.
     */
    public function getAttr()
    {
        return $this->data['fields']['attr'];
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


    public function preInsertExtensions()
    {

    }


    public function postInsertExtensions()
    {

    }


    public function preUpdateExtensions()
    {

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
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['tf'])) {
            $this->setTf($array['tf']);
        }
        if (isset($array['idf'])) {
            $this->setIdf($array['idf']);
        }
        if (isset($array['attr'])) {
            $this->setAttr($array['attr']);
        }
        if (isset($array['state'])) {
            $this->setState($array['state']);
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

        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['tf']) {
            $array['tf'] = $this->data['fields']['tf'];
        }
        if (null !== $this->data['fields']['idf']) {
            $array['idf'] = $this->data['fields']['idf'];
        }
        if (null !== $this->data['fields']['attr']) {
            $array['attr'] = $this->data['fields']['attr'];
        }
        if (null !== $this->data['fields']['state']) {
            $array['state'] = $this->data['fields']['state'];
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
                'name' => array(
                    'type' => 'string',
                ),
                'tf' => array(
                    'type' => 'string',
                ),
                'idf' => array(
                    'type' => 'string',
                ),
                'attr' => array(
                    'type' => 'string',
                ),
                'state' => array(
                    'type' => 'integer',
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