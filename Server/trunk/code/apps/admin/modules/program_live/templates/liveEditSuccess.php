<script type="text/javascript">
$(document).ready(function(){
	$("#button_cctv").click(function(){
		$(this).css({"font-weight":"bold" , "font-size":"15px"});
		$("#button_tv").css({"font-weight":"" , "font-size":""});
		$("#data_cctv").show();
		$("#data_tv").hide();
	});
	$("#button_tv").click(function(){
		$(this).css({"font-weight":"bold","font-size":"15px"});
		$("#button_cctv").css({"font-weight":""  ,  "font-size":""});
		$("#data_tv").show();
		$("#data_cctv").hide();
	});
	/*$("#save_data").live('click',function(){
		var arrone = new Array();
		var arrtwo = new Array();
		var y = -1;
		$(".cctvids").each(function(i){
			if(this.checked)
			{
				y+=1;
				arrone[y]=$(this).val();
			}
		});
		var x = -1;
		$(".tvids").each(function(i){
			if(this.checked)
			{
				x+=1;
				arrtwo[x]=$(this).val();
			}
		});		
		alert(arrone);
		alert(arrtwo);
		return false;
	});*/
})
//全选
function checkAll(object,id)
{
    var flag    = object.checked;
    var box     = $("#"+id+" input[type=checkbox]");
    if (flag) {
        box.attr('checked',true);
    }else{
        box.attr('checked',false);
    }
}
function submitform(action){
    if (action) {
        document.adminForm.batch_action.value=action;
    }
    if (typeof document.adminForm.onsubmit == "function") {
        document.adminForm.onsubmit();
    }
    document.adminForm.submit();
}

function Publish(publish)
{
    $("#publish_off").val(publish);
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('theme/publish')?>";
    admin_form.submit();
}
</script>
<div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarList')?>
            <div class="table_nav">              
              <div class="clear"></div>
            </div>
            <span id="button_cctv" style="font-weight:bold;font-size:15px;cursor:pointer;">央视频道</span> | <span id="button_tv" style="cursor:pointer;">卫视频道</span>
			<?php include_partial('global/flashes')?>
            <form action="<?php echo url_for("program_live/liveEdit")?>" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0" id="data_cctv" style="">
              <thead>
                <tr>
                  <th scope="col" class="list_id" style="width: 25%;">
                  	<input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this,'data_cctv');" />全选
                  </th>
                  <th scope="col" class="list_model" style="width: 25%;"></th>
                  <th scope="col" class="list_model" style="width: 25%;"></th>
                  <th scope="col" class="list_modified_by" style="width: 25%;"></th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this,'data_cctv');" />全选</th>
                  <th scope="col" class="list_id"></th>
                  <th scope="col" class="list_model"></th>
                  <th scope="col" class="list_model"></th>

                </tr>
              </tfoot>
              <tbody><?php  $cctv_cookie_name = $adminid.'_cctvcodes';?>
                <?php foreach ($cctv_channels as $cctvkey=>$cctv_channel):?>
                <?php if($cctvkey%4==0):?>
					<?php if($cctvkey!=0):?></tr><?php endif;?>
                	<tr>
                <?php endif;?>
                  <td><input type="checkbox" <?php if(isset($_COOKIE[$cctv_cookie_name]) && array_search($cctv_channel->getCode(),unserialize($_COOKIE[$cctv_cookie_name]))!==FALSE):?><?php echo 'checked="checked"'?><?php endif;?> class="sf_admin_batch_checkbox cctvids" value="<?php echo $cctv_channel->getCode();?>" name="cctvcodes[]"><?php echo $cctv_channel->getName()?></td>
                <?php endforeach;?>
              </tbody>
            </table> 
            
            
            
            <table cellspacing="0" id="data_tv" style="display:none">
              <thead>
                <tr>
                  <th scope="col" class="list_id" style="width: 25%;"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this,'data_tv');" />全选</th>
                  <th scope="col" class="list_model" style="width: 25%;"></th>
                  <th scope="col" class="list_model" style="width: 25%;"></th>
                  <th scope="col" class="list_modified_by" style="width: 25%;"></th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this,'data_tv');" />全选</th>
                  <th scope="col" class="list_id"></th>
                  <th scope="col" class="list_model"></th>
                  <th scope="col" class="list_model"></th>

                </tr>
              </tfoot>
              <tbody><?php  $tv_cookie_name = $adminid.'_tvcodes';?>
                <?php foreach ($tv_channels as $tvkey=>$tv_channel):?>
                <?php if($tvkey%4==0):?>
                	<?php if($tvkey!=0):?></tr><?php endif;?>
                	<tr>
                <?php endif;?>
                  <td><input type="checkbox" <?php if(isset($_COOKIE[$tv_cookie_name]) && array_search($tv_channel->getCode(),unserialize($_COOKIE[$tv_cookie_name]))!==FALSE):?><?php echo 'checked="checked"'?><?php endif;?> class="sf_admin_batch_checkbox tvids" value="<?php echo $tv_channel->getCode();?>" name="tvcodes[]"><?php echo $tv_channel->getName()?></td>
                <?php endforeach;?>
              </tbody>
            </table>                
            <input type="hidden" name="publish" value="0" id="publish_off" /> 
            </form>   
            <div class="clear"></div>
          
        </div>
      </div>