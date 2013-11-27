<?php

/**
 * Repository of Wiki document.
 */
class WikiRepository extends \BaseWikiRepository
{
	//删除
	public $buyaode = array('4d00877a2f2a241bd700c72d','4d5b641cedcd88991b00016e','4d622164edcd889f1b00024b','4d0088422f2a241bd700ce4c','4e68545dedcd888c610093a9','4d0596da2f2a241eec0002a8','gongchandangren','4d059b8e2f2a241eec000a39','4d05990e2f2a241eec000510','4d0592132f2a241eec0000cc');
    /**
     * Find documents.
     *
     * Options:
     *
     *   * query:  the query (array)
     *   * fields: the fields (array)
     *   * sort:   the sort
     *   * limit:  the limit
     *   * skip:   the skip
     *   * one:    if returns one result (incompatible with limit)
     *
     * @param array $options An array of options.
     *
     * @return mixed The document/s found within the parameters.
     */
    public function find(array $options = array())
    {
        // query
        if (!isset($options['query'])) {
            $options['query'] = array();
        }

        // fields
        if (!isset($options['fields'])) {
            $options['fields'] = array();
        }

        // cursor
        $cursor = $this->getCollection()->find($options['query'], $options['fields']);

        // sort
        if (isset($options['sort'])) {
            $cursor->sort($options['sort']);
        }

        // one
        if (isset($options['one'])) {
            $cursor->limit(1);
        // limit
        } elseif (isset($options['limit'])) {
            $cursor->limit($options['limit']);
        }

        // skip
        if (isset($options['skip'])) {
            $cursor->skip($options['skip']);
        }

        // results
        $results = array();
        foreach ($cursor as $data) {
            if (isset($data['model'])) {
                //$document_class = $data['model'];
                $document = $this->factory($data['model']);
            } else {
                //$document_class = $this->documentClass;
                $document = new $this->documentClass;
            }
            $results[] = $document; // = new $document_class();
            if ($this->isFile) {
                $file = $data;
                $data = $file->file;
                $data['file'] = $file;
            }
            $document->setDocumentData($data);
        }

        if ($results) {
            // one
            if (isset($options['one'])) {
                return array_shift($results);
            }

            return $results;
        }

        return null;
    }

    /**
     * 工厂方法，生产所需之维基对象
     * @param string $wiki_name
     * @return Wiki
     * @author zhigang
     */
    public function factory($wiki_name) 
    {
        $model = null;
        switch ($wiki_name) {
            case "film":
                $model = new Wiki_FlimTV_Film();
                break;
            case "teleplay":
                $model = new Wiki_FilmTV_Teleplay();
                break;
            case "television":
                $model = new Wiki_Television();
                break;
            case "actor":
                $model = new Wiki_People_Actor();
                break;
            case "basketball_player":
                $model = new Wiki_People_BasketballPlayer();
                break;
            case "footerball_player":
                $model = new Wiki_People_FooterballPlayer();
                break;
            case 'basketball_team':
                $model = new Wiki_Team_BasketballTeam();
                break;
            case 'footerball_team':
                $model = new Wiki_Team_FooterballTeam();
                break;
            case 'nba_team':
                $model = new Wiki_Team_NBATeam();
        }

        return $model;
    }

    /**
     * 模糊查询WIKI_NAME
     * @param  string $wiki_title
     * @return Wiki
     * @author tianzhongsheng-ex@huan.tv
     * @since 2013-04-03 13:08:00
     */
    public function likeWikiName($wiki_title)
    {
        $reg_str = "/.*".$wiki_title.".*/i";
        $regex_obj = new MongoRegex($reg_str);
        return $this->find(array(
                        'query' => array(
                            'title' => $regex_obj,
                        ),
                        "limit" => 20,
                        "sort" => array("updated_at" => -1)
                    )
                );
    }

