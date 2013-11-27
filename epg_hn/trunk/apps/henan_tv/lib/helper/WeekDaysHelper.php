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

    function showWeek() {
        $today = getdate();
        $tw = $today['wday'];
        $secs = time();
        $tw = $tw ? $tw : 7;
        $times = 60 * 60 * 24;
        $html = array();
        for ($i = 0; $i < 7; ++$i) {
            $day = date('Y-m-d', $secs + $i * $times);
            $d = date('d', $secs + $i * $times);
            $day_zh = date('m月d日', $secs + $i * $times);
            $day_zh = $i ? $day_zh : $day_zh . ' <small>（今天）</small>';
            $html[] = sprintf('<span class="action day%s" title="%s" name="%s">%s</span>', $i ? '' : ' current', $day, $day_zh, $d);
        }
        return join($html, '');
    }

    function timeDiff($start, $end) {
        $str = '';
        if(!isset($end)) {
            $end = time();
        }
        $times = $end - $start;
        $hours = floor($times/3600);
        $mints = floor(($times - 3600 * $hours)/60);
        if ($hours) {
            $str .= $hours . '小时';
        }
        $str .= $mints . '分钟';
        return $str;
    }

    function tcl_week() {
        $html = array('<ul id="weekday">');
        $today_week = date('w');
        $secs = time();
        $week_zh = array('一', '二', '三', '四', '五', '六', '日');

        if ($today_week == 0) $today_week = 7;
        
        $times = 60 * 60 * 24;
        for ($i = 1; $i <= 7; ++$i) {
            $n = $i - $today_week;
            $day = date('m/d', $secs + $n * $times);
            $fday = date('Y-m-d', $secs + $n * $times);
            $day_week = $i%7 ? $i%7 : 7;
            $html[] = sprintf('<li class="action%s" rel="%s">星期%s %s</li>', $n ? '' : ' actived',  $fday,$week_zh[$day_week - 1], $day);
        }

         $html[] = '</ul>';

        return join($html, '');
    }
?>
