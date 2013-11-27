<script>
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
        defaultDate: +0,
        model:false
    });
    $('.fk_').each(function(){
    	$(this).click(function(){
        	if($.trim($(this).val())=='请输入时分'){
    	  	  $(this).val('');
          }
  	  });
    	$(this).blur(function(){
  		  if($.trim($(this).val())==''){
    			$(this).val('请输入时分');
  	    }
      });
    });
});
</script>
    <div id="content">
        <div class="content_inner">
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
            

              <div class="clear"></div>
            </div>
    <form method='post' action='/imgcheck/programs'>
           <b style='color:green'>开始:</b><input type="input" name="start_time"  maxlengtjh="10" value="<?php echo isset($start_time)?$start_time:'请选择开始日期'; ?>" class="datepicker"><input class='fk_' type='text' name='start_hi' value='<?php echo isset($start_hi)?$start_hi:'请输入时分'; ?>'>
            &nbsp;&nbsp;<b style='color:green'>结束:</b><input type="input" name="end_time" maxlengtjh="10" value="<?php echo isset($end_time)?$end_time:'请选择结束日期'; ?>" class="datepicker"><input class='fk_' type='text' name='end_hi' value='<?php echo isset($end_hi)?$end_hi:'请输入时分'; ?>'>
       &nbsp;&nbsp;<input type='submit' value='查询'><small style='color:#666'>(因数据量大，请编辑尽量查询一天内的数据)</small>
    </form>        
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_model" style='width:23%'>名称</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_model" style='width:23%'>名称</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($programscoverno)):?>
                  <?php 
                  foreach ($programscoverno as $i => $rs):?>
                            <tr>
                              <td><a href="<?php echo '/wiki/edit?id='.$rs['wiki_id'];?>"><?php echo $rs['name'];?></a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
                
              </tbody>
            </table>

            <div class="clear"></div>
        </div>
      </div>