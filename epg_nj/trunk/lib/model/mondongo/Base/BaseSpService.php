<?php

/**
 * Base class of SpService document.
 */
abstract class BaseSpService extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'sp_code' => null,
            'name' => null,
            'serviceId' => null,
            'frequency' => null,
            'symbolRate' => null,
            'modulation' => null,
            'onId' => null,
            'tsId' => null,
            'logicNumber' => null,
            'videoPID' => null,
            'audioPID' => null,
            'PCRPID' => null,
            'isFree' => null,
            'location' => null,
            'tags' => null,
            'channel_id' => null,
            'channel_code' => null,
            'channel_codea' => null,
            'channel_logo' => null,
            'channel_num' => null,
            'hot' => null,
            'check_epg' => null,
            'check_epgbak' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'sp_code' => 'SpCode',
        'name' => 'Name',
        'serviceId' => 'ServiceId',
        'frequency' => 'Frequency',
        'symbolRate' => 'SymbolRate',
        'modulation' => 'Modulation',
        'onId' => 'OnId',
        'tsId' => 'TsId',
        'logicNumber' => 'LogicNumber',
        'videoPID' => 'VideoPID',
        'audioPID' => 'AudioPID',
        'PCRPID' => 'PCRPID',
        'isFree' => 'IsFree',
        'location' => 'Location',
        'tags' => 'Tags',
        'channel_id' => 'ChannelId',
        'channel_code' => 'ChannelCode',
        'channel_codea' => 'ChannelCodea',
        'channel_logo' => 'ChannelLogo',
        'channel_num' => 'ChannelNum',
        'hot' => 'Hot',
        'check_epg' => 'CheckEpg',
        'check_epgbak' => 'CheckEpgbak',
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
        return \Mondongo\Container::getForDocumentClass('SpService');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('SpService');
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

        if (isset($data['sp_code'])) {
            $this->data['fields']['sp_code'] = (string) $data['sp_code'];
        }
        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['serviceId'])) {
            $this->data['fields']['serviceId'] = (string) $data['serviceId'];
        }
        if (isset($data['frequency'])) {
            $this->data['fields']['frequency'] = (string) $data['frequency'];
        }
        if (isset($data['symbolRate'])) {
            $this->data['fields']['symbolRate'] = (string) $data['symbolRate'];
        }
        if (isset($data['modulation'])) {
            $this->data['fields']['modulation'] = (string) $data['modulation'];
        }
        if (isset($data['onId'])) {
            $this->data['fields']['onId'] = (string) $data['onId'];
        }
        if (isset($data['tsId'])) {
            $this->data['fields']['tsId'] = (string) $data['tsId'];
        }
        if (isset($data['logicNumber'])) {
            $this->data['fields']['logicNumber'] = (int) $data['logicNumber'];
        }
        if (isset($data['videoPID'])) {
            $this->data['fields']['videoPID'] = (string) $data['videoPID'];
        }
        if (isset($data['audioPID'])) {
            $this->data['fields']['audioPID'] = (string) $data['audioPID'];
        }
        if (isset($data['PCRPID'])) {
            $this->data['fields']['PCRPID'] = (string) $data['PCRPID'];
        }
        if (isset($data['isFree'])) {
            $this->data['fields']['isFree'] = (string) $data['isFree'];
        }
        if (isset($data['location'])) {
            $this->data['fields']['location'] = (string) $data['location'];
        }
        if (isset($data['tags'])) {
            $this->data['fields']['tags'] = $data['tags'];
        }
        if (isset($data['channel_id'])) {
            $this->data['fields']['channel_id'] = (string) $data['channel_id'];
        }
        if (isset($data['channel_code'])) {
            $this->data['fields']['channel_code'] = (string) $data['channel_code'];
        }
        if (isset($data['channel_codea'])) {
            $this->data['fields']['channel_codea'] = (string) $data['channel_codea'];
        }
        if (isset($data['channel_logo'])) {
            $this->data['fields']['channel_logo'] = (string) $data['channel_logo'];
        }
        if (isset($data['channel_num'])) {
            $this->data['fields']['channel_num'] = (string) $data['channel_num'];
        }
        if (isset($data['hot'])) {
            $this->data['fields']['hot'] = (int) $data['hot'];
        }
        if (isset($data['check_epg'])) {
            $this->data['fields']['check_epg'] = (bool) $data['check_epg'];
        }
        if (isset($data['check_epgbak'])) {
            $this->data['fields']['check_epgbak'] = (bool) $data['check_epgbak'];
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
        if (isset($fields['sp_code'])) {
            $fields['sp_code'] = (string) $fields['sp_code'];
        }
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['serviceId'])) {
            $fields['serviceId'] = (string) $fields['serviceId'];
        }
        if (isset($fields['frequency'])) {
            $fields['frequency'] = (string) $fields['frequency'];
        }
        if (isset($fields['symbolRate'])) {
            $fields['symbolRate'] = (string) $fields['symbolRate'];
        }
        if (isset($fields['modulation'])) {
            $fields['modulation'] = (string) $fields['modulation'];
        }
        if (isset($fields['onId'])) {
            $fields['onId'] = (string) $fields['onId'];
        }
        if (isset($fields['tsId'])) {
            $fields['tsId'] = (string) $fields['tsId'];
        }
        if (isset($fields['logicNumber'])) {
            $fields['logicNumber'] = (int) $fields['logicNumber'];
        }
        if (isset($fields['videoPID'])) {
            $fields['videoPID'] = (string) $fields['videoPID'];
        }
        if (isset($fields['audioPID'])) {
            $fields['audioPID'] = (string) $fields['audioPID'];
        }
        if (isset($fields['PCRPID'])) {
            $fields['PCRPID'] = (string) $fields['PCRPID'];
        }
        if (isset($fields['isFree'])) {
            $fields['isFree'] = (string) $fields['isFree'];
        }
        if (isset($fields['location'])) {
            $fields['location'] = (string) $fields['location'];
        }
        if (isset($fields['tags'])) {
            $fields['tags'] = $fields['tags'];
        }
        if (isset($fields['channel_id'])) {
            $fields['channel_id'] = (string) $fields['channel_id'];
        }
        if (isset($fields['channel_code'])) {
            $fields['channel_code'] = (string) $fields['channel_code'];
        }
        if (isset($fields['channel_codea'])) {
            $fields['channel_codea'] = (string) $fields['channel_codea'];
        }
        if (isset($fields['channel_logo'])) {
            $fields['channel_logo'] = (string) $fields['channel_logo'];
        }
        if (isset($fields['channel_num'])) {
            $fields['channel_num'] = (string) $fields['channel_num'];
        }
        if (isset($fields['hot'])) {
            $fields['hot'] = (int) $fields['hot'];
        }
        if (isset($fields['check_epg'])) {
            $fields['check_epg'] = (bool) $fields['check_epg'];
        }
        if (isset($fields['check_epgbak'])) {
            $fields['check_epgbak'] = (bool) $fields['check_epgbak'];
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
     * Set the "sp_code" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSpCode($value)
    {
        if (!array_key_exists('sp_code', $this->fieldsModified)) {
            $this->fieldsModified['sp_code'] = $this->data['fields']['sp_code'];
        } elseif ($value === $this->fieldsModified['sp_code']) {
            unset($this->fieldsModified['sp_code']);
        }

        $this->data['fields']['sp_code'] = $value;
    }

    /**
     * Returns the "sp_code" field.
     *
     * @return mixed The sp_code field.
     */
    public function getSpCode()
    {
        return $this->data['fields']['sp_code'];
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
     * Set the "serviceId" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setServiceId($value)
    {
        if (!array_key_exists('serviceId', $this->fieldsModified)) {
            $this->fieldsModified['serviceId'] = $this->data['fields']['serviceId'];
        } elseif ($value === $this->fieldsModified['serviceId']) {
            unset($this->fieldsModified['serviceId']);
        }

        $this->data['fields']['serviceId'] = $value;
    }

    /**
     * Returns the "serviceId" field.
     *
     * @return mixed The serviceId field.
     */
    public function getServiceId()
    {
        return $this->data['fields']['serviceId'];
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
     * Set the "symbolRate" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSymbolRate($value)
    {
        if (!array_key_exists('symbolRate', $this->fieldsModified)) {
            $this->fieldsModified['symbolRate'] = $this->data['fields']['symbolRate'];
        } elseif ($value === $this->fieldsModified['symbolRate']) {
            unset($this->fieldsModified['symbolRate']);
        }

        $this->data['fields']['symbolRate'] = $value;
    }

    /**
     * Returns the "symbolRate" field.
     *
     * @return mixed The symbolRate field.
     */
    public function getSymbolRate()
    {
        return $this->data['fields']['symbolRate'];
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
     * Set the "onId" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setOnId($value)
    {
        if (!array_key_exists('onId', $this->fieldsModified)) {
            $this->fieldsModified['onId'] = $this->data['fields']['onId'];
        } elseif ($value === $this->fieldsModified['onId']) {
            unset($this->fieldsModified['onId']);
        }

        $this->data['fields']['onId'] = $value;
    }

    /**
     * Returns the "onId" field.
     *
     * @return mixed The onId field.
     */
    public function getOnId()
    {
        return $this->data['fields']['onId'];
    }

    /**
     * Set the "tsId" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTsId($value)
    {
        if (!array_key_exists('tsId', $this->fieldsModified)) {
            $this->fieldsModified['tsId'] = $this->data['fields']['tsId'];
        } elseif ($value === $this->fieldsModified['tsId']) {
            unset($this->fieldsModified['tsId']);
        }

        $this->data['fields']['tsId'] = $value;
    }

    /**
     * Returns the "tsId" field.
     *
     * @return mixed The tsId field.
     */
    public function getTsId()
    {
        return $this->data['fields']['tsId'];
    }

    /**
     * Set the "logicNumber" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLogicNumber($value)
    {
        if (!array_key_exists('logicNumber', $this->fieldsModified)) {
            $this->fieldsModified['logicNumber'] = $this->data['fields']['logicNumber'];
        } elseif ($value === $this->fieldsModified['logicNumber']) {
            unset($this->fieldsModified['logicNumber']);
        }

        $this->data['fields']['logicNumber'] = $value;
    }

    /**
     * Returns the "logicNumber" field.
     *
     * @return mixed The logicNumber field.
     */
    public function getLogicNumber()
    {
        return $this->data['fields']['logicNumber'];
    }

    /**
     * Set the "videoPID" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setVideoPID($value)
    {
        if (!array_key_exists('videoPID', $this->fieldsModified)) {
            $this->fieldsModified['videoPID'] = $this->data['fields']['videoPID'];
        } elseif ($value === $this->fieldsModified['videoPID']) {
            unset($this->fieldsModified['videoPID']);
        }

        $this->data['fields']['videoPID'] = $value;
    }

    /**
     * Returns the "videoPID" field.
     *
     * @return mixed The videoPID field.
     */
    public function getVideoPID()
    {
        return $this->data['fields']['videoPID'];
    }

    /**
     * Set the "audioPID" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAudioPID($value)
    {
        if (!array_key_exists('audioPID', $this->fieldsModified)) {
            $this->fieldsModified['audioPID'] = $this->data['fields']['audioPID'];
        } elseif ($value === $this->fieldsModified['audioPID']) {
            unset($this->fieldsModified['audioPID']);
        }

        $this->data['fields']['audioPID'] = $value;
    }

    /**
     * Returns the "audioPID" field.
     *
     * @return mixed The audioPID field.
     */
    public function getAudioPID()
    {
        return $this->data['fields']['audioPID'];
    }

    /**
     * Set the "PCRPID" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPCRPID($value)
    {
        if (!array_key_exists('PCRPID', $this->fieldsModified)) {
            $this->fieldsModified['PCRPID'] = $this->data['fields']['PCRPID'];
        } elseif ($value === $this->fieldsModified['PCRPID']) {
            unset($this->fieldsModified['PCRPID']);
        }

        $this->data['fields']['PCRPID'] = $value;
    }

    /**
     * Returns the "PCRPID" field.
     *
     * @return mixed The PCRPID field.
     */
    public function getPCRPID()
    {
        return $this->data['fields']['PCRPID'];
    }

    /**
     * Set the "isFree" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setIsFree($value)
    {
        if (!array_key_exists('isFree', $this->fieldsModified)) {
            $this->fieldsModified['isFree'] = $this->data['fields']['isFree'];
        } elseif ($value === $this->fieldsModified['isFree']) {
            unset($this->fieldsModified['isFree']);
        }

        $this->data['fields']['isFree'] = $value;
    }

    /**
     * Returns the "isFree" field.
     *
     * @return mixed The isFree field.
     */
    public function getIsFree()
    {
        return $this->data['fields']['isFree'];
    }

    /**
     * Set the "location" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLocation($value)
    {
        if (!array_key_exists('location', $this->fieldsModified)) {
            $this->fieldsModified['location'] = $this->data['fields']['location'];
        } elseif ($value === $this->fieldsModified['location']) {
            unset($this->fieldsModified['location']);
        }

        $this->data['fields']['location'] = $value;
    }

    /**
     * Returns the "location" field.
     *
     * @return mixed The location field.
     */
    public function getLocation()
    {
        return $this->data['fields']['location'];
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
     * Set the "channel_codea" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelCodea($value)
    {
        if (!array_key_exists('channel_codea', $this->fieldsModified)) {
            $this->fieldsModified['channel_codea'] = $this->data['fields']['channel_codea'];
        } elseif ($value === $this->fieldsModified['channel_codea']) {
            unset($this->fieldsModified['channel_codea']);
        }

        $this->data['fields']['channel_codea'] = $value;
    }

    /**
     * Returns the "channel_codea" field.
     *
     * @return mixed The channel_codea field.
     */
    public function getChannelCodea()
    {
        return $this->data['fields']['channel_codea'];
    }

    /**
     * Set the "channel_logo" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelLogo($value)
    {
        if (!array_key_exists('channel_logo', $this->fieldsModified)) {
            $this->fieldsModified['channel_logo'] = $this->data['fields']['channel_logo'];
        } elseif ($value === $this->fieldsModified['channel_logo']) {
            unset($this->fieldsModified['channel_logo']);
        }

        $this->data['fields']['channel_logo'] = $value;
    }

    /**
     * Returns the "channel_logo" field.
     *
     * @return mixed The channel_logo field.
     */
    public function getChannelLogo()
    {
        return $this->data['fields']['channel_logo'];
    }

    /**
     * Set the "channel_num" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setChannelNum($value)
    {
        if (!array_key_exists('channel_num', $this->fieldsModified)) {
            $this->fieldsModified['channel_num'] = $this->data['fields']['channel_num'];
        } elseif ($value === $this->fieldsModified['channel_num']) {
            unset($this->fieldsModified['channel_num']);
        }

        $this->data['fields']['channel_num'] = $value;
    }

    /**
     * Returns the "channel_num" field.
     *
     * @return mixed The channel_num field.
     */
    public function getChannelNum()
    {
        return $this->data['fields']['channel_num'];
    }

    /**
     * Set the "hot" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setHot($value)
    {
        if (!array_key_exists('hot', $this->fieldsModified)) {
            $this->fieldsModified['hot'] = $this->data['fields']['hot'];
        } elseif ($value === $this->fieldsModified['hot']) {
            unset($this->fieldsModified['hot']);
        }

        $this->data['fields']['hot'] = $value;
    }

    /**
     * Returns the "hot" field.
     *
     * @return mixed The hot field.
     */
    public function getHot()
    {
        return $this->data['fields']['hot'];
    }

    /**
     * Set the "check_epg" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCheckEpg($value)
    {
        if (!array_key_exists('check_epg', $this->fieldsModified)) {
            $this->fieldsModified['check_epg'] = $this->data['fields']['check_epg'];
        } elseif ($value === $this->fieldsModified['check_epg']) {
            unset($this->fieldsModified['check_epg']);
        }

        $this->data['fields']['check_epg'] = $value;
    }

    /**
     * Returns the "check_epg" field.
     *
     * @return mixed The check_epg field.
     */
    public function getCheckEpg()
    {
        return $this->data['fields']['check_epg'];
    }

    /**
     * Set the "check_epgbak" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCheckEpgbak($value)
    {
        if (!array_key_exists('check_epgbak', $this->fieldsModified)) {
            $this->fieldsModified['check_epgbak'] = $this->data['fields']['check_epgbak'];
        } elseif ($value === $this->fieldsModified['check_epgbak']) {
            unset($this->fieldsModified['check_epgbak']);
        }

        $this->data['fields']['check_epgbak'] = $value;
    }

    /**
     * Returns the "check_epgbak" field.
     *
     * @return mixed The check_epgbak field.
     */
    public function getCheckEpgbak()
    {
        return $this->data['fields']['check_epgbak'];
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
        if (isset($array['sp_code'])) {
            $this->setSpCode($array['sp_code']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['serviceId'])) {
            $this->setServiceId($array['serviceId']);
        }
        if (isset($array['frequency'])) {
            $this->setFrequency($array['frequency']);
        }
        if (isset($array['symbolRate'])) {
            $this->setSymbolRate($array['symbolRate']);
        }
        if (isset($array['modulation'])) {
            $this->setModulation($array['modulation']);
        }
        if (isset($array['onId'])) {
            $this->setOnId($array['onId']);
        }
        if (isset($array['tsId'])) {
            $this->setTsId($array['tsId']);
        }
        if (isset($array['logicNumber'])) {
            $this->setLogicNumber($array['logicNumber']);
        }
        if (isset($array['videoPID'])) {
            $this->setVideoPID($array['videoPID']);
        }
        if (isset($array['audioPID'])) {
            $this->setAudioPID($array['audioPID']);
        }
        if (isset($array['PCRPID'])) {
            $this->setPCRPID($array['PCRPID']);
        }
        if (isset($array['isFree'])) {
            $this->setIsFree($array['isFree']);
        }
        if (isset($array['location'])) {
            $this->setLocation($array['location']);
        }
        if (isset($array['tags'])) {
            $this->setTags($array['tags']);
        }
        if (isset($array['channel_id'])) {
            $this->setChannelId($array['channel_id']);
        }
        if (isset($array['channel_code'])) {
            $this->setChannelCode($array['channel_code']);
        }
        if (isset($array['channel_codea'])) {
            $this->setChannelCodea($array['channel_codea']);
        }
        if (isset($array['channel_logo'])) {
            $this->setChannelLogo($array['channel_logo']);
        }
        if (isset($array['channel_num'])) {
            $this->setChannelNum($array['channel_num']);
        }
        if (isset($array['hot'])) {
            $this->setHot($array['hot']);
        }
        if (isset($array['check_epg'])) {
            $this->setCheckEpg($array['check_epg']);
        }
        if (isset($array['check_epgbak'])) {
            $this->setCheckEpgbak($array['check_epgbak']);
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

        if (null !== $this->data['fields']['sp_code']) {
            $array['sp_code'] = $this->data['fields']['sp_code'];
        }
        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['serviceId']) {
            $array['serviceId'] = $this->data['fields']['serviceId'];
        }
        if (null !== $this->data['fields']['frequency']) {
            $array['frequency'] = $this->data['fields']['frequency'];
        }
        if (null !== $this->data['fields']['symbolRate']) {
            $array['symbolRate'] = $this->data['fields']['symbolRate'];
        }
        if (null !== $this->data['fields']['modulation']) {
            $array['modulation'] = $this->data['fields']['modulation'];
        }
        if (null !== $this->data['fields']['onId']) {
            $array['onId'] = $this->data['fields']['onId'];
        }
        if (null !== $this->data['fields']['tsId']) {
            $array['tsId'] = $this->data['fields']['tsId'];
        }
        if (null !== $this->data['fields']['logicNumber']) {
            $array['logicNumber'] = $this->data['fields']['logicNumber'];
        }
        if (null !== $this->data['fields']['videoPID']) {
            $array['videoPID'] = $this->data['fields']['videoPID'];
        }
        if (null !== $this->data['fields']['audioPID']) {
            $array['audioPID'] = $this->data['fields']['audioPID'];
        }
        if (null !== $this->data['fields']['PCRPID']) {
            $array['PCRPID'] = $this->data['fields']['PCRPID'];
        }
        if (null !== $this->data['fields']['isFree']) {
            $array['isFree'] = $this->data['fields']['isFree'];
        }
        if (null !== $this->data['fields']['location']) {
            $array['location'] = $this->data['fields']['location'];
        }
        if (null !== $this->data['fields']['tags']) {
            $array['tags'] = $this->data['fields']['tags'];
        }
        if (null !== $this->data['fields']['channel_id']) {
            $array['channel_id'] = $this->data['fields']['channel_id'];
        }
        if (null !== $this->data['fields']['channel_code']) {
            $array['channel_code'] = $this->data['fields']['channel_code'];
        }
        if (null !== $this->data['fields']['channel_codea']) {
            $array['channel_codea'] = $this->data['fields']['channel_codea'];
        }
        if (null !== $this->data['fields']['channel_logo']) {
            $array['channel_logo'] = $this->data['fields']['channel_logo'];
        }
        if (null !== $this->data['fields']['channel_num']) {
            $array['channel_num'] = $this->data['fields']['channel_num'];
        }
        if (null !== $this->data['fields']['hot']) {
            $array['hot'] = $this->data['fields']['hot'];
        }
        if (null !== $this->data['fields']['check_epg']) {
            $array['check_epg'] = $this->data['fields']['check_epg'];
        }
        if (null !== $this->data['fields']['check_epgbak']) {
            $array['check_epgbak'] = $this->data['fields']['check_epgbak'];
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
                'sp_code' => array(
                    'type' => 'string',
                ),
                'name' => array(
                    'type' => 'string',
                ),
                'serviceId' => array(
                    'type' => 'string',
                ),
                'frequency' => array(
                    'type' => 'string',
                ),
                'symbolRate' => array(
                    'type' => 'string',
                ),
                'modulation' => array(
                    'type' => 'string',
                ),
                'onId' => array(
                    'type' => 'string',
                ),
                'tsId' => array(
                    'type' => 'string',
                ),
                'logicNumber' => array(
                    'type' => 'integer',
                ),
                'videoPID' => array(
                    'type' => 'string',
                ),
                'audioPID' => array(
                    'type' => 'string',
                ),
                'PCRPID' => array(
                    'type' => 'string',
                ),
                'isFree' => array(
                    'type' => 'string',
                ),
                'location' => array(
                    'type' => 'string',
                ),
                'tags' => array(
                    'type' => 'raw',
                ),
                'channel_id' => array(
                    'type' => 'string',
                ),
                'channel_code' => array(
                    'type' => 'string',
                ),
                'channel_codea' => array(
                    'type' => 'string',
                ),
                'channel_logo' => array(
                    'type' => 'string',
                ),
                'channel_num' => array(
                    'type' => 'string',
                ),
                'hot' => array(
                    'type' => 'integer',
                ),
                'check_epg' => array(
                    'type' => 'boolean',
                ),
                'check_epgbak' => array(
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