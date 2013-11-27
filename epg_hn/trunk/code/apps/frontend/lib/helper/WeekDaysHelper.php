<?php

    /**
     * 返回定制格式星期数据
     * @param <string> $date_format
     * @return <array>
     */
    function weekdays_nav($date_format = 'm-d') {
        $today_week = date('w');
        $today = time();
        $week_zh = array('一', '二', '三', '四', '五', '六', '日');

        if ($today_week == 0) $today_week = 7;
        $times = 60 * 60 * 24;
        $weeks = array();
        for ($i = 1; $i < 8; ++$i) {
            $n = $i - $today_week;
            $day = date($date_format, $today + $n * $times);
            $week = date('Y-m-d', $today + $n * $times);
            $day_week = $i%7 ? $i%7 : 7;
            $weeks[] = array(
                    'week' => $week,
                    'week_cn' => '周' . $week_zh[$day_week-1],
                    'date' => $day
                );
        }

        return $weeks;
    }

    /**
     * 返回中文星期
     * @param <type> $w
     */
    function weekdays_zh_cn($w) {
        
       $week = array('日','一','二','三','四','五','六');
       
       return $week[$w];
    }

?>