    /**
     * 全文搜索
     * @param <type> $text
     * @param <type> $searchRange 搜索区间
     * @param <type> $searchSort  搜索排序
     * (tag:爱情 tag:电影)
     */
    public function search($search_text, &$total, $offset=0, $limit=20,$searchRange = null,$searchSort=null) {
        $scws = scws_new();
        $scws->set_charset("utf8");
        $query_array = $this->getSearchWord($search_text,$scws, '');
        $xapian_db = SearchEngine::getDatabase('wiki');
        $enquire = new XapianEnquire($xapian_db);
        $qp = new XapianQueryParser();
        $qp->set_database($xapian_db);
        $qp->set_stemming_strategy(XapianQueryParser::STEM_SOME);
        $qp->set_default_op(XapianQuery::OP_AND);

        $qp->add_prefix("tag", "K");            //tag
        $qp->add_prefix("actor", "XACTOR");     //艺人
        //edit by guoqiang.zhang  -- 2011-7-4
        $qp->add_prefix("area","X");            //上映地区
        $qp->add_prefix("type", "XTYPE");       //model区分 (影视剧=>video,栏目=>television,艺人=>actor)
        $qp->add_prefix("source","XSOURCE");    //根据视频源来检索
        $qp->add_prefix("hasvideo","XHASVIDEO"); //是否有视频源
       

        $query = $qp->parse_query(implode(" ", $query_array), XapianQueryParser::FLAG_DEFAULT, "Z");

        // 标题权重
        /*
        $FACTOR = 2.5;
            foreach ($query_array as $query_word) {
                $title_query = new XapianQuery("S".$query_word);
                $query = new XapianQuery(XapianQuery::OP_OR,
                                         new XapianQuery(XapianQuery::OP_SCALE_WEIGHT, $title_query, $FACTOR),
                                         $query);
            }
         */
        if($searchRange){
           $query = $this->rangeSearch($this->getRange($searchRange),$query);
        }
        if($searchSort==1){
            $enquire->set_sort_by_relevance();                      //相关度来排序
        }elseif($searchSort==2){
            $enquire->set_sort_by_value((int)$searchSort,FALSE);      //参照wiki->addXapianValue()方法
        }
	//var_dump($query->get_description());
        $enquire->set_query($query);
        
        $matches = $enquire->get_mset($offset, $limit);       
        $total = $matches->get_matches_estimated();
        $wikis = array();
        $i = $matches->begin();
        while (!$i->equals($matches->end())) {
            $data = json_decode($i->get_document()->get_data(), true);
            $wiki = $this->findOneById(new MongoId($data['id'])); //new MongoId($data['id']);
            if (!empty($wiki)) {
                $wikis[] = $wiki;
                unset($wiki);
            }
           
            $i->next();
        }
        
//        $wikis = $this->find(array("query" => array("_id" => array('$in' => $wiki_ids))));

        return $wikis;
    }
    /*
     * XapianEnquire添加自定义前缀
     * @param XapianEnquire
     * @author guoqiang.zhang
     */
     public function addPrefix($qp){
        $qp->add_prefix("tag", "K");            //tag
        $qp->add_prefix("actor", "XACTOR");     //艺人
        //edit by guoqiang.zhang  -- 2011-7-4
        $qp->add_prefix("area","X");            //上映地区
        $qp->add_prefix("type", "XTYPE");       //model区分 (影视剧=>video,栏目=>television,艺人=>actor)
        $qp->add_prefix("source","XSOURCE");    //根据视频源来检索
        $qp->add_prefix("hasvideo","XHASVIDEO"); //是否有视频源
        return $qp;
     }

    /*
     * 返回区间
     * @param $field
     */
     public function getRange($field){
         $range = array();
         if(strpos($field,"gt")!== false){
             $range['begin'] = str_replace("gt",'',$field);   //  大于等于
             $range['end'] = 0;
         }elseif(strpos($field,"lt") !== false){
             $range['begin'] = 0;   
             $range['end'] = str_replace("lt",'',$field);   //小于等于
         }elseif(strpos($field, "-") !== false){
             $tmp = explode("-",$field);
             $range['begin'] = $tmp[0];
             $range['end'] = $tmp[1];                       //区间
         }else{
             $range['begin'] = $field;
             $range['end'] = $field;                       //固定年份       因xapian区间只有大于等于or小于等于or 年份-年份的区间限定
         }
         return $range;
     }
     /*
      * 添加搜索区间
      * @param array 区间值
      * @return XapianQuery
      */

     public function rangeSearch($range,$query){
        if($range['begin'] && $range['end']){
            $datequery = new XapianQuery( XapianQuery::OP_VALUE_RANGE , 1, Xapian::sortable_serialise($range['begin']),  Xapian::sortable_serialise($range['end']));
        }
        if($range['begin'] && !$range['end']){
            $datequery = new XapianQuery( XapianQuery::OP_VALUE_GE , 1, Xapian::sortable_serialise($range['begin']));
        }
        if(!$range['begin'] && $range['end']){
            $datequery = new XapianQuery( XapianQuery::OP_VALUE_LE , 1, Xapian::sortable_serialise($range['end']));
        }
        return $query =  new XapianQuery( XapianQuery::OP_AND, $query, $datequery );
     }
    /*
     * 获取搜索文本
     * @param <type> $searchText
     * @param <type> $scws
     * @return <type> $queryArray
     */
    public function getSearchWord($searchText,$scws,$defultWord=null){
        $query_array = array();
        $search_words = explode(" ", $searchText);
        foreach ($search_words as $search_word) {
            if (preg_match("~[a-z]+\:~", $search_word)) {
                $query_array[] = $search_word;
            } else {
                $scws->send_text($search_word);
                $words = $scws->get_words("~un");
                // 查询语句分词
                foreach ($words as $word) {
                    $query_array[] = $word['word'];
                }
            }
        }
        if($defultWord && !$query_array){
            $query_array[] = $defaultWord;
        }        
        return $query_array;
    }
    /**
     * 获取演员参演的电视剧或电影
     * @param <type> $wiki_title
     * @return <type>
     */
    /*function getActorWorks($wiki_title){
        return $this->find(array(
            "query" =>  array("starring"=>$wiki_title),
            "sort"  => array("created_at" => -1),
        ));
    }*/

