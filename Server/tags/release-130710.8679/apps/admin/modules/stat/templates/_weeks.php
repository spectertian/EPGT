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
        var sd = $("#sd").val();
        var ed = $("#ed").val();
        if(sd == '请选择起始日期')
        {
          alert('请选择起始日期!');
          return true;
        }else if(ed == '请选择结束日期'){
      	  alert('请选择结束日期!');
      	  return true;
        }

        if(sd>ed){
            alert('请正确选择起止日期！');
            return true;
        }
        admin_form = document.getElementById('adminForm');
        admin_form.action = "<?php echo url_for('stat/index')?>";
        admin_form.submit();
    });

    $("#goindex").click(function(){
  	  var url = "<?php echo url_for('stat/index') ?>";
    	window.location.href = url;
    })
});
</script>

<div class="widget">
  <div class="widget-body">
  <form action="#" id="adminForm" name="adminForm" method="post" >
    <ul class="week-action">
      <li>按选择日期查询：
          <input style='width:85px;margin-left:5px' type="text" name="startdate" maxlengtjh="10" value="<?php if($startdate) echo $startdate ;else echo '请选择起始日期'; ?>" id='sd' class="datepicker">
          <input style='width:85px;margin-left:5px' type="text" name="enddate" maxlengtjh="10" value="<?php if($enddate) echo $enddate ;else echo '请选择结束日期'; ?>" id='ed' class="datepicker">
          <input style='width:85px;margin-left:5px' type="button" id="goToFilters" value="显示">
          <input style='width:85px;margin-left:5px' type="button" id="goindex" value="返回查看今天">
      </li>
    </ul>
  </form>
    <div class="clear"></div>
  </div>
</div>