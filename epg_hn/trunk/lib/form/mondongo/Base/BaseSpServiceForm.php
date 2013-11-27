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
            'channelType' => new sfWidgetFormInputText(array(), array()),
            'name' => new sfWidgetFormInputText(array(), array()),
            'serviceId' => new sfWidgetFormInputText(array(), array()),
            'channelNetworkId' => new sfWidgetFormInputText(array(), array()),
            'logicNumber' => new sfWidgetFormInputText(array(), array()),
            'tags' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'channel_logo' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'channelType' => new sfValidatorInteger(array(), array()),
            'name' => new sfValidatorString(array(), array()),
            'serviceId' => new sfValidatorString(array(), array()),
            'channelNetworkId' => new sfValidatorInteger(array(), array()),
            'logicNumber' => new sfValidatorInteger(array(), array()),
            'tags' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'channel_logo' => new sfValidatorString(array(), array()),
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