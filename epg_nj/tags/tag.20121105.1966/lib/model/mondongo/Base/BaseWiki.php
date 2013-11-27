<?php

/**
 * Base class of Wiki document.
 */
abstract class BaseWiki extends \Mondongo\Document\Document implements \ArrayAccess
{


    protected $data = array(
        'fields' => array(
            'rev' => null,
            'cover' => null,
            'wiki_id' => null,
            'title' => null,
            'html_cache' => null,
            'content' => null,
            'tags' => null,
            'comment_tags' => null,
            'model' => null,
            'has_video' => null,
            'like_num' => null,
            'dislike_num' => null,
            'watched_num' => null,
            'admin_id' => null,
            'do_date' => null,
            'source' => null,
            'tvsou_id' => null,
            'first_letter' => null,
            'created_at' => null,
            'updated_at' => null,
            'slug' => null,
        ),
    );


    protected $fieldsModified = array(

    );


    static protected $dataCamelCaseMap = array(
        'rev' => 'Rev',
        'cover' => 'Cover',
        'wiki_id' => 'WikiId',
        'title' => 'Title',
        'html_cache' => 'HtmlCache',
        'content' => 'Content',
        'tags' => 'Tags',
        'comment_tags' => 'CommentTags',
        'model' => 'Model',
        'has_video' => 'HasVideo',
        'like_num' => 'LikeNum',
        'dislike_num' => 'DislikeNum',
        'watched_num' => 'WatchedNum',
        'admin_id' => 'AdminId',
        'do_date' => 'DoDate',
        'source' => 'Source',
        'tvsou_id' => 'TvsouId',
        'first_letter' => 'FirstLetter',
        'created_at' => 'CreatedAt',
        'updated_at' => 'UpdatedAt',
        'slug' => 'Slug',
    );

    /**
     * Returns the Mondongo of the document.
     *
     * @return Mondongo\Mondongo The Mondongo of the document.
     */
    public function getMondongo()
    {
        return \Mondongo\Container::getForDocumentClass('Wiki');
    }

    /**
     * Returns the repository of the document.
     *
     * @return Mondongo\Repository The repository of the document.
     */
    public function getRepository()
    {
        return $this->getMondongo()->getRepository('Wiki');
    }


    protected function updateTimestampableCreated()
    {
        $this->setCreatedAt(new \DateTime());
    }


    protected function updateTimestampableUpdated()
    {
        $this->setUpdatedAt(new \DateTime());
    }


    protected function updateSluggableSlug()
    {
        $slug = $proposal = call_user_func(array (
  0 => 'Wiki',
  1 => 'slugify',
), $this->getTitle());

        $similarSlugs = array();
        foreach ($this->getRepository()
            ->getCollection()
            ->find(array('slug' => new \MongoRegex('/^'.$slug.'/')))
        as $result) {
            $similarSlugs[] = $result['slug'];
        }

        $i = 1;
        while (in_array($slug, $similarSlugs)) {
            $slug = $proposal.'-'.++$i;
        }

        $this->setSlug($slug);
    }

