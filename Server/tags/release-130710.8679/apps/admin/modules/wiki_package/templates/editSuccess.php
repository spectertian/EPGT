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
		return true;
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
		return true;
	}else{
		alert('请选择正确的起止时间');
		return false;
	}
}
</script>
<?php include_partial("wiki/screenshots"); ?>
<div id="content">
    <div class="content_inner">
        <?php include_partial('toolbarList',array('pageTitle'=>'修改推荐'))?>
        <?php include_partial('global/flashes') ?>
        <div class="table_nav">
            <form method="post" action="" enctype="multipart/form-data" name="editfrom"  onsubmit='return adddate();'>
                <tbody>
                <table>
                    <tr>
                        <td style="width: 6%;">名称</td>
                        <td><input type="text" value="<?php echo $wiki_package->getName();?>" id="wiki_name" name="name" size="30" style="width: 150px; "> </td>
                    </tr>
                    <tr>
                        <td>区域</td>
                        <td>
                            <select name="scene">
                                <option value="">请选择区域</option>
								<option value="tcl_index_update"<?php echo ('tcl_index_update' == $wiki_package->getScene()) ? 'selected=selected' : ''?>>tcl首页今日更新</option>
                            </select>(表示显示在哪里，如tcl首页今日更新(tcl_index_update))</td>
                    </tr>
                    <tr>
                        <td>排序</td>
                        <td><input type="text" value="<?php if($wiki_package !=null) echo $wiki_package->getSort();?>" name="sort" size="30" style="width: 150px;"> （表示所推荐的显示前后,请使用数字，数字越小，所将会显示在更前面,从1开始）</td>
                    </tr>
                     <tr>
                        <td>显示</td>
                        <?php 
                        if($wiki_package!=null) $ispublic = $wiki_package->getIsPublic();//var_dump($ispublic);
                        ?>
                        <td><input type="radio" name="ispublic" value="true" <?php if ($ispublic==true) echo "checked"?> > 显示 <input type="radio" name="ispublic" value="false" <?php if ($ispublic==false) echo "checked"?> > 不显示</td>
                    </tr>
                    <tr>
                    	<td>时间</td>
                        <td>
                        	<input type="button" name="start_time"  maxlengtjh="10" value="<?php if($wiki_package->getStartTime()){echo $wiki_package->getStartTime();}else{echo'起始日期';} ?>" class="datepicker_s">——
                        	<input type="button" name="end_time"  maxlengtjh="10" value="<?php if($wiki_package->getEndTime()){echo $wiki_package->getEndTime();}else{echo'结束日期';} ?>" class="datepicker_e">&nbsp;&nbsp;<a href='###' onclick='cleardate()'>重置</a>
                        	<div style='display:none' id='add_date'></div>
                        </td>
                    </tr>
                    <tr>
                        <td><input name="wiki_id" id="wiki_id"  value="" type="hidden"></td>
                        <td><input type="submit" value="保存" /></td>
                    </tr>
                </table>
                </tbody>
            </form>
        </div>
    </div>
</div>