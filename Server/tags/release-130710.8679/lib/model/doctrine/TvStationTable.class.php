<?php


class TvStationTable extends Doctrine_Table
{
    public static function getInstance()
    {
        return Doctrine_Core::getTable('TvStation');
    }

    /**
     * 根据省名获取此省份下所有电视台
     * @param <type> $province
     */
    public function getByProvince($province) 
    {
        $province_code = md5($province);
        $province_station = $this->findOneByCode($province_code);
        if ($province_station) {
            $child_station = $this->getChannelByTvStationId($province_station->getId());
            $station_data = $child_station->getData();
            array_unshift($station_data, $province_station);
            return $station_data;
        } else {
            return false;
        }
    }

    /**
     * @author huang
     * @param <Int> $parent_id
     * @return <Array>
     */
    public function getTvStationIdsByParentId($parent_id = 0)
    {
        $tvStations = $this->createQuery()
            ->where("parent_id = ?",$parent_id)
            ->orWhere("id = ?",$parent_id)
            ->execute();
            
        $tvStation_ids = array();
        if($tvStations->count() > 0) {
            foreach($tvStations as $tvStation) {
                $tvStation_ids[] = $tvStation->getId();
            }
        }else{
            $tvStation_ids[] = $parent_id;
        }
        return $tvStation_ids;
    }

    public function getParentTvStationId($child_id)
    {
        $tv_station = $this->findOneById($child_id);
        $parent_id = $tv_station->getParentId();
        if ( $parent_id > 0 ) {
            $parent_id = $this->getParentTvStationId($parent_id);
        }else{
            $parent_id = $tv_station->getId();
        }
        return $parent_id;
    }

    /**
     * @author ward
     * @param <Int> $parent_id
     * @return <Array>
     * @final 2010-09-09 11:33
     */
    public function  getParentArray($parent_id = 0) 
    {
        $return = array();
        $tv = $this->createQuery()->select('id,name')
            ->where('parent_id = ?', 0)
            ->orderBy('sort asc ,id asc')
            ->execute();
        if ($tv) {
            foreach ($tv as $rs) {
                $return[$rs->getId()]   = $rs->getName();
            }
        }
        return $return;
    }
    
    /**
     * @author ward
     * @param <Int> $parent_id
     * @return <Array>
     * @final 2010-09-09 11:33
     */
    public function  getChildArray() 
    {
        $return = array();
        $tv = $this->createQuery()->select('id,name')
            ->where('parent_id > ?', 0)
            ->orderBy('sort asc ,id asc')
            ->execute();
        if ($tv) {
            foreach ($tv as $rs) {
                $return[$rs->getId()]   = $rs->getName();
            }
        }
        return $return;
    }

    public static function update_data($id,$key,$value) {
        $rs = Doctrine::getTable('TvStation')->findOneById($id);
        $rs->set($key, $value);
        $rs->save();
        return array('code' =>1 ,'msg' => '修改成功');
    }

//    public function getParentTvStationAllIds($code) {
//        $top_tv_station = $this->findOneByCode($code);  //查询当前省级电视台
//        return $this->getChildTvStationIds($top_tv_station->getId());
//    }
//    //递归获取N级电视台,待完善
//    public function getChildTvStationIds($id) {
//        $tv_stations = $this->createQuery()->where('parent_id = ?',$id)->execute();
//        $tv_station_ids = array();
//        foreach ( $tv_stations as $tv_station ) {
//            $child_tv_stations = $this->findByParentId( $tv_station->getId() );
//            if($child_tv_stations->count() > 0 )
//            {
//                $tv_station_ids[] = $tv_station->getId();
//                $tv_station_ids = array_merge($tv_station_ids,$this->getChildTvStationIds($tv_station->getId()));
//            }else{
//                $tv_station_ids[] = $tv_station->getId();
//            }
//        }
//        return $tv_station_ids;
//    }

    /**
     * 根据城市md5返回可授权访问电视频道tv_station_id
     * @param <type> $code
     * @return <type>
     */
    public function get_tv_station_id_by_md5($md5 ='e94e8bd35fc8144f38fd1ebc1f81ab36') 
    {
        $arr = array();
        if (empty($md5)) {
            return '';
        }
        $ids = $this->createQuery()
            ->select('id')
            ->whereIn('code' ,$md5)
            ->execute();
            
        if ($ids) {
            foreach ($ids as $rs){
                $arr[]  = $rs->getId();
                if ($rs->getParentId() != 0) {
                    $arr[]  = $rs->getParentId();
                }
            }
            $arr    = array_unique($arr);
        }
        return $arr;
    }

    /*
     * 根据md5查询tv_station_id
     * @auth ward
     */
    public function getTvStationsByCode($code)
    {
        $ids = array();
        $tv_stations = Doctrine::getTable('TvStation')
            ->createQuery('t')
            ->where('code = ?', md5($code))
            ->execute();
        return  $tv_stations;
    }
    
    /**
     * 根据tv_station_id获取子类id数组
     * @param <Int> $tv_station_id
     * @return array
     * @author ward
     */
    public function getChannelByTvStationId($tv_station_id) 
    {
        $return = array();
        if(!is_numeric($tv_station_id)) {
            return $return;
        }
        $tv = $this->createQuery()
            ->where('parent_id = ?', $tv_station_id)
            ->orderBy('sort asc ,id asc')
            ->execute();
        if (!$tv) {
            return $return;
        } else {
            return $tv;
        }
    }
}