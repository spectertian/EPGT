<?php

/**
 * Wiki document.
 */
class Wiki extends \BaseWiki
{
    // 申明评分变量
    protected
        $like_num,
        $dislike_num,
        $watch_num,
        $rating,
        $rating_total,
        $comments,
        $comment_count;
    protected $basejuzhao = "1313572849892.png";
        /**
     * 手动set调用
     * @param <type> $field
     * @param <type> $value
     */     
    public function setPropety($field, $value) {
        if (null == $value && null == $this->data["fields"][$field]) {
            return ;
        }
        if (!array_key_exists($field, $this->fieldsModified)) {
            $this->fieldsModified[$field] = $this->data['fields'][$field];
        } elseif ($value === $this->fieldsModified[$field]) {
            unset($this->fieldsModified[$field]);
        }
        $this->data["fields"][$field]   = $value;
    }
/*
    public function  setId($value) {
        $this->setPropety('_id', $value);
        //parent::setId($value);
    }
*/
    /**
     * 数组转换字符串
     * @param <String> $glue
     * @param <Array> $piecesarray
     * @return <String>
     * @author ward
     */
    public function arrayToString($piecesarray, $glue=',') {
        $piecesarray    = $this->data['fields'][$piecesarray];
        
        if ('' == $glue || !is_array($piecesarray)) {
            return $piecesarray;
        }
        
        return implode($glue, $piecesarray);
    }

    /**
     * 字符串转换成数组
     * @param <String> $delimiter
     * @param <Array> $array
     * @return <Array>
     */
    public function stringToArray($array, $delimiter='') {
        $array  = $this->data['fields'][$array];
        if ($delimiter) {
            return explode($delimiter, $array);
        }
        return $array;
    }

    /**
     * 自动设置属性
     * @param string $method
     * @param mixed $arguments
     * @return mixed
     * @author zhigang
     */
    public function  __call($method,  $arguments) {
        if (in_array($verb = substr($method, 0, 3), array('set', 'get'))) {
            $name = substr($method, 3);
            $field_name = sfInflector::underscore($name);
            if ($verb === 'get') {
                if (array_key_exists($field_name, $this->data['fields'])) {
                    return $this->data["fields"][$field_name];
                }
            } elseif ($verb === "set") {
                $value = $arguments[0];
                $this->setPropety($field_name, $value);
                /*if (!array_key_exists($field_name, $this->fieldsModified)) {
                    $this->fieldsModified[$field_name] = $this->data['fields'][$field_name];
                } elseif ($value === $this->fieldsModified[$field_name]) {
                    unset($this->fieldsModified[$field_name]);
                }
                $this->data["fields"][$field_name] = $value;*/
            }
        }
    }

    /**
     * 数据新增前事件, 触发于数据尚未保存至数据库前
     * @author zhigang
     */
    public function preInsert() {
        $this->updateModelName();
    }

    /**
     * 数据保存后事件，包括插入与新增
     * @author zhigang
     */
    public function postSave() {
        /*
        $memcache = tvCache::getInstance();
        $memcache_key = md5($this->getSlug());
        if($memcache->has($memcache_key)){
            $memcache->delete($memcache_key);
        }
        $this->updateXunSearchDocument();
        */
    }

    /**
     * 数据删除后同步删除 Xapian 索引
     */
    public function postDelete() {
        $memcache = tvCache::getInstance();
        $memcache_key = md5($this->getSlug());
        if($memcache->has($memcache_key)){
            $memcache->delete($memcache_key);
        }

        // xapian 删除
				/* $xapian_database = SearchEngine::getWritableDatabase('wiki');
					 $xapian_database->delete_document("Q".$this->getId());
				*/
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
        $xs = new XS('epg_wiki');
        $index = $xs->index;
				$index->del($this->getId(), 'id'); 
		
        $mongo = $this->getMondongo();
        $recommendRepos = $mongo->getRepository('WikiRecommend');
        $wikiMetaRepos = $mongo->getRepository('wikiMeta');
        // 推荐删除
        $recommend = $recommendRepos->findOne(array('query' => array('wiki_id' => (string)$this->getId())));
        if (!is_null($recommend)) $recommend->delete();
         // 维基Meta删除
        $wikiMetas = $wikiMetaRepos->getMetasByWikiId((string)$this->getId());
        if (!is_null($wikiMetas)) {
            foreach ($wikiMetas as $meta) {
                $meta->delete();
            }
        }
    }

