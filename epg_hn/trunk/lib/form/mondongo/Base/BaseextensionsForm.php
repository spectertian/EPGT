<?php

/**
 * extensions Base Form.
 */
class BaseextensionsForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(

        ));

        $this->setValidators(array(

        ));

        $this->widgetSchema->setNameFormat('extensions[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'extensions';
    }
}