<?php

/**
 * SpService Base Form.
 */
class BaseSpServiceForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'sp_code' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'serviceId' => new sfWidgetFormInputText(array(), array()),
            'frequency' => new sfWidgetFormInputText(array(), array()),
            'symbolRate' => new sfWidgetFormInputText(array(), array()),
            'modulation' => new sfWidgetFormInputText(array(), array()),
            'onId' => new sfWidgetFormInputText(array(), array()),
            'tsId' => new sfWidgetFormInputText(array(), array()),
            'logicNumber' => new sfWidgetFormInputText(array(), array()),
            'videoPID' => new sfWidgetFormInputText(array(), array()),
            'audioPID' => new sfWidgetFormInputText(array(), array()),
            'PCRPID' => new sfWidgetFormInputText(array(), array()),
            'isFree' => new sfWidgetFormInputText(array(), array()),
            'location' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'channel_id' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'channel_codea' => new sfWidgetFormInputText(array(), array()),
            'channel_logo' => new sfWidgetFormInputText(array(), array()),
            'channel_num' => new sfWidgetFormInputText(array(), array()),
            'hot' => new sfWidgetFormInputText(array(), array()),
            'check_epg' => new sfWidgetFormInputCheckbox(array(), array()),
            'check_epgbak' => new sfWidgetFormInputCheckbox(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'sp_code' => new sfValidatorString(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'serviceId' => new sfValidatorString(array(), array()),
            'frequency' => new sfValidatorString(array(), array()),
            'symbolRate' => new sfValidatorString(array(), array()),
            'modulation' => new sfValidatorString(array(), array()),
            'onId' => new sfValidatorString(array(), array()),
            'tsId' => new sfValidatorString(array(), array()),
            'logicNumber' => new sfValidatorInteger(array(), array()),
            'videoPID' => new sfValidatorString(array(), array()),
            'audioPID' => new sfValidatorString(array(), array()),
            'PCRPID' => new sfValidatorString(array(), array()),
            'isFree' => new sfValidatorString(array(), array()),
            'location' => new sfValidatorString(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'channel_id' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'channel_codea' => new sfValidatorString(array(), array()),
            'channel_logo' => new sfValidatorString(array(), array()),
            'channel_num' => new sfValidatorString(array(), array()),
            'hot' => new sfValidatorInteger(array(), array()),
            'check_epg' => new sfValidatorBoolean(array(), array()),
            'check_epgbak' => new sfValidatorBoolean(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('sp_service[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'SpService';
    }
}