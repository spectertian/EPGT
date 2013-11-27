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
        if(isset($id))
        {
        	$tem = $this->findOneById(new mongoId($id));
            if($tem)
            {
            	if($name == 'time' )
            	{
            		$old_ymd = $tem->getStartTime()->format("Y-m-d");
            		$new_start_time = new datetime($old_ymd.' '.$value);
            		$tem->setStartTime($new_start_time);
            		$tem->$method($value);
            		$tem->save();
            	}
                else
                {
            		$tem->$method($value);
            		$tem->save();
            	}                
                $return = array('code'=>1, 'msg'=>'更新成功');
            }
            else
            {
                $return['msg']  = '记录不存在!';
                return $return;
            }
        }
        return  $return;
    } 
    /**
     * 获取一天的节目列表
     * @param <string> $channel_code
     * @param <string> $date e: 2010-11-11
     * @update lifucang 2013-08-06  加上缓存
     * @author pjl
     */
    public function getDayPrograms($channel_code, $date) {
        //$memcache = tvCache::getInstance();
        //$key = "getDayPrograms_$channel_code_$date";
        //$programs=$memcache->get($key);
        //if(!$programs){
            $programs=$this->find(
                        array(
                            'query' => array(
                                'channel_code' => $channel_code,
                                'date' => $date
                            ),
                            'sort' => array('time' => 1)
                        )
            );
            //$memcache->set($key,$programs,3600);  //1小时
        //}
        return $programs;
    }
    
    /**
     * 统计某频道某一天是否有节目
     * @param <string> $channel_code,$date
     * @param <string> $date 2013-01-18
     * @author lifucang
     */
    public function countDayPrograms($channel_code, $date,$isWiki=false) {
        if($isWiki){
            $query=array(
                            'channel_code' => $channel_code,
                            'date' => $date,
                            'wiki_id' => array('$exists' => true)
                        );
        }else{
            $query=array(
                            'channel_code' => $channel_code,
                            'date' => $date
                        );
        }
        return $this->count($query);
    }
    /**
     * 统计某频道某一天的节目名称是否以播出为准
     * @param <string> $channel_code,$date
     * @param <string> $date 2013-01-18
     * @author lifucang
     */
    public function countDayProgramsBochu($channel_code, $date) {
        return $this->count(
                    array(
                            'channel_code' => $channel_code,
                            'date' => $date,
                            'name' => '以播出为准'
                        )
                );
    }   
    /**
     * 统计某频道某一时间段是否有节目
     * @param <string> $channel_code,$start_time,$end_time
     * @param <string> $date 2013-01-18
     * @author lifucang
     */
    public function countTimePrograms($channel_code, $start_time, $end_time ) {
        $start_time = new MongoDate(strtotime($start_time));
        $end_time   = new MongoDate(strtotime($end_time));  
        return $this->count(
                    array(
                            'channel_code' => $channel_code,
                            'start_time' => array('$gte' => $start_time,'$lt'=>$end_time),
                        )
                );
    }     
    /**
     * 获取一天的节目列表 带分页
     */
    public function getDayProgramsByPage($channel_code, $date, $page=1, $size=10){
    	
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
    	
    	return $this->find(
                    array(
                        'query' => array(
                            'channel_code' => $channel_code,
                            'date' => $date
                        ),
                        'limit'=> $size,
                        'skip'  => $offset,
                        'sort' => array('time' => 1),
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
     * 获得一天的节目列表并且是有wiki
     * @param string $channel_code
     * @param string date
     * @return void
     * @author lizhi
     */
    public function getDayProgramsWiki($channel_code, $date) {
        return $this->find(
            array(
                'query' => array(
                    'channel_code' => $channel_code,
                    'date' => $date,
                    'wiki_id'=>array('$exists'=>"true",'$ne'=>null)
                ),
                'sort' => array('time' => 1)
            )
        );
    }


    /**
     * 根据频道获取直播节目
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified lifucang 2012-10-26
     */
    public function getLiveProgramByChannel($channel_code="") {
        $now = new MongoDate();
        return $this->findOne(
                    array(
                        'query' => array(
                            'channel_code' => $channel_code,
                            'start_time' => array('$lt' => $now),
                            'end_time' => array('$gt' => $now),
                            'wiki_id' => array('$exists' => true),
                        )
                    )
                );   
    }
    /**
     * 根据频道以及tag获取直播节目
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified lifucang 2012-12-12
     */
    public function getLiveProgramByChannelTag($channel_code="",$tag="",$model="") {
        $now = new MongoDate();
        if(is_array($tag)){
            $tag_query=array('$in'=>$tag);
        }else{
            $tag_query=$tag;
        }
        return $this->findOne(
                    array(
                        'query' => array(
                            'tags' => $tag_query,
                            'channel_code' => $channel_code,
                            'start_time' => array('$lt' => $now),
                            'end_time' => array('$gt' => $now),
                            'wiki_id' => array('$exists' => true),
                        )
                    )
                ); 

    }
    
    public function getLiveProgramByChannelTags($channel_code="",$tag="",$limit=10) {
        $now = new MongoDate();
        if(is_array($tag)){
            $tag_query=array('$in'=>$tag);
        }else{
            $tag_query=$tag;
        }
        if(is_array($channel_code)){
            $channel_code_query=array('$in'=>$channel_code);
        }else{
            $channel_code_query=$tag;
        }
        return $this->find(
                    array(
                        'query' => array(
                            'channel_code' => $channel_code_query,
                            'start_time' => array('$lt' => $now),
                            'end_time' => array('$gt' => $now),
                            'wiki_id' => array('$exists' => true),
                            'tags' => $tag_query,
                        ),
                        'limit'=> $limit
                    )
                );
    }
    /**
     * 根据频道获取直播节目(多个频道)
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified lifucang 2012-10-26
     */
    public function getLiveProgramsByChannel($channel_code=null) {
        $now = new MongoDate();
        return $this->find(
                    array(
                        'query' => array(
                            'channel_code' => array('$in' => $channel_code),
                            'start_time' => array('$lt' => $now),
                            'end_time' => array('$gt' => $now),
                            'wiki_id' => array('$exists' => true),
                        )
                    )
                );   
    }
    /**
     * 获取指定频道列表，当前正在直播的某TAG节目，专为电视端前台显示，要求拥有 Wiki
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified zhigang 2011-1-4
     */
    public function getLiveProgramByTag($tag, $channels="",$limit = 0) {
        $channel_codes = array();
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getCode();
        }
        $now = new MongoDate();
        if(empty($tag))
        {
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true,'$ne'=>null),
	                        ),
	                        'limit'=> $limit,
	                        "sort" => array("start_time" => 1),
	                    )
	                );        	
        }
        else
        {
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'tags' => $tag,
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true),
	                        ),
	                        'limit'=> $limit,
	                        "sort" => array("start_time" => 1),
	                    )
	                );
        }
    }
    /**
     * 获取指定频道列表，当前正在直播的某TAG节目，专为电视端前台显示，要求拥有 Wiki
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified zhigang 2011-1-4
     */
    public function getLiveProgramByTagPage($tag, $channels="",$page=1,$size=10) {
        $channel_codes = array();
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getCode();
        }
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
                
        $now = new MongoDate();
        if(empty($tag))
        {
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true),
	                        ),
	                        'limit'=> $size,
                            'skip'  => $offset,
	                        "sort" => array("start_time" => 1),
	                    )
	                );        	
        }
        else
        {
            if (is_array($tag)){
                $tags=array('$in' => $tag);
            }else{
                $tags=$tag;
            }
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'tags' => $tags,
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true),
	                        ),
	                        'limit'=> $size,
                            'skip'  => $offset,
	                        "sort" => array("start_time" => 1),
	                    )
	                );
        }
    }
    
    /**
     * 获取当前正在直播的某TAG节目，专为电视端前台显示，要求拥有 Wiki
     * @param string $tag
     * @return array
     * @modified lifucang 2012-11-15
     */
    public function getLiveProgramByTags($tag=null, $page=1,$size=10) {
    	$offset = $size * ($page-1);
    	if($offset<0)$offset = 0;
                
        $now = new MongoDate();
        if(empty($tag))
        {
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true),
	                        ),
	                        'limit'=> $size,
                            'skip'  => $offset,
	                        "sort" => array("start_time" => 1),
	                    )
	                );        	
        }else{
            if (is_array($tag)){
                $tags=array('$in' => $tag);
            }else{
                $tags=$tag;
            }
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'tags' => $tags,
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true),
	                        ),
	                        'limit'=> $size,
                            'skip'  => $offset,
	                        "sort" => array("start_time" => 1),
	                    )
	                );
        }
    }  
    /**
     * 获取当前正在直播的某TAG节目，广电专用
     * @param string $tag
     * @return array
     * @modified lifucang 2012-11-23
     */
    public function getLiveProgramByTagsGd($tag=null, $limit) {
        if (is_array($tag)){
            $tags=array('$in' => $tag);
        }else{
            $tags=$tag;
        }
        $now = new MongoDate();
        return $this->find(
                    array(
                        'query' => array(
                            'tags' => $tags,
                            //'channel_code'=>array('$not'=> new MongoRegex("/gaoqing/")),
                            'start_time' => array('$lt' => $now),
                            'end_time' => array('$gt' => $now),
                            'wiki_id' => array('$exists' => true),
                        ),
                        'limit'=> $limit,
                        "sort" => array("start_time" => 1),
                    )
       );

    } 
    /**
     * 获取当前正在直播的某TAG节目，广电专用
     * @param string $tag
     * @return array
     * @modified lifucang 2012-12-6
     */
    public function getLiveProgramByChannelCode($channelCode,$tag=null, $limit) {
        $now = new MongoDate();
        if (is_array($tag)){
            $tags=array('$in' => $tag);
        }else{
            $tags=$tag;
        }
        return $this->find(
                    array(
                        'query' => array(
                            'tags' => $tags,
                            'channel_code'=>array('$in'=> $channelCode),
                            'start_time' => array('$lt' => $now),
                            'end_time' => array('$gt' => $now),
                            'wiki_id' => array('$exists' => true),
                        ),
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
     */
    public function getOneLiveProgramByTag($tag, $channels="") {
        $channel_codes = array();
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getCode();
        }
        $now = new MongoDate();
        if(empty($tag))
        {
	        return $this->findOne(
	                    array(
	                        'query' => array(
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true),
	                        )
	                    )
	                );        	
        }
        else
        {
	        return $this->findOne(
	                    array(
	                        'query' => array(
	                            'tags' => $tag,
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$lt' => $now),
	                            'end_time' => array('$gt' => $now),
	                            'wiki_id' => array('$exists' => true),
	                        )
	                    )
	                );
        }
    }
    /**
     * 获取指定频道列表，今天将要直播的某TAG节目，有重复wiki_id的
     * 在getLiveProgramByTag基础上作的修改
     * @param string $tag
     * @param array $channels
     * @return array
     * @modified lifucang 2012-5-18
     */
    public function getDayLiveProgramByTag($tag, $channels="",$limit = 0) {
        $channel_codes = array();
        foreach ($channels as $channel) {
            $channel_codes[] = $channel->getCode();
        } 
        $starttime = new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $endtime=new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y')));
               
        if(empty($tag))
        {
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$gte' => $starttime,'$lte' => $endtime),
	                            'wiki_id' => array('$exists' => true),
                                
	                        ),
	                        'limit'=> $limit,
	                        "sort" => array("start_time" => 1),
	                    )
	                );        	
        }
        else
        {
	        return $this->find(
	                    array(
	                        'query' => array(
	                            'tags' => $tag,
	                            'channel_code' => array('$in' => $channel_codes),
	                            'start_time' => array('$gte' => $starttime,'$lte' => $endtime),
	                            'wiki_id' => array('$exists' => true),
	                        ),
	                        'limit'=> $limit,
	                        "sort" => array("start_time" => 1),
	                    )
	                );
        }
    }
    /**
     * 根据WIKI ID获取未播出的电视节目
     * @param <int> $wiki_id
     * @return <array>
     * @author pjl
     */
    public function getUnPlayedProgramByWikiId($wiki_id) {
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
                        //"publish"=> true
                    )
                );
        return $rets;
    }
    /**
     * 根据WIKI ID获取当天的电视节目
     * @param <int> $wiki_id
     * @return <array>
     * @author lfc
     */
    public function getdayUnPlayedProgramByWikiId($wiki_id) {
        //$now = new MongoDate();
        $date_start = new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $date_end=new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y')));
        $rets = $this->find(
                    array(
                        'query' => array(
                            'wiki_id' => (string)$wiki_id,                          
                            'start_time' => array('$gte' => $date_start,'$lte' => $date_end),
                        ),
                        "sort" => array("start_time" => 1),
                        //"publish"=> true
                    )
                );
        return $rets;
    }
    /**
     * 根据WIKI ID获取当天的电视节目数,广电专用（因有一些频道已删除，但节目表还有，所以只取channel表里有的频道）
     * @param <int> $wiki_id
     * @return <array>
     * @author lifucang
     */
    public function countDayPlayedProgramByWikiIdGd($wiki_id) {
        //$now = new MongoDate();
        $date_start = new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        $date_end=new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y')));
        $count = $this->count(
                    array(
                            'wiki_id' => (string)$wiki_id,                          
                            'start_time' => array('$gte' => $date_start,'$lte' => $date_end),
                            //"publish"=> true
                        )
                );
        return $count;
    }    
    /**
     * 根据WIKI ID获取当天已播放的电视节目,广电专用（因有一些频道已删除，但节目表还有，所以只取channel表里有的频道）
     * @param <int> $wiki_id
     * @return <array>
     * @author lifucang
     */
    public function getDayPlayedProgramByWikiIdGd($wiki_id,$limit=0,$startTime='') {
        $now = new MongoDate();
        $date_start= empty($startTime)?new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y'))):new MongoDate(strtotime($startTime));
        //$date_end= empty($endTime)?new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y'))):new MongoDate(strtotime($endTime));
        $rets = $this->find(
                    array(
                        'query' => array(
                            'wiki_id' => (string)$wiki_id,                          
                            'start_time' => array('$gte' => $date_start,'$lte' => $now),
                            'cpg_content_id'=>array('$exists'=>true),
                            //"publish"=> true
                        ),
                        "limit"=>$limit,
                        "sort" => array("start_time" => -1),
                        
                    )
                );
        return $rets;
    }     
    /**
     * 根据WIKI ID获取当天未播放的电视节目,广电专用（因有一些频道已删除，但节目表还有，所以只取channel表里有的频道）
     * @param <int> $wiki_id
     * @return <array>
     * @author lifucang
     */
    public function getDayUnPlayedProgramByWikiIdGd($wiki_id,$limit=0,$endTime='') {
        $now = new MongoDate();
        //$date_start= empty($startTime)?new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y'))):new MongoDate(strtotime($startTime));
        $date_end= empty($endTime)?new MongoDate(mktime(23, 59, 0, date('m'), date('d'), date('Y'))):new MongoDate(strtotime($endTime));
        $rets = $this->find(
                    array(
                        'query' => array(
                            'wiki_id' => (string)$wiki_id,                          
                            'start_time' => array('$gt' => $now,'$lte' => $date_end),
                            //"publish"=> true
                        ),
                        "limit"=>$limit,
                        "sort" => array("start_time" => 1),
                        
                    )
                );
        return $rets;
    }  
    /**
     * 根据WIKI ID获取当天未播放的电视节目,广电专用（因有一些频道已删除，但节目表还有，所以只取channel表里有的频道）
     * @param <int> $wiki_id
     * @return <array>
     * @author lifucang
     */
    public function getProgramByWikiIdTime($wiki_id,$start_time,$end_time,$limit=0) {
        $now = new MongoDate();
        $date_start = new MongoDate(strtotime($start_time));
        $date_end=new MongoDate(strtotime($end_time));
        $rets = $this->find(
                    array(
                        'query' => array(
                            'wiki_id' => (string)$wiki_id,                          
                            'start_time' => array('$gte' => $date_start,'$lte' => $date_end),
                            //"publish"=> true
                        ),
                        "limit"=>$limit,
                        "sort" => array("start_time" => 1),
                        
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
    public function getCustomDateProgramByWikiId($wiki_id, $date_from, $date_end) {
        $rets = $this->find(
                    array(
                        'query' => array(
                            'wiki_id' => (string) $wiki_id,
                            'date' => array('$lte' => $date_end, '$gte' => $date_from),
                        ),
                        
                        "sort" => array("start_time" => 1)
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
    public function getProgramByDateAndChannelCode($date, $channel_code) {
        return $this->find(
                array(
                    'query' => array(
                        'date' => $date,
                        'channel_code' => $channel_code,
                        //'publish' => true,
                        'wiki_id' => array('$exists' => true)
                    ),
                    
                    "sort" => array("start_time" => 1)
                )
            );
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
    public function getUserRelateProgramByDate($wiki_id, $province, $fromdate, $enddate, $limit = 0) {
        $channels = Doctrine::getTable('Channel')->getUserChannels("",$province);
        foreach ($channels as $channel) {
            $codes[] = $channel->getCode();
        }
        return $this->find(
                        array(
                            'query' => array(
                                'wiki_id' => $wiki_id,
                                //'publish' => true,
                                'date' => array('$gte' => $fromdate, '$lte' => $enddate,),
                                'channel_code' => array('$in' => $codes)
                            ),
                            
                            'limit'=> $limit,
                            "sort" => array("start_time" => 1),
                        )
                );
    }

    /**
     * 节目表全文搜索
     * @param <string> $search_text
     * @param <integer> $total
     * @param <integer> $offset
     * @param <integer> $limit
     * @return <array>
     */
    public function search($search_text, &$total, $offset=0, $limit=20) {
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
    public function getLiveProgramByWiki($wiki_id) {
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
     */
    public function getLiveProgramByCode($channel_code,$has_wiki_id = true) {
        $now = new MongoDate();
        if($has_wiki_id)
        {
	        return $this->findOne(
	                    array(
	                        'query' => array(
	                            'start_time' => array('$lte' => $now),
	                            'end_time' => array('$gte' => $now),
	                            'channel_code' => $channel_code,
	                            'wiki_id' => array('$exists' => true)
	                            )
	                        )
	                );
        }
        else 
        {
	        return $this->findOne(
	                    array(
	                        'query' => array(
	                            'start_time' => array('$lte' => $now),
	                            'end_time' => array('$gte' => $now),
	                            'channel_code' => $channel_code,
	                            )
	                        )
	                );
        	
        }
    }   
    public function getLivePrograms($channel_codes) {
        $now = new MongoDate();
        return $this->find(
                    array(
                        'query' => array(
                            "channel_code" => array('$in'=>$channel_codes),
                            'start_time' => array('$lte' => $now),
                            'end_time' => array('$gte' => $now),
                            'wiki_id' => array('$exists' => true),
                            )
                        )
                );
    }
    /**
     * 根据 channel_code 获取节目列表
     * @param <type> $channel_code 节目所属频道code
     * @param <type> $starttime 节目开始时间
     * @param <type> $endtime   节目结束
     * @return <type>
     * @author luren
     */
    public function getProgramsByCode($channel_code,$starttime='',$endtime='',$sort='start_time') 
    {
    	$starttime   = empty($starttime)?time():strtotime($starttime);
    	$m_starttime = new MongoDate($starttime);
    	$m_endtime   = empty($endtime)?new MongoDate(($starttime + 3600*8)):new MongoDate(strtotime($endtime));
/*    	$starttime=empty($starttime)?new MongoDate(time()+(8*3600)):new MongoDate(strtotime($starttime)+(8*3600));//对的
    	$endtime=empty($endtime)?new MongoDate(($starttime + 7200)+(8*3600)):new MongoDate(strtotime($endtime)+(8*3600));*/
        return $this->find(
                    array(
                        'query' => array(
                            "channel_code" => $channel_code,
                    		"start_time" =>array('$lte' => $m_endtime), 
                            'end_time' => array('$gte' => $m_starttime),
                            ),
                         "sort" => array($sort => 1),   
                     )
                );
    }    
    /**
     * 日期范围内的节目数据
     * @param <type> $date_from
     * @param <type> $date_end
     * @return <type>
     */
    public function getNeedPrograms($date_end,$limit){
        return $rets = $this->find(
                    array(
                        'query' => array(
                            'date' => array('$lte' => $date_end)
                        ),
                        'limit' => $limit,
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
    public function getPrograms($channels,$tag='',$starttime,$endtime) 
    {
		foreach($channels  as $channel)
		{    
			$channel_codes[] = $channel['code'];
		}	    	
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
     * 获取距离当前时间最近的下一个要播放的节目
     * @param array $channels
     * @param string $endtime
     * @param string $tag
     * @return object
     * @author wangan
     */
    public function getNextUpdate($channels,$endtime='',$tag='')
    {
    	
		foreach($channels  as $channel)
		{    
			$channel_codes[] = $channel['code'];
		}
		if(empty($endtime))
			$endtime = date("Y-m-d H:i:s",mktime(23, 59, 0, date('m'), date('d'), date('Y')));
		if(!empty($tag))
		{
	        return $this->findOne(
	                    array(
	                        'query' => array(
	                    		"channel_code" => array('$in' => $channel_codes),
	                            'start_time' => array('$gt' => new MongoDate(strtotime($endtime))),
	                    		'tags'=>array('$in'=>array($tag)),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                         'sort' => array("start_time" => 1),
	                        )
	                );
		}
		else
		{
	        return $this->findOne(
	                    array(
	                        'query' => array(
	                    		"channel_code" => array('$in' => $channel_codes),
	                            'start_time' => array('$gt' => new MongoDate(strtotime($endtime))),
	                    		'wiki_id'=>array('$exists'=>true)
	                            ),
	                         'sort' => array("start_time" => 1),
	                        )
	                );			
		}	                
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
		foreach($channels  as $channel)
		{    
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
}

