<?php

/**
 * Repository of Program document.
 */
class ProgramRepository extends \BaseProgramRepository
{	
    /**
     * 根据字段名称设置字段值
     * @param <Int> $id
     * @param <String> $name
     * @param <String> $value
     * @return Array
     */
    public  function ajaxUpdate($id, $name, $value)
    {
        $return = array('code'=>0, 'msg'=>'位置错误');
        $uper_name = ucfirst($name);
        $method = "set".$uper_name;
        
        if(!isset($id)) {
            return array('code'=>0, 'msg'=>'位置错误');
        }
        $tem = $this->findOneById(new mongoId($id));
        if(!$tem) {
            return array('code'=>0, 'msg'=>'记录不存在');
        }
        if($name == 'time' ) {
            $old_ymd = $tem->getStartTime()->format("Y-m-d");
            $new_start_time = new datetime($old_ymd.' '.$value);
            $tem->setStartTime($new_start_time);
            $tem->$method($value);
            $tem->save();
        } else {
            $tem->$method($value);
            $tem->save();
        }                
        return array('code'=>1, 'msg'=>'更新成功');
    } 
    
    /**
     * getDayProgramsByChannelCode别名
     */
    public function getDayPrograms($channel_code, $date, $ifwiki = false)
    {
        return $this->getDayProgramsByChannelCode($channel_code, $date, $ifwiki);
    }
	
    /**
     * 获取某频道某天的节目列表
     * @param <string> $channel_code
     * @param <string> $date e: 2010-11-11
     * @return <array>
     * @author pjl
     */
    public function getDayProgramsByChannelCode($channel_code, $date, $ifwiki = false) 
    {        
        $query = array(
            'channel_code' => $channel_code,
            'date' => $date
        );
        if($ifwiki) {                
            $query['wiki_id'] = array('$exists' => true);
        }
        return $this->find(array(
                'query' => $query,
                'sort' => array('start_time' => 1)
            )
        );        
    }
    
    /**
     * 统计某频道某天的节目数
     * @param <string> $channel_code,$date
     * @param <string> $date 2013-01-18
     * @author lifucang
     */
    public function countDayProgramsByChannelCode($channel_code, $date, $ifwiki = false) 
    {
        $query =  array(
            'channel_code' => $channel_code,
            'date' => $date
        );                        
        if($ifwiki) {
            $query['wiki_id'] = array('$exists' => true);
            
        } 
        return $this->count($query);
    }   
    
    /**
     * 获得一天的节目列表并且是有wiki
     * @param string $channel_code
     * @param string date
     * @return void
     * @author lizhi
     */
    public function getDayProgramsWiki($channel_code, $date) {
        return $this->getDayProgramsByChannelCode($channel_code, $date, true);
    }
    
    /**
     * 获取某频道某一时间段的节目列表
     * @param <string> $channel_code
     * @param <string> $start_time e:2013-01-18 01:12:23
     * @param <string> $end_time e:2013-01-18 01:12:23
     * @author lifucang
     */
    public function getTimePrograms($channel_code, $start_time, $end_time, $ifwiki = false) 
    {
        $start_time = new MongoDate(strtotime($start_time));
        $end_time   = new MongoDate(strtotime($end_time)); 
        $query = array(
            'channel_code' => $channel_code,
            'start_time' => array('$gte' => $start_time,'$lt'=>$end_time)
        );
        if($ifwiki) { 
            $query['wiki_id'] = array('$exists' => true);
        }
        return $this->find(array('query' => $query,'sort' => array('time' => 1)));
    }
    
    /**
     * 统计某频道某一时间段是否有节目
     * @param <string> $channel_code
     * @param <string> $start_time e:2013-01-18 01:12:23
     * @param <string> $end_time e:2013-01-18 01:12:23
     * @author lifucang
     */
    public function countTimePrograms($channel_code, $start_time, $end_time, $ifwiki = false) 
    {
        $start_time = new MongoDate(strtotime($start_time));
        $end_time   = new MongoDate(strtotime($end_time)); 
        $query = array(
            'channel_code' => $channel_code,
            'start_time' => array('$gte' => $start_time,'$lt'=>$end_time)
        );
        if($ifwiki) { 
            $query['wiki_id'] = array('$exists' => true);
        }       
        return $this->count($query);
    }    

