<?php

/**
 * DoubanMovie Base Form.
 */
class BaseDoubanMovieForm extends BaseFormMondongo
{

    /**
     * @see sfForm
     */
    public function setup()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array()),
            'douban_id' => new sfWidgetFormInputText(array(), array()),
            'wiki_id' => new sfWidgetFormInputText(array(), array()),
            'syn_status' => new sfWidgetFormInputText(array(), array()),
            'title' => new sfWidgetFormInputText(array(), array()),
            'original_title' => new sfWidgetFormInputText(array(), array()),
            'aka' => new sfWidgetFormInputText(array(), array()),
            'images' => new sfWidgetFormInputText(array(), array()),
            'rating' => new sfWidgetFormInputText(array(), array()),
            'ratings_count' => new sfWidgetFormInputText(array(), array()),
            'wish_count' => new sfWidgetFormInputText(array(), array()),
            'collect_count' => new sfWidgetFormInputText(array(), array()),
            'subtype' => new sfWidgetFormInputText(array(), array()),
            'directors' => new sfWidgetFormInputText(array(), array()),
            'casts' => new sfWidgetFormInputText(array(), array()),
            'writers' => new sfWidgetFormInputText(array(), array()),
            'mainland_pubdate' => new sfWidgetFormInputText(array(), array()),
            'year' => new sfWidgetFormInputText(array(), array()),
            'languages' => new sfWidgetFormInputText(array(), array()),
            'durations' => new sfWidgetFormInputText(array(), array()),
            'genres' => new sfWidgetFormInputText(array(), array()),
            'countries' => new sfWidgetFormInputText(array(), array()),
            'summary' => new sfWidgetFormInputText(array(), array()),
            'comments_count' => new sfWidgetFormInputText(array(), array()),
            'reviews_count' => new sfWidgetFormInputText(array(), array()),
            'seasons_count' => new sfWidgetFormInputText(array(), array()),
            'current_season' => new sfWidgetFormInputText(array(), array()),
            'episodes_count' => new sfWidgetFormInputText(array(), array()),
            'photos' => new sfWidgetFormInputText(array(), array()),
            'popular_reviews' => new sfWidgetFormInputText(array(), array()),
            'created_at' => new sfWidgetFormDateTime(array(), array()),
            'updated_at' => new sfWidgetFormDateTime(array(), array()),

        ));

        $this->setValidators(array(
            'name' => new sfValidatorString(array(), array()),
            'douban_id' => new sfValidatorInteger(array(), array()),
            'wiki_id' => new sfValidatorString(array(), array()),
            'syn_status' => new sfValidatorInteger(array(), array()),
            'title' => new sfValidatorString(array(), array()),
            'original_title' => new sfValidatorString(array(), array()),
            'aka' => new sfValidatorString(array(), array()),
            'images' => new sfValidatorString(array(), array()),
            'rating' => new sfValidatorString(array(), array()),
            'ratings_count' => new sfValidatorString(array(), array()),
            'wish_count' => new sfValidatorInteger(array(), array()),
            'collect_count' => new sfValidatorInteger(array(), array()),
            'subtype' => new sfValidatorString(array(), array()),
            'directors' => new sfValidatorString(array(), array()),
            'casts' => new sfValidatorString(array(), array()),
            'writers' => new sfValidatorString(array(), array()),
            'mainland_pubdate' => new sfValidatorString(array(), array()),
            'year' => new sfValidatorString(array(), array()),
            'languages' => new sfValidatorString(array(), array()),
            'durations' => new sfValidatorString(array(), array()),
            'genres' => new sfValidatorString(array(), array()),
            'countries' => new sfValidatorString(array(), array()),
            'summary' => new sfValidatorString(array(), array()),
            'comments_count' => new sfValidatorInteger(array(), array()),
            'reviews_count' => new sfValidatorInteger(array(), array()),
            'seasons_count' => new sfValidatorInteger(array(), array()),
            'current_season' => new sfValidatorInteger(array(), array()),
            'episodes_count' => new sfValidatorInteger(array(), array()),
            'photos' => new sfValidatorString(array(), array()),
            'popular_reviews' => new sfValidatorString(array(), array()),
            'created_at' => new sfValidatorDateTime(array(), array()),
            'updated_at' => new sfValidatorDateTime(array(), array()),

        ));

        $this->widgetSchema->setNameFormat('douban_movie[%s]');
    }

    /**
     * @see sfMondongoForm
     */
    public function getModelName()
    {
        return 'DoubanMovie';
    }
}