<?php


class ChannelTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Channel');
    }
    /**
     * 返回所有卫视频道 
     * @author wangnan
     */
    public function getWeiShiChannels() 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = md5('getWeiShiChannels');
        $channels = $memcache->get($memcache_key);
        if(!$channels){    
    		$channels = $this->createQuery("c")
    			->where('c.type = ?','tv')
    			->addOrderBy('c.hot = -1')
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
        return $this->createQuery()->whereIn('tv_station_id',$tvStation_ids)->execute()->toArray();
    }
    /**
     * 根据 频道名称获取code
     * @param <Array> $tvStation_ids
     * @return Array
     */
    public function getNameByCode($name)
    {
        return $this->findOneByName($name)->getCode();
    }
    //设置发布状态
    public static function setStatus($id,$is_show=1)
    {
        $msg    = '发布';
        $rs = Doctrine::getTable('Channel')-> createQuery()
                -> update('Channel')
                -> set('publish=?', $is_show)
                -> whereIn('id', $id)
                -> execute();
        if($is_show)    $msg = '隐藏';
        return array('code'=>1, 'msg'=>$msg .'成功');
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
        $return = array('code'=>0, 'msg'=>'位置错误');
        if(is_numeric($id))
        {
            $tem    = Doctrine::getTable('Channel')->findOneById($id);

            if($tem)
            {
                $tem->set($name, $value);
                $tem->save();
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
     * 根据 $tv_station_id 取得已发布 channel 列表
     * @param int $tv_station_id
     * @return  Array
     */
    public function getChannlesWithTvStation($tv_station_id) {
        return $this->createQuery()
                ->where('publish = ?', 1)
                ->andWhere('tv_station_id = ?', $tv_station_id)
                ->orderBy('sort_id')
                ->execute();
    }

    /**
     * 取得用户本地、央视、卫视的频道列表，用户可以收到的频道
     * @param array $tv_station_ids
     * @param string $type
     * @return mixed
     */
    public function getReceiveChannels($city, $provice='') {

        //央视
        $cctv_channel_ids   = $this->getCctvChannelIds();
        //卫视
        $channel_tv_ids = $this->findListByType('tv');
        foreach ($channel_tv_ids as $rs) {
            $cctv_channel_ids[]   = $rs->getId();
        }
        //本地
        $local_ids      = $this->getLocalChannelIds($city, $provice);
        $channel_ids    = array_merge($cctv_channel_ids, $local_ids);

        $channel    = $this->createQuery()
                    ->where('publish= ?', 1)
                    ->whereIn('id', $channel_ids)
                    ->orderBy("sort_id")
                    //->orderBy(sprintf('FIND_IN_SET(%s, "%s")', 'id', implode(',', $channel_ids)))
                    //->orderBy(sprintf('SUBSTRING_INDEX("%s", %s, 1)', implode(',', $channel_ids), 'id'))
                    ->execute();

        
        //$channel    = usort($channel, array("Common", "ObjectArraySort"));//Common::ObjectArraySort($channel_ids, $channel);
        return  $channel;
//        if ($channel) {
//            foreach ($channel as $rs) {
//                $channel_ids[]  = $rs->getId();
//            }
//        }
//
//        $idss[] = 1;
//
//        $ids = array_unique($idss);
//
//        natsort($ids);
//
//        return $this->createQuery()
//                ->where('publish = ?', 1)
//                ->andWhereIn('tv_station_id', $ids)
//                ->orderBy('sort_id')
//                ->execute();
    }

    /**
     * 根据tv_station_id查询
     * @param <Array> $channel_ids
     * @return <Array>
     * @author ward
     * @final 2010-08-31 15:31
     * 2010-10-30，变量名形象化，参数增加 $offset, $limit 用以限制数量
     */
    public function findInTvStaionId($channel_ids, $offset = null, $limit = null){
        $memcache = tvCache::getInstance();
        $memcache_key = md5(implode(",", $channel_ids));
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
    public function findInCodes($channelcode, $offset = null, $limit = null) {
        $memcache = tvCache::getInstance();
        $memcache_key = md5(implode(",", $channelcode));
        $channels = $memcache->get($memcache_key);
        if(!$channels){
            $channels_query =   $this->createQuery()
                ->whereIn('code', $channelcode)
                ->andWhere('publish = ?', 1)
                ->orderBy('sort_id');
            if (!is_null($offset) && !is_null($limit)) {
                $channels_query->offset($offset)->limit($limit);
            }
            //var_dump($channels_query);
            $channels = $channels_query->execute();
            $memcache->set($memcache_key,$channels);
        }
        return $channels;
    }

    /**
     * 根据tv_station_id查询数量
     * @param <Array> $channel_ids
     * @return <Int>
     * @author ward
     * @date    2010-10-31
     */
    public function total_find_in_tv_staion_id($channel_ids){
        $count_query =   $this->createQuery()
//            ->select('id,name')
            ->whereIn('tv_station_id', $channel_ids)
            ->andWhere('publish = ?', 1)
            ->count();
        return $count_query;
    }
    
     /**
     * 根据type查询
     * @param <String> $type
     * @return <Array>
     * @author ward
     * @final 2010-08-31 15:31
     */
    public function findListByType($type, $offset = null, $limit = null) {
        $memcache = tvCache::getInstance();
        $memcache_key = md5("type".$type);
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
     * 统计 根据type查询 记录数
     * @param <String> $type
     * @return <Array>
     * @author ward
     * @final 2010-10-31 15:31
     */
    public function total_find_list_by_type($type) {
        $channels_query = $this->createQuery()
//        ->select('id,name')
        ->where('type = ?', $type)
        ->andWhere('publish = ?', 1)
        ->count();
        return $channels_query;
    }


    /**
     * 获取本地频道ID
     * @param <String> $city
     * @param <String> $provice
     * @return <Array>
     * @author ward
     * @date    2010-11-08
     */
    public function getLocalChannelIds($city, $provice='') {
        $ids        = array();
        /**
         *只显示央视及卫视，去掉本地频道
         */
        if (!empty($provice)) {
            $md5            = array(md5($provice), md5($city));
        }else{
            $md5            = array(md5($city));
        }
        $tv_station_ids     = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
        

        //获取channel_id
        $tv_station_ids = $this->createQuery()->select('id')->whereIn('tv_station_id', $tv_station_ids)->execute();
        if ($tv_station_ids) {
            foreach ($tv_station_ids as $rss) {
                $ids[] = $rss->getId();
            }
        }
        return $ids;
    }

    /**
     * 获取央视频道
     * @return <Array>
     * @author ward
     * @date 2010-11-09
     */
    public function getCctvChannelIds() {
        $channel_ids    = array();
        $channel        = $this->findBy('tv_station_id', 1);
        if ($channel) {
            foreach ($channel as $rs) {
                $channel_ids[]  = $rs->getId();
            }
        }
        return $channel_ids;
    }

    /**
     * 根据城市名获取用户能收看到的频道 
     * @param <string> $city
     * @param <string> $provice
     * @return <array>
     * @author pjl
     */
    public function getUserChannels($city, $provice='',$order='') {
        //查询本地电视台ids
        $memcache = tvCache::getInstance();
        if ($provice) {
            $md5 = array(md5($provice), md5($city));
        }else{
            $md5 = array(md5($city));
        }
        $memcache_key = md5(implode(",", $md5));
        $channels = $memcache->get($memcache_key);
        if(!$channels){
            $tv_station_ids = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
            //查询央视, 卫视, 本地频道 publish = 1
            if($order!='') {
                $channels = $this->createQuery("c")
                            ->where('c.publish = 1')
                            ->andWhere('c.type = "cctv" OR c.type = "tv" OR c.tv_station_id IN ('.implode(",",$tv_station_ids).')')
                            ->addOrderBy('c.hot DESC')
                            ->execute(); 
            }else{
                $channels = $this->createQuery("c")
                            ->where('c.publish = 1')
                            ->andWhere('c.type = "cctv" OR c.type = "tv" OR c.tv_station_id IN ('.implode(",",$tv_station_ids).')')
                            ->execute(); 
            }
            $memcache->set($memcache_key,$channels);
        }
        
        return $channels;
    }


   /**
    * 获取本地央视卫视
    * @param <type> $provice
    * @return <type>
    * ly
    */
    
    function getAllChannel($provice){
         $tv_station = Doctrine::getTable('TvStation')->findOneByCode(md5($provice));
         $local_channel_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station->getId());
         $channel    = $this->createQuery()
                    ->select('id,name')
                    ->Where('publish = ?', 1)
                    ->whereIn('tv_station_id', $local_channel_ids)
                    ->orWhere('type = ?', "tv")
                    ->orWhere('tv_station_id = ?', 1)
                    ->orderBy('id')
                    ->execute();
        return  $channel;
         
    }
   /**
    * 获取央视卫视
    * @param <type> $provice
    * @return <type>
    * ly
    */
    
    function getAllChannelByTv($type=null){
        if($type){
            $channels = $this->createQuery("c")
                        ->where('c.publish = 1')
                        ->andWhere("c.type = '$type'")
                        ->execute(); 
        }else{
            $channels = $this->createQuery("c")
                        ->where('c.publish = 1')
                        ->andWhere('c.type = "cctv" OR c.type = "tv"')
                        ->execute(); 
        }

        return  $channels;
    }
	/**
    * 获取央视卫视
    * @param <type> $provice
    * @return <type>
    * ly
    */
    
    function getPublicChannel(){
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
    public function search($search_text, $offset=0, $limit = 20) {
        return $this->createQuery()
                    ->where('name LIKE ?', '%'.$search_text.'%')
                    ->offset(intval($offset))
                    ->limit(intval($limit))
                    ->execute();
    }

    /**
     *
     * @param <type> $channel_oce
     * @author ly
     * date 2011-05-25
     */
//    public function getChannelByCode($channel_code){
//        $memcache = tvCache::getInstance();
//        $channel = $memcache->get($channel_code);
//        if(!$channel){
//            $channel = $this->findOneByCode($channel_code);
//            $memcache->set($channel_code, $channel);
//        }
//        return $channel;
//    }
    /**
    * 获得所有的频道信息
    */
    public function getChannels($order='hot desc') {    
        //$memcache = tvCache::getInstance();
        //$memcache_key = md5('getChannels');
        //$channels = $memcache->get($memcache_key);
        //if(!$channels){    
    		$channels = $this->createQuery()
                    ->Where('publish = ?', 1)
                    ->orderBy($order)
                    ->execute();
            //$memcache->set($memcache_key,$channels);   
        //}    
        return $channels;                    
    }
    /**
     * 返回所有央视频道 
     * @author lifucang
     */
    public function getCCTVChannelsGd() 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = md5('getCCTVChannelsGd');
        $channels = $memcache->get($memcache_key);
        if(!$channels){    
    		$channels = $this->createQuery()
                ->select('id,code,tv_station_id,name')
    			->where('type = ?','cctv')
                ->andWhere('publish = 1')
    			->execute();
            $memcache->set($memcache_key,$channels);   
        }    
        return $channels;
    }   
    /**
     * 返回所有省外频道 
     * @author lifucang
     */
    public function getProvinceTvChannelsGd($tv_station_id=114) 
    {
        $memcache = tvCache::getInstance();
        $memcache_key = md5('getProvinceTvChannelsGd');
        $channels = $memcache->get($memcache_key);
        if(!$channels){    
    		$channels = $this->createQuery()
                ->select('id,code,tv_station_id,name')
    			->where('type = ?','tv')
                ->andWhere('tv_station_id <> ?',$tv_station_id)
                ->andWhere('publish = 1')
    			->execute();
            $memcache->set($memcache_key,$channels);   
        }    
        return $channels;
    }    
    /**
     * 返回所有本地频道 
     * @author lifucang
     */
    public function getLocalChannelsGd($tv_station_id=114)
    {
        $memcache = tvCache::getInstance();
        $memcache_key = md5('getLocalChannelsGd');
        $channels = $memcache->get($memcache_key);
        if(!$channels){
            $tv_station_ids     = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($tv_station_id);
    		$channels = $this->createQuery()
    			->select('id,code,tv_station_id,name')
                ->Where('publish = 1')
                ->whereIn('tv_station_id', $tv_station_ids)
    			->execute();
            $memcache->set($memcache_key,$channels);   
        }    
        return $channels;
    }       
}
