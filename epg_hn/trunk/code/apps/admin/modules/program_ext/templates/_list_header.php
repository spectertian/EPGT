<div id="submenu-box">
    <div class="t"><div class="t"><div class="t"></div></div></div>
    <div class="m">
        <div class="submenu-box">
            <div class="submenu-pad">
                <ul class="configuration week-action" id="submenu">
                    <li>开始时间：<input type="text" id="date_from" class="datepicker" value="<?php echo $sf_user->getAttribute('admin_date_from');?>" /></li>
                    <li>结束时间：<input type="text" id="date_to" class="datepicker" value="<?php echo $sf_user->getAttribute('admin_date_to');?>" /></li>
                    <li>类型：<select id="style" name="style">
                        <option value=""></option>
                        <option value="new" id="admin_new">新节目</option>
                        <option value="hot" id="admin_hot">热播</option>
                        <option value="top" id="admin_top">推荐</option>
                        </select>
                    </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="goLink();" id="goToFilters" value="查询" name=""></li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="goLinkNull();" id="goToFilters" value="全部" name=""></li>
                </ul>
                <div class="clr"></div>
            </div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="b"><div class="b"><div class="b"></div></div></div>
</div>
<script type="text/javascript">
(function($){
        $(function(){
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
        });
    })(jQuery);
    
    function goLink(){
        var date_from   = $("#date_from").val();
        var date_to   = $("#date_to").val();
        var style   = $("#style").val();
        window.location.href = 'program_ext?date_from=' +date_from + "&date_to="+date_to + "&style="+style;
    }

    function goLinkNull(){
        window.location.href = 'program_ext';
    }
    $(document).ready(function(){
        $("#admin_<?php echo $sf_user->getAttribute('admin_style');?>").attr('selected', 'selected');
    });
    
</script>