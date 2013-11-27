<script type="text/javascript">
$(document).ready(function(){
    $.datepicker.setDefaults($.datepicker.regional['zh_CN']);
    $('.datepicker').datepicker({
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });
});
</script>
  <div id="content">
    <div class="content_inner">
        <?php include_partial('toolbarList',array("pageTitle"=>$pageTitle))?>
        <div class="table_nav">
            <form method="get" action="">
                日期：<input type="text" name="date1" value="<?php echo $date1?>"  class="datepicker"/>到<input type="text" name="date2" value="<?php echo $date2?>"   class="datepicker"/>
                 <input type="submit" value="统计">
            </form>
        </div>
        <div style="float:left; width:100%">
          <div class="widget">
            <h3><?php echo $pageTitle;?></h3>
    		<div class="widget-body">
    		  <ul class="wiki-meta">
                 <li><b>ADI总数：</b><br /><?php echo $nums['injectNum'];?></li>
                 <li><b>ADI删除总数：</b><br /><?php echo $nums['injectDelNum'];?></li>
                 <!--
                 <li><b>ADI解析错误数：</b><br /><?php echo $nums['injectNum4'];?></li>
                 <li><b>ADI未知类型数：</b><br /><?php echo $nums['injectNum3'];?></li>
                 <li><b>ADI未设置类型数：</b><br /><?php echo $nums['injectNum1'];?></li>
                 <li><b>ADI无点播信息数：</b><br /><?php echo $nums['injectNum2'];?></li>
                 -->
                 <li><b>入库总数：</b><br /><?php echo $nums['importNum'];?></li>
                 <li><b>匹配wiki总数：</b><br /><?php echo $nums['importWikiNum'];?></li>
              </ul>
    		</div>
          </div>
        </div> 
    </div>
  </div>
  