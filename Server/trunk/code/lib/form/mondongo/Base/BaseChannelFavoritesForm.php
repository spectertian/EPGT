<?php

/**
 * ChannelFavorites Base Form.
 */
class BaseChannelFavoritesForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'user_id' => new sfWidgetFormInputText(array(), array()),
            'channel_type' => new sfWidgetFormInputText(array(), array()),
            'channel_code' => new sfWidgetFormInputText(array(), array()),
            'channel_id' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'user_id' => new sfValidatorString(array(), array()),
            'channel_type' => new sfValidatorString(array(), array()),
            'channel_code' => new sfValidatorString(array(), array()),
            'channel_id' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('channel_favorites[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ChannelFavorites';
    }
}