<?php include_partial("wiki/screenshots"); ?>
<script language="javascript">

$(document).ready(function(){
    $('.datepicker_s').datepicker({
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

    $('.datepicker_e').datepicker({
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
});

function cleardate(){
	$('.datepicker_s').val('起始日期');
	$('.datepicker_e').val('结束日期');
	$('#add_date').html('');
}

function adddate()
{
	var start_time = $('.datepicker_s').val();
	var end_time   = $('.datepicker_e').val();
	
	//Modify by tianzhongsheng-ex 2013-11-12 14:29:00 验证修改增加url验证和名称验证，并修复和优化日期验证
	var url = $.trim($('#url').val());
	var name = $.trim($('#name').val());
	if(name='')
	{
		alert('请填写广告名称');
		return false;
	}
	if(url=='')
	{
		alert('请填写广告url');
		return false;
	}
	if(start_time=='请填写url' || end_time=='结束日期')
	{
		alert('请选择日期');
		return false;
	}
	if(start_time>end_time)
	{
		alert('请选择正确的起止时间');
		return false;
	}

	var html = '';
	html += '<input type="hidden" name="start_time" value="'+start_time+'">';
	html += '<input type="hidden" name="end_time" value="'+end_time+'">';
	$('#add_date').html(html);
	$('#adForm').submit();return;
}
</script>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">编辑广告</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="adddate();">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("simple_ad/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			
            <form method="POST" id="adForm" name="adForm" action="/simple_ad/edit">
            
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
				     <input type='hidden' name='id' value='<?php echo $id; ?>'>
					 <li><label>广告名称：</label><input type='text' id="name" name='name' value='<?php echo $ad->getName(); ?>'></li>  
					 <li><label>广告url：</label><input type='text' id="url" name='url' value='<?php echo $ad->getUrl(); ?>'></li>
					 <li><label>时间：</label><input type="button" name="start_time" maxlengtjh="10" value="<?php if($ad->getStartTime()){echo $ad->getStartTime();}else{echo'起始日期';} ?>" class="datepicker_s">&nbsp;——&nbsp;
					 <input type="button" name="end_time" maxlengtjh="10" value="<?php if($ad->getEndTime()){echo $ad->getEndTime();}else{echo'结束日期';} ?>" class="datepicker_e">&nbsp;&nbsp;<a href='###' onclick='cleardate()'>重置</a></li>
					 <div style='display:none' id='add_date'></div>
					 <li>
					 	<label>广告图片:</label>
					      <ul id="right">
							<li id="screenshots_index_Wwp6tDBJlBVf8JCxdCsAMJXfXcQ9571811">   
                        <?php if($ad->getImage()):?>    
								<!--<input id="theme_img" name="theme[img]" value="<?php echo $ad->getImage();?>" type="hidden" />-->			
								<img style="" id="screenshots_pic_Wwp6tDBJlBVf8JCxdCsAMJXfXcQ9571811" src="<?php echo file_url($ad->getImage());?>" alt="加载中"> 					    
						 <?php endif;?>	
							</li>
			            </ul>
                        
					 	<?php if($ad->getImage()):?>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=adscreenshotAdds">更改剧照</a>
					 	<?php else:?>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=adscreenshotAdds">上传剧照</a>
					 	<?php endif;?>
		                </ul> 
					 </li>                     
				  </ul>
				</div>
              </div>
            </div> 
			</form>
            <div style="width:33%; float:right;">
              <div class="widget">
                <h3>辅助函数</h3>                
              </div>
            </div>   
          </form>
        </div>
      </div>
