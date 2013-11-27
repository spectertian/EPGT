<script type="text/javascript">
$(document).ready(function(){
    $.datepicker.setDefaults($.datepicker.regional['zh_CN']);
    $('.datepicker').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0
    });

    $("#goToFilters").click(function(){
        var date = $(".datepicker").val();
        if(date == '请选择日期')
        {
            alert('请选择日期!');
            return true;
        }
        var url = '<?php echo url_for('program/index') ?>' + '?channel_id=<?php echo $sf_user->getAttribute('channel_id') ?>' +  '&date=' + date;
        window.location.href=url;
    });
});
</script>

<div class="widget">
  <div class="widget-body">
    <ul class="week-action">
          <?php
            $tmps = array();
            $times = 24 * 60 * 60;
            $channel_id = $sf_user->getAttribute('channel_id'); //debug
            $current_date = $sf_request->getParameter('date',( $sf_user->getAttribute('date') ? $sf_user->getAttribute('date') :date("Y-m-d",time()))) ;
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
                echo sprintf('<li><a href="%s" class="%s" link=\'{"channel_id":%s,"date":"%s"}\'>%s</a></li>',
                        url_for('@program').'?channel_id=' . $channel_id . '&date=' . $tmps[$i],  $current_date == $tmps[$i] ? 'active' : '', $channel_id, $tmps[$i], (($i != 0 && $i != 8 ) ? $tmps[$i] : '') . $weeks[$i]);
            }
      ?>

      <li>按选择日期查询：
          <input type="button" name="date" value="请选择日期" maxlengtjh="10" value="<?php echo ($sf_request->getParameter('date')) ? $sf_request->getParameter('date') : '请选择日期' ?>" class="datepicker">
          <input type="button" id="goToFilters" value="显示"></li>
    </ul>
    <div class="clear"></div>
  </div>
</div>