    /**
     * 设置维基所属模型，如: 电视剧、电影等，主要由 preInsert 事件调用
     * @author zhigang
     */
    public function updateModelName() {
        //$model = get_class($this);
        $model_name = $this->getModelName()?$this->getModelName():$this->getModel();  //加上? :(2012-9-21 lfc)
        $this->setModel($model_name);
    }

    /**
     * 更新或插入 Xapian 全文搜索引擎索引
     * @param string $doc_id
     */
    public function updateXapianDocument() {
        $id = $this->getId();
        $xapian_database = SearchEngine::getWritableDatabase('wiki');
        $xapian_document = new XapianDocument();
        $xapian_document->add_term("Q".$id);
//        $data = $this->toArray();
//        unset($data['title']);
        $title = $this->getTitle();
        $content = $this->getContent();
 
        $xapian_document->add_term("S".$title);
        $xapian_document->add_term("Z".$title, 10);
//        $content = implode("\n", $data);
        // 开始分词
        $scws = scws_new();
        $scws->set_charset("utf8");
        $scws->send_text($title);
        $title_words = $scws->get_words("~un");
        foreach ($title_words as $word) {
            $xapian_document->add_term("Z".$word['word'], 5);
        }
        
        $scws->send_text($content);
        $key_word = array();
        while ($tmp = $scws->get_result()) {
            foreach ($tmp as $w) {
                if ($w['attr'] == "un") {
                    continue;
                }
                $xapian_document->add_posting("Z".$w['word'], $w['off']);
            }
        }
        $xapian_default_index = "Z";
        $xapian_default_factor = 0;
        if ($tags = $this->getTags()) {
            foreach ($tags as $tag) {
                $xapian_document->add_term("K".$tag);
            }
        }

        switch ($this->getModel()) {
            //电影
            case 'film':
                 // 别名
                if ($alias = $this->getAlias()) {
                    $this->toXapainFactory($alias, $scws, $xapian_document, $xapian_default_index,5);
                }          
                // 导演
                if ($directors = $this->getDirector()) {
                    $this->toXapainFactory($directors, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);
                }

                //演员
                if ($stars = $this->getStarring()) {
                    $this->toXapainFactory($stars, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);
                }
                //上映地区
                if($countory = $this->getCountry()){
                    $this->toXapainFactory($countory, $scws, $xapian_document, "X", $xapian_default_factor);
                }
                //上映时间
                if($released = $this->getReleased()){
                    if($year = $this->getYear($released)){
                        $this->addXapianValue('year',$year,$xapian_document);
                    }                    
                }
                
                 //评分
                if($this->getRating() > 0){
                    $this->addXapianValue('rating', $this->getRating(), $xapian_document);
                }
                
                $xapian_document->add_term("XTYPEvideo");                                    //给影视剧设置自定义前缀

                //是否有视频源
                if($videos = $this->getVideos()){
                   $xapian_document->add_term("XHASVIDEOtrue");                             //标示是否有视频源
                   foreach($videos as $video){
                    $this->toXapainFactory($video->getReferer(), $scws, $xapian_document, "XSOURCE", $xapian_default_factor);
                   }
                }
                break;
            //电视剧
            case 'teleplay':
                // 导演
                if ($directors = $this->getDirector()) {
                    $this->toXapainFactory($directors, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);
                }
                
                //演员
                if ($stars = $this->getStarring()) {
                    $this->toXapainFactory($stars, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);
                }
                
                //echo "别名\n    ";
                if ($alias= $this->getAlias()) {
                    $this->toXapainFactory($alias, $scws, $xapian_document, $xapian_default_index,5);
                }
                //上映地区
                if($countory = $this->getCountry()){
                    $this->toXapainFactory($countory, $scws, $xapian_document, "X", $xapian_default_factor);
                }
                //上映时间
                if($released = $this->getReleased()){
                    if($year = $this->getYear($released)){
                        $this->addXapianValue('year',$year,$xapian_document);
                    }                   
                }
                 //评分
                if($this->getRating() > 0){
                    $this->addXapianValue('rating', $this->getRating(), $xapian_document);
                }
                
                $xapian_document->add_term("XTYPEvideo");                                    //给影视剧设置自定义前缀
                //是否有视频源
                if($videos = $this->getPlayList()){
                   $xapian_document->add_term("XHASVIDEOtrue");                             //标示是否有视频源
                   foreach($videos as $video){
                    $this->toXapainFactory($video->getReferer(), $scws, $xapian_document, "XSOURCE", $xapian_default_factor);
                   }
                }

                break;
            //栏目
            case 'television':
                if ($guests = $this->getGuest()) {
                    $this->toXapainFactory($guests, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);
                }
     
                if ($hosts = $this->getHost()) {
                    $this->toXapainFactory($hosts, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);
                }

               if ($alias = $this->getAlias()) {
                      $this->toXapainFactory($alias, $scws, $xapian_document, $xapian_default_index,5);
                }

                if ($channel = $this->getChannel()) {
                    $this->toXapainFactory($alias, $scws, $xapian_document, $xapian_default_index,$xapian_default_factor);
                }
                 //评分
                if($this->getRating() > 0){
                    $this->addXapianValue('rating', $this->getRating(), $xapian_document);
                }

                //模型
                if($model = $this->getModel()){
                    $xapian_document->add_term("XTYPE".$this->getModel());
                }
                break;
            //艺人, 篮球球员， 足球球员
            case 'actor':
                if($title = $this->getTitle()){
                     $this->toXapainFactory($title, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);//加入正文索引
                     $xapian_document->add_term("XTYPE".$this->getModel());                                             //给予特定前缀来区分
                     $xapian_document->add_term("XACTOR".$title);                     
                }
                if($english_name = $this->getEnglish_name()){
                    $this->toXapainFactory($english_name, $scws, $xapian_document,$xapian_default_index,$xapian_default_factor);//加入正文索引
                }                
                break;
                
            case 'basketball_player':
            case 'footerball_player':
                break;
            //篮球球队， 足球球队，NBA 球队
            case 'basketball_team':
            case 'footerball_team':
            case 'nba_team':
                break;
            default:
                //...
        }
        
        $data = array("id" => (string) $id, "title" => $title);
        $xapian_document->set_data(json_encode($data));
        //$doc_id = $xapian_database->replace_document("Q".$id, $xapian_document);
        
    }
    /*
     * 根据不同的需求添加value到XapianDocument 以便区间检索和排序
     * @param type $type
     * @param 字段
     * @param XapianDocument对象
     * @return XapianDocument
     */
    public function addXapianValue($type,$value,XapianDocument &$xapian_document){
        switch($type){
            case "year":
                    $this->addNumberValue(1,$value,$xapian_document);
                    break;
            case "rating":
                    $this->addNumberValue(2,$value, $xapian_document);
                    break;
            case "create":
                    $this->addNumberValue(3, $value, $xapian_document);
                    break;
        }
    }
    /*
     * 返回日期中的年份
     * @param $field 字段
     * @param $length 长度
     * return integer 
     */
    public function getYear($field){
       preg_match_all("/\D?(\d{4})\D?/", $field, $matches);
       if($matches[1]){
           return $matches[1][0];
       }else{
           return false;
       }
    }