     /**
     * 获取最新的5条WIKI记录
     * @return Wiki
     * @author huang
     */
    public function getNewsWiki(){
        return $this->find(array(
                        "limit" => 5,
                        "sort" => array("created_at" => -1)
                    )
                );
    }

    /**
     * 根据相关标签获取相关的维基 应用 mongo $all 查询
     * @param <array> $tags
     * @param <integer> $limit
     * @return <object>
     * @author luren
     */
    public function getWikiByTags(Wiki $wiki, $tags, $limit){
        return $this->find(array(
                            'query' => array(
                                    '_id' => array('$ne' => $wiki->getId()), //跳过当前这条维基
                                    'model' => $wiki->getModel(),
                                    'tags' => array('$all' => $tags)
                                ),
                            "sort" => array("created_at" => -1),            
                            'limit' => $limit
                        )
                    );
    }

    /**
     * 根据维基id得到维基slug/缓存
     * @param <type> $id
     * @return <type>
     * @author ly
     */
    function getSlugById($id){
        $memcache = tvCache::getInstance();
        if(!$memcache->has($id)){
            $wiki = $this->findOneById(new MongoId($id));
            if($wiki){
                $wiki_slug = $wiki->getSlug();
                $memcache->set($id,$wiki_slug);
                if(!$memcache->has($wiki_slug)){
                    $memcache->set($wiki_slug,$wiki);
                }
            }else{
                return null;
            }
        }else{
            $wiki_slug = $memcache->get($id);
        }
        
        return $wiki_slug;
    }

    /**
     * 根据wiki id找到wiki/缓存
     * @param <type> $id
     * @return <type>
     * @author ly
     */
    function getWikiById($id)
    {
        $memcache = tvCache::getInstance();
        $memcache_key = "wiki_".$id;
        $wiki = $memcache->get($memcache_key);        
        if(!$wiki){
            $wiki = $this->findOneById(new MongoId($id));
            if($wiki) {
                $memcache->set($memcache_key, $wiki); 
            }
        }      
        return $wiki;
    }

    /**
     * 根据维基slug得到维基/缓存
     * @param <type> $wikiSlug
     * @return <type> object
     * @author ly
     */
    public function getWikiBySlug($wikiSlug)
    {
        $memcache = tvCache::getInstance();
        $memcache_key = "wiki_".$wikiSlug;
        $wiki = $memcache->get($memcache_key);        
        if(!$wiki){
            $wiki =  $this->find(array("query"=>array("slug"=>$wikiSlug),"one"=>true));
            if($wiki) {
                $memcache->set($memcache_key, $wiki); 
            }
        }      
        return $wiki;
    }
    
    /*
     * 根据维基type得到tags
     * @param <type> $wikitype
     * @retrun <type>
     * @author guoqiang.zhang
     */
    public function getTagsByType($wikitype)
    {
        $category = $this->getCategory();
        foreach($category as $cat){
            if(in_array($wikitype,$cat)){
                return $cat['child'];
            }
        }
        return null;
    }
    
