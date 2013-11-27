<?php
    function weekdays_nav($date_format = 'n/j') {
        $df = $date_format;
//        $today = date($df);
        $today_week = date('w');
//        $today_week = 0;
        $today = time();
//        $today = strtotime('2010-09-05');
        $week_zh = array('一', '二', '三', '四', '五', '六', '日');
        
        if ($today_week == 0) $today_week = 7;

        $html = array('<ul id="weekday">');
        $times = 60 * 60 * 24;
        for ($i = 1; $i < 15; ++$i) {
            $n = $i - $today_week;
            $day = date($df, $today + $n * $times);
            $day_1 = date('Y-m-d', $today + $n * $times);
            $day_week = $i%7 ? $i%7 : 7;
//            echo $day_week, '--', $week_zh[$day_week-1], '--', $n, '--', $day, "\n";
            $html[] = sprintf('<li id="week_%s" rel="%s" class="action %s"><span>周%s <small>%s</small></span></li>', $day_week, $day_1, ($n ? '' : 'active'), $week_zh[$day_week-1], $day);
        }

        $html[] = '</ul>';

        return join($html, '');
    }
?>
