
<div class="widget">
  <div class="widget-body">
    <ul class="week-action">
          <?php
            $tmps = array();
            $times = 24 * 60 * 60;
            $current_date = $sf_request->getParameter('date',( $sf_user->getAttribute('date') ? $sf_user->getAttribute('date') :date("Y-m-d",time()))) ;
            $startTime = $sf_request->getParameter('startTime',( $sf_user->getAttribute('startTime') ? $sf_user->getAttribute('startTime') :'')) ;
            $EndTime = $sf_request->getParameter('EndTime',( $sf_user->getAttribute('EndTime') ? $sf_user->getAttribute('EndTime') :'')) ;
            $w = date('w', strtotime($current_date));
            if ($w == 0) $w = 7;
            $s = strtotime($current_date);
            $weeks = array('上一周', '(一)', '(二)', '(三)', '(四)', '(五)', '(六)', '(天)', '下一周');
            for ($i = 0; $i <= 8; ++$i) {
                if ($i == 0) {
                    $n = $s - (6 + $w) * $times;
                } else if ($i == $w) {
                    $n = $s;
                } else {
                    $n = $s + ($i - $w) * $times;
                }
                $tmps[$i] = date('Y-m-d', $n);
                echo sprintf('<li><a href="%s" class="%s">%s</a></li>',
                        '/program_rec?date=' . $tmps[$i],  $current_date == $tmps[$i] ? 'active' : '',  (($i != 0 && $i != 8 ) ? $tmps[$i] : '') . $weeks[$i]);
            }
		?>
    </ul>
    <div class="clear"></div>
  </div>
</div>