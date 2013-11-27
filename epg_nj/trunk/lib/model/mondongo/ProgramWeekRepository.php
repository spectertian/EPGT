<?php

/**
 * Repository of ProgramWeek document.
 */
class ProgramWeekRepository extends \BaseProgramWeekRepository
{
    /**
     * 获取一天的节目列表
     * @author lifucang 2013-09-06
     */
    public function getDayPrograms($channel_code, $date) {
        $programs=$this->find(
                    array(
                        'query' => array(
                            'channel_code' => $channel_code,
                            'date' => $date
                        ),
                        'sort' => array('time' => 1)
                    )
        );
        return $programs;
    }
    /**
     * 删除一天的节目列表
     * @editor 2013-09-09
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
     * 统计某频道某一天是否有节目
     * @param <string> $channel_code,$date
     * @param <string> $date 2013-09-09
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
}