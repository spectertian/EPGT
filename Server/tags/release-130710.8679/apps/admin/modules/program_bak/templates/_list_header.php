<div id="submenu-box">
    <div class="t"><div class="t"><div class="t"></div></div></div>
    <div class="m">
        <div class="submenu-box">
            <div class="submenu-pad">
                <ul id="submenu" class="configuration week-action">
                    <?php
                    $tmps = array();
                    $times = 24 * 60 * 60;
                    $datas = $sf_user->getAttribute('datas');
                    $w = $datas['week'];
                    if ($w == 0) $w = 7;
                    $s = strtotime($datas['date']);
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
                                url_for('@program').'?channel_id=' . $datas['channel_id'] . '&date=' . $tmps[$i],  $datas['date'] == $tmps[$i] ? 'active' : '', $datas['channel_id'], $tmps[$i], (($i != 0 && $i != 8 ) ? $tmps[$i] : '') . $weeks[$i]);
                    }
                    ?>
                    <!--<li><a href="#" class="">上一周</a></li>
                    <li><a href="#" class="">(一)</a></li>
                    <li><a href="#" class="">(二)</a></li>
                    <li><a href="#" class="">(三)</a></li>
                    <li><a href="#" class="">(四)</a></li>
                    <li><a href="#" class="">(五)</a></li>
                    <li><a href="#" class="">(六)</a></li>
                    <li><a href="#" class="">(天)</a></li>
                    <li><a href="#" class="">下一周</a></li>-->
                    <li>&nbsp;&nbsp;按选择日期查询&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="date" class="datepicker" maxlengtjh="10" value="请选择日期"/></li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="" value="显示" id="goToFilters" onclick="goLink();"/></li>
                </ul>
                <div class="clr"></div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="b"><div class="b"><div class="b"></div></div></div>
</div>
<div id="dialog-form" style="display: none">
    <div id="dialog-form-index">
        <ul></ul>
    </div>
    <div id="dialog-form-template" style="display: none">
        <ul></ul>
    </div>
    <div id="dialog-form-action" style="display: none">
        <input type="button" value="返回" onclick="$(this).hide();$('#dialog-form-template').hide();$('#dialog-form-index').show();" />
    </div>
</div>
<script type="text/javascript">
    (function($){
        $(function(){
            var channel_id = $('#program_filters_channel_id');
            var date_from = $('#program_filters_date_from');
            var date_to = $('#program_filters_date_to');
            var form = channel_id.parent();
            $('ul.week-action li a').click(function(evt){
                var rel = $.parseJSON($(this).attr('rel'));
                date_from.val(rel.date);
                date_to.val(rel.date);
                channel_id.val(rel.channel_id);
                form.submit();
                evt.preventDefault();
            });


            $.datepicker.setDefaults($.datepicker.regional['zh_CN']);
            $('.datepicker').datepicker({
                //			changeMonth: true,
                //			changeYear: true
                showButtonPanel: true,
                showOtherMonths: true,
                selectOtherMonths: true,
                dateFormat: 'yy-mm-dd',
                showWeek: true,
                firstDay: 1
            });

            $('#goToFilters').click(function(){
                var v = $('input[name=date]').val();
                date_from.val(v);
                date_to.val(v);
                channel_id.val(<?php echo $datas['channel_id'] ?>);
                form.submit();
            });

        });
    })(jQuery);

    function goLink()
    {
        var d = new Date();
        var today = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDay();
        var url = $(".datepicker").val();

        if(isNaN(parseInt(url)))
        {
          url = today;
        }
        
        window.location.href    = "program?date=" + url;
    }
</script>