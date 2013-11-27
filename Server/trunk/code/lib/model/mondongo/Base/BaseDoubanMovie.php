<?php

/**
 * Base class of DoubanMovie document.
 */
abstract class BaseDoubanMovie extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'name' => null,
            'douban_id' => null,
            'wiki_id' => null,
            'syn_status' => null,
            'title' => null,
            'original_title' => null,
            'aka' => null,
            'images' => null,
            'rating' => null,
            'ratings_count' => null,
            'wish_count' => null,
            'collect_count' => null,
            'subtype' => null,
            'directors' => null,
            'casts' => null,
            'writers' => null,
            'mainland_pubdate' => null,
            'year' => null,
            'languages' => null,
            'durations' => null,
            'genres' => null,
            'countries' => null,
            'summary' => null,
            'comments_count' => null,
            'reviews_count' => null,
            'seasons_count' => null,
            'current_season' => null,
            'episodes_count' => null,
            'photos' => null,
            'popular_reviews' => null,
            'created_at' => null,
            'updated_at' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'name' => 'Name',
        'douban_id' => 'DoubanId',
        'wiki_id' => 'WikiId',
        'syn_status' => 'SynStatus',
        'title' => 'Title',
        'original_title' => 'OriginalTitle',
        'aka' => 'Aka',
        'images' => 'Images',
        'rating' => 'Rating',
        'ratings_count' => 'RatingsCount',
        'wish_count' => 'WishCount',
        'collect_count' => 'CollectCount',
        'subtype' => 'Subtype',
        'directors' => 'Directors',
        'casts' => 'Casts',
        'writers' => 'Writers',
        'mainland_pubdate' => 'MainlandPubdate',
        'year' => 'Year',
        'languages' => 'Languages',
        'durations' => 'Durations',
        'genres' => 'Genres',
        'countries' => 'Countries',
        'summary' => 'Summary',
        'comments_count' => 'CommentsCount',
        'reviews_count' => 'ReviewsCount',
        'seasons_count' => 'SeasonsCount',
        'current_season' => 'CurrentSeason',
        'episodes_count' => 'EpisodesCount',
        'photos' => 'Photos',
        'popular_reviews' => 'PopularReviews',
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
        return \Mondongo\Container::getForDocumentClass('DoubanMovie');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('DoubanMovie');
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

        if (isset($data['name'])) {
            $this->data['fields']['name'] = (string) $data['name'];
        }
        if (isset($data['douban_id'])) {
            $this->data['fields']['douban_id'] = (int) $data['douban_id'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (string) $data['wiki_id'];
        }
        if (isset($data['syn_status'])) {
            $this->data['fields']['syn_status'] = (int) $data['syn_status'];
        }
        if (isset($data['title'])) {
            $this->data['fields']['title'] = (string) $data['title'];
        }
        if (isset($data['original_title'])) {
            $this->data['fields']['original_title'] = (string) $data['original_title'];
        }
        if (isset($data['aka'])) {
            $this->data['fields']['aka'] = $data['aka'];
        }
        if (isset($data['images'])) {
            $this->data['fields']['images'] = $data['images'];
        }
        if (isset($data['rating'])) {
            $this->data['fields']['rating'] = $data['rating'];
        }
        if (isset($data['ratings_count'])) {
            $this->data['fields']['ratings_count'] = $data['ratings_count'];
        }
        if (isset($data['wish_count'])) {
            $this->data['fields']['wish_count'] = (int) $data['wish_count'];
        }
        if (isset($data['collect_count'])) {
            $this->data['fields']['collect_count'] = (int) $data['collect_count'];
        }
        if (isset($data['subtype'])) {
            $this->data['fields']['subtype'] = (string) $data['subtype'];
        }
        if (isset($data['directors'])) {
            $this->data['fields']['directors'] = $data['directors'];
        }
        if (isset($data['casts'])) {
            $this->data['fields']['casts'] = $data['casts'];
        }
        if (isset($data['writers'])) {
            $this->data['fields']['writers'] = $data['writers'];
        }
        if (isset($data['mainland_pubdate'])) {
            $this->data['fields']['mainland_pubdate'] = (string) $data['mainland_pubdate'];
        }
        if (isset($data['year'])) {
            $this->data['fields']['year'] = (string) $data['year'];
        }
        if (isset($data['languages'])) {
            $this->data['fields']['languages'] = $data['languages'];
        }
        if (isset($data['durations'])) {
            $this->data['fields']['durations'] = $data['durations'];
        }
        if (isset($data['genres'])) {
            $this->data['fields']['genres'] = $data['genres'];
        }
        if (isset($data['countries'])) {
            $this->data['fields']['countries'] = $data['countries'];
        }
        if (isset($data['summary'])) {
            $this->data['fields']['summary'] = (string) $data['summary'];
        }
        if (isset($data['comments_count'])) {
            $this->data['fields']['comments_count'] = (int) $data['comments_count'];
        }
        if (isset($data['reviews_count'])) {
            $this->data['fields']['reviews_count'] = (int) $data['reviews_count'];
        }
        if (isset($data['seasons_count'])) {
            $this->data['fields']['seasons_count'] = (int) $data['seasons_count'];
        }
        if (isset($data['current_season'])) {
            $this->data['fields']['current_season'] = (int) $data['current_season'];
        }
        if (isset($data['episodes_count'])) {
            $this->data['fields']['episodes_count'] = (int) $data['episodes_count'];
        }
        if (isset($data['photos'])) {
            $this->data['fields']['photos'] = $data['photos'];
        }
        if (isset($data['popular_reviews'])) {
            $this->data['fields']['popular_reviews'] = $data['popular_reviews'];
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
        if (isset($fields['name'])) {
            $fields['name'] = (string) $fields['name'];
        }
        if (isset($fields['douban_id'])) {
            $fields['douban_id'] = (int) $fields['douban_id'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (string) $fields['wiki_id'];
        }
        if (isset($fields['syn_status'])) {
            $fields['syn_status'] = (int) $fields['syn_status'];
        }
        if (isset($fields['title'])) {
            $fields['title'] = (string) $fields['title'];
        }
        if (isset($fields['original_title'])) {
            $fields['original_title'] = (string) $fields['original_title'];
        }
        if (isset($fields['aka'])) {
            $fields['aka'] = $fields['aka'];
        }
        if (isset($fields['images'])) {
            $fields['images'] = $fields['images'];
        }
        if (isset($fields['rating'])) {
            $fields['rating'] = $fields['rating'];
        }
        if (isset($fields['ratings_count'])) {
            $fields['ratings_count'] = $fields['ratings_count'];
        }
        if (isset($fields['wish_count'])) {
            $fields['wish_count'] = (int) $fields['wish_count'];
        }
        if (isset($fields['collect_count'])) {
            $fields['collect_count'] = (int) $fields['collect_count'];
        }
        if (isset($fields['subtype'])) {
            $fields['subtype'] = (string) $fields['subtype'];
        }
        if (isset($fields['directors'])) {
            $fields['directors'] = $fields['directors'];
        }
        if (isset($fields['casts'])) {
            $fields['casts'] = $fields['casts'];
        }
        if (isset($fields['writers'])) {
            $fields['writers'] = $fields['writers'];
        }
        if (isset($fields['mainland_pubdate'])) {
            $fields['mainland_pubdate'] = (string) $fields['mainland_pubdate'];
        }
        if (isset($fields['year'])) {
            $fields['year'] = (string) $fields['year'];
        }
        if (isset($fields['languages'])) {
            $fields['languages'] = $fields['languages'];
        }
        if (isset($fields['durations'])) {
            $fields['durations'] = $fields['durations'];
        }
        if (isset($fields['genres'])) {
            $fields['genres'] = $fields['genres'];
        }
        if (isset($fields['countries'])) {
            $fields['countries'] = $fields['countries'];
        }
        if (isset($fields['summary'])) {
            $fields['summary'] = (string) $fields['summary'];
        }
        if (isset($fields['comments_count'])) {
            $fields['comments_count'] = (int) $fields['comments_count'];
        }
        if (isset($fields['reviews_count'])) {
            $fields['reviews_count'] = (int) $fields['reviews_count'];
        }
        if (isset($fields['seasons_count'])) {
            $fields['seasons_count'] = (int) $fields['seasons_count'];
        }
        if (isset($fields['current_season'])) {
            $fields['current_season'] = (int) $fields['current_season'];
        }
        if (isset($fields['episodes_count'])) {
            $fields['episodes_count'] = (int) $fields['episodes_count'];
        }
        if (isset($fields['photos'])) {
            $fields['photos'] = $fields['photos'];
        }
        if (isset($fields['popular_reviews'])) {
            $fields['popular_reviews'] = $fields['popular_reviews'];
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
     * Set the "douban_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDoubanId($value)
    {
        if (!array_key_exists('douban_id', $this->fieldsModified)) {
            $this->fieldsModified['douban_id'] = $this->data['fields']['douban_id'];
        } elseif ($value === $this->fieldsModified['douban_id']) {
            unset($this->fieldsModified['douban_id']);
        }

        $this->data['fields']['douban_id'] = $value;
    }

    /**
     * Returns the "douban_id" field.
     *
     * @return mixed The douban_id field.
     */
    public function getDoubanId()
    {
        return $this->data['fields']['douban_id'];
    }

    /**
     * Set the "wiki_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWikiId($value)
    {
        if (!array_key_exists('wiki_id', $this->fieldsModified)) {
            $this->fieldsModified['wiki_id'] = $this->data['fields']['wiki_id'];
        } elseif ($value === $this->fieldsModified['wiki_id']) {
            unset($this->fieldsModified['wiki_id']);
        }

        $this->data['fields']['wiki_id'] = $value;
    }

    /**
     * Returns the "wiki_id" field.
     *
     * @return mixed The wiki_id field.
     */
    public function getWikiId()
    {
        return $this->data['fields']['wiki_id'];
    }

    /**
     * Set the "syn_status" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSynStatus($value)
    {
        if (!array_key_exists('syn_status', $this->fieldsModified)) {
            $this->fieldsModified['syn_status'] = $this->data['fields']['syn_status'];
        } elseif ($value === $this->fieldsModified['syn_status']) {
            unset($this->fieldsModified['syn_status']);
        }

        $this->data['fields']['syn_status'] = $value;
    }

    /**
     * Returns the "syn_status" field.
     *
     * @return mixed The syn_status field.
     */
    public function getSynStatus()
    {
        return $this->data['fields']['syn_status'];
    }

    /**
     * Set the "title" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTitle($value)
    {
        if (!array_key_exists('title', $this->fieldsModified)) {
            $this->fieldsModified['title'] = $this->data['fields']['title'];
        } elseif ($value === $this->fieldsModified['title']) {
            unset($this->fieldsModified['title']);
        }

        $this->data['fields']['title'] = $value;
    }

    /**
     * Returns the "title" field.
     *
     * @return mixed The title field.
     */
    public function getTitle()
    {
        return $this->data['fields']['title'];
    }

    /**
     * Set the "original_title" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setOriginalTitle($value)
    {
        if (!array_key_exists('original_title', $this->fieldsModified)) {
            $this->fieldsModified['original_title'] = $this->data['fields']['original_title'];
        } elseif ($value === $this->fieldsModified['original_title']) {
            unset($this->fieldsModified['original_title']);
        }

        $this->data['fields']['original_title'] = $value;
    }

    /**
     * Returns the "original_title" field.
     *
     * @return mixed The original_title field.
     */
    public function getOriginalTitle()
    {
        return $this->data['fields']['original_title'];
    }

    /**
     * Set the "aka" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAka($value)
    {
        if (!array_key_exists('aka', $this->fieldsModified)) {
            $this->fieldsModified['aka'] = $this->data['fields']['aka'];
        } elseif ($value === $this->fieldsModified['aka']) {
            unset($this->fieldsModified['aka']);
        }

        $this->data['fields']['aka'] = $value;
    }

    /**
     * Returns the "aka" field.
     *
     * @return mixed The aka field.
     */
    public function getAka()
    {
        return $this->data['fields']['aka'];
    }

    /**
     * Set the "images" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setImages($value)
    {
        if (!array_key_exists('images', $this->fieldsModified)) {
            $this->fieldsModified['images'] = $this->data['fields']['images'];
        } elseif ($value === $this->fieldsModified['images']) {
            unset($this->fieldsModified['images']);
        }

        $this->data['fields']['images'] = $value;
    }

    /**
     * Returns the "images" field.
     *
     * @return mixed The images field.
     */
    public function getImages()
    {
        return $this->data['fields']['images'];
    }

    /**
     * Set the "rating" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setRating($value)
    {
        if (!array_key_exists('rating', $this->fieldsModified)) {
            $this->fieldsModified['rating'] = $this->data['fields']['rating'];
        } elseif ($value === $this->fieldsModified['rating']) {
            unset($this->fieldsModified['rating']);
        }

        $this->data['fields']['rating'] = $value;
    }

    /**
     * Returns the "rating" field.
     *
     * @return mixed The rating field.
     */
    public function getRating()
    {
        return $this->data['fields']['rating'];
    }

    /**
     * Set the "ratings_count" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setRatingsCount($value)
    {
        if (!array_key_exists('ratings_count', $this->fieldsModified)) {
            $this->fieldsModified['ratings_count'] = $this->data['fields']['ratings_count'];
        } elseif ($value === $this->fieldsModified['ratings_count']) {
            unset($this->fieldsModified['ratings_count']);
        }

        $this->data['fields']['ratings_count'] = $value;
    }

    /**
     * Returns the "ratings_count" field.
     *
     * @return mixed The ratings_count field.
     */
    public function getRatingsCount()
    {
        return $this->data['fields']['ratings_count'];
    }

    /**
     * Set the "wish_count" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWishCount($value)
    {
        if (!array_key_exists('wish_count', $this->fieldsModified)) {
            $this->fieldsModified['wish_count'] = $this->data['fields']['wish_count'];
        } elseif ($value === $this->fieldsModified['wish_count']) {
            unset($this->fieldsModified['wish_count']);
        }

        $this->data['fields']['wish_count'] = $value;
    }

    /**
     * Returns the "wish_count" field.
     *
     * @return mixed The wish_count field.
     */
    public function getWishCount()
    {
        return $this->data['fields']['wish_count'];
    }

    /**
     * Set the "collect_count" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCollectCount($value)
    {
        if (!array_key_exists('collect_count', $this->fieldsModified)) {
            $this->fieldsModified['collect_count'] = $this->data['fields']['collect_count'];
        } elseif ($value === $this->fieldsModified['collect_count']) {
            unset($this->fieldsModified['collect_count']);
        }

        $this->data['fields']['collect_count'] = $value;
    }

    /**
     * Returns the "collect_count" field.
     *
     * @return mixed The collect_count field.
     */
    public function getCollectCount()
    {
        return $this->data['fields']['collect_count'];
    }

    /**
     * Set the "subtype" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSubtype($value)
    {
        if (!array_key_exists('subtype', $this->fieldsModified)) {
            $this->fieldsModified['subtype'] = $this->data['fields']['subtype'];
        } elseif ($value === $this->fieldsModified['subtype']) {
            unset($this->fieldsModified['subtype']);
        }

        $this->data['fields']['subtype'] = $value;
    }

    /**
     * Returns the "subtype" field.
     *
     * @return mixed The subtype field.
     */
    public function getSubtype()
    {
        return $this->data['fields']['subtype'];
    }

    /**
     * Set the "directors" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDirectors($value)
    {
        if (!array_key_exists('directors', $this->fieldsModified)) {
            $this->fieldsModified['directors'] = $this->data['fields']['directors'];
        } elseif ($value === $this->fieldsModified['directors']) {
            unset($this->fieldsModified['directors']);
        }

        $this->data['fields']['directors'] = $value;
    }

    /**
     * Returns the "directors" field.
     *
     * @return mixed The directors field.
     */
    public function getDirectors()
    {
        return $this->data['fields']['directors'];
    }

    /**
     * Set the "casts" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCasts($value)
    {
        if (!array_key_exists('casts', $this->fieldsModified)) {
            $this->fieldsModified['casts'] = $this->data['fields']['casts'];
        } elseif ($value === $this->fieldsModified['casts']) {
            unset($this->fieldsModified['casts']);
        }

        $this->data['fields']['casts'] = $value;
    }

    /**
     * Returns the "casts" field.
     *
     * @return mixed The casts field.
     */
    public function getCasts()
    {
        return $this->data['fields']['casts'];
    }

    /**
     * Set the "writers" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWriters($value)
    {
        if (!array_key_exists('writers', $this->fieldsModified)) {
            $this->fieldsModified['writers'] = $this->data['fields']['writers'];
        } elseif ($value === $this->fieldsModified['writers']) {
            unset($this->fieldsModified['writers']);
        }

        $this->data['fields']['writers'] = $value;
    }

    /**
     * Returns the "writers" field.
     *
     * @return mixed The writers field.
     */
    public function getWriters()
    {
        return $this->data['fields']['writers'];
    }

    /**
     * Set the "mainland_pubdate" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setMainlandPubdate($value)
    {
        if (!array_key_exists('mainland_pubdate', $this->fieldsModified)) {
            $this->fieldsModified['mainland_pubdate'] = $this->data['fields']['mainland_pubdate'];
        } elseif ($value === $this->fieldsModified['mainland_pubdate']) {
            unset($this->fieldsModified['mainland_pubdate']);
        }

        $this->data['fields']['mainland_pubdate'] = $value;
    }

    /**
     * Returns the "mainland_pubdate" field.
     *
     * @return mixed The mainland_pubdate field.
     */
    public function getMainlandPubdate()
    {
        return $this->data['fields']['mainland_pubdate'];
    }

    /**
     * Set the "year" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setYear($value)
    {
        if (!array_key_exists('year', $this->fieldsModified)) {
            $this->fieldsModified['year'] = $this->data['fields']['year'];
        } elseif ($value === $this->fieldsModified['year']) {
            unset($this->fieldsModified['year']);
        }

        $this->data['fields']['year'] = $value;
    }

    /**
     * Returns the "year" field.
     *
     * @return mixed The year field.
     */
    public function getYear()
    {
        return $this->data['fields']['year'];
    }

    /**
     * Set the "languages" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLanguages($value)
    {
        if (!array_key_exists('languages', $this->fieldsModified)) {
            $this->fieldsModified['languages'] = $this->data['fields']['languages'];
        } elseif ($value === $this->fieldsModified['languages']) {
            unset($this->fieldsModified['languages']);
        }

        $this->data['fields']['languages'] = $value;
    }

    /**
     * Returns the "languages" field.
     *
     * @return mixed The languages field.
     */
    public function getLanguages()
    {
        return $this->data['fields']['languages'];
    }

    /**
     * Set the "durations" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDurations($value)
    {
        if (!array_key_exists('durations', $this->fieldsModified)) {
            $this->fieldsModified['durations'] = $this->data['fields']['durations'];
        } elseif ($value === $this->fieldsModified['durations']) {
            unset($this->fieldsModified['durations']);
        }

        $this->data['fields']['durations'] = $value;
    }

    /**
     * Returns the "durations" field.
     *
     * @return mixed The durations field.
     */
    public function getDurations()
    {
        return $this->data['fields']['durations'];
    }

    /**
     * Set the "genres" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setGenres($value)
    {
        if (!array_key_exists('genres', $this->fieldsModified)) {
            $this->fieldsModified['genres'] = $this->data['fields']['genres'];
        } elseif ($value === $this->fieldsModified['genres']) {
            unset($this->fieldsModified['genres']);
        }

        $this->data['fields']['genres'] = $value;
    }

    /**
     * Returns the "genres" field.
     *
     * @return mixed The genres field.
     */
    public function getGenres()
    {
        return $this->data['fields']['genres'];
    }

    /**
     * Set the "countries" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCountries($value)
    {
        if (!array_key_exists('countries', $this->fieldsModified)) {
            $this->fieldsModified['countries'] = $this->data['fields']['countries'];
        } elseif ($value === $this->fieldsModified['countries']) {
            unset($this->fieldsModified['countries']);
        }

        $this->data['fields']['countries'] = $value;
    }

    /**
     * Returns the "countries" field.
     *
     * @return mixed The countries field.
     */
    public function getCountries()
    {
        return $this->data['fields']['countries'];
    }

    /**
     * Set the "summary" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSummary($value)
    {
        if (!array_key_exists('summary', $this->fieldsModified)) {
            $this->fieldsModified['summary'] = $this->data['fields']['summary'];
        } elseif ($value === $this->fieldsModified['summary']) {
            unset($this->fieldsModified['summary']);
        }

        $this->data['fields']['summary'] = $value;
    }

    /**
     * Returns the "summary" field.
     *
     * @return mixed The summary field.
     */
    public function getSummary()
    {
        return $this->data['fields']['summary'];
    }

    /**
     * Set the "comments_count" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCommentsCount($value)
    {
        if (!array_key_exists('comments_count', $this->fieldsModified)) {
            $this->fieldsModified['comments_count'] = $this->data['fields']['comments_count'];
        } elseif ($value === $this->fieldsModified['comments_count']) {
            unset($this->fieldsModified['comments_count']);
        }

        $this->data['fields']['comments_count'] = $value;
    }

    /**
     * Returns the "comments_count" field.
     *
     * @return mixed The comments_count field.
     */
    public function getCommentsCount()
    {
        return $this->data['fields']['comments_count'];
    }

    /**
     * Set the "reviews_count" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setReviewsCount($value)
    {
        if (!array_key_exists('reviews_count', $this->fieldsModified)) {
            $this->fieldsModified['reviews_count'] = $this->data['fields']['reviews_count'];
        } elseif ($value === $this->fieldsModified['reviews_count']) {
            unset($this->fieldsModified['reviews_count']);
        }

        $this->data['fields']['reviews_count'] = $value;
    }

    /**
     * Returns the "reviews_count" field.
     *
     * @return mixed The reviews_count field.
     */
    public function getReviewsCount()
    {
        return $this->data['fields']['reviews_count'];
    }

    /**
     * Set the "seasons_count" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSeasonsCount($value)
    {
        if (!array_key_exists('seasons_count', $this->fieldsModified)) {
            $this->fieldsModified['seasons_count'] = $this->data['fields']['seasons_count'];
        } elseif ($value === $this->fieldsModified['seasons_count']) {
            unset($this->fieldsModified['seasons_count']);
        }

        $this->data['fields']['seasons_count'] = $value;
    }

    /**
     * Returns the "seasons_count" field.
     *
     * @return mixed The seasons_count field.
     */
    public function getSeasonsCount()
    {
        return $this->data['fields']['seasons_count'];
    }

    /**
     * Set the "current_season" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCurrentSeason($value)
    {
        if (!array_key_exists('current_season', $this->fieldsModified)) {
            $this->fieldsModified['current_season'] = $this->data['fields']['current_season'];
        } elseif ($value === $this->fieldsModified['current_season']) {
            unset($this->fieldsModified['current_season']);
        }

        $this->data['fields']['current_season'] = $value;
    }

    /**
     * Returns the "current_season" field.
     *
     * @return mixed The current_season field.
     */
    public function getCurrentSeason()
    {
        return $this->data['fields']['current_season'];
    }

    /**
     * Set the "episodes_count" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setEpisodesCount($value)
    {
        if (!array_key_exists('episodes_count', $this->fieldsModified)) {
            $this->fieldsModified['episodes_count'] = $this->data['fields']['episodes_count'];
        } elseif ($value === $this->fieldsModified['episodes_count']) {
            unset($this->fieldsModified['episodes_count']);
        }

        $this->data['fields']['episodes_count'] = $value;
    }

    /**
     * Returns the "episodes_count" field.
     *
     * @return mixed The episodes_count field.
     */
    public function getEpisodesCount()
    {
        return $this->data['fields']['episodes_count'];
    }

    /**
     * Set the "photos" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPhotos($value)
    {
        if (!array_key_exists('photos', $this->fieldsModified)) {
            $this->fieldsModified['photos'] = $this->data['fields']['photos'];
        } elseif ($value === $this->fieldsModified['photos']) {
            unset($this->fieldsModified['photos']);
        }

        $this->data['fields']['photos'] = $value;
    }

    /**
     * Returns the "photos" field.
     *
     * @return mixed The photos field.
     */
    public function getPhotos()
    {
        return $this->data['fields']['photos'];
    }

    /**
     * Set the "popular_reviews" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setPopularReviews($value)
    {
        if (!array_key_exists('popular_reviews', $this->fieldsModified)) {
            $this->fieldsModified['popular_reviews'] = $this->data['fields']['popular_reviews'];
        } elseif ($value === $this->fieldsModified['popular_reviews']) {
            unset($this->fieldsModified['popular_reviews']);
        }

        $this->data['fields']['popular_reviews'] = $value;
    }

    /**
     * Returns the "popular_reviews" field.
     *
     * @return mixed The popular_reviews field.
     */
    public function getPopularReviews()
    {
        return $this->data['fields']['popular_reviews'];
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
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['douban_id'])) {
            $this->setDoubanId($array['douban_id']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['syn_status'])) {
            $this->setSynStatus($array['syn_status']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['original_title'])) {
            $this->setOriginalTitle($array['original_title']);
        }
        if (isset($array['aka'])) {
            $this->setAka($array['aka']);
        }
        if (isset($array['images'])) {
            $this->setImages($array['images']);
        }
        if (isset($array['rating'])) {
            $this->setRating($array['rating']);
        }
        if (isset($array['ratings_count'])) {
            $this->setRatingsCount($array['ratings_count']);
        }
        if (isset($array['wish_count'])) {
            $this->setWishCount($array['wish_count']);
        }
        if (isset($array['collect_count'])) {
            $this->setCollectCount($array['collect_count']);
        }
        if (isset($array['subtype'])) {
            $this->setSubtype($array['subtype']);
        }
        if (isset($array['directors'])) {
            $this->setDirectors($array['directors']);
        }
        if (isset($array['casts'])) {
            $this->setCasts($array['casts']);
        }
        if (isset($array['writers'])) {
            $this->setWriters($array['writers']);
        }
        if (isset($array['mainland_pubdate'])) {
            $this->setMainlandPubdate($array['mainland_pubdate']);
        }
        if (isset($array['year'])) {
            $this->setYear($array['year']);
        }
        if (isset($array['languages'])) {
            $this->setLanguages($array['languages']);
        }
        if (isset($array['durations'])) {
            $this->setDurations($array['durations']);
        }
        if (isset($array['genres'])) {
            $this->setGenres($array['genres']);
        }
        if (isset($array['countries'])) {
            $this->setCountries($array['countries']);
        }
        if (isset($array['summary'])) {
            $this->setSummary($array['summary']);
        }
        if (isset($array['comments_count'])) {
            $this->setCommentsCount($array['comments_count']);
        }
        if (isset($array['reviews_count'])) {
            $this->setReviewsCount($array['reviews_count']);
        }
        if (isset($array['seasons_count'])) {
            $this->setSeasonsCount($array['seasons_count']);
        }
        if (isset($array['current_season'])) {
            $this->setCurrentSeason($array['current_season']);
        }
        if (isset($array['episodes_count'])) {
            $this->setEpisodesCount($array['episodes_count']);
        }
        if (isset($array['photos'])) {
            $this->setPhotos($array['photos']);
        }
        if (isset($array['popular_reviews'])) {
            $this->setPopularReviews($array['popular_reviews']);
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

        if (null !== $this->data['fields']['name']) {
            $array['name'] = $this->data['fields']['name'];
        }
        if (null !== $this->data['fields']['douban_id']) {
            $array['douban_id'] = $this->data['fields']['douban_id'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['syn_status']) {
            $array['syn_status'] = $this->data['fields']['syn_status'];
        }
        if (null !== $this->data['fields']['title']) {
            $array['title'] = $this->data['fields']['title'];
        }
        if (null !== $this->data['fields']['original_title']) {
            $array['original_title'] = $this->data['fields']['original_title'];
        }
        if (null !== $this->data['fields']['aka']) {
            $array['aka'] = $this->data['fields']['aka'];
        }
        if (null !== $this->data['fields']['images']) {
            $array['images'] = $this->data['fields']['images'];
        }
        if (null !== $this->data['fields']['rating']) {
            $array['rating'] = $this->data['fields']['rating'];
        }
        if (null !== $this->data['fields']['ratings_count']) {
            $array['ratings_count'] = $this->data['fields']['ratings_count'];
        }
        if (null !== $this->data['fields']['wish_count']) {
            $array['wish_count'] = $this->data['fields']['wish_count'];
        }
        if (null !== $this->data['fields']['collect_count']) {
            $array['collect_count'] = $this->data['fields']['collect_count'];
        }
        if (null !== $this->data['fields']['subtype']) {
            $array['subtype'] = $this->data['fields']['subtype'];
        }
        if (null !== $this->data['fields']['directors']) {
            $array['directors'] = $this->data['fields']['directors'];
        }
        if (null !== $this->data['fields']['casts']) {
            $array['casts'] = $this->data['fields']['casts'];
        }
        if (null !== $this->data['fields']['writers']) {
            $array['writers'] = $this->data['fields']['writers'];
        }
        if (null !== $this->data['fields']['mainland_pubdate']) {
            $array['mainland_pubdate'] = $this->data['fields']['mainland_pubdate'];
        }
        if (null !== $this->data['fields']['year']) {
            $array['year'] = $this->data['fields']['year'];
        }
        if (null !== $this->data['fields']['languages']) {
            $array['languages'] = $this->data['fields']['languages'];
        }
        if (null !== $this->data['fields']['durations']) {
            $array['durations'] = $this->data['fields']['durations'];
        }
        if (null !== $this->data['fields']['genres']) {
            $array['genres'] = $this->data['fields']['genres'];
        }
        if (null !== $this->data['fields']['countries']) {
            $array['countries'] = $this->data['fields']['countries'];
        }
        if (null !== $this->data['fields']['summary']) {
            $array['summary'] = $this->data['fields']['summary'];
        }
        if (null !== $this->data['fields']['comments_count']) {
            $array['comments_count'] = $this->data['fields']['comments_count'];
        }
        if (null !== $this->data['fields']['reviews_count']) {
            $array['reviews_count'] = $this->data['fields']['reviews_count'];
        }
        if (null !== $this->data['fields']['seasons_count']) {
            $array['seasons_count'] = $this->data['fields']['seasons_count'];
        }
        if (null !== $this->data['fields']['current_season']) {
            $array['current_season'] = $this->data['fields']['current_season'];
        }
        if (null !== $this->data['fields']['episodes_count']) {
            $array['episodes_count'] = $this->data['fields']['episodes_count'];
        }
        if (null !== $this->data['fields']['photos']) {
            $array['photos'] = $this->data['fields']['photos'];
        }
        if (null !== $this->data['fields']['popular_reviews']) {
            $array['popular_reviews'] = $this->data['fields']['popular_reviews'];
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
                'name' => array(
                    'type' => 'string',
                ),
                'douban_id' => array(
                    'type' => 'integer',
                ),
                'wiki_id' => array(
                    'type' => 'string',
                ),
                'syn_status' => array(
                    'type' => 'integer',
                ),
                'title' => array(
                    'type' => 'string',
                ),
                'original_title' => array(
                    'type' => 'string',
                ),
                'aka' => array(
                    'type' => 'raw',
                ),
                'images' => array(
                    'type' => 'raw',
                ),
                'rating' => array(
                    'type' => 'raw',
                ),
                'ratings_count' => array(
                    'type' => 'raw',
                ),
                'wish_count' => array(
                    'type' => 'integer',
                ),
                'collect_count' => array(
                    'type' => 'integer',
                ),
                'subtype' => array(
                    'type' => 'string',
                ),
                'directors' => array(
                    'type' => 'raw',
                ),
                'casts' => array(
                    'type' => 'raw',
                ),
                'writers' => array(
                    'type' => 'raw',
                ),
                'mainland_pubdate' => array(
                    'type' => 'string',
                ),
                'year' => array(
                    'type' => 'string',
                ),
                'languages' => array(
                    'type' => 'raw',
                ),
                'durations' => array(
                    'type' => 'raw',
                ),
                'genres' => array(
                    'type' => 'raw',
                ),
                'countries' => array(
                    'type' => 'raw',
                ),
                'summary' => array(
                    'type' => 'string',
                ),
                'comments_count' => array(
                    'type' => 'integer',
                ),
                'reviews_count' => array(
                    'type' => 'integer',
                ),
                'seasons_count' => array(
                    'type' => 'integer',
                ),
                'current_season' => array(
                    'type' => 'integer',
                ),
                'episodes_count' => array(
                    'type' => 'integer',
                ),
                'photos' => array(
                    'type' => 'raw',
                ),
                'popular_reviews' => array(
                    'type' => 'raw',
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