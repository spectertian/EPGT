<?php

/**
 * Dict Base Form.
 */
class BaseDictForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'tf' => new sfWidgetFormInputText(array(), array()),
            'idf' => new sfWidgetFormInputText(array(), array()),
            'attr' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'tf' => new sfValidatorString(array(), array()),
            'idf' => new sfValidatorString(array(), array()),
            'attr' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('dict[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'Dict';
    }
}