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

function adddate(){
	var start_time = $('.datepicker_s').val();
	var end_time   = $('.datepicker_e').val();
	if(start_time=='起始日期' && end_time=='结束日期'){
		//return true;
		$('#adForm').submit();return;
	}else if(start_time=='起始日期'){
		alert('请选择起始日期');
		return false;
	}else if(end_time=='结束日期'){
		alert('请选择结束日期');
		return false;
	}
	if(start_time<end_time){
		var html = '';
		html += '<input type="hidden" name="start_time" value="'+start_time+'">';
		html += '<input type="hidden" name="end_time" value="'+end_time+'">';
		$('#add_date').html(html);
		$('#adForm').submit();return;
	}else{
		alert('请选择正确的起止时间');
		return false;
	}
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
					 <li><label>广告名称：</label><input type='text' name='name' value=''></li>  
					 <li><label>广告url：</label><input type='text' name='url' value=''></li>
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
