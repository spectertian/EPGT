<?php include_partial("wiki/screenshots"); ?>
<script type="text/javascript">
//定义全局变量
var replace_stcok_data = {};	//保存数据
 
function programOpt(arg) 
{
	var juge = arg.val();
	
	switch (juge){
	  case 'addyesterday': 
		  	var program_name = $.trim(arg.parent().parent().children('td').eq(1).text());
		    var channel_code = arg.parent().parent().children('td').eq(12).attr("tv_code");
		    var date = $.trim(arg.parent().parent().children('td').eq(7).text());
		    var start_time = $.trim(arg.parent().parent().children('td').eq(2).text());
		    var end_time = $.trim(arg.parent().parent().children('td').eq(3).text());
		    var wiki_id = arg.parent().parent().children('td').eq(5).attr("rel");
		    var tags = $.trim(arg.parent().parent().children('td').eq(6).text());
		   	replace_stcok_data = {};	//保存数据中介
			replace_stcok_data['channel_code'] = channel_code;
			replace_stcok_data['date'] = date;
			replace_stcok_data['start_time'] = start_time;
			replace_stcok_data['end_time'] = end_time;
			replace_stcok_data['wiki_id'] = wiki_id;
			replace_stcok_data['tags'] = tags;
			
			$("#program_name").attr("value",program_name);
			$('#div_1').find('ul').find('li').eq(3).show();
			$("#play_url").attr("value",'');
			$("#add_prgrame").html('昨日回顾节目添加');
			$('#div_1').show();
	    break;
	  case "addnextweek":
			var program_name = $.trim(arg.parent().parent().children('td').eq(1).text());
		    var channel_code = arg.parent().parent().children('td').eq(12).attr("tv_code");
		    var date = $.trim(arg.parent().parent().children('td').eq(7).text());
		    var start_time = $.trim(arg.parent().parent().children('td').eq(2).text());
		    var end_time = $.trim(arg.parent().parent().children('td').eq(3).text());
		    var wiki_id = arg.parent().parent().children('td').eq(5).attr("rel");
		    var tags = $.trim(arg.parent().parent().children('td').eq(6).text());
		    
		   	replace_stcok_data = {};	//保存数据中介
			replace_stcok_data['channel_code'] = channel_code;
			replace_stcok_data['date'] = date;
			replace_stcok_data['start_time'] = start_time;
			replace_stcok_data['end_time'] = end_time;
			replace_stcok_data['wiki_id'] = wiki_id;
			replace_stcok_data['tags'] = tags;
			
			$("#play_url").attr("value",'undefinition');
			$('#div_1').find('ul').find('li').eq(3).css('visibility','hidden');
			$("#program_name").attr("value",program_name);
			$("#add_prgrame").html('下周预告节目添加');
			
			$('#div_1').show();
		  
	    break;
	  case 'edit': 
		    changeTR(arg,'newProgram',false);return false;
	    break;
	  case 'delete': 
		  if(confirm('确定删除吗？')){program_deleteone(arg,1);return false;}
	    break;
	  case 'deleteWiki': 
		  if(confirm('确定删除维基吗？')){delete_wiki(arg,1);return false;}
	    break;
	  default: 
		  if(juge){
			   window.open( juge);
			  }

		 break
	}
}

//保存表单
function saves(arg) 
{
	$("#div_1").hide();
	var program_name = $("#program_name").val();
	var aspect = $("#aspect").val();
	var play_url = $("#play_url").val();
	var state = $("#state").val();
	var poster = $("#poster").val();	//图片
	var sort = $("#sort").val();
	var style = $("#style").val();

	replace_stcok_data['program_name'] = program_name;
	replace_stcok_data['aspect'] = aspect;
	replace_stcok_data['play_url'] = play_url;
	replace_stcok_data['state'] = state;
	replace_stcok_data['poster'] = poster;
	replace_stcok_data['sort'] = sort;
	replace_stcok_data['style'] = style;
	var template = JSON.stringify(replace_stcok_data);

	$.ajax({
		type:"post",
		url: '<?php echo url_for("yesterday_program/saves");?>',
		data: {template:template},
		success: function(msg){
			$("#div_1").hide();
					if(msg == 'true')
					{
						alert('存储成功');
						window.location.reload()
					}else 
					{
						alert('存储失败');
						window.location.reload()
					}
					
				},
		});

}

//关闭层
function closediv(getdiv)
{
	$('#div_1').hide();
	return;
}
</script>

<div id="div_1" style="display: none">
	<form name="" method="post" id="" action="">
		<ul>
			<li><h2 id="add_prgrame">节目添加</h2></li>
			<li><label>电视节目:</label>
				<input type="input" name="program_name" id="program_name">
			</li>
			<li><label>推荐新语:</label>
				<textarea id='aspect' name='aspect'  rows="1" cols="3"></textarea>
			</li>
			<li><label>播放地址:</label>
				<input type="input" name="play_url" id="play_url">
			</li>
			<li><label>排序(数字):</label>
				<input onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d.]/g,''))" id="sort" name="sort">
			</li>
			<li><label>发布状态:</label>
				<select name="state" id="state" >
	            <option value="1">发布</option>
	            <option value="0">不发布</option>
	            </select>
			</li>
			<li><label>图片样式:</label>
				<select name="style" id="style" >
			<?php foreach($style as $k =>$v):?>
	            <option value="<?php echo $k?>"><?php echo $v?></option>
			<?php endforeach;?>
	            </select>
			</li>
			<li><label>节目图片:</label>
				<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=programscreenshotAdds">上传封面</a>
			</li>
			<li id="right"> </li> 
			<li id="list_button" >
				<input type="button" value="保存" class="btn" onclick="saves();" />
				<input type="reset" value="重置" class="btn"/>
				<input type="button" value="取消" class="btn" onclick="closediv('div_1');" />
			</li>
		</ul>
	</form>
</div>