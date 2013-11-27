<?php include_partial("wiki/screenshots"); ?>
<script language="javascript">

$(document).ready(function(){
    $('#wiki_name').simpleAutoComplete('<?php echo url_for('wiki_package/loadWiki') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_id',
        max       : 20
    },function(date){
        var date = eval("("+date+")");
        var id = date.id;
        $('#wiki_id').attr('value',id);
    });


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
	
	//Modify by tianzhongsheng-ex 2013-11-12 13:55:00 验证修改增加url验证和名称验证，并修复和优化日期验证
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
				<h2 class="content">添加广告</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="adddate();">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("simple_ad/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			
            <form method="POST" id="adForm" name="adForm" action="/simple_ad/add">
            
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>广告名称：</label><input id="name" type='text' name='name' value=''></li>  
					 <li><label>广告url：</label><input id="url" type='text' name='url' value=''></li>
					 <input type="hidden" name='formToken' value='<?php echo $formToken;?>'>
					 <li><label>时间：</label><input type="button" name="start_time" value="起始日期" maxlengtjh="10" value="起始日期" class="datepicker_s">&nbsp;——&nbsp;
					 <input type="button" name="end_time" value="结束日期" maxlengtjh="10" value="结束日期" class="datepicker_e">&nbsp;&nbsp;<a href='###' onclick='cleardate()'>重置</a></li>
					 <div style='display:none' id='add_date'></div>
					 <li>
					 	<label>广告图片:</label>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=adscreenshotAdds">上传剧照</a></li>
					 </li>                     
					<ul id="right">
		            </ul> 
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
