<?php


class ChannelTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Channel');
    }
    
    /**
     * 返回所有央视视频道 
     * @author wangnan
     */
    public function getYangShiChannels($offset=0, $limit = 100, $sort = 0) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channel_YangShis728_'.$offset.'_'.$limit.'_'.$sort;
        $channels = $memcache->get($memcache_key);
        if(!$channels){   
            switch(intval($sort)) {
                case 1:
                    $sort = 'hot desc';
                    break;
                default:
                    $sort = 'recommend desc';
            }
    		$channels = $this->createQuery()
    			->where('type = ?','cctv')
    			->orderBy($sort)
                ->offset(intval($offset))
                ->limit(intval($limit))
    			->execute();
            $memcache->set($memcache_key,$channels);   
        }    
        return $channels;
    }
    
    /**
     * 返回所有卫视频道 
     * @author wangnan
     */
    public function getWeiShiChannels($offset=0, $limit = 100, $sort = 0) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channel_WeiShis729_'.$offset.'_'.$limit.'_'.$sort;
        $channels = $memcache->get($memcache_key);
        if(!$channels){ 
            switch(intval($sort)) {
                case 1:
                    $sort = 'hot desc';
                    break;
                default:
                    $sort = 'recommend desc';
            }
    		$channels = $this->createQuery()
    			->where('type = ?','tv')
    			->orderBy($sort)
                ->offset(intval($offset))
                ->limit(intval($limit))
    			->execute();
            $memcache->set($memcache_key,$channels);   
        }    
        return $channels;
    }
    
    /**
     * 返回所有央视卫视频道 
     * @author wangnan
     */
    public function getYangShiAndWeiShiChannels($offset = 0, $limit = 200, $sort = 0) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channel_YSAWS727_'.$offset.'_'.$limit.'_'.$sort;
        $channels = $memcache->get($memcache_key);
        if(!$channels){ 
            switch(intval($sort)) {
                case 1:
                    $dsort = 'hot desc';
                    break;
                default:
                    $dsort = 'recommend desc';
            }
    		$channels = $this->createQuery()
    			->where('type = ?','cctv')
                ->orWhere('type = ?','tv')
    			->orderBy($dsort)
                ->offset(intval($offset))
                ->limit(intval($limit))
    			->execute();
            
            $memcache->set($memcache_key,$channels);   
        }
        return $channels;
    }
    
    /**
     * 返回所有教育频道
     * @author wangnan
     */
    public function getEduChannels($offset=0, $limit = 100, $sort = 0) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channel_Edus729_'.$offset.'_'.$limit.'_'.$sort;	
        $channels = $memcache->get($memcache_key);
        if(!$channels){ 
            switch(intval($sort)) {
                case 1:
                    $sort = 'hot desc';
                    break;
                default:
                    $sort = 'recommend desc';
            }
    		$channels = $this->createQuery()
    			->where('type = ?','edu')
    			->orderBy($sort)
                ->offset(intval($offset))
                ->limit(intval($limit))
    			->execute();
            $memcache->set($memcache_key,$channels);   
        }    
        return $channels;
    }
    
    /**
     * 根据 tv_station_id 获取频道
     * @param <Array> $tvStation_ids
     * @return Array
     */
    public function getChannelsForTvStations($tvStation_ids)
    {
        return $this->createQuery()
                    ->whereIn('tv_station_id',$tvStation_ids)
                    ->addOrderBy('id = -1')
                    ->execute()
                    ->toArray();
    }
    
    /**
     * 根据 tv_station_id 获取频道,返回非array
     * @param <Array> $tvStation_ids
     * @return Objects
     */
    public function getChannelsByTvStations($tvStation_ids)
    {
        return $this->createQuery()
                    ->whereIn('tv_station_id',$tvStation_ids)
                    ->addOrderBy('id = -1')
                    ->execute();
    }

    /**
     * 根据 $tv_station_id 取得已发布 channel 列表
     * @param int $tv_station_id
     * @return  Array
     */
    public function getChannlesWithTvStation($tv_station_id) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channel_Tvs_'.$tv_station_id;
        $channels = $memcache->get($memcache_key);
        if(!$channels){ 
            $channels = $this->createQuery()
                ->where('publish = ?', 1)
                ->andWhere('tv_station_id = ?', $tv_station_id)
                ->orderBy('sort_id')
                ->execute();
            $memcache->set($memcache_key, $channels); 
        }
        return $channels;
    }
    
    /**
     * 设置发布状态
     * @param <int> $id channel_id
     * @param <int> $is_show
     * @return Array
     */
    public static function setStatus($id, $is_show = 1)
    {
        return $this->ajaxUpdate($id, "publish", $is_show);
    }

    /**
     * 根据字段名称设置字段值
     * @param <Int> $id
     * @param <String> $name
     * @param <String> $value
     * @return Array
     */
    public static function ajaxUpdate($id, $name, $value)
    {
        if(!is_numeric($id)) {
            return array('code'=>0, 'msg'=>'位置错误');
        }
        
        $chennel = Doctrine::getTable('Channel')->findOneById($id);
        if($chennel) {
            $chennel->set($name, $value);
            $chennel->save();
            return array('code'=>1, 'msg'=>'更新成功');
        } else {
            return array('code'=>0, 'msg'=>'记录不存在!');
        }
    }

    /**
     * 取得用户本地的频道列表
     * @param array $tv_station_ids
     * @param string $type
     * @return mixed
     */
    public function getLocalChannels($city, $province='', $offset = 0, $limit = 100, $order = 0) 
    {  
        if(!$province) {
            return null;
        }
        
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channels_getLocal729_'.$city.'_'.$province."_".$order."_".$offset."_".$limit;	
        $channels = $memcache->get($memcache_key);
        if(!$channels) {
            switch(intval($sort)) {
                case 1:
                    $sort = 'hot desc';
                    break;
                default:
                    $sort = 'recommend desc';
            }
            $channels = $this->createQuery()                       
                        ->where('province = ?',$province)
                        ->orderBy($sort)
                        ->offset(intval($offset))
                        ->limit(intval($limit))
                        ->execute(); 
            $memcache->set($memcache_key,$channels);            
        }
        return $channels;        	
    }
    
    /**
     * 取得用户本地、央视、卫视的频道列表，用户可以收到的频道
     * @param array $tv_station_ids
     * @param string $type
     * @return mixed
     */
    public function getReceiveChannels($city, $province='', $order=0) 
    {
        if(!$province) {
            return $this->getYangShiAndWeiShiChannels();
        }
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channels_Receive_'.$city.'_'.$province;	
        $channels = $memcache->get($memcache_key);
        if(!$channels){ 
             switch(intval($sort)) {
                case 1:
                    $sort = 'hot desc';
                    break;
                default:
                    $sort = 'recommend desc';
            }
            $channels = $this->createQuery()
                        ->where('type = ?','cctv')
                        ->orWhere('type = ?','tv')
                        ->orWhere('province = ?',$province)
                        ->orderBy("sort_id")
                        ->addOrderBy($sort)
                        ->execute();
            $memcache->set($memcache_key,$channels);
        }
        return  $channels;
    }
    
    /**
     * 根据城市名获取用户能收看到的频道 
     * @param <string> $city
     * @param <string> $province
     * @return <array>
     * @author pjl
     */
    public function getUserChannels($city, $province='', $sort=0, $offset=0, $limit = 100) 
    {
        //如果为空，只返回央视、卫视
        if(!$province) {
            return $this->getYangShiAndWeiShiChannels($offset, $limit, $sort);
        }
        
        //查询本地电视台ids
        $memcache = tvCache::getInstance();
        $memcache_key = "Channels_getUser729_".$province."_".$sort."_".$offset."_".$limit;
        $channels = $memcache->get($memcache_key);
        
        if(!$channels){
            switch(intval($sort)) {
                case 1:
                    $sort = 'hot desc';
                    break;
                default:
                    $sort = 'recommend desc';
            }
            $channels = $this->createQuery()
                            ->where('type = ?','cctv')
                            ->orWhere('type = ?','tv')
                            ->orWhere('province = ?',$province)
                            ->orderBy($sort)
                            ->offset(intval($offset))
                            ->limit(intval($limit))
                            ->execute();
           
            $memcache->set($memcache_key,$channels);
        }
        return $channels;
    }
    
    /**
     * 获取本地央视卫视
     * @param <type> $province
     * @return <type>
     * ly
     */    
    function getAllChannel($province, $order  = 0, $offset = 0, $limit = 100)
    {
        //如果为空，只返回央视、卫视
        if(!$province) {
            return $this->getYangShiAndWeiShiChannels($offset, $limit, $order);
        }
        
        //查询本地电视台ids
        $memcache = tvCache::getInstance();
        $md5 = md5($province);        
        $memcache_key = "Channels_getAllChannel_".$md5."_".$order."_".$offset."_".$limit;
        $channels = $memcache->get($memcache_key);
        if(!$channels){
            switch(intval($sort)) {
                case 1:
                    $sort = 'hot desc';
                    break;
                default:
                    $sort = 'recommend desc';
            }
            $channels = $this->createQuery()                            
                            ->select('id,name')
                            ->where('type = ?','cctv')
                            ->orWhere('type = ?','tv')
                            ->orWhere('province = ?',$province)
                            ->orderBy($sort)
                            ->offset(intval($offset))
                            ->limit(intval($limit))
                            ->execute();           
            $memcache->set($memcache_key,$channels);
        }
        return $channels;         
    }

    /**
     * 根据tv_station_id查询
     * @param <Array> $channel_ids
     * @return <Array>
     * @author ward
     * @final 2010-08-31 15:31
     * 2010-10-30，变量名形象化，参数增加 $offset, $limit 用以限制数量
     */
    public function findInTvStaionId($channel_ids, $offset = null, $limit = null)
    {    
        if(!is_array($channel_ids)) {
            return null;
        }
        $memcache = tvCache::getInstance();
        $memcache_key = "findInTvStaionId-".implode(",", $channel_ids);        
        $channels = $memcache->get($memcache_key);
        if(!$channels){
            $channels_query =   $this->createQuery()
                        ->whereIn('tv_station_id', $channel_ids)
                        ->andWhere('publish = ?', 1)
                        ->orderBy('sort_id');
            if (!is_null($offset) && !is_null($limit)) {
                $channels_query->offset($offset)->limit($limit);
            }
            $channels = $channels_query->execute();
            $memcache->set($memcache_key,$channels);
        }
        return $channels;
    }
    
    /**
    * 根据code 查询
    * @param array channelcode
    * @param int offset
    * @param int Limit
    * @return array
    * @author lizhi
    * @date 2011-7-19
    */
    public function findInCodes($channelcode, $offset = null, $limit = null) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = "findInCodes-".implode(",", $channelcode);	
        $channels = $memcache->get($memcache_key);
        if(!$channels){
            $channels_query = $this->createQuery()
                ->whereIn('code', $channelcode)
                ->andWhere('publish = ?', 1)
                ->orderBy('sort_id');
            if (!is_null($offset) && !is_null($limit)) {
                $channels_query->offset($offset)->limit($limit);
            }
            $channels = $channels_query->execute();
            $memcache->set($memcache_key,$channels);
        }
        return $channels;
    }
  
    /**
     * 根据type查询
     * @param <String> $type
     * @return <Array>
     * @author ward
     * @final 2010-08-31 15:31
     */
    public function findListByType($type, $offset = null, $limit = null) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = "Channel_findListByType-".$type."-".$offset."-".$limit;	
        $channels = $memcache->get($memcache_key);
        if(!$channels){
            $channels_query = $this->createQuery()
                    ->where('type = ?', $type)
                    ->andWhere('publish = ?', 1)
                    ->orderBy('sort_id');
            if (!is_null($offset) && !is_null($limit)) {
                $channels_query->offset($offset)->limit($limit);
            }
            $channels = $channels_query->execute();
            $memcache->set($memcache_key,$channels);
        }
        return $channels;
    }
    
   /**
    * 获取央视卫视
    * @param <type> $province
    * @return <type>
    * ly
    * @edit gaobo 20130709 add memcache
    */    
    function getAllChannelByTv($type=null, $offset=0, $limit = 100)
    {
        $memcache = tvCache::getInstance();
        if($type){
            $memKey = 'Channel_getAllByTv_'.$type.'_'.$offset.'_'.$limit;
            $channels = $memcache->get($memKey);
            if(!$channels){
                $channels = $this->createQuery("c")
                ->where('c.publish = 1')
                ->andWhere("c.type = '$type'")
                ->addWhere("c.autosyn = '1'")
                ->offset(intval($offset))
                ->limit(intval($limit))
                ->execute();
                $memcache->set($memKey,$channels,86400);
            }
        }else{
            $memKey = 'getAllChannelByTv';
            $channels = $memcache->get($memKey);
            if(!$channels){
                $channels = $this->createQuery("c")
                        ->where('c.publish = 1')
                        ->andWhere('c.type = "cctv" OR c.type = "tv"')
				        ->offset(intval($offset))
                        ->limit(intval($limit))
                        ->execute();
                $memcache->set($memKey,$channels,86400);
            }
        }

        return  $channels;
    }
    
	/**
    * 获取央视卫视
    * @param <type> $province
    * @return <type>
    * ly
    */    
    function getPublicChannel()
    {
         $channel    = $this->createQuery()
                    ->select('id,name')
                    ->Where('publish = ?', 1)
                    ->orWhere('type = ?', "tv")
                    ->orderBy('id')
                    ->execute();
        return  $channel;         
    }

    /**
     * 用 SQL LIKE 搜索频道
     * @param <string> $search_text
     * @param <integer> $offset
     * @param <integer> $limit
     * @return <object>
     * @author luren
     */
    public function search($search_text, $offset=0, $limit = 20) 
    {
        return $this->createQuery()
                    ->where('name LIKE ?', '%'.$search_text.'%')
                    ->offset(intval($offset))
                    ->limit(intval($limit))
                    ->execute();
    }

    /**
    * 获得所有的频道信息
    */
    public function getChannels() 
    {
        $channels = $this->createQuery()
                    ->Where('publish = ?', 1)
                    ->execute();        
        return $channels;         
    }
    
    /**
    * 获得所有待重新抓取的频道信息
    */
    public function getChannelsByTvsouGet() 
    {
        $channels = $this->createQuery()
                    ->Where('publish = ?', 1)
                    ->andWhere('tvsou_get = ?', 1)
                    ->execute();        
        return $channels;         
    } 
    
    /**
     * 返回所有本地频道和央视卫视频道 
     * @author lifucang
     * @date 2013-01-18
     */
    public function getLocalTvChannels($tv_station_id=114)
    {
        $tv_station_ids     = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station_id);
		$channels = $this->createQuery()
			->select('id,code,tv_station_id,name')
            ->Where('publish = 1')
            ->whereIn('tv_station_id', $tv_station_ids)
            ->orWhere('type = "cctv" OR type = "tv"')
			->execute();
        return $channels;
    }
    
	/**
     *
     * @desc 获取所有的节目的频道名称 
     * @param <type>
     * @return <array>  
     * @author tianzhongsheng
     * @date 2013-06-19 10：55：00
     */
    public function getAllChannelsNames()
    {
		$memcache = tvCache::getInstance();
        $memcache_key = 'Channel_getAllNames';
		$channleNames = $memcache->get($memcache_key);
        if(!$channleNames) {
        	$channels = $this->createQuery()
                ->select('code,name')
                ->execute();
			
			$channleNames = array();
			foreach($channels  as  $k =>$v) {				
				$channleNames[$v['code']] = $v['name'];
			}
			$memcache->set($memcache_key,$channleNames,86400); 
        }
        return $channleNames;
    }
    
    /**
     * 根据频道code查询频道
     * 
     * @param <type>
     * @return <array>  
     * @author superwen
     * @date 2013-06-19 10：55：00
     */
    public function findOneByCode($channel_code)
    {
        $memcache = tvCache::getInstance();
        $memcache_key = 'Channel_findOneByCode_'.$channel_code;
        $channle = $memcache->get($memcache_key);
        if(!$channle) {
           $channle = parent::findOneByCode($channel_code);
           $memcache->set($memcache_key,$channle);
        }
        return $channle;        
    }
}
