<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 电影
 *
 * @author zhigang
 */
class Wiki_FlimTV_Film extends Wiki_FilmTV_Abstract {
    protected $model_name = "film";
    protected $model_show_name = "电影";
    protected $model_form = "WikiFilmForm";

    protected $wFilmFileds = array(
            'runtime' => 'string', //片长
        );
    protected $types = array('电影', '战争片', '喜剧片');

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wFilmFileds);

        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wFilmFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wFilmFileds, $data);
        parent::setDocumentData($data);
    }

    public function getTypes($join = "") {
        $tags = $this->getTags();

        $intersect = array_intersect($tags, $this->types);

        if($join) {
            $intersect = implode($intersect, $join);
        }

        return $intersect;
    }

    /**
     * 获取电影维基的相关视频
     * @author luren
     */
    public function getVideos() {
        $mongo = $this->getMondongo();
        $video_repos = $mongo->getRepository('Video');
        return $video_repos->find(
                            array(
                                   'query' => array('wiki_id' => (string) $this->getId())
                                )
                            );
    }
}
