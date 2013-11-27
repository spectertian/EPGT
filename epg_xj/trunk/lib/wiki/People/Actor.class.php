<?php

/**
 * 艺人
 *
 * @author pjl
 */
class Wiki_People_Actor extends Wiki_People_Abstract {
    protected $model_name = "actor";
    protected $model_show_name = "艺人";
    protected $model_form = "WikiActorForm";

    protected $wActorFileds = array(
            'faith' => 'string', //宗教信仰
            'region' => 'string', //地域
            'debut' => 'string', //出道日期
        );


    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wActorFileds);

        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wActorFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wActorFileds, $data);
        parent::setDocumentData($data);
    }

    /**
     * 获取演员参演的电视剧或电影
     * @param <type> $wiki_title
     * @return <type>
     */
    function getFilmography ($wiki_title){
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        return $wiki_repository->find(array(
                "query" =>  array("starring"=>$wiki_title),
                "sort"  => array("created_at" => -1),
        ));
    }
    /**
     * 获取演员参演的电视剧或电影,分页获取
     * @param <type> $wiki_title
     * @author lifucang
     * @return <type>
     */
    function getFilmographyBySize ($wiki_title,$page,$size){
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        return $wiki_repository->find(array(
                "query" =>  array("starring"=>$wiki_title),
                "sort"  => array("created_at" => -1),
				'skip' => ($page - 1)*$size,
				'limit'=> $size,                
        ));
    }
    /**
     * 获取演员参演的电视剧或电影总数
     * @param <type> $wiki_title
     * @author lifucang
     * @return <type>
     */
    function getFilmographyCount ($wiki_title){
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        return $wiki_repository->count(array(
                "starring"=>$wiki_title,

        ));
    }    
}