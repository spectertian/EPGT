<?php

/**
 * Repository of YesterdayProgram document.
 */
class YesterdayProgramRepository extends \BaseYesterdayProgramRepository
{
	/**
     * 根据日期获取日节目回顾的数据
     * @param  string $date(2013-06-03)
     * @return array
     * @author tianzhongsheng-ex@huan.tv
     * @since 2013-06-03 17:09:00
     */
    public function getDatePrograms($date)
    {
        return $this->find(
        			array(
						'query' => array('date' => $date,),
                        "sort" => array('sort' => 1,'updated_at' => -1)
						)
					);
    }
	
}