    /**/
    /*
     * 添加数值到XapianDocument
     * @param <type> $key
     * @param $xapian_document XapianDocument对象
     * @return XapianDocument
     * @author guoqiang.zhagn
     */
     public function addNumberValue($valueno,$value,  XapianDocument &$xapian_document){
        $xapian_document->add_value($valueno,Xapian::sortable_serialise($value));
        //$xapian_document->add_value($valueno,2011);
     }

    /*
     * 添加索引到$xapian_document的factory方法
     * @param <type> $keyword
     * @param $scws SCWS分词对象
     * @param $xapian_document XapianDocument对象
     * @param $xapian_index 添加到XapianDocument中的Xapian Index
     * @param $xapian_factor 权重
     * @retrun XapianDocument
     */
     public function toXapainFactory($keyword,$scws,  XapianDocument &$xapian_document,$xapian_index="Z",$xapian_factor=0)
     {
        if(is_array($keyword)){
            return $this->_arrayScwsAddTerm($keyword,$scws, $xapian_document,$xapian_index,$xapian_factor);
        }else{
            return $this->_scwsAddTerm($keyword,$scws, $xapian_document,$xapian_index,$xapian_factor);
        }
     }

    /**
     * 单条数据需要分词 应该 scws 分词并保存到 $xapian_docment
     * @param <type> $array
     * @param $scws  SCWS 分词对象
     * @param $xapian_document XapianDocument 对象
     * @param $xapian_index  XapianDocument index
     * @param $xapian_factor 权重
     * @return XapianDocument
     */
     private  function _scwsAddTerm($keyword,$scws,XapianDocument &$xapian_document,$xapian_index="Z",$xapian_factor=0)
     {
         $scws->send_text($keyword);
         $words = $scws->get_words('~un');
         foreach ($words as $w) {
            $xapian_document->add_term($xapian_index.$w['word'],$xapian_factor);
         }
     }
     
