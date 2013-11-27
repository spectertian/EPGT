<?php include_partial("wiki/screenshots"); ?>
<script type="text/javascript">
//全选
function checkAll()
{
    var flag    = $("#sf_admin_list_batch_checkbox").attr('checked');
    var box     = $("input[type=checkbox]");
    if (flag) {
        box.attr('checked',true);
    }else{
        box.attr('checked',false);
    }

}
function mySubmit(flag)
{  
	return true;

      
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

function programOpt(arg) 
{
	var program_name = $.trim(arg.parent().parent().find('td').eq(0).text());
	var channel_code = $.trim(arg.parent().parent().find('td').eq(1).text());
	var date = $.trim(arg.parent().parent().find('td').eq(3).text());
	var start_time = $.trim(arg.parent().parent().find('td').eq(4).text());
	var end_time = $.trim(arg.parent().parent().find('td').eq(5).text());
	var wiki_id = $.trim(arg.parent().parent().find('td').eq(2).text());
	var tags = $.trim(arg.parent().parent().children('td').eq(7).text());
	replace_stcok_data = {};	//保存数据中介			
	replace_stcok_data['channel_code'] = channel_code;
	replace_stcok_data['date'] = date;
	replace_stcok_data['start_time'] = start_time;
	replace_stcok_data['end_time'] = end_time;
	replace_stcok_data['wiki_id'] = wiki_id;
	replace_stcok_data['tags'] = tags;
	$("#program_name").attr("value",program_name);
	$('#div_1').show();
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
<div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarList',array('date' => $date,'pageTitle' => $pageTitle))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
              <?php include_partial('search', array('condition' => $condition, 'name' => $name, 'date' => $date)) ?>
		<?php if($isPager):?>
             <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('yesterday_program/add?page='.$pager->getFirstPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('yesterday_program/add?page='.$pager->getPreviousPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('yesterday_program/add?page='.$page.'&condition='.$condition.'&name='.$name.'&date='.$date);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('yesterday_program/add?page='.$pager->getNextPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('yesterday_program/add?page='.$pager->getLastPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>
		<?php endif;?>
              <div class="clear"></div>
            </div>


            <table cellspacing="0" id="yesterday_tables">
              <thead>
                <tr>
                  <th scope="col" class="list_tags list_tagsl">节目名称</th>
                  <th scope="col" class="list_time">播放日期</th>
                  <th scope="col" class="list_time">播放时间</th>
                  <th scope="col" class="list_time">结束时间</th>
                  <th scope="col" class="list_model">频道名称</th>
                  <th scope="col" class="list_created_at">标签</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_tags list_tagsl">节目名称</th>
                  <th scope="col" class="list_time">播放日期</th>
                  <th scope="col" class="list_time">播放时间</th>
                  <th scope="col" class="list_time">结束时间</th>
                  <th scope="col" class="list_model">频道名称</th>
                  <th scope="col" class="list_updated_at">标签</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  foreach ($pager->getResults() as $i => $rs): ?>
                            <tr id="<?php echo $rs->getId(); ?>">
                              <td><?php echo $rs->getName();?></td>
                              <td style="display:none"><?php echo $rs->getChannelCode();?></td>
                              <td style="display:none"><?php echo $rs->getWikiId();?></td>
                              <td><?php echo $rs->getDate();?></td>
                              <td><?php $starttime = $rs->getStartTime(); if($starttime) echo $rs->getStartTime()->format("H:i")?></td>
                              <td><?php $endtime = $rs->getEndTime(); if($endtime) echo $rs->getEndTime()->format("H:i")?></td>
                              <td><?php echo $channelNmaes[$rs->getChannelCode()]?></td>
                              <td>
                              	<?php if($rs->getTags()):?><?php foreach($rs->getTags() as $tag): ?><?php echo $tag; ?>,<?php endforeach; ?><?php endif;?>
                              </td>
                              <td><a href="#" onclick="programOpt($(this));">添加</a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
		<?php if($isPager):?>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('yesterday_program/add?page='.$pager->getFirstPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('yesterday_program/add?page='.$pager->getPreviousPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('yesterday_program/add?page='.$page.'&condition='.$condition.'&name='.$name.'&date='.$date);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('yesterday_program/add?page='.$pager->getNextPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('yesterday_program/add?page='.$pager->getLastPage().'&condition='.$condition.'&name='.$name.'&date='.$date);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        	</div>
		<?php endif;?>

            <div class="clear"></div>
        </div>
      </div>
<div id="div_1" style="display: none">
	<form name="" method="post" id="" action="">
		<ul>
			<li><h2>昨日回顾添加</h2></li>
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