<?php

/**
 * ChannelUpdate Base Form.
 */
class BaseChannelUpdateForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'time' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'channel_code' => new sfValidatorString(array(), array()),
            'time' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('channel_update[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ChannelUpdate';
    }
}