    /**
     * 遍历数组 应该 scws 分词并保存到 $xapian_docment
     * @param <type> $array
     * @param $scws  SCWS 分词对象
     * @param $xapian_document XapianDocument 对象
     * @param $xapian_index  XapianDocument index
     * @param $xapian_factor 权重
     * @return XapianDocument
     */
    private function _arrayScwsAddTerm($array, $scws, XapianDocument &$xapian_document,$xapian_index="Z",$xapian_factor=0)
    {
        foreach ($array as $item) {
            $scws->send_text($item);
            $words = $scws->get_words('~un');
            foreach ($words as $w) {
                $xapian_document->add_term($xapian_index.$w['word'],$xapian_factor=0);
            }
        }     
        return $xapian_document;
    }
      
    /**
     * 生成url,过滤特殊字符
     * @param <String> $text
     * @return <String>
     * @author ward
     */
    static public function slugify($text) {
        $pattern = '/[^\x61-\xff\d]/';
        $text = preg_replace($pattern, '-', $text);
        $text = strtolower(trim($text, '-'));
        return $text;
    }


    public function  setTags($value) {
        $value  = $this->tryConvertArray($value);
        $this->setPropety('tags', $value);
    }

    /**
     * 设置内容时，自动设置 HTML Cache
     * @param string $value
     * @author zhigang
     */
    public function  setContent($value) {
        parent::setContent($value);

        $html_cache = WikiPasers::render($value);
        $this->setHtmlCache($html_cache);
    }

    public function getTags($join = "") {
        $tags   = $this->arrayToString('tags', $join);
        return $tags;
    }


    /**
     * 尝试转换数组
     * @param <Array> $value
     * @author ward
     */
    public function tryConvertArray($value) {
        if (!is_array($value) && strlen($value)) {
            $value  = explode(',', $value);
        }
        return $value;
    }

    /**
     * 覆盖父级方法，实现对象转换数组，主要供 Form 使用
     * @param bool $withEmbeddeds
     * @return array
     */
    public function  toArray($withEmbeddeds = true) {
        return $this->data['fields'];
    }

    /**
     * 覆盖父级方法，实现数组赋值对象，主要供 Form 使用
     * @param array $array
     */
    public function  fromArray($array) {
        foreach ($array as $key => $value) {
            $set_function = "set".sfInflector::camelize($key);
            $this->$set_function($value);
        }
    }
//    public function  getTagsToString() {
//        return implode(',', $this->getTags());
//    }