    /**
     * 获取指定频道列表，当前正在直播的某TAG节目，专为电视端前台显示，要求拥有 Wiki
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified zhigang 2011-1-4
     */
    public function getLiveProgramByTag($tag, $channels = "", $limit = 50) 
    {
        $channel_codes = array();
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getCode();
        }
        $now = new MongoDate();
        $query = array(
	        'channel_code' => array('$in' => $channel_codes),
	        'start_time' => array('$lt' => $now),
	        'end_time' => array('$gt' => $now),
	        'wiki_id' => array('$exists' => true),
	    );
        if(!empty($tag)) {
            $query['tags'] = $tag;
        }
	    return $this->find(
	        array(
	            'query' => $query,
	            'limit'=> $limit,
	            "sort" => array("start_time" => 1),
	        )
	    ); 
    }

    /**
     * 获取指定频道列表，当前正在直播的一个TAG节目，专为电视端前台显示，要求拥有 Wiki
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified lifucang 2012-5-18
     * @superwen: 只有phone和android_gap项目在用，是否可以去掉.
     * @而且当前正在播放的就唯一，不需要用tag匹配
     */
    public function getOneLiveProgramByTag($tag, $channels="") 
    {
        $channel_codes = array();
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getCode();
        }
        $now = new MongoDate();
        $query = array(
	        'channel_code' => array('$in' => $channel_codes),
	        'start_time' => array('$lt' => $now),
	        'end_time' => array('$gt' => $now),
	        'wiki_id' => array('$exists' => true),
	    );
        if(!empty($tag)) {
            $query['tags'] = $tag;
        }        
	    return $this->findOne(array('query' => $query));
    }
    
    /**
     * 获取指定频道列表某天直播列表
     * 
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified lifucang 2012-5-18
     */
    public function getDayLiveProgramByTag($tag, $channels = "",$limit = 100) 
    {
        $channel_codes = array();
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getCode();
        } 
        $starttime = new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime = new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y')));
        
        $query = array(
	        'channel_code' => array('$in' => $channel_codes),
	        'start_time' => array('$gte' => $starttime,'$lte' => $endtime),
	        'wiki_id' => array('$exists' => true)
	    );
        if(!empty($tag)) {
            $query['tags'] = $tag;
        }
	    return $this->find(
	        array(
	            'query' => $query,
	            'limit'=> $limit,
	            "sort" => array("start_time" => 1),
	        )
	    );
    }
    
    /**
     * 根据WIKI ID获取未播出的电视节目
     * @param <int> $wiki_id
     * @return <array>
     * @author pjl
     */
    public function getUnPlayedProgramByWikiId($wiki_id, $limit = 20) 
    {
        $now = new MongoDate();
        $rets = $this->find(
                    array(
                        'query' => array(
                            'wiki_id' => (string)$wiki_id,
                            '$or' => array(
                                array('start_time' => array('$gte' => $now)),
                                array('end_time' => array('$gte' => $now)),
                            )
                        ),
                        "sort" => array("start_time" => 1),
                        "limit" => $limit
                    )
                );
        return $rets;
    }
    
    /**
     * 根据WIKI ID获取未播出当天的电视节目
     * @param <int> $wiki_id
     * @return <array>
     * @author lfc
     */
    public function getdayUnPlayedProgramByWikiId($wiki_id) 
    {
        $date_start = new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $date_end = new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y')));
        $rets = $this->find(
            array(
                'query' => array(
                    'wiki_id' => (string)$wiki_id,                          
                    'start_time' => array('$gte' => $date_start, '$lte' => $date_end),
                ),
                "sort" => array("start_time" => 1)
            )
        );
        return $rets;
    }
    
    /**
     * 根据wiki id获取指定日期范围内的节目数据
     * @param <int> $wiki_id
     * @param <date> $date_from
     * @param <date> $date_end
     * @return <array>
     * @author pjl
     */
    public function getCustomDateProgramByWikiId($wiki_id, $date_from, $date_end, $offset = 0, $limit = 100) 
    {
        $rets = $this->find(
            array(
               'query' => array(
                    'wiki_id' => (string) $wiki_id,
                    'date' => array('$lte' => $date_end, '$gte' => $date_from),
                ),                        
                "sort" => array("start_time" => 1),
                "limit" => $limit
            )
        );
        return $rets;
    }

    /**
     * 根据节目播出日期和频道获取指定日期范围内的节目数据 要求拥有维基
     * @param <type> $date
     * @return <array>
     * @author luren
     */
    public function getProgramByDateAndChannelCode($date, $channel_code) 
    {
        return $thsi->getDayProgramsByChannelCode($channel_code, $date,true);
    }

    /**
     * 根据 wiki_id, $fromdate, $enddate, $province 获取用户所在省级域能看到的相关节目
     * @param <type> $wiki_id
     * @param <date> $fromdate
     * @param <date> $enddate
     * @param <type> $province
     * @param <type> $limit
     * @return <type>
     * @author luren
     */
    public function getUserRelateProgramByDate($wiki_id, $province, $fromdate, $enddate, $limit = 100) 
    {
        $channels = Doctrine::getTable('Channel')->getUserChannels("",$province);
        foreach ($channels as $channel) {
            $codes[] = $channel->getCode();
        }
        return $this->find(
            array(
                'query' => array(
                    'wiki_id' => $wiki_id,
                    'date' => array('$gte' => $fromdate, '$lte' => $enddate,),
                    'channel_code' => array('$in' => $codes)
                ),
                "sort" => array("start_time" => 1),
                'limit'=> $limit
            )
        );
    }

    /**
     * 节目表全文搜索《弃用》
     * @param <string> $search_text
     * @param <integer> $total
     * @param <integer> $offset
     * @param <integer> $limit
     * @return <array>
     */
    public function search($search_text, &$total, $offset = 0, $limit = 20) 
    {
        $scws = scws_new();
        $scws->set_charset('utf8');
        $scws->send_text($search_text);
        $words = $scws->get_words('~un');

        $query_word = array();
        foreach ($words as $word) {
            $query_word[] = $word['word'];
        }

        $xapian_db = SearchEngine::getDatabase('program');
        $enquire = new XapianEnquire($xapian_db);
        $qp = new XapianQueryParser();
        $qp->set_database($xapian_db);
        $qp->set_stemming_strategy(XapianQueryParser::STEM_SOME);
        $qp->set_default_op(XapianQuery::OP_AND);
        $query = $qp->parse_query(implode(' ', $query_word), XapianQueryParser::FLAG_DEFAULT, 'Z');

        $enquire->set_query($query);
        $matches = $enquire->get_mset($offset, $limit);
        $total = $matches->get_matches_estimated();
        $programs = array();
        $handler = $matches->begin();

        while(!$handler->equals($matches->end())) {
            $data = json_decode($handler->get_document()->get_data(), true);
            $program = $this->findOneById(new MongoId($data['id']));
            if (!is_null($program)) {
                $programs[] = $program;
                unset ($program);
            }

            $handler->next();
        }

        return $programs;
    }
    
    /**
     * 通过wiki获取当前正在播放的节目
     * @author lifucang
     */
    public function getLiveProgramByWiki($wiki_id) 
    {
        $now = new MongoDate();
        return $this->findOne(
            array(
                'query' => array(
                    'start_time' => array('$lte' => $now),
                    'end_time' => array('$gte' => $now),
                    'wiki_id' => $wiki_id
                )
            )
        );
    }
    
    /**
     * 通过code获取当前正在播放的节目
     * @author lifucang
     * @modify qhm 2013/7/1
     */
    public function getLiveProgramByCode($channel_code, $now = '',$has_wiki_id = true,$sort= false) 
    {
        $now = new MongoDate();
        $query = array(
	        'start_time' => array('$lte' => $now),
	        'end_time' => array('$gte' => $now),
	        'channel_code' => $channel_code
	    );
        if($has_wiki_id) {
            $query['wiki_id'] = array('$exists' => true);
        }
        if($sort){
        	$sort=array('time' => -1);
        }else{
        	$sort=array();
        }
	    return $this->findOne(array('query' => $query, 'sort'=>$sort));        
    } 

    
    /**
     * 通过codes获取当前正在播放的节目
     * @author lifucang
     */    
    public function getLivePrograms($channel_codes, $now = '', $has_wiki_id = true) 
    {
        $now = empty($now)?  new MongoDate() : new MongoDate(strtotime($now));
        $query = array(
	        "channel_code" => array('$in'=>$channel_codes),
	        'start_time' => array('$lte' => $now),
	        'end_time' => array('$gte' => $now)
        ); 
        if($has_wiki_id) {
            $query['wiki_id'] = array('$exists' => true);
        }
	    return $this->find(array('query' => $query, 'sort'=>array('time' => -1)));		
    }
    
    /**
     * 根据 channel_code 获取节目列表
     * @param <type> $channel_code 节目所属频道code
     * @param <type> $starttime 节目开始时间 时间戳？
     * @param <type> $endtime   节目结束 时间戳？
     * @return <type>
     * @author luren
     */
    public function getProgramsByCode($channel_code, $starttime = '', $endtime = '', $limit = 100) 
    {
    	$starttime   = empty($starttime) ? time() : strtotime($starttime);
    	$m_starttime = new MongoDate($starttime);
    	$m_endtime   = empty($endtime) ? new MongoDate(($starttime + 3600*8)) : new MongoDate(strtotime($endtime));
        return $this->find(
            array(
                'query' => array(
                    "channel_code" => $channel_code,
                    "start_time" =>array('$lte' => $m_endtime), 
                    'end_time' => array('$gt' => $m_starttime),
                ),
                "sort" => array("start_time" => 1), 
                "limit" => $limit
            )
        );
    }  
    
    /**
     * 日期范围内的节目数据
     * @param <type> $date_from
     * @param <type> $date_end
     * @return <type>
     */
    public function getNeedPrograms($date_end,$limit)
    {
        return $rets = $this->find(
            array(
                'query' => array(
                    'date' => array('$lte' => $date_end)
                ),
                'sort' => array("date_end" => 1),
                'limit' => $limit
            )
        );
    }
    
    /**
     * 根据 channel_code,tag,starttime,endtime 获取节目列表
     * @param <type> $channel_code 节目所属频道code
     * @param <type> $tag 节目标识
     * @param <type> $starttime 节目开始时间
     * @param <type> $endtime   节目结束
     * @return <type>
     * @author luren
     * @editor lifucang  2012-6-18  增加排序字段sort
     */
    public function getPrograms($channels, $tag='', $starttime, $endtime, $page = 1, $pagesize = 100) 
    {
		foreach($channels  as $channel)	{    
			$channel_codes[] = $channel['code'];
		}	    	
    	$starttime = empty($starttime) ? new MongoDate(time()) : new MongoDate(strtotime($starttime));
    	$endtime   = empty($endtime)   ? new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y'))) : new MongoDate(strtotime($endtime));
    	$skip = ($page - 1) * $pagesize;
        $query = array(
	        "channel_code" => array('$in' => $channel_codes),
	        'start_time' => array('$lte' => $endtime),
	        'end_time' => array('$gt' => $starttime),
	        'wiki_id' => array('$exists'=>true)
	    );        
    	if(!empty($tag)) {
            $arr_tags = (gettype($tag) != 'array') ? (array)$tag : $tag;
            $query['tags'] = array('$in'=>$arr_tags);
        }
	    return $this->find(
	        array(
	            'query' => $query,
	            "sort" => array("sort" => -1,"start_time" => 1),
	            'skip'=>$skip,
	            'limit'=>$pagesize,
	        )
	    );
    }
    
    /**
     * 获取距离当前时间最近的下一个要播放的节目
     * @param array $channels
     * @param string $endtime
     * @param string $tag
     * @return object
     * @author wangan
     */
    public function getNextUpdate($channels, $endtime = '', $tag = '')
    {    	
		foreach($channels  as $channel) {    
			$channel_codes[] = $channel['code'];
		}
		if(empty($endtime)) {
			$endtime = date("Y-m-d H:i:s",mktime(23, 59, 0, date('m'), date('d'), date('Y')));
        }
        $query = array(
	        "channel_code" => array('$in' => $channel_codes),
	        'start_time' => array('$gt' => new MongoDate(strtotime($endtime))),
	        'wiki_id'=>array('$exists'=>true)
	    );        
		if(!empty($tag)) {
			$arr_tags = (gettype($tag) != 'array') ? (array)$tag : $tag;
            $query['tags'] = array('$in'=>$arr_tags);
        }
	    return $this->findOne(
	        array(
	            'query' => $query,
	            'sort' => array("start_time" => 1),
            )
	    );	 
    }    
    
    /**
     * 根据 channel_code,key,starttime,endtime 获取节目列表
     * @param <type> $channel_code 节目所属频道code
     * @param <type> $key 节目名称
     * @param <type> $starttime 节目开始时间
     * @param <type> $endtime   节目结束
     * @return <type>
     * @author luren
     */
    public function getProgramsByCKSE($channels,$key='',$starttime,$endtime) 
    {
		foreach($channels as $channel){    
			$channel_codes[] = $channel['code'];
		}	    	
    	$starttime   = empty($starttime)?time():strtotime($starttime);
    	$m_starttime = new MongoDate($starttime);
    	$m_endtime   = empty($endtime)?new MongoDate(($starttime + 3600*2)):new MongoDate(strtotime($endtime));
    	
    	$key = new MongoRegex("/.*".$key.".*/");
    	if(!empty($key))
    	{
	    	return $this->find(
	                    array(
	                        'query' => array(
	                            "channel_code" => array('$in' => $channel_codes),
	                    		'name'=>$key,
	                            'start_time' => array('$lte' => $m_endtime),
	                            'end_time' => array('$gte' => $m_starttime),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                        "sort" => array("start_time" => 1),
	                        )
	                );
    	}
    	else
    	{
	    	return $this->find(
	                    array(
	                        'query' => array(
	                            "channel_code" => array('$in' => $channel_codes),
	                            'start_time' => array('$lte' => $m_endtime),
	                            'end_time' => array('$gte' => $m_starttime),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                        "sort" => array("start_time" => 1),	                            
	                        )
	                );
    	}
    } 
    
    /**
     * 获取接下来要播放的节目
     * @param unknown_type $channel_codes
     * @param unknown_type $now
     * @param unknown_type $has_wiki_id
     * @edit gaobo 
     * @editinfo add param $taskParam 增加一个参数，避免调用此方法的计划任务报错中断。
     */
    public function getNextProgram($channel_codes, $now = '',$has_wiki_id = true, $taskParam=false) 
    {
        $now = empty($now)?  new MongoDate() : new MongoDate(strtotime($now));
        foreach($channel_codes as $channel_code) {
            $query = array(
		        "channel_code" => $channel_code,
		        'start_time' => array('$gt' => $now)
		    );
            if($has_wiki_id) {
                $query['wiki_id'] = array('$exists' => true);
            }
		    $programs[] = $this->findOne(
		        array(
		            'query' => $query,
		            'sort'=>array('start_time' => 1),
		        )
		    );     
		}
		if(!$taskParam){
		    $programs_real = array();
		    if($programs){
		        $sort = array();
		        foreach($programs as $k=>$v){
		            $sort[$k] = $v->getDate().' '.$v->getTime();
		        }
		        asort($sort);
		        $i=0;
		        foreach($sort as $k=>$v){
		            $programs_real[$i] = $programs[$k];
		            $i++;
		        }
		    }
		    
		    return $programs_real;
		}else{
		    return $programs;
		}
		
    } 
    
    /**
     * 根据code排序 正在播放的节目
     * @param $channel_codes
     * @param $now
     */
    public function getLiveProgramsSortByCode($channel_codes, $now='') {
        $now = empty($now)?  new MongoDate():new MongoDate(strtotime($now));
	    return $this->find(
	                    array(
	                        'query' => array(
	                            "channel_code" => array('$in'=>$channel_codes),
	                            'start_time' => array('$lte' => $now),
	                            'end_time' => array('$gt' => $now)
	                            ),
	                         'sort'=>array('start_time' => -1),
	                        )
	                );					      
       
    }
    
    /**
     * 自动为program 匹配wiki  当前时间以后本周之前的节目
     */ 
    public function autoWiki($program_name,$time,$wiki_id,$channel_code)
    {
        $mongo = $this->getMondongo();
		$repository = $mongo->getRepository('wiki');         
    	$wiki = $repository->findOneById(new mongoId($wiki_id));
    	$unix = strtotime($time);
    	$now = new MongoDate($unix);//起始时间
    	$n = date("N",$unix);
    	$i = 7 - $n;
    	$end_date = date("Y-m-d",$unix);
    	$program_name_reg = preg_replace("/\d/","\\d*",$program_name);
	    $programs = $this->find(
	                    array(
	                        'query' => array(
	                            "name" => new MongoRegex("/$program_name_reg/i"),
	                    		"channel_code" => $channel_code,
	                            'start_time' => array('$gt' => $now),
	                            'end_time' => array('$lt' => new MongoDate(strtotime($end_date." 23:59:59 +".$i."day")))
	                            )
	                        )
	                );    
	    if($programs) {
	    	foreach($programs as $program) {	    		
	    		$program->setWikiId($wiki_id);
	    		if($wiki)
	    			$program->setTags($wiki->getTags());
	    		else
	    			$program->setTags('');
	    		$program->save();
	    	}
	    }	
    }
     
    public function getProgramsByCodes($channel_codes,$tag='',$starttime,$endtime) 
    {
    	$starttime=empty($starttime)?new MongoDate(time()):new MongoDate(strtotime($starttime));//对的
    	$endtime=empty($endtime)?new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y'))):new MongoDate(strtotime($endtime));
    	
    	if(!empty($tag))
    	{
	    	return $this->find(
	                    array(
	                        'query' => array(
	                            "channel_code" => array('$in' => $channel_codes),
	                    		'tags'=>array('$in'=>array($tag)),
	                            'start_time' => array('$lte' => $endtime),
	                            'end_time' => array('$gt' => $starttime),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                        "sort" => array("sort" => -1,"start_time" => 1),
	                        )
	                );
    	}
    	else
    	{
	    	return $this->find(
	                    array(
	                        'query' => array(
	                            "channel_code" => array('$in' => $channel_codes),
	                            'start_time' => array('$lte' => $endtime),
	                            'end_time' => array('$gte' => $starttime),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                        "sort" => array("sort" => -1,"start_time" => 1),	                            
	                        )
	                );
    	}
    } 
    /**
     * 根据 $wiki_id 获取节目列表
     * @param <type> $wiki_id 
     * @param <type> $starttime 节目开始时间
     * @param <type> $endtime   节目结束
     * @return <type>
     * @author wn
     */
    public function getProgramsByWikiId($wiki_id,$starttime='',$endtime='') 
    {
    	$starttime   = empty($starttime)?time():strtotime($starttime);
    	$m_starttime = new MongoDate($starttime);
    	$m_endtime   = empty($endtime)?new MongoDate(($starttime + 3600*2)):new MongoDate(strtotime($endtime));
/*    	$starttime=empty($starttime)?new MongoDate(time()+(8*3600)):new MongoDate(strtotime($starttime)+(8*3600));//对的
    	$endtime=empty($endtime)?new MongoDate(($starttime + 7200)+(8*3600)):new MongoDate(strtotime($endtime)+(8*3600));*/
        return $this->find(
                    array(
                        'query' => array(
                            "wiki_id" => $wiki_id,
                    		"start_time" =>array('$lte' => $m_endtime), 
                            'end_time' => array('$gt' => $m_starttime),
                            ),
                         "sort" => array("start_time" => 1),   
                     )
                );
    }
    /**
     * 获取正在播放的节目
     * @param <type> $starttime  当前时间戳
     * @return <type>
     * @author wn
     */    
    public function getAllLivePrograms($starttime) {
    	$m_starttime = new MongoDate($starttime);
        return $this->find(
                    array(
                        'query' => array(
                            'wiki_id'=>array('$exists'=>true,'$ne'=>''),
                    		"start_time" =>$m_starttime, 
                            ),
                     )
                );
    } 
    /**
     * 删除一天的节目列表
     * @param <string> $channel_code
     * @param <string> $date e: 2012-09-20
     * @return <array>
     * @author lifucang
     */
    public function removeDayPrograms($channel_code, $date) {
        return $this->remove(
                     array(
                            'channel_code' => $channel_code,
                            'date' => $date
                        )
                );

    } 
    /**
     * 删除当天及以后的节目列表
     * @param <string> $channel_code
     * @param <string> $date e: 2012-09-20
     * @return <array>
     * @author lifucang
     */
    public function removeDaysPrograms($channel_code, $date) {
        return $this->remove(
                     array(
                            'channel_code' => $channel_code,
                            'date' => array('$gte'=>$date)
                        )
                );

    }   
    /**
     * 根据 channel_code,tag,starttime,endtime 获取”开始播放“的节目列表
     * @param <type> $channel_code 节目所属频道code
     * @param <type> $tag 节目标识
     * @param <type> $starttime 节目开始时间
     * @param <type> $endtime   节目结束
     * @return <type>
     * @author luren
     * @editor lifucang  2012-6-18  增加排序字段sort
     */
    public function getStartPrograms($channels,$tag='',$starttime,$endtime, $page = 1, $pagesize = 100) 
    {
		foreach($channels  as $channel)
		{    
			$channel_codes[] = $channel['code'];
		}	    	
    	$starttime = empty($starttime) ? new MongoDate(time())                                             : new MongoDate(strtotime($starttime));//对的
    	$endtime   = empty($endtime)   ? new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y'))) : new MongoDate(strtotime($endtime));
    	$skip = ($page - 1) * $pagesize;
    	if(!empty($tag))
    	{
    		$arr_tags=(gettype($tag)!='array')?(array)$tag:$tag;
	    	return $this->find(
	                    array(
	                        'query' => array(
	                            "channel_code" => array('$in' => $channel_codes),
	                    		'tags'=>array('$in'=>$arr_tags),
	                            'start_time' => array('$gt'=>$starttime,'$lte' => $endtime),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                           "sort" => array("sort" => -1,"start_time" => 1),
	                    	   'skip'=>$skip,
	                    	   'limit'=>$pagesize
	                        )
	                );
	    	
	 
    	}
    	else
    	{
	    	return $this->find(
	                    array(
	                        'query' => array(
	                            "channel_code" => array('$in' => $channel_codes),
	                            'start_time' => array('$gt'=>$starttime,'$lte' => $endtime),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                        "sort" => array("sort" => -1,"start_time" => 1),
						    'skip'=>$skip,
	                    	'limit'=>$pagesize,
	                        )
	                );
    	}
    }
    
    /**
     * 
     * @desc 根据条件返回需要的sql
     * @param <type> $starttime 节目开始时间 时间戳？
     * @param <type> $endtime   节目结束 时间戳？
     * @param <string> $program_name 节目名称
     * @param <string> $date   日期
     * @return <array>
     * @time 2013-07-01
     * @author tianzhongsheng
     */
    public function getProgramsByCodeByDateSql($program_name='', $starttime = '', $endtime = '', $date = '')
    {

		if($program_name != '')
 		{
			$name = "/.*".$program_name.".*/i";
			$program_name = new MongoRegex($name);
			$querys['name'] = $program_name;

 		}
    	$starttime = new MongoDate(strtotime($date.' '.$starttime));
    	$endtime   = new MongoDate(strtotime($date.' '.$endtime));
		
		$querys['channel_type'] = array('$in' => array('cctv','tv'));
    	$querys['start_time'] = array('$gte'=>$starttime,'$lt' => $endtime);
    	$querys['date'] = $date;
    	return  array( 'query' => $querys,"sort" => array("start_time" => 1));

    }  
    
}

