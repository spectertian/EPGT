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
                 <li><b>发送上线数量：</b><?php echo $nums['onlineNum'];?></li>
                 <li><b>实际上线数量：</b><?php echo $nums['onlineNum1'];?></li>
                 <li><b>发送下线数量：</b><?php echo $nums['offlineNum'];?></li>
                 <li><b>影片库数量（总）：</b><br />电影：<?php echo $nums['filmNum'];?><br />电视剧：<?php echo $nums['teleplayNum'];?><br />栏目：<?php echo $nums['televisionNum'];?></li>
              </ul>
    		</div>
          </div>
        </div> 
    </div>
  </div>
  