    /**
     * 获取 Wiki 封面地址
     * @param $return_default bool
     * @return string
     * @author pjl 增加return_default参数, 用于是否返回默认图片
     */
    public function getCoverUrl($return_default = true) {
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('GetFileUrl','Asset'));
        $cover = $this->getCover();
        if ($cover) {
            $cover_url = file_url($cover);
        } else {
            if($return_default) {
                $cover_url = image_path("details_no_cover.png", true);
            } else {
                $cover_url = '';
            }
        }
        return $cover_url;
    }

    /**
     * 获取剧照地址
     * @return <array>
     * @author pjl
     */
    public function getScreenshotUrls($width = 150, $height = 150) {
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('GetFileUrl','Asset'));
        $screenshots = $this->getScreenshots();
        $urls = array();
        if($screenshots) {
            foreach($screenshots as $screenshot) {
                $urls[] = thumb_url($screenshot, $width, $height);
            }
        }

        return $urls;
    }
    

    /**
     * 获取剧照数
     * @return <int>
     * @author pjl
     */
    public function getScreenshotsCount() {
        $screenshots = $this->getScreenshots();
        
        return count($screenshots);
    }

    public function  getHtmlCache($lengh = 0) {
        $html_cache = trim($this->data['fields']['html_cache']);
        if ($lengh != 0) {
            $html_cache = mb_strimwidth(strip_tags($html_cache), 0, $lengh, '...', 'utf-8');
        }
        return $html_cache;
    }

    /**
     * 根据 开始日期 结束日期 省份 获取该条用户能看到的相关节目
     * @param <string> $province
     * @param <date> $fromdate
     * @param <date> $enddate
     * @return <type>
     * @author luren
     */
    public function getUserRelateProgramByDate($province, $fromdate, $enddate) {
        $mongo = $this->getMondongo();
        $program_repository = $mongo->getRepository('Program');
        return $program_repository->getUserRelateProgramByDate((string) $this->getId(),$province, $fromdate, $enddate);
    }

    /**
     * 获取该条维基的 wikimeta 数据
     * @return <type>
     * @author luren
     */
    public function getWikiMeta() {
        $mongo = $this->getMondongo();
        $wikiMetaRepos = $mongo->getRepository('WikiMeta');
        return $wikiMetaRepos->getMetasByWikiId((string) $this->getId());
        
    }
    
     /**
     * 设置 Has_video 的值
     * @param <boolean> $value true/false
     * @author luren
     */
    public function setHasVideo($value) {
        if (is_bool($value)) {
            $has_video = $this->getHasVideo();
            if ($has_video) {
                if ($value) {
                    parent::setHasVideo($has_video + 1);
                } else {
                     parent::setHasVideo($has_video - 1);
                }
            } else {
                if ($value) {
                    parent::setHasVideo(1);
                } else {
                    parent::setHasVideo(0);
                }
            }
        } elseif (is_numeric($value)) {
            parent::setHasVideo($value);
        }
    }

    /**
     * 传递动作行为参数 设置 喜欢 / 不喜欢 / 看过的值 并更新 UserWiki 记录
     * @param <string> $action   LikeNum / DislikeNum / WatchedNum
     * @param <boolean> $value
     * @author luren
     */
    public function setActionValue($action, $value,$isComment=true,$user_id=null,$repeat=false,$comentContent='') {
        $get_method = 'get'.ucfirst($action).'Num';
        $set_method = 'set'.ucfirst($action).'Num';
        $FieldValue = $this->$get_method();
        
        //判断是否是同一个人点击:lfc
        if($repeat){
            $userid=sfContext::getInstance()->getUser()->getAttribute('user_id');
            $currentuser=sfContext::getInstance()->getUser()->getAttribute('currentuser');
            if($Currentuser==''){
                sfContext::getInstance()->getUser()->setAttribute('currentuser',$userid);
            }       
        }
        
        if (is_bool($value)) {
            if ($value) {
                if ($FieldValue) {
                    if($repeat){
                        if($currentuser!=$userid)  //不是同一个人点击才加1:lfc
                        $FieldValue = $FieldValue + 1;
                    }else{
                        $FieldValue = $FieldValue + 1;
                    }
                } else {
                    $FieldValue = 1;
                }
                if($isComment){
                    // 增加一条评论记录
                    $commend = new Comment();
                    if($user_id)
                    {
                    	$commend->saveComent((string) $this->getId(), $action,0,$comentContent,$user_id);
                    }
                    else
                    {
                    	$commend->saveComent((string) $this->getId(), $action);
                    }
                }
            } else {
                if ($FieldValue > 0) {
                    $FieldValue = $FieldValue - 1 ;
                } else {
                    $FieldValue = 0;
                }
                if($isComment){
                // 删除一条评论记录
                    $mongo = $this->getMondongo();
                    $CommentRepository = $mongo->getRepository('Comment');
                    $user_id = sfContext::getInstance()->getUser()->getAttribute('user_id');
                    $comment = $CommentRepository->getOneComment($user_id, (string) $this->getId(), $action);
                    if ($comment) $comment->delete();
                }
            }
            call_user_func(array($this, $set_method), $FieldValue);
        } else {
            call_user_func(array($this, $set_method), $value);
        }
    }

    /**
     * 返回该条维基的评分
     * @author luren
     */
    public function getRating() {
        if (!isset($this->rating)) {
            $this->rating = ($this->getRatingTotal() > 0) ? round($this->getLikeNum() / $this->getRatingTotal(), 2) * 10 : 0;
        }
        return $this->rating;
    }
    /**
     * 返回该条维基的评分总数
     * @author luren
     */
    public function getRatingTotal() {
        if (!isset($this->rating_total)) {
            $this->rating_total = $this->getLikeNum() + $this->getDislikeNum();
        }

        return $this->rating_total;
    }
    /**
     * 返回该条维基的喜欢数量
     * @author luren
     */
    public function  getLikeNum() {
        if (!isset($this->like_num)){
            $this->like_num = (parent::getLikeNum()) ? parent::getLikeNum() : 0;
        }
        
        return $this->like_num;
    }
    
    /**
     * 返回该条维基的不喜欢数量
     * @author luren
     */
    public function getDislikeNum() {
        if (!isset($this->dislike_num)){
            $this->dislike_num = (parent::getDislikeNum()) ? parent::getDislikeNum() : 0;
        }

        return $this->dislike_num;
    }
    
    /**
     * 返回该条维基的看过数量
     * @author luren
     */
    public function  getWatchedNum() {
        if (!isset($this->watch_num)){
            $this->watch_num = (parent::getWatchedNum()) ? parent::getWatchedNum() : 0;
        }
        
        return $this->watch_num;
    }

    /**
     * 返回该条维基的评分的整数位
     * @author luren
     */
    public function getRatingInt() {
        return ($this->getRating() > 0 ) ? intval($this->getRating()) : 0;
    }

    /**
     * 返回该条维基的评分的小数位
     * @author luren
     */
    public function getRatingFloat() {
        if ($this->getRating() > 0) {
            $rating_array = explode('.', $this->getRating());
            if (count($rating_array) == 2) {
                return end($rating_array);
            } 
        }
        
        return 0;
    }

    /**
     * 返回该条维基的评分的显示颜色
     * @author luren
     */
    public function getRatingColor() {
        $color = 'red';
        
        if ($this->getRatingTotal() == 0) {
            $color = 'no-rating';
        } elseif ($this->getRating() > 7){
            $color = 'green';
        } elseif ($this->getRating() > 5) {
            $color = 'yellow';
        }

        return $color;
    }

    /**
     * 获取维基相关评论 要求评论内容不为空 父级 ID 为0
     * @author luren
     */
    public function getComments($limit = 10) {
        if (!isset($this->comments)) {
           $mongo = $this->getMondongo();
           $comment_repository = $mongo->getRepository('Comment');
           $this->comments = $comment_repository->find(array(
                                                        'query' => array(
                                                                'wiki_id'=> (string) $this->getId(),
                                                                'is_publish'=> true,
                                                                'parent_id'=> "0",
                                                                'text' => array('$ne' => ''),
                                                            ),
                                                        'sort' => array('created_at' => 1),
                                                        'limit' => $limit
                                                )
                                            );
        }

        return $this->comments;
    }
    /**
     * 获取该条维基的评论总数
     * @author luren
     */
    public function getCommentCount() {
        if (!isset($this->comment_count)) {
            $mongo = $this->getMondongo();
            $comment_repositroy = $mongo->getRepository('Comment');
            $this->comment_count =  $comment_repositroy->countCommentByWikiId((string) $this->getId());
        }
        
        return $this->comment_count;
    }
    /**
     * 获取该条维基的评论总数
     * @author luren
     */
    public function getVideoCount() {
        $mongo = $this->getMondongo();
        $video_repositroy = $mongo->getRepository('Video');
        $video_count =  $video_repositroy->countVideosByWikiId((string) $this->getId());
        return $video_count;
    }

   public function getAdminName() {
        if($this->getAdminId()){
            return Doctrine::getTable('Admin')->findOneById($this->getAdminId());
        }
    }
    
    /**
     * 默认设置暂无图片
     * @author lizhi
     */
    public function getCover() {
        //$basecover = '1313030694207.png';
        $basecover = '1353812833257.jpg';
        if(parent::getCover()==NULL) {
            return $basecover;
        }else{
            return parent::getCover();
        }
    }
    /**
     * 数组转化为字符串
     * @param array $arrs
     * @author wangnan
     */
	public function getStr($arrs)
	{
		if(is_array($arrs))
		{
	        $str = '';
	        foreach ($arrs as $arr) 
	        {
	        	$str .= $arr.' ';
	        }
	        return $str;
		}
		else 
			return $arrs;
	
	}
     /**
     * 更新或插入 XunSearch 全文搜索引擎索引
     * @param string $doc_id
     * @author wangnan
     */
    public function updateXunSearchDocument() {        
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
		
        $xs = new XS('epg_wiki');
        $index = $xs->index; 
        
        if ($tags = $this->getTags())
        {
        	$xun_tag = $this->getStr($tags);
        }
        else
        	$xun_tag = '';
		$data = array(
            'id' => $this->getId(),
            'title' => $this->getTitle()?$this->getTitle():'',
            'content' => $this->getContent()?$this->getContent():'',
			'tag'=>$xun_tag,
            'first_letter'=>$this->getFirstLetter()?$this->getFirstLetter():'',
            'updated_at'=>$this->getUpdatedAt()?$this->getUpdatedAt()->getTimestamp():0,
        );
        unset($xun_tag);
    	switch ($this->getModel()) 
    	{
            //电影
            case 'film':
            	$data['model'] = "film";
                 // 别名
                if ($alias = $this->getAlias()) 
                {
		        	$xun_alias = $this->getStr($alias);
                    $data['alias'] = $xun_alias;
                    unset($xun_alias);
                }          
                // 导演
                if ($directors = $this->getDirector()) 
                {
		        	$xun_director = $this->getStr($directors);
                    $data['director'] = $xun_director;
                    unset($xun_director);
                }

                //演员
                if ($stars = $this->getStarring()) 
                {
		        	$xun_starring = $this->getStr($stars);
                    $data['starring'] = $xun_starring;
                    unset($xun_starring);
                }
                //上映地区
                if($countory = $this->getCountry())
                {
		        	$xun_area = $this->getStr($countory);
                    $data['area'] = $xun_area; 
                    unset($xun_area);                   
                }
                //上映时间
                if($released = $this->getReleased())
                {
                    if($year = $this->getYear($released))
                    {
                    	$data['released'] = $year; 
                    	unset($year);                             
                    }                    
                }
                
                 //评分
                if($this->getRating() > 0){
                    $data['rating'] = $this->getRating(); 
                }
                
                $data['type'] = 'video';                                     //给影视剧设置自定义前缀

                //是否有视频源
                if($videos = $this->getVideos())
                {
                   $data['hasvideo'] = true; //标示是否有视频源
                   foreach($videos as $video)
                   {
						$sources[] = $video->getReferer();
                    	$sources = array_unique($sources);
                   }
                   $xun_source = $this->getStr($sources);
                   $data['source'] = $xun_source;
/*                   $this->setHasVideo(count($videos));
                   $this->setSource($sources);
                   $this->save();*/
                }
                break;
            //电视剧
            case 'teleplay':
            	$data['model'] = "teleplay";
                 // 别名
                if ($alias = $this->getAlias()) 
                {
		        	$xun_alias = $this->getStr($alias);
                    $data['alias'] = $xun_alias;
                }          
                // 导演
                if ($directors = $this->getDirector()) 
                {
		        	$xun_director = $this->getStr($directors);
                    $data['director'] = $xun_director;
                }

                //演员
                if ($stars = $this->getStarring()) 
                {
		        	$xun_starring = $this->getStr($stars);
                    $data['starring'] = $xun_starring;
                }
                //上映地区
                if($countory = $this->getCountry())
                {
		        	$xun_area = $this->getStr($countory);
                    $data['area'] = $xun_area;                    
                }
                //上映时间
                if($released = $this->getReleased())
                {
                    if($year = $this->getYear($released))
                    {
                    	$data['released'] = $year;                          
                    }                    
                }
                
                 //评分
                if($this->getRating() > 0){
                    $data['rating'] = $this->getRating(); 
                }
                
                $data['type'] = 'video';                                     //给影视剧设置自定义前缀

                //是否有视频源
                if($playLists = $this->getPlayList())
                {
//				   $num = 0;
                   $data['hasvideo'] = true; //标示是否有视频源
                   foreach($playLists as $playList)
                   {
//                   		$num += $playList->countVideo();
						$sources[] = $playList->getReferer();
                    	$sources = array_unique($sources);
                   }
                   $xun_source = $this->getStr($sources);
                   $data['source'] = $xun_source;
/*                   $this->setHasVideo($num);
                   $this->setSource($sources);
                   $this->save(); */                  
                }
                break;
            //栏目
            case 'television':
            	$data['model'] = "television";
                //上映地区
                if($countory = $this->getCountry()){
		        	$xun_area = $this->getStr($countory);
                    $data['area'] = $xun_area; 
                    unset($xun_area);                   
                }
                if ($guests = $this->getGuest()) {
		        	$xun_guest = $this->getStr($guests);
                    $data['guest'] = $xun_guest;                     
                }
     
                if ($hosts = $this->getHost()) {
		        	$xun_host = $this->getStr($hosts);
                    $data['host'] = $xun_host; 
                }

               if ($alias = $this->getAlias()) {
		        	$xun_alias = $this->getStr($alias);
                    $data['alias'] = $xun_alias; 
                }

                if ($channel = $this->getChannel()) {
		        	$xun_channel = $this->getStr($channel);
                    $data['channel'] = $xun_channel; 
                }
                 //评分
                if($this->getRating() > 0){
                    $data['rating'] = $this->getRating(); 
                }

                //模型
                if($model = $this->getModel()){
                    $data['type'] = $this->getModel(); 
                }
                
                //是否有视频源
                if($playLists = $this->getPlayList())
                {
                   $data['hasvideo'] = true; //标示是否有视频源
                   foreach($playLists as $playList)
                   {
						$sources[] = $playList->getReferer();
                    	$sources = array_unique($sources);
                   }
                   $xun_source = $this->getStr($sources);
                   $data['source'] = $xun_source;              
                }
                break;
            //艺人, 篮球球员， 足球球员
            case 'actor':
            	$data['model'] = "actor";
                if($title = $this->getTitle()){
                     $data['type'] = $this->getModel(); 
                     $data['actor'] = $this->getTitle();                    
                }
                if($english_name = $this->getEnglish_name()){
                    $data['englishname'] = $this->getEnglish_name();//加入正文索引
                }                
                break;
                
            case 'basketball_player':
            case 'footerball_player':
                break;
            //篮球球队， 足球球队，NBA 球队
            case 'basketball_team':
            case 'footerball_team':
            case 'nba_team':
                break;
            default:
                //...
        } 
        $doc = new XSDocument;
        $doc->setFields($data);

        $index->update($doc);
         
               
    }
}