    /**
     * Set the data in the document (hydrate).
     *
     * @return void
     */
    public function setDocumentData($data)
    {
        $this->id = $data['_id'];

        if (isset($data['rev'])) {
            $this->data['fields']['rev'] = (int) $data['rev'];
        }
        if (isset($data['cover'])) {
            $this->data['fields']['cover'] = (string) $data['cover'];
        }
        if (isset($data['wiki_id'])) {
            $this->data['fields']['wiki_id'] = (int) $data['wiki_id'];
        }
        if (isset($data['title'])) {
            $this->data['fields']['title'] = (string) $data['title'];
        }
        if (isset($data['html_cache'])) {
            $this->data['fields']['html_cache'] = (string) $data['html_cache'];
        }
        if (isset($data['content'])) {
            $this->data['fields']['content'] = (string) $data['content'];
        }
        if (isset($data['tags'])) {
            $this->data['fields']['tags'] = $data['tags'];
        }
        if (isset($data['comment_tags'])) {
            $this->data['fields']['comment_tags'] = $data['comment_tags'];
        }
        if (isset($data['model'])) {
            $this->data['fields']['model'] = (string) $data['model'];
        }
        if (isset($data['has_video'])) {
            $this->data['fields']['has_video'] = (int) $data['has_video'];
        }
        if (isset($data['like_num'])) {
            $this->data['fields']['like_num'] = (int) $data['like_num'];
        }
        if (isset($data['dislike_num'])) {
            $this->data['fields']['dislike_num'] = (int) $data['dislike_num'];
        }
        if (isset($data['watched_num'])) {
            $this->data['fields']['watched_num'] = (int) $data['watched_num'];
        }
        if (isset($data['admin_id'])) {
            $this->data['fields']['admin_id'] = (int) $data['admin_id'];
        }
        if (isset($data['do_date'])) {
            $date = new \DateTime(); $date->setTimestamp($data['do_date']->sec); $this->data['fields']['do_date'] = $date;
        }
        if (isset($data['source'])) {
            $this->data['fields']['source'] = $data['source'];
        }
        if (isset($data['tvsou_id'])) {
            $this->data['fields']['tvsou_id'] = (string) $data['tvsou_id'];
        }
        if (isset($data['first_letter'])) {
            $this->data['fields']['first_letter'] = (string) $data['first_letter'];
        }
        if (isset($data['created_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['created_at']->sec); $this->data['fields']['created_at'] = $date;
        }
        if (isset($data['updated_at'])) {
            $date = new \DateTime(); $date->setTimestamp($data['updated_at']->sec); $this->data['fields']['updated_at'] = $date;
        }
        if (isset($data['slug'])) {
            $this->data['fields']['slug'] = (string) $data['slug'];
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
        if (isset($fields['rev'])) {
            $fields['rev'] = (int) $fields['rev'];
        }
        if (isset($fields['cover'])) {
            $fields['cover'] = (string) $fields['cover'];
        }
        if (isset($fields['wiki_id'])) {
            $fields['wiki_id'] = (int) $fields['wiki_id'];
        }
        if (isset($fields['title'])) {
            $fields['title'] = (string) $fields['title'];
        }
        if (isset($fields['html_cache'])) {
            $fields['html_cache'] = (string) $fields['html_cache'];
        }
        if (isset($fields['content'])) {
            $fields['content'] = (string) $fields['content'];
        }
        if (isset($fields['tags'])) {
            $fields['tags'] = $fields['tags'];
        }
        if (isset($fields['comment_tags'])) {
            $fields['comment_tags'] = $fields['comment_tags'];
        }
        if (isset($fields['model'])) {
            $fields['model'] = (string) $fields['model'];
        }
        if (isset($fields['has_video'])) {
            $fields['has_video'] = (int) $fields['has_video'];
        }
        if (isset($fields['like_num'])) {
            $fields['like_num'] = (int) $fields['like_num'];
        }
        if (isset($fields['dislike_num'])) {
            $fields['dislike_num'] = (int) $fields['dislike_num'];
        }
        if (isset($fields['watched_num'])) {
            $fields['watched_num'] = (int) $fields['watched_num'];
        }
        if (isset($fields['admin_id'])) {
            $fields['admin_id'] = (int) $fields['admin_id'];
        }
        if (isset($fields['do_date'])) {
            if ($fields['do_date'] instanceof \DateTime) { $fields['do_date'] = $fields['do_date']->getTimestamp(); } elseif (is_string($fields['do_date'])) { $fields['do_date'] = strtotime($fields['do_date']); } $fields['do_date'] = new \MongoDate($fields['do_date']);
        }
        if (isset($fields['source'])) {
            $fields['source'] = $fields['source'];
        }
        if (isset($fields['tvsou_id'])) {
            $fields['tvsou_id'] = (string) $fields['tvsou_id'];
        }
        if (isset($fields['first_letter'])) {
            $fields['first_letter'] = (string) $fields['first_letter'];
        }
        if (isset($fields['created_at'])) {
            if ($fields['created_at'] instanceof \DateTime) { $fields['created_at'] = $fields['created_at']->getTimestamp(); } elseif (is_string($fields['created_at'])) { $fields['created_at'] = strtotime($fields['created_at']); } $fields['created_at'] = new \MongoDate($fields['created_at']);
        }
        if (isset($fields['updated_at'])) {
            if ($fields['updated_at'] instanceof \DateTime) { $fields['updated_at'] = $fields['updated_at']->getTimestamp(); } elseif (is_string($fields['updated_at'])) { $fields['updated_at'] = strtotime($fields['updated_at']); } $fields['updated_at'] = new \MongoDate($fields['updated_at']);
        }
        if (isset($fields['slug'])) {
            $fields['slug'] = (string) $fields['slug'];
        }


        return $fields;
    }

    /**
     * Set the "rev" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setRev($value)
    {
        if (!array_key_exists('rev', $this->fieldsModified)) {
            $this->fieldsModified['rev'] = $this->data['fields']['rev'];
        } elseif ($value === $this->fieldsModified['rev']) {
            unset($this->fieldsModified['rev']);
        }

        $this->data['fields']['rev'] = $value;
    }

    /**
     * Returns the "rev" field.
     *
     * @return mixed The rev field.
     */
    public function getRev()
    {
        return $this->data['fields']['rev'];
    }

    /**
     * Set the "cover" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCover($value)
    {
        if (!array_key_exists('cover', $this->fieldsModified)) {
            $this->fieldsModified['cover'] = $this->data['fields']['cover'];
        } elseif ($value === $this->fieldsModified['cover']) {
            unset($this->fieldsModified['cover']);
        }

        $this->data['fields']['cover'] = $value;
    }

    /**
     * Returns the "cover" field.
     *
     * @return mixed The cover field.
     */
    public function getCover()
    {
        return $this->data['fields']['cover'];
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
     * Set the "html_cache" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setHtmlCache($value)
    {
        if (!array_key_exists('html_cache', $this->fieldsModified)) {
            $this->fieldsModified['html_cache'] = $this->data['fields']['html_cache'];
        } elseif ($value === $this->fieldsModified['html_cache']) {
            unset($this->fieldsModified['html_cache']);
        }

        $this->data['fields']['html_cache'] = $value;
    }

    /**
     * Returns the "html_cache" field.
     *
     * @return mixed The html_cache field.
     */
    public function getHtmlCache()
    {
        return $this->data['fields']['html_cache'];
    }

    /**
     * Set the "content" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setContent($value)
    {
        if (!array_key_exists('content', $this->fieldsModified)) {
            $this->fieldsModified['content'] = $this->data['fields']['content'];
        } elseif ($value === $this->fieldsModified['content']) {
            unset($this->fieldsModified['content']);
        }

        $this->data['fields']['content'] = $value;
    }

    /**
     * Returns the "content" field.
     *
     * @return mixed The content field.
     */
    public function getContent()
    {
        return $this->data['fields']['content'];
    }

    /**
     * Set the "tags" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTags($value)
    {
        if (!array_key_exists('tags', $this->fieldsModified)) {
            $this->fieldsModified['tags'] = $this->data['fields']['tags'];
        } elseif ($value === $this->fieldsModified['tags']) {
            unset($this->fieldsModified['tags']);
        }

        $this->data['fields']['tags'] = $value;
    }

    /**
     * Returns the "tags" field.
     *
     * @return mixed The tags field.
     */
    public function getTags()
    {
        return $this->data['fields']['tags'];
    }

    /**
     * Set the "comment_tags" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setCommentTags($value)
    {
        if (!array_key_exists('comment_tags', $this->fieldsModified)) {
            $this->fieldsModified['comment_tags'] = $this->data['fields']['comment_tags'];
        } elseif ($value === $this->fieldsModified['comment_tags']) {
            unset($this->fieldsModified['comment_tags']);
        }

        $this->data['fields']['comment_tags'] = $value;
    }

    /**
     * Returns the "comment_tags" field.
     *
     * @return mixed The comment_tags field.
     */
    public function getCommentTags()
    {
        return $this->data['fields']['comment_tags'];
    }

    /**
     * Set the "model" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setModel($value)
    {
        if (!array_key_exists('model', $this->fieldsModified)) {
            $this->fieldsModified['model'] = $this->data['fields']['model'];
        } elseif ($value === $this->fieldsModified['model']) {
            unset($this->fieldsModified['model']);
        }

        $this->data['fields']['model'] = $value;
    }

    /**
     * Returns the "model" field.
     *
     * @return mixed The model field.
     */
    public function getModel()
    {
        return $this->data['fields']['model'];
    }

    /**
     * Set the "has_video" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setHasVideo($value)
    {
        if (!array_key_exists('has_video', $this->fieldsModified)) {
            $this->fieldsModified['has_video'] = $this->data['fields']['has_video'];
        } elseif ($value === $this->fieldsModified['has_video']) {
            unset($this->fieldsModified['has_video']);
        }

        $this->data['fields']['has_video'] = $value;
    }

    /**
     * Returns the "has_video" field.
     *
     * @return mixed The has_video field.
     */
    public function getHasVideo()
    {
        return $this->data['fields']['has_video'];
    }

    /**
     * Set the "like_num" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setLikeNum($value)
    {
        if (!array_key_exists('like_num', $this->fieldsModified)) {
            $this->fieldsModified['like_num'] = $this->data['fields']['like_num'];
        } elseif ($value === $this->fieldsModified['like_num']) {
            unset($this->fieldsModified['like_num']);
        }

        $this->data['fields']['like_num'] = $value;
    }

    /**
     * Returns the "like_num" field.
     *
     * @return mixed The like_num field.
     */
    public function getLikeNum()
    {
        return $this->data['fields']['like_num'];
    }

    /**
     * Set the "dislike_num" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDislikeNum($value)
    {
        if (!array_key_exists('dislike_num', $this->fieldsModified)) {
            $this->fieldsModified['dislike_num'] = $this->data['fields']['dislike_num'];
        } elseif ($value === $this->fieldsModified['dislike_num']) {
            unset($this->fieldsModified['dislike_num']);
        }

        $this->data['fields']['dislike_num'] = $value;
    }

    /**
     * Returns the "dislike_num" field.
     *
     * @return mixed The dislike_num field.
     */
    public function getDislikeNum()
    {
        return $this->data['fields']['dislike_num'];
    }

    /**
     * Set the "watched_num" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setWatchedNum($value)
    {
        if (!array_key_exists('watched_num', $this->fieldsModified)) {
            $this->fieldsModified['watched_num'] = $this->data['fields']['watched_num'];
        } elseif ($value === $this->fieldsModified['watched_num']) {
            unset($this->fieldsModified['watched_num']);
        }

        $this->data['fields']['watched_num'] = $value;
    }

    /**
     * Returns the "watched_num" field.
     *
     * @return mixed The watched_num field.
     */
    public function getWatchedNum()
    {
        return $this->data['fields']['watched_num'];
    }

    /**
     * Set the "admin_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setAdminId($value)
    {
        if (!array_key_exists('admin_id', $this->fieldsModified)) {
            $this->fieldsModified['admin_id'] = $this->data['fields']['admin_id'];
        } elseif ($value === $this->fieldsModified['admin_id']) {
            unset($this->fieldsModified['admin_id']);
        }

        $this->data['fields']['admin_id'] = $value;
    }

    /**
     * Returns the "admin_id" field.
     *
     * @return mixed The admin_id field.
     */
    public function getAdminId()
    {
        return $this->data['fields']['admin_id'];
    }

    /**
     * Set the "do_date" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setDoDate($value)
    {
        if (!array_key_exists('do_date', $this->fieldsModified)) {
            $this->fieldsModified['do_date'] = $this->data['fields']['do_date'];
        } elseif ($value === $this->fieldsModified['do_date']) {
            unset($this->fieldsModified['do_date']);
        }

        $this->data['fields']['do_date'] = $value;
    }

    /**
     * Returns the "do_date" field.
     *
     * @return mixed The do_date field.
     */
    public function getDoDate()
    {
        return $this->data['fields']['do_date'];
    }

    /**
     * Set the "source" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSource($value)
    {
        if (!array_key_exists('source', $this->fieldsModified)) {
            $this->fieldsModified['source'] = $this->data['fields']['source'];
        } elseif ($value === $this->fieldsModified['source']) {
            unset($this->fieldsModified['source']);
        }

        $this->data['fields']['source'] = $value;
    }

    /**
     * Returns the "source" field.
     *
     * @return mixed The source field.
     */
    public function getSource()
    {
        return $this->data['fields']['source'];
    }

    /**
     * Set the "tvsou_id" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setTvsouId($value)
    {
        if (!array_key_exists('tvsou_id', $this->fieldsModified)) {
            $this->fieldsModified['tvsou_id'] = $this->data['fields']['tvsou_id'];
        } elseif ($value === $this->fieldsModified['tvsou_id']) {
            unset($this->fieldsModified['tvsou_id']);
        }

        $this->data['fields']['tvsou_id'] = $value;
    }

    /**
     * Returns the "tvsou_id" field.
     *
     * @return mixed The tvsou_id field.
     */
    public function getTvsouId()
    {
        return $this->data['fields']['tvsou_id'];
    }

    /**
     * Set the "first_letter" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setFirstLetter($value)
    {
        if (!array_key_exists('first_letter', $this->fieldsModified)) {
            $this->fieldsModified['first_letter'] = $this->data['fields']['first_letter'];
        } elseif ($value === $this->fieldsModified['first_letter']) {
            unset($this->fieldsModified['first_letter']);
        }

        $this->data['fields']['first_letter'] = $value;
    }

    /**
     * Returns the "first_letter" field.
     *
     * @return mixed The first_letter field.
     */
    public function getFirstLetter()
    {
        return $this->data['fields']['first_letter'];
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

    /**
     * Set the "slug" field.
     *
     * @param mixed $value The value.
     *
     * @return void
     */
    public function setSlug($value)
    {
        if (!array_key_exists('slug', $this->fieldsModified)) {
            $this->fieldsModified['slug'] = $this->data['fields']['slug'];
        } elseif ($value === $this->fieldsModified['slug']) {
            unset($this->fieldsModified['slug']);
        }

        $this->data['fields']['slug'] = $value;
    }

    /**
     * Returns the "slug" field.
     *
     * @return mixed The slug field.
     */
    public function getSlug()
    {
        return $this->data['fields']['slug'];
    }


    public function preInsertExtensions()
    {
        $this->updateTimestampableCreated();
        $this->updateSluggableSlug();

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
        if (isset($array['rev'])) {
            $this->setRev($array['rev']);
        }
        if (isset($array['cover'])) {
            $this->setCover($array['cover']);
        }
        if (isset($array['wiki_id'])) {
            $this->setWikiId($array['wiki_id']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['html_cache'])) {
            $this->setHtmlCache($array['html_cache']);
        }
        if (isset($array['content'])) {
            $this->setContent($array['content']);
        }
        if (isset($array['tags'])) {
            $this->setTags($array['tags']);
        }
        if (isset($array['comment_tags'])) {
            $this->setCommentTags($array['comment_tags']);
        }
        if (isset($array['model'])) {
            $this->setModel($array['model']);
        }
        if (isset($array['has_video'])) {
            $this->setHasVideo($array['has_video']);
        }
        if (isset($array['like_num'])) {
            $this->setLikeNum($array['like_num']);
        }
        if (isset($array['dislike_num'])) {
            $this->setDislikeNum($array['dislike_num']);
        }
        if (isset($array['watched_num'])) {
            $this->setWatchedNum($array['watched_num']);
        }
        if (isset($array['admin_id'])) {
            $this->setAdminId($array['admin_id']);
        }
        if (isset($array['do_date'])) {
            $this->setDoDate($array['do_date']);
        }
        if (isset($array['source'])) {
            $this->setSource($array['source']);
        }
        if (isset($array['tvsou_id'])) {
            $this->setTvsouId($array['tvsou_id']);
        }
        if (isset($array['first_letter'])) {
            $this->setFirstLetter($array['first_letter']);
        }
        if (isset($array['created_at'])) {
            $this->setCreatedAt($array['created_at']);
        }
        if (isset($array['updated_at'])) {
            $this->setUpdatedAt($array['updated_at']);
        }
        if (isset($array['slug'])) {
            $this->setSlug($array['slug']);
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

        if (null !== $this->data['fields']['rev']) {
            $array['rev'] = $this->data['fields']['rev'];
        }
        if (null !== $this->data['fields']['cover']) {
            $array['cover'] = $this->data['fields']['cover'];
        }
        if (null !== $this->data['fields']['wiki_id']) {
            $array['wiki_id'] = $this->data['fields']['wiki_id'];
        }
        if (null !== $this->data['fields']['title']) {
            $array['title'] = $this->data['fields']['title'];
        }
        if (null !== $this->data['fields']['html_cache']) {
            $array['html_cache'] = $this->data['fields']['html_cache'];
        }
        if (null !== $this->data['fields']['content']) {
            $array['content'] = $this->data['fields']['content'];
        }
        if (null !== $this->data['fields']['tags']) {
            $array['tags'] = $this->data['fields']['tags'];
        }
        if (null !== $this->data['fields']['comment_tags']) {
            $array['comment_tags'] = $this->data['fields']['comment_tags'];
        }
        if (null !== $this->data['fields']['model']) {
            $array['model'] = $this->data['fields']['model'];
        }
        if (null !== $this->data['fields']['has_video']) {
            $array['has_video'] = $this->data['fields']['has_video'];
        }
        if (null !== $this->data['fields']['like_num']) {
            $array['like_num'] = $this->data['fields']['like_num'];
        }
        if (null !== $this->data['fields']['dislike_num']) {
            $array['dislike_num'] = $this->data['fields']['dislike_num'];
        }
        if (null !== $this->data['fields']['watched_num']) {
            $array['watched_num'] = $this->data['fields']['watched_num'];
        }
        if (null !== $this->data['fields']['admin_id']) {
            $array['admin_id'] = $this->data['fields']['admin_id'];
        }
        if (null !== $this->data['fields']['do_date']) {
            $array['do_date'] = $this->data['fields']['do_date'];
        }
        if (null !== $this->data['fields']['source']) {
            $array['source'] = $this->data['fields']['source'];
        }
        if (null !== $this->data['fields']['tvsou_id']) {
            $array['tvsou_id'] = $this->data['fields']['tvsou_id'];
        }
        if (null !== $this->data['fields']['first_letter']) {
            $array['first_letter'] = $this->data['fields']['first_letter'];
        }
        if (null !== $this->data['fields']['created_at']) {
            $array['created_at'] = $this->data['fields']['created_at'];
        }
        if (null !== $this->data['fields']['updated_at']) {
            $array['updated_at'] = $this->data['fields']['updated_at'];
        }
        if (null !== $this->data['fields']['slug']) {
            $array['slug'] = $this->data['fields']['slug'];
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
                'rev' => array(
                    'type' => 'integer',
                ),
                'cover' => array(
                    'type' => 'string',
                ),
                'wiki_id' => array(
                    'type' => 'integer',
                ),
                'title' => array(
                    'type' => 'string',
                ),
                'html_cache' => array(
                    'type' => 'string',
                ),
                'content' => array(
                    'type' => 'string',
                ),
                'tags' => array(
                    'type' => 'raw',
                ),
                'comment_tags' => array(
                    'type' => 'raw',
                ),
                'model' => array(
                    'type' => 'string',
                ),
                'has_video' => array(
                    'type' => 'integer',
                ),
                'like_num' => array(
                    'type' => 'integer',
                ),
                'dislike_num' => array(
                    'type' => 'integer',
                ),
                'watched_num' => array(
                    'type' => 'integer',
                ),
                'admin_id' => array(
                    'type' => 'integer',
                ),
                'do_date' => array(
                    'type' => 'date',
                ),
                'source' => array(
                    'type' => 'raw',
                ),
                'tvsou_id' => array(
                    'type' => 'string',
                ),
                'first_letter' => array(
                    'type' => 'string',
                ),
                'created_at' => array(
                    'type' => 'date',
                ),
                'updated_at' => array(
                    'type' => 'date',
                ),
                'slug' => array(
                    'type' => 'string',
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