 	/**
     * 根据维基title得到维基/缓存
     * @param <type> $wikiSlug
     * @return <type> object
     * @author lifucang (2012-11-14)
     */ 
    public function getWikiByTitle($wikiSlug)
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'Wiki_Title_'.$wikiSlug;
        $wiki = $memcache->get($memcache_key);        
        if(!$wiki){
            $wiki =  $this->findOne(array("query"=>array("title"=>$wikiSlug)));
            if(!$wiki){
                $wiki =  $this->findOne(array("query"=>array("alias"=>$wikiSlug)));
            }
            if($wiki){
                $memcache->set($memcache_key, $wiki);
            }
        }      
        return $wiki;
    }   

 	/**
     * 根据维基title得到维基/缓存，只获取匹配到一个的
     * @param <type> $wikiSlug
     * @return <type> object
     * @author lifucang (edit 2013-07-08)
     */ 
    public function getOneWikiByTitle($wikiTitle)
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'wiki_count_'.$wikiTitle;
        $wikiNum = $memcache->get($memcache_key);
        if(!$wikiNum){
            $wikiNum =  $this->count(array("title"=>$wikiTitle));
            $memcache->set($memcache_key, $wikiNum);
        }
        $wiki=null;
        if($wikiNum == 1){
            $memcacheKey = 'getWikiByTitle'.$wikiTitle;
            $wiki = $memcache->get($memcacheKey);
            if(!$wiki){
                $wiki =  $this->findOne(array("query"=>array("title"=>$wikiTitle)));
                /*
                if(!$wiki){
                    $wiki =  $this->findOne(array("query"=>array("alias"=>$wikiTitle)));
                } 
                */
                if($wiki){
                    $memcache->set($memcacheKey, $wiki);
                }
            }
        }        
        return $wiki;
    }   
    
    /*
     * 返回xapian检索后count
     * @param text 搜索关键字
     */
    public function getSearchCount($search_text,$searchRange=null)
    {
        $scws = scws_new();
        $scws->set_charset("utf8");
        $query_array = $this->getSearchWord($search_text,$scws, '');
        $xapian_db = SearchEngine::getDatabase('wiki');
        $enquire = new XapianEnquire($xapian_db);
        $qp = new XapianQueryParser();
        $qp->set_database($xapian_db);
        $qp->set_stemming_strategy(XapianQueryParser::STEM_SOME);
        $qp->set_default_op(XapianQuery::OP_AND);
        $qp->add_prefix("tag", "K");            //tag
        $qp->add_prefix("actor", "XACTOR");     //艺人
        //edit by guoqiang.zhang  -- 2011-7-4
        $qp->add_prefix("area","X");            //上映地区
        $qp->add_prefix("type", "XTYPE");       //model区分 (影视剧=>video,栏目=>television,艺人=>actor)
        $qp->add_prefix("source","XSOURCE");    //根据视频源来检索
        $qp->add_prefix("hasvideo","XHASVIDEO"); //是否有视频源
        $query = $qp->parse_query(implode(" ", $query_array), XapianQueryParser::FLAG_DEFAULT, "Z");
        // 标题权重
        /*
        $FACTOR = 2.5;
        if($query_array['condition']){
            foreach($query_array['condition'] as $condition){
                $condition_query = $qp->parse_query($condition);
                $query = new XapianQuery(XapianQuery::OP_AND,$query,$condition_query);
            }
        }
        */
        if($searchRange){
           $query = $this->rangeSearch($this->getRange($searchRange),$query);
        }
        $enquire->set_query($query);
        $matches = $enquire->get_mset(0,1000);
        $total = $matches->get_matches_estimated();
        return $total;
    }
    
    /**
     * 常用的数组信息
     * @author lizhi
     * @return array
     */
    public function getUsedArr() 
    {
        $country = array('大陆','香港', '美国', '台湾', '日本','韩国','其它');
        $time = array('2011'=>'2011', '2010'=>"2010", '2009'=>'2009', '2000-2008'=>"2000-2008", '1990-1999'=>'1990-1999', '1980-1989'=>'1980-1989', '1980年以前'=>'lt1980');
        $arr['country'] = $country;
        $arr['time'] = $time;
        return $arr;
    }
   
    /*
     * 返回category 数组
     * @return array
     * @author guoqiang.zhang
     */	 
    public function getCategory()
    {
        return sfConfig::get("app_vod_category");
    }
     
      /**
      * 通过大量的wiki_id获取相应的值
      * @param array wiki_id_arr
      * @return obj | NULL
      * @author lizhi
      */
    public function getwikiInfoArr(array $wiki_id_arr) 
    {
        return $this->find(array(
            "query"=>array("_id"=>array('$in'=>$wiki_id_arr)))
        );
    }
 
    /**
     * 获取当天更新的wiki
     * @param array wiki_id_arr
     * @return obj | NULL
     * @author lifucang
     */
    public function getwikisByDay() 
    {
        $starttime = new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime = new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y')));
        return $this->find(array(
                    "query"=>array(
                        'created_at' => array('$gte' => $starttime,'$lte' => $endtime),
                    )
                ));
    }

    /**
     * xunsearch 全文搜索
     * @param <type> $text
     * @param <type> $searchRange 搜索区间
     * @param <type> $searchSort  搜索排序
     * (tag:爱情 tag:电影)
     * @author wangnan
     */
    public function xun_search($search_text, &$total, $offset=0, $limit=20,$searchRange = null,$searchSort='',$weight_key='') 
    {
		require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
		$xun_range = $this->getXunSearchRange($searchRange);
		$xs = new XS('epg_wiki'); 
		$search = $xs->search;//->setFuzzy(); 
		$search_text = mb_strcut($search_text, 0, 170, 'utf-8');
		$search_text = $this->area_replace($search_text);
		switch ($searchSort) {
		    case 4:    		        
    		    $objs = $search->setLimit($limit,$offset)
                               ->search($search_text);
		        break;
			case 1:
				if(($searchRange['begin']==$searchRange['end']) && empty($searchRange['begin'])&&empty($searchRange['end'])) {	
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)
								   ->setLimit($limit,$offset)
								   ->search();
				} else {
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)					
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   //->addRange('hasvideo', $xun_range['begin'], $xun_range['end'])
								   ->search();	
				}											
                break; 
			case 2:
				if(($searchRange['begin']==$searchRange['end'])&&empty($searchRange['begin'])&&empty($searchRange['end'])) {			   
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)
								   ->setLimit($limit,$offset)
								   ->setSort('rating')
								   ->search();
				} else {
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)					
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   ->setSort('rating')
								   ->search();
				}
                break;
			case 3:
				if(($searchRange['begin']==$searchRange['end'])&&empty($searchRange['begin'])&&empty($searchRange['end'])) {			   
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)					
								   ->setLimit($limit,$offset)
								   ->setSort('hasvideo')
								   ->search();
				} else {
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)					
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   ->setSort('hasvideo')
								   ->search();
				}
                break;              
			case 0:
				if(($searchRange['begin']==$searchRange['end'])&&empty($searchRange['begin'])&&empty($searchRange['end'])) {
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)					
								   ->setLimit($limit,$offset)
								   ->setSort('released')
								   ->search();
				} else {
					$objs = $search->setQuery($search_text)
								   ->addWeight('title',$weight_key)					
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   ->setSort('released')
								   ->search();
				}
                break;
		}
		
		$total = $search->getLastCount();
		$wikis = array();
		foreach($objs as $obj) {
			$wiki = $this->findOneById(new MongoId($obj['id'])); //new MongoId($data['id']);
            if (!empty($wiki)) {
                $wikis[] = $wiki;
                unset($wiki);
            }
		}
        return $wikis;
    } 
    
    /*
     * xunsearch 返回区间
     * @param $time
     * @autohr wangnan
     */
    public function getXunSearchRange($time)
    {
        $range = array();
        if(strpos($time,"gt")!== false) {
            $range['begin'] = str_replace("gt",'',$time);
            $range['end'] = null;
        } elseif(strpos($time,"lt") !== false) {
            $range['begin'] = null;   
            $range['end'] = str_replace("lt",'',$time);
        } elseif(strpos($time, "-") !== false) {
            $tmp = explode("-",$time);
            $range['begin'] = $tmp[0];
            $range['end'] = $tmp[1];
        } else {
            $range['begin'] = $time;
            $range['end'] = $time;
        }
        return $range;
    } 
    
    /*
     * 返回xunsearch检索后count
     * @param text 搜索关键字
     * @author wangnan
     */
    public function getXunSearchCount($search_text,$searchSort=null) 
    {
		require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
		$xun_range = $this->getXunSearchRange($searchRange);
		$xs = new XS('epg_wiki'); 
		$search = $xs->search;
		$search_text = mb_strcut($search_text, 0, 170, 'utf-8');
		$search->setQuery($search_text)
					   ->addRange('released', $xun_range['begin'], $xun_range['end'])
					   ->search();	
		$total = $search->getLastCount();
		return $total;
    }
    
    /*
     * 关键词区域处理
     *
     * @param text 搜索关键字
     * @author wangnan
     */
    public function area_replace($text)
    {
    	if(preg_match('/area:华语/',$text))	{
    		return preg_replace('/area:华语/','(area:中国大陆 OR area:大陆)',$text);
    	}
    	if(preg_match('/area:其它/',$text)) {
    		//return preg_replace('/area:其它/',' -area:中国大陆 -area:大陆 -area:美国 -area:日本 -area:欧洲 -area:韩国',$text);//epg 如www.5itest.tv使用
    		return preg_replace('/area:其它/',' -area:中国大陆 -area:大陆 -area:美国 -area:日本 -area:台湾 -area:韩国 -area:香港',$text);//迎合epg需求
    	}
    	return $text;
    } 
    
    /**
     * 平滑重建 XunSearch 全文搜索引擎索引
     * @param string $doc_id
     * @author wangnan
     */
    public function rebuildXunSearchDocument() 
    {        
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';

        $xs = new XS('epg_wiki');
        $index = $xs->index; 
        
	    $wiki_count = $this->count();	    
        $index->beginRebuild(); 
	    $i = 0;
	    while ($i < $wiki_count){
	        $wikis = $this->find(array("sort" => array("_id" => 1), "skip" => $i, "limit" => 50));
	        foreach ($wikis as $wiki) {
	        	printf("%s\n", $wiki->getTitle());
                if ($tags = $wiki->getTags()) {
	        		$xun_tag = $wiki->getStr($tags);
	        	} else {
		        	$xun_tag = '';
                }
				$data = array(
		            'id' => (string)$wiki->getId(),
		            'title' => $wiki->getTitle()?$wiki->getTitle():'',
		            'content' => $wiki->getContent()?$wiki->getContent():'',
					'tag'=>$xun_tag,
					
		        );
		        unset($xun_tag);
		    	switch ($wiki->getModel()) {
		            //电影
		            case 'film':
		            	$data['model'] = "film";
		                 // 别名
		                if ($alias = $wiki->getAlias()) {
				        	$xun_alias = $wiki->getStr($alias);
		                    $data['alias'] = $xun_alias;
		                    unset($xun_tag);
		                }          
		                // 导演
		                if ($directors = $wiki->getDirector()) 
		                {
				        	$xun_director = $wiki->getStr($directors);
		                    $data['director'] = $xun_director;
		                    unset($xun_director);
		                }
		
		                //演员
		                if ($stars = $wiki->getStarring()) 
		                {
				        	$xun_starring = $wiki->getStr($stars);
		                    $data['starring'] = $xun_starring;
		                    unset($xun_starring);
		                }
		                //上映地区
		                if($countory = $wiki->getCountry())
		                {
				        	$xun_area = $wiki->getStr($countory);
		                    $data['area'] = $xun_area; 
		                    unset($xun_area);                   
		                }
		                //上映时间
		                if($released = $wiki->getReleased())
		                {
		                    if($year = $wiki->getYear($released))
		                    {
		                    	$data['released'] = $year; 
		                    	unset($year);                             
		                    }                    
		                }
		                
		                 //评分
		                if($wiki->getRating() > 0){
		                    $data['rating'] = $wiki->getRating(); 
		                }
		                
		                $data['type'] = 'video';                                     //给影视剧设置自定义前缀
		
		    	        //是否有视频源
		                if($videos = $wiki->getVideos())//直接在video表中查找wikiid的记录 准！
		                {
		                   foreach($videos as $video)
		                   {
		                   		$refer = $video->getReferer();
		                   		//if($refer == 'qiyi')
		                   		    $data['hasvideo'] = true; //标示是否有视频源
								$sources[] = $refer;
		                    	$sources = array_unique($sources);
		                   }
		                   $xun_source = $wiki->getStr($sources);
		                   $data['source'] = $xun_source;
		                }
		                break;
		            //电视剧
		            case 'teleplay':
		            	$data['model'] = "teleplay";
		                 // 别名
		                if ($alias = $wiki->getAlias()) 
		                {
				        	$xun_alias = $wiki->getStr($alias);
		                    $data['alias'] = $xun_alias;
		                }          
		                // 导演
		                if ($directors = $wiki->getDirector()) 
		                {
				        	$xun_director = $wiki->getStr($directors);
		                    $data['director'] = $xun_director;
		                }
		
		                //演员
		                if ($stars = $wiki->getStarring()) 
		                {
				        	$xun_starring = $wiki->getStr($stars);
		                    $data['starring'] = $xun_starring;
		                }
		                //上映地区
		                if($countory = $wiki->getCountry())
		                {
				        	$xun_area = $wiki->getStr($countory);
		                    $data['area'] = $xun_area;                    
		                }
		                //上映时间
		                if($released = $wiki->getReleased())
		                {
		                    if($year = $wiki->getYear($released))
		                    {
		                    	$data['released'] = $year;                          
		                    }                    
		                }
		                
		                 //评分
		                if($wiki->getRating() > 0){
		                    $data['rating'] = $wiki->getRating(); 
		                }
		                
		                $data['type'] = 'video';                                     //给影视剧设置自定义前缀
		
		    	        if($playLists = $wiki->getPlayList())//只在videoplaylist里找wikiid相等的记录 不准
		                {
		                   foreach($playLists as $playList)
		                   {
		                   		$refer = $playList->getReferer();
		                   		//if($refer == 'qiyi')
		                   		//{
		                   			if($playList->countVideo()>0)$data['hasvideo'] = true;//标示是否有视频源
		                   		//}
								$sources[] = $refer;
		                    	$sources = array_unique($sources);
		                   }
		                   $xun_source = $wiki->getStr($sources);
		                   $data['source'] = $xun_source;
		                }	                
		                break;
		            //栏目
		            case 'television':
		            	$data['model'] = "television";
		                if ($guests = $wiki->getGuest()) {
				        	$xun_guest = $wiki->getStr($guests);
		                    $data['guest'] = $xun_guest;                     
		                }
		     
		                if ($hosts = $wiki->getHost()) {
				        	$xun_host = $wiki->getStr($hosts);
		                    $data['host'] = $xun_host; 
		                }
		    			                //上映地区
		                if($countory = $wiki->getCountry())
		                {
				        	$xun_area = $wiki->getStr($countory);
		                    $data['area'] = $xun_area;                    
		                }		
		               if ($alias = $wiki->getAlias()) {
				        	$xun_alias = $wiki->getStr($alias);
		                    $data['alias'] = $xun_alias; 
		                }
		
		                if ($channel = $wiki->getChannel()) {
				        	$xun_channel = $wiki->getStr($channel);
		                    $data['channel'] = $xun_channel; 
		                }
		                 //评分
		                if($wiki->getRating() > 0){
		                    $data['rating'] = $wiki->getRating(); 
		                }
		
		                //模型
		                if($model = $wiki->getModel()){
		                    $data['type'] = $wiki->getModel(); 
		                }
		    	        if($playLists = $wiki->getPlayList())//只在videoplaylist里找wikiid相等的记录 不准
		                {
		                   foreach($playLists as $playList)
		                   {
		                   		$refer = $playList->getReferer();
		                   		//if($refer == 'qiyi')
		                   		//{
		                   			if($playList->countVideo()>0)$data['hasvideo'] = true;//标示是否有视频源
		                   		//}
								$sources[] = $refer;
		                    	$sources = array_unique($sources);
		                   }
		                   $xun_source = $wiki->getStr($sources);
		                   $data['source'] = $xun_source;
		                }	                
		                break;
		            //艺人, 篮球球员， 足球球员
		            case 'actor':
		            	$data['model'] = "actor";
		                if($title = $wiki->getTitle()){
		                     $data['type'] = $wiki->getModel(); 
		                     $data['actor'] = $wiki->getTitle();                    
		                }
		                if($english_name = $wiki->getEnglish_name()){
		                    $data['englishname'] = $wiki->getEnglish_name();//加入正文索引
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
		        $index->add($doc);   
	        }
	        unset($wikis);
	        $i = $i + 50;
	    }
        $index->endRebuild();     
    } 
    
    public function findOneByTitle($title)
    {
        return $this->findOne(array(
                            'query' => array(
                                    'title' => $title,
                                ),
                        )
                    );
    }

    /**
     * 根据相关标签获取相关的维基 应用 mongo $all 查询
     * @param <array> $tags
     * @param <integer> $limit
     * @return <object>
     * @author qhm
     */
    public function getWikiTagsIn($wiki_id, $tags='',$page = 1, $pagesize = 100){
      $skip = ($page - 1) * $pagesize;
      if(!empty($tags)) {
    		return $this->find(array(
    				'query' => array(
    						'_id' => array('$in' =>$wiki_id),  
    						'tags' => array('$in' => $tags)
    				),
    				"sort" => array("wiki_id" => 1),
    				'skip'=>$skip,
    				'limit'=>$pagesize,
    		      )	
    	     );
    	}else{
    		
    		return $this->find(array(
    				'query' => array(
    						'_id' => array('$in' =>$wiki_id),  
    				),
    				"sort" => array("wiki_id" => 1),
    				'skip'=>$skip,
    				'limit'=>$pagesize,
    		      )
    		 );
    	}

    }
    
    
    /**
     * 根据相关标签获取相关的维基 应用 mongo $all 查询
     * @param <array> $tags
     * @param <integer> $limit
     * @return <object>
     * @author qhm
     */
    public function getWikiTags($wiki_id, $tags=''){
    	if(!empty($tags)) {
    		return $this->find(array(
    				'query' => array(
    						'_id' => $wiki_id,  
    						'tags' => array('$in' => $tags)
    				),
    				"sort" => array("created_at" => -1),
    		)
    		);
    	}else{
    
    		return $this->find(array(
    				'query' => array(
    						'_id' => $wiki_id,  
    				),
    				"sort" => array("created_at" => -1),
    		)
    		);
    	}
    
    }
    
    /*
     * 通过节目名称匹配wiki
     * 
     * @param <string> $program_title
     * @param <string> $channel_code
     * @return <Wiki>
     * @author superwen     
     */
    public function getWikiByProgramTitle($program_title, $channel_code="")
    {        
        $model = "";        
        $modelPatterns = array(
            '/电影(：|:)/' => 'film',
            '/影院(：|:)/' => 'film',
            '/电视剧(：|:)/' => 'teleplay',
            '/剧苑(：|:)/' => 'teleplay',
            '/剧院(：|:)/' => 'teleplay',                  
            '/连续剧/' => 'teleplay',
            '/.*剧场/' => 'teleplay',
            '/第.*集/' => 'teleplay',
            '/\d+集/' => 'teleplay',
            '/\d+\(-|―)\d+/' => 'teleplay',
            '/大结局/' => 'teleplay',
            '/动画片/' => 'teleplay',
            '/儿童剧/' => 'teleplay'
        );
        foreach($modelPatterns as $pattern => $value) {
            if(preg_match($pattern,$program_title)) {
                $model = $value;
                break;
            }
        }
        $channels = array(
            'chcgaoqingdianying' => 'film',
            'chcjiatingyingyuan' => 'film',
            'chcdongzuody' => 'film',
            '4821a7f7b9f3793b6996b93a974ebc61' => 'film',
            'sitv-movie' => 'film',
            'fd5e69184516f4e96a7f4d41e52b3bb0' => 'film',
            '50c24d98ec6280f31256abf1ed23ca4a' => 'film',
            'doxjuchang' => 'teleplay',
            'fengyunjuchang' => 'teleplay'
        );
        if(!$model && $channel_code) {
            if(array_key_exists($channel_code, $channels)) {
                $model = $channels[$channel_code];
            }
        }
        
        $trimPatterns = array('/电影(：|:)/','/.*影院(：|:)/','/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/','/《.*》/',
                          '/电视剧/','/精华版/','/精装版/','/特别版/','/首播/','/复播/','/复/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/.*剧场/','/;提示/','/好剧看不停/','/午夜狂放/',
                          '/第.*集/','/\d+集/','/\d/','/―/','/\d+年\d+月\d+日/','/\d+(-|―)\d+-\d+/','/\d+_.*/','/-.*/');
                          
        $program_title = preg_replace($trimPatterns, "", $program_title);
        
        if($model) {
            $query["model"] = $model;
        }
        $query["title"] = $program_title;
        
        $wkCol = $this->find(array("query" => $query));
        $wkColnum = count($wkCol);        
        if($wkColnum == 1) {
            return $wkCol[0];
        }
        if($wkColnum == 0) {
            unset($query["title"]);
            $query["alias"] = $program_title; 
            $wkCol = $this->find(array("query" => $query));
            $wkColnum = count($wkCol);  
            if($wkColnum == 1) {
                return $wkCol[0];
            }
        }
        return null;
    }
    
    /*
     * 通过豆瓣电影获取匹配的wiki
     * 
     * @param <DoubanMovie> $dmDoc
     * @return <Wiki>
     * @author superwen     
     */
    public function getWikiByDoubanMovie($dmDoc)
    {        
        $model = ($dmDoc->getSubtype() == "movie") ? "film" : "teleplay";
        $query = array(
            "model" => $model,
            '$or' => array(
                array("title" => $dmDoc->getTitle()),
                array("alias"  => $dmDoc->getTitle())
            )
        ); 
        $wkCol = $this->find(array("query" => $query));
        $wkColnum = count($wkCol);        
        if($wkColnum == 1) {
            return $wkCol[0];
        }elseif($wkColnum > 1) {
            foreach($wkCol as $Col) {
                $dircs = $Col->getDirector();
                if($dircs && $dircs[0] != "") {
                    $dd = $dmDoc->getDirectors();
                    if($dd && $dd[0]['name'] == $dircs[0]) {
                        file_put_contents("./log/task_doubanmoviemap_2.txt",
                                          $dmDoc->getDoubanId()."\t".$dmDoc->getTitle()."\t".$Col->getId()."\t".$Col->getTitle()."\n", FILE_APPEND);
                        return $Col;
                    }
                }
            }
            //return null;
        } else { 
            $titles = $or = array();
            if($dmDoc->getOriginalTitle()) {
                $titles[] = $dmDoc->getOriginalTitle();
            }
            if($dmDoc->getAka()) {
                foreach($dmDoc->getAka() as $aka) {
                    $titles[] = trim($aka);
                }
            }
            if(count($titles) <= 0) {
                return null;
            }
            foreach($titles as $title) {
                $or[] = array("title" => $title); 
                //$or[] = array("alias" => $title);
            }
            $query = array(
                "model" => $model,
                '$or' => $or
            );
            $wkCol = $this->find(array("query" => $query));
            if (count($wkCol) == 1) {
                $wkDoc = $wkCol[0];
                file_put_contents("./log/task_doubanmoviemap_s.txt",
                                  $dmDoc->getDoubanId()."\t".$dmDoc->getTitle()."\t".$wkDoc->getId()."\t".$wkDoc->getTitle()."\n",FILE_APPEND);
                return $wkDoc;
            }
        }
        return null;
    }
}
