<?php

/**
 * ShortMoviePackageItem Base Form.
 */
class BaseShortMoviePackageItemForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'package_id' => new sfWidgetFormInputText(array(), array()),
            'short_movie_id' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'package_id' => new sfValidatorString(array(), array()),
            'short_movie_id' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('short_movie_package_item[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'ShortMoviePackageItem';
    }
}