<?php

/**
 * ShortMoviePackage Base Form.
 */
class BaseShortMoviePackageForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'cover' => new sfWidgetFormInputText(array(), array()),
            'desc' => new sfWidgetFormInputText(array(), array()),
            'tag' => new sfWidgetFormInputText(array(), array()),
            'state' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'cover' => new sfValidatorString(array(), array()),
            'desc' => new sfValidatorString(array(), array()),
            'tag' => new sfValidatorString(array(), array()),
            'state' => new sfValidatorInteger(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('short_movie_package[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ShortMoviePackage';
    }
}