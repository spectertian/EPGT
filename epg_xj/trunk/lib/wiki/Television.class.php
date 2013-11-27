<?php

/**
 * 电视栏目模型
 */
class Wiki_Television extends WikiAbstract {
    // 模型代号
    protected $model_name = "television";
    protected $model_show_name = "栏目";
    protected $model_form = "WikiTelevisionForm";

    protected $types = array('综艺节目', '婚恋', '娱乐');
    
    /**字段=>类型*/
    protected $wTelevisionFileds = array(
        'alias' => 'raw', //别名
        "channel" => "string", // 播出频道
        "play_time" => "string", // 播出时间
        "host" => "raw", // 主持人
        "guest" => "raw", // 嘉宾
        "screenshots" => "raw", // 图片
        'producer' => 'raw', //监制
        'runtime' => 'string', //播出时长
        'country' => 'string', //国家地区
        'language' => 'string', //语言
        'imdb' => 'string',
        'douban_id'=> 'string',
    );

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wTelevisionFileds);
        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wTelevisionFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wTelevisionFileds, $data);
        parent::setDocumentData($data);
    }

    /**
     * 栏目主持人
     * @param <type> $host
     */
    public function setHost($host) {
        $host  = $this->tryConvertArray($host);
        $this->setPropety('host', $host);
    }

    public function getHost($join = "") {
        $host   = $this->arrayToString('host', $join);
        return $host;
    }

    /**
     * 嘉宾 set方法
     * @param <type> $guest
     * @author ward
     */
    public function setGuest($guest) {
        $guest  = $this->tryConvertArray($guest);
        $this->setPropety('guest', $guest);
    }

    /**
     * 嘉宾 get方法
     * @param <type> $join
     * @return <type>
     */
    public function getGuest($join = "") {
        $guest   = $this->arrayToString('guest', $join);
        return $guest;
    }

    /**
     * 监制 set方法
     * @param <string> $producer
     * @author pjl
     */
    public function setProducer($value) {
        $value  = $this->tryConvertArray($value);
        $this->setPropety('producer', $value);
    }

    /**
     * 监制 get方法
     * @param <string> $join
     * @return <array | string>
     * @author pjl
     */
    public function getProducer($join = "") {
        $producer   = $this->arrayToString('producer', $join);
        return $producer;
    }

    /**
     * 别名 set方法
     * @param <string> $join
     * @return <array | string>
     * @author pjl
     */
    public function getAlias($join = "") {
        $tags   = $this->arrayToString('alias', $join);
        return $tags;
    }

    /**
     * 别名 get 方法
     * @param <string> $value
     */
    public function  setAlias($value) {
        $value  = $this->tryConvertArray($value);
        $this->setPropety('alias', $value);
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
     * 获取维基栏目的相关视频需要传递一个 mark 分期
     * @author luren
     */
    public function getVideos($mark=null) {
        $mongo = $this->getMondongo();
        $video_repos = $mongo->getRepository('Video');
        if($mark){
            return $video_repos->find(
                                array(
                                   'query' => array(
                                            'wiki_id' => (string) $this->getId(),
                                            'mark' => $mark
                                        )
                                    )
                                );
        }else{
            return $video_repos->find(
                                array(
                                   'query' => array(
                                            'wiki_id' => (string) $this->getId()
                                        )
                                    )
                                );
        }
    }
    /**
     * 获取维基栏目的相关视频
     * @author lifucang
     */
    public function getVideosByWiki() {
        $mongo = $this->getMondongo();
        $video_repos = $mongo->getRepository('Video');
        return $video_repos->find(
                            array(
                               'query' => array(
                                        'wiki_id' => (string) $this->getId()
                                    ),
                                'sort'=>array('mark'=>-1,'created_at'=>-1)
                                )
                            );
    }    
    /**
     * 获取播放列表
     * @return <type>
     * @author lifucang
     */
    public function getPlayList() {
        $mongo = $this->getMondongo();
        $playlist_repos = $mongo->getRepository('VideoPlayList');
        return $playlist_repos->getVideosByWikiId((string) $this->getId());
    }  
    /**
     * 获取单个栏目的所有分期的归档日期
     * 用于归档页面的过滤功能
     * 传递一个归档年份
     * @author luren
     */
    public function getArchiveDate($Y) {
        $years = $months = array();
        $Y = ($Y) ? $Y : date('Y', time());
        $mongo = $this->getMondongo();
        $wikimeta_repository = $mongo->getRepository('wikiMeta');
        $metas = $wikimeta_repository->find(array(
                                            'query' => array('wiki_id' => (string) $this->getId()),
                                            'fields' => array('year', 'month')
                                        )
                                    );
        if ($metas) {
            foreach($metas as $meta) {
                $year = $meta->getYear();
                $years[] = $year;
                $months[$year][] = $meta->getMonth();
            }

            $years = array_unique($years);
            rsort($years);

            if (isset($months[$Y])) {
                $months = array_unique($months[$Y]);
            } else {
                ksort($months);
                $months = array_unique(end($months));
            }
            
            rsort($months);
        }

        return array('years' => $years, 'months' => $months);
    }
}
