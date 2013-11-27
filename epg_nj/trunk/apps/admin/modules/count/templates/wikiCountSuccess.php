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
                 <li><b>新增wiki数：</b><?php echo $nums['wikiNum'];?></li>
                 <li><b>敏感词数量：</b><?php echo $nums['wordNum'];?></li>
              </ul>
    		</div>
          </div>
        </div> 
    </div>
  </div>