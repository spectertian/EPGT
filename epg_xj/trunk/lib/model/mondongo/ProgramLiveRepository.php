<?php

/**
 * Repository of ProgramLive document.
 */
class ProgramLiveRepository extends \BaseProgramLiveRepository
{
    /**
     * 删除过期数据
     * @param <type> $starttime  当前时间戳
     * @return <type>
     * @author wn
     */    
    public function getAllGuoQiPrograms($starttime) {
    	$m_starttime = new MongoDate($starttime);
        return $this->find(
                    array(
                        'query' => array(
                    		"end_time" =>array('$lte' => $m_starttime)
                            ),
                     )
                );
    } 
    /**
     * 根据codes获取当前正在播放的节目
     * @return <type>
     * @author wn
     */    
    public function getProgramsByCode($channel_codes) 
    {
        return $this->find(
                    array(
                        'query' => array(
                            "channel_code" => array('$in'=>$channel_codes)
                            )
                     )
                );
    }  
    /**
     * 根据code获取当前正在播放的节目
     * @return <type>
     * @author wn
     */    
    public function getProgramByCode($channel_codes) 
    {
        return $this->findOne(
                    array(
                        'query' => array(
                            "channel_code" =>$channel_codes
                            )
                     )
                );
    }
    /**
     * 根据wiki_id获取当前正在播放的节目
     * @return <type>
     * @author qhm
     */
    public function getProgramByWiki($wiki_id)
    {
    	return $this->findOne(
    			array(
    					'query' => array(
    							"wiki_id" =>$wiki_id
    					)
    			)
    	);
    } 
}