<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 影视剧抽象类
 * 
 * @author Administrator
 */
abstract class Wiki_FilmTV_Abstract extends WikiAbstract
{
    /**字段=>类型*/
    protected $wFilmTvFileds = array('ename' => 'string',
        'alias' =>'raw','director'=>'raw',
        'starring'=>'raw','time'=>'string','produced'=>'string','released'=>'string',
        'language'=>'string','screenshots'=>'raw', 'country' => 'string',
        'writer' => 'raw', //编剧
        'distributor' => 'raw', //发行商
        'qiyi' => 'string',  // 视频播放地址
        );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wFilmTvFileds);
        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wFilmTvFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wFilmTvFileds, $data);
        parent::setDocumentData($data);
    }
    
    public function getStarring($join = "") {
        $value   = $this->arrayToString('starring', $join);
        return $value;
    }

    public function  setStarring($value) {
        $value  = $this->tryConvertArray($value);
        $this->setPropety('starring', $value);
    }

    public function getAlias($join = "") {
        $tags   = $this->arrayToString('alias', $join);
        return $tags;
    }
    
    public function  setAlias($value) {
        $value  = $this->tryConvertArray($value);
        $this->setPropety('alias', $value);
    }

    /**
     * 设置导演
     * @param array|string director 导演，可为以,分割的字符串或数组
     */
    public function setDirector($director) {
        $director = $this->tryConvertArray($director);
        $this->setPropety("director", $director);
    }

    /**
     * 导演
     */
    public function getDirector($join = "") {
        $value   = $this->arrayToString('director', $join);
        return $value;
    }

    /**
     * 设置编剧
     * @param array|string write 编剧，可为以,分割的字符串或数组
     */
    public function setWriter($writer) {
        $writer = $this->tryConvertArray($writer);
        $this->setPropety("writer", $writer);
    }

    /**
     * 编剧
     */
    public function getWriter($join = "") {
        $value   = $this->arrayToString('writer', $join);
        return $value;
    }

    /**
     * 设置发行商
     * @param array|string distributor 发行商，可为以,分割的字符串或数组
     */
    public function setDistributor($distributor) {
        $distributor = $this->tryConvertArray($distributor);
        $this->setPropety("distributor", $distributor);
    }

    /**
     * 发行商
     */
    public function getDistributor($join = "") {
        $value   = $this->arrayToString('distributor', $join);
        return $value;
    }

}
