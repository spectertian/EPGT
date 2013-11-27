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
    public function factory($wiki_name) {
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
     * @author huang
     */
    public function likeWikiName($wiki_title){
        $reg_str = "/^".$wiki_title.".*?/im";
        $regex_obj = new MongoRegex($reg_str);
        return $this->find(array(
                        'query' => array(
                            'title' => $regex_obj,
                        ),
                        "limit" => 20,
                        "sort" => array("updated_at",-1)
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
     * 根据wiki_id获取相关wiki
     * @return Wiki
     * @author huang
     */
    public function getWikisById($wiki_id){
        $wiki=$this->findOneById(new MongoId($wiki_id));
        if($wiki){
            $tags = $wiki->getTags();
            if (!empty($tags)) {
                if (count($tags) > 1) {
                    array_shift($tags);
                    shuffle($tags);                     //打乱标签
                    $tags = array_slice($tags, 0, 2);  //取两个相关的标签
                }
                $wikis = $this->getWikiByTags($wiki, $tags, 0);
            } else {
                $wikis = null;
            }
            return $wikis;
        }else{
            return null;
        }
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
                if(!$memcache->has(md5($wiki_slug))){
                    $memcache->set(md5($wiki_slug),$wiki);
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
    function getWikiById($id){
        $memcache = tvCache::getInstance();
        $wiki_slug = $this->getSlugById($id);
        if($wiki_slug){
            return $this->getWikiBySlug($wiki_slug);
        }else{
            return null;
        }
    }
    /**
     * 根据维基slug得到tag
     * @param <type> $wikiSlug
     * @return <type> array
     * @author lifucang
     */
    public function getTagBySlug($wikiSlug){
        $wiki=   $this->getWikiBySlug($wikiSlug);
        return $wiki->getTags();
    }
    /**
     * 根据维基slug得到维基/缓存
     * @param <type> $wikiSlug
     * @return <type> object
     * @author ly
     */
    public function getWikiBySlug($wikiSlug){
        $memcache = tvCache::getInstance();
        $memcache_key = md5($wikiSlug);
        $wiki = $memcache->get($memcache_key);
        
        if(!$wiki){
            if(!$wiki = $memcache->get($memcache_key)){
                $wiki =  $this->find(array("query"=>array("slug"=>$wikiSlug),"one"=>true));
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
    public function getTagsByType($wikitype){
        $category = $this->getCategory();
        foreach($category as $cat){
            if(in_array($wikitype,$cat)){
                return $cat['child'];
            }
        }
        return ;
        /*
        
        $memcache = tvCache::getInstance();
        $tagsRepos = $memcache->get($wikitype);
	//var_dump($tagsRepos);
        if(!$tagsRepos){
            $tagsRepos =  $this->find(array(
                   "query" => array("type"=>$wikitype),
                   "fields" => array("tags"),
                   "sort"  => array("created_at" => -1),
            ));
	   // var_dump($wikitype);
            $tmpNum = 0;
	    //var_dump($tagsRepos);
	    
            if(count($tagsRepos)==0){
                return null;
            }
	    
            foreach ($tagsRepos as $key => $values){
                $tag = $values->getTags();
                foreach($tag as $i => $value){
                    if($value && !in_array($value,$typeArray)){
                        $tmp[$tmpNum] = $value;
                        $tmpNum++;
                    }
                }
            }
            $arrayCount = array_count_values($tmp);
            $array = array_multisort($arrayCount,SORT_NUMERIC,SORT_DESC);
            //var_dump(array_splice($arrayCount, 0,$num));
            $memcache->set($wikitype,array_splice($arrayCount, 0,30));
            return array_splice($arrayCount, 0,$num);
       }else{
          return $tagsRepos;
       }
         * 
         */
    }

    /*
     * 返回xapian检索后count
     * @param text 搜索关键字
     */
    public function getSearchCount($search_text,$searchRange=null){
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
    public function getUsedArr() {
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
	 
     public function getCategory(){
        $cateogry = array();
        $category[1] = array(
                        'name' => '电影',
                        'child' => array(
                                11 => '喜剧',
                                12 => '爱情',
                                13 => '古装',
                                14 => '家庭',
                                15 => '剧情',
                                16 => '悬疑',
                                17 => '谍战',
                                18 => '动作',
                                19 => '战争',
                                20 => '恐怖',
                                21 => '动画'
                               ),
                            );
        $category[2] = array(
                        'name' => '电视剧',
                        'child' => array(
                                22 => '偶像',
                                23 => '喜剧',
                                24 => '爱情',
                                25 => '古装',
                                26 => '武侠',
                                27 => '家庭',
                                28 => '剧情',
                                29 => '悬疑',
                                30 => '谍战',
                                31 => '动作',
                                32 => '战争',
                                33 => '恐怖',
                                34 => '动画',
                                35 => '科幻',
                                36 => '警匪',
                                37 => '励志',
                                38 => '伦理',
                                39 => '犯罪',
                                40 => '商战',
                                41 => '革命'
                        ),
                     );
        return $category;
     }
     
      /**
      * 通过大量的wiki_id获取相应的值
      * @param array wiki_id_arr
      * @return obj | NULL
      * @author lizhi
      */
     public function getwikiInfoArr(array $wiki_id_arr) {
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
     public function getwikisByDay() {
        $starttime = new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime=new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y')));
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
    public function xun_search($search_text, &$total, $offset=0, $limit=20,$searchRange = null,$searchSort='') 
    {
		require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
		$xun_range = $this->getXunSearchRange($searchRange);
		$xs = new XS('epg_wiki'); 
		$search = $xs->search; 
		$search_text = mb_strcut($search_text, 0, 170, 'utf-8');
		$search_text = $this->area_replace($search_text);
        //echo $search_text;
		switch ($searchSort)
		{
			case 1:
				if(($searchRange['begin']==$searchRange['end'])&&empty($searchRange['begin'])&&empty($searchRange['end']))
				{				
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->search();	
				}
				else
				{
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   ->search();	
				}											
			  break; 
			case 2:
				if(($searchRange['begin']==$searchRange['end'])&&empty($searchRange['begin'])&&empty($searchRange['end']))
				{			   
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->setSort('rating')
								   ->search();
				}
				else
				{
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   ->setSort('rating')
								   ->search();
				}
			  break;
			case 3:
				if(($searchRange['begin']==$searchRange['end'])&&empty($searchRange['begin'])&&empty($searchRange['end']))
				{			   
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->setSort('hasvideo')
								   ->search();
				}
				else
				{
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   ->setSort('hasvideo')
								   ->search();
				}
			  break;              
			case 0:
				if(($searchRange['begin']==$searchRange['end'])&&empty($searchRange['begin'])&&empty($searchRange['end']))
				{
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->setSort('released')
								   ->search();
				}
				else
				{
					$objs = $search->setQuery($search_text)
								   ->setLimit($limit,$offset)
								   ->addRange('released', $xun_range['begin'], $xun_range['end'])
								   ->setSort('released')
								   ->search();
				}
		}
		
		$total = $search->getLastCount();
		$wikis = array();
		foreach($objs as $obj)
		{
			//if(in_array($obj['id'],$this->buyaode))//删除
			//	continue;//删除
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
         if(strpos($time,"gt")!== false)
         {
             $range['begin'] = str_replace("gt",'',$time);   //  大于等于
             $range['end'] = null;
         }elseif(strpos($time,"lt") !== false){
             $range['begin'] = null;   
             $range['end'] = str_replace("lt",'',$time);   //小于等于
         }elseif(strpos($time, "-") !== false){
             $tmp = explode("-",$time);
             $range['begin'] = $tmp[0];
             $range['end'] = $tmp[1];
         }else{
             $range['begin'] = $time;
             $range['end'] = $time;                       //固定年份       因xapian区间只有大于等于or小于等于or 年份-年份的区间限定
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
    
    public function area_replace($text)
    {
    	if(preg_match('/area:华语/',$text))
    	{
    		$text = preg_replace('/area:华语/','area:中国大陆 OR area:大陆',$text);
    		return $text;
    	}
    	if(preg_match('/area:其它/',$text))
    	{
    		$text = preg_replace('/area:其它/',' -area:中国大陆 -area:大陆 -area:美国 -area:日本 -area:欧洲 -area:韩国',$text);
    		return $text;
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
	    while ($i < $wiki_count) {
	        $wikis = $this->find(array("sort" => array("_id" => 1), "skip" => $i, "limit" => 50));
	        foreach ($wikis as $wiki) 
	        {
	        	printf("%s\n", $wiki->getTitle());
                if ($tags = $wiki->getTags())
	        	{
	        		$xun_tag = $wiki->getStr($tags);
	        	}
		        else
		        	$xun_tag = '';
				$data = array(
		            'id' => $wiki->getId(),
		            'title' => $wiki->getTitle()?$wiki->getTitle():'',
		            'content' => $wiki->getContent()?$wiki->getContent():'',
					'tag'=>$xun_tag,
                    'first_letter'=>$wiki->getFirstLetter()?$wiki->getFirstLetter():'',
		        );
		        unset($xun_tag);
		    	switch ($wiki->getModel()) 
		    	{
		            //电影
		            case 'film':
		            	$data['model'] = "film";
		                 // 别名
		                if ($alias = $wiki->getAlias()) 
		                {
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
		                if($videos = $wiki->getVideos())
		                {
		                   $data['hasvideo'] = true; //标示是否有视频源
		                   foreach($videos as $video)
		                   {
								$sources[] = $video->getReferer();
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
		
		                //是否有视频源
		                if($playLists = $wiki->getPlayList())
		                {
		                   $data['hasvideo'] = true; //标示是否有视频源
		                   foreach($playLists as $playList)
		                   {
								$sources[] = $playList->getReferer();
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
}
