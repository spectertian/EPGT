<?php

/**
 * Repository of Cpg document.
 */
class CpgRepository extends \BaseCpgRepository
{
  /**
   * 获取一天的回看节目列表
   * @param <string> $channel_code
   * @param <string> $date e: 2010-11-11
   * @return <array>
   * @author pjl
   */
  public function getCpgDayPrograms($channel_code, $date) {
    return $this->find(
        array(
            'query' => array(
                'channel_code' => $channel_code,
                'date' => $date
            ),
            'sort' => array('start_time' => 1)
        )
    );
  
  }
    /**
     * 获取一天的节目列表
     * @param <string> $channel_code
     * @param <string> $date e: 2010-11-11
     * @return <array>
     * @author pjl
     */
    public function getDayPrograms($channel_code, $date) {
        return $this->find(
                    array(
                        'query' => array(
                            'channel_code' => $channel_code,
                            'date' => $date
                        ),
                        'sort' => array('start_time' => 1)
                    )
                );

    }
    
    /**
     * 统计某频道某一天是否有回看节目
     * @param <string> $channel_code,$date
     * @param <string> $date 2013-04-18
     * @author lifucang
     */
    public function countDayPrograms($channel_code, $date) {
        return $this->count(
                    array(
                            'channel_code' => $channel_code,
                            'date' => $date
                        )
                );
    }   
}