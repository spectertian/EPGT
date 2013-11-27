<?php

/**
 * Base class of ReportSpchannel document.
 */
abstract class BaseReportSpchannel extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'spid' => null,
            'name' => null,
            'service_id' => null,
            'frequency' => null,
            'symbol_rate' => null,
            'modulation' => null,
            'on_id' => null,
            'ts_id' => null,
            'logic_number' => null,
            'channel_code' => null,
            'num' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'spid' => 'Spid',
        'name' => 'Name',
        'service_id' => 'ServiceId',
        'frequency' => 'Frequency',
        'symbol_rate' => 'SymbolRate',
        'modulation' => 'Modulation',
        'on_id' => 'OnId',
        'ts_id' => 'TsId',
        'logic_number' => 'LogicNumber',
        'channel_code' => 'ChannelCode',
        'num' => 'Num',
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
        return \Mondongo\Container::getForDocumentClass('ReportSpchannel');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('ReportSpchannel');
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

        if (isset($data['spid'])) {
            $this->data['fields']['spid'] = (string) $data['spid'];
        }
        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['service_id'])) {
            $this->data['fields']['service_id'] = (string) $data['service_id'];
        }
        if (isset($data['frequency'])) {
            $this->data['fields']['frequency'] = (string) $data['frequency'];
        }
        if (isset($data['symbol_rate'])) {
            $this->data['fields']['symbol_rate'] = (string) $data['symbol_rate'];
        }
        if (isset($data['modulation'])) {
            $this->data['fields']['modulation'] = (string) $data['modulation'];
        }
        if (isset($data['on_id'])) {
            $this->data['fields']['on_id'] = (string) $data['on_id'];
        }
        if (isset($data['ts_id'])) {
            $this->data['fields']['ts_id'] = (string) $data['ts_id'];
        }
        if (isset($data['logic_number'])) {
            $this->data['fields']['logic_number'] = (string) $data['logic_number'];
        }
        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['num'])) {
            $this->data['fields']['num'] = (int) $data['num'];
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
        if (isset($fields['spid'])) {
            $fields['spid'] = (string) $fields['spid'];
        }
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['service_id'])) {
            $fields['service_id'] = (string) $fields['service_id'];
        }
        if (isset($fields['frequency'])) {
            $fields['frequency'] = (string) $fields['frequency'];
        }
        if (isset($fields['symbol_rate'])) {
            $fields['symbol_rate'] = (string) $fields['symbol_rate'];
        }
        if (isset($fields['modulation'])) {
            $fields['modulation'] = (string) $fields['modulation'];
        }
        if (isset($fields['on_id'])) {
            $fields['on_id'] = (string) $fields['on_id'];
        }
        if (isset($fields['ts_id'])) {
            $fields['ts_id'] = (string) $fields['ts_id'];
        }
        if (isset($fields['logic_number'])) {
            $fields['logic_number'] = (string) $fields['logic_number'];
        }
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['num'])) {
            $fields['num'] = (int) $fields['num'];
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
     * Set the "spid" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSpid($value)
    {
        if (!array_key_exists('spid', $this->fieldsModified)) {
            $this->fieldsModified['spid'] = $this->data['fields']['spid'];
        } elseif ($value === $this->fieldsModified['spid']) {
            unset($this->fieldsModified['spid']);
        }

        $this->data['fields']['spid'] = $value;
    }

    /**
     * Returns the "spid" field.
     *
     * @return mixed The spid field.
     */
    public function getSpid()
    {
        return $this->data['fields']['spid'];
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
     * Set the "service_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setServiceId($value)
    {
        if (!array_key_exists('service_id', $this->fieldsModified)) {
            $this->fieldsModified['service_id'] = $this->data['fields']['service_id'];
        } elseif ($value === $this->fieldsModified['service_id']) {
            unset($this->fieldsModified['service_id']);
        }

        $this->data['fields']['service_id'] = $value;
    }

    /**
     * Returns the "service_id" field.
     *
     * @return mixed The service_id field.
     */
    public function getServiceId()
    {
        return $this->data['fields']['service_id'];
    }

    /**
     * Set the "frequency" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setFrequency($value)
    {
        if (!array_key_exists('frequency', $this->fieldsModified)) {
            $this->fieldsModified['frequency'] = $this->data['fields']['frequency'];
        } elseif ($value === $this->fieldsModified['frequency']) {
            unset($this->fieldsModified['frequency']);
        }

        $this->data['fields']['frequency'] = $value;
    }

    /**
     * Returns the "frequency" field.
     *
     * @return mixed The frequency field.
     */
    public function getFrequency()
    {
        return $this->data['fields']['frequency'];
    }

    /**
     * Set the "symbol_rate" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSymbolRate($value)
    {
        if (!array_key_exists('symbol_rate', $this->fieldsModified)) {
            $this->fieldsModified['symbol_rate'] = $this->data['fields']['symbol_rate'];
        } elseif ($value === $this->fieldsModified['symbol_rate']) {
            unset($this->fieldsModified['symbol_rate']);
        }

        $this->data['fields']['symbol_rate'] = $value;
    }

    /**
     * Returns the "symbol_rate" field.
     *
     * @return mixed The symbol_rate field.
     */
    public function getSymbolRate()
    {
        return $this->data['fields']['symbol_rate'];
    }

    /**
     * Set the "modulation" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setModulation($value)
    {
        if (!array_key_exists('modulation', $this->fieldsModified)) {
            $this->fieldsModified['modulation'] = $this->data['fields']['modulation'];
        } elseif ($value === $this->fieldsModified['modulation']) {
            unset($this->fieldsModified['modulation']);
        }

        $this->data['fields']['modulation'] = $value;
    }

    /**
     * Returns the "modulation" field.
     *
     * @return mixed The modulation field.
     */
    public function getModulation()
    {
        return $this->data['fields']['modulation'];
    }

    /**
     * Set the "on_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setOnId($value)
    {
        if (!array_key_exists('on_id', $this->fieldsModified)) {
            $this->fieldsModified['on_id'] = $this->data['fields']['on_id'];
        } elseif ($value === $this->fieldsModified['on_id']) {
            unset($this->fieldsModified['on_id']);
        }

        $this->data['fields']['on_id'] = $value;
    }

    /**
     * Returns the "on_id" field.
     *
     * @return mixed The on_id field.
     */
    public function getOnId()
    {
        return $this->data['fields']['on_id'];
    }

    /**
     * Set the "ts_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTsId($value)
    {
        if (!array_key_exists('ts_id', $this->fieldsModified)) {
            $this->fieldsModified['ts_id'] = $this->data['fields']['ts_id'];
        } elseif ($value === $this->fieldsModified['ts_id']) {
            unset($this->fieldsModified['ts_id']);
        }

        $this->data['fields']['ts_id'] = $value;
    }

    /**
     * Returns the "ts_id" field.
     *
     * @return mixed The ts_id field.
     */
    public function getTsId()
    {
        return $this->data['fields']['ts_id'];
    }

    /**
     * Set the "logic_number" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLogicNumber($value)
    {
        if (!array_key_exists('logic_number', $this->fieldsModified)) {
            $this->fieldsModified['logic_number'] = $this->data['fields']['logic_number'];
        } elseif ($value === $this->fieldsModified['logic_number']) {
            unset($this->fieldsModified['logic_number']);
        }

        $this->data['fields']['logic_number'] = $value;
    }

    /**
     * Returns the "logic_number" field.
     *
     * @return mixed The logic_number field.
     */
    public function getLogicNumber()
    {
        return $this->data['fields']['logic_number'];
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
     * Set the "num" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setNum($value)
    {
        if (!array_key_exists('num', $this->fieldsModified)) {
            $this->fieldsModified['num'] = $this->data['fields']['num'];
        } elseif ($value === $this->fieldsModified['num']) {
            unset($this->fieldsModified['num']);
        }

        $this->data['fields']['num'] = $value;
    }

    /**
     * Returns the "num" field.
     *
     * @return mixed The num field.
     */
    public function getNum()
    {
        return $this->data['fields']['num'];
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
        if (isset($array['spid'])) {
            $this->setSpid($array['spid']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['service_id'])) {
            $this->setServiceId($array['service_id']);
        }
        if (isset($array['frequency'])) {
            $this->setFrequency($array['frequency']);
        }
        if (isset($array['symbol_rate'])) {
            $this->setSymbolRate($array['symbol_rate']);
        }
        if (isset($array['modulation'])) {
            $this->setModulation($array['modulation']);
        }
        if (isset($array['on_id'])) {
            $this->setOnId($array['on_id']);
        }
        if (isset($array['ts_id'])) {
            $this->setTsId($array['ts_id']);
        }
        if (isset($array['logic_number'])) {
            $this->setLogicNumber($array['logic_number']);
        }
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['num'])) {
            $this->setNum($array['num']);
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

        if (null !== $this->data['fields']['spid']) {
            $array['spid'] = $this->data['fields']['spid'];
        }
        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['service_id']) {
            $array['service_id'] = $this->data['fields']['service_id'];
        }
        if (null !== $this->data['fields']['frequency']) {
            $array['frequency'] = $this->data['fields']['frequency'];
        }
        if (null !== $this->data['fields']['symbol_rate']) {
            $array['symbol_rate'] = $this->data['fields']['symbol_rate'];
        }
        if (null !== $this->data['fields']['modulation']) {
            $array['modulation'] = $this->data['fields']['modulation'];
        }
        if (null !== $this->data['fields']['on_id']) {
            $array['on_id'] = $this->data['fields']['on_id'];
        }
        if (null !== $this->data['fields']['ts_id']) {
            $array['ts_id'] = $this->data['fields']['ts_id'];
        }
        if (null !== $this->data['fields']['logic_number']) {
            $array['logic_number'] = $this->data['fields']['logic_number'];
        }
        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['num']) {
            $array['num'] = $this->data['fields']['num'];
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
                'spid' => array(
                    'type' => 'string',
                ),
                'name' => array(
                    'type' => 'string',
                ),
                'service_id' => array(
                    'type' => 'string',
                ),
                'frequency' => array(
                    'type' => 'string',
                ),
                'symbol_rate' => array(
                    'type' => 'string',
                ),
                'modulation' => array(
                    'type' => 'string',
                ),
                'on_id' => array(
                    'type' => 'string',
                ),
                'ts_id' => array(
                    'type' => 'string',
                ),
                'logic_number' => array(
                    'type' => 'string',
                ),
                'channel_code' => array(
                    'type' => 'string',
                ),
                'num' => array(
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