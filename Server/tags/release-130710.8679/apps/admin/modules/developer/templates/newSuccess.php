<div id="content">
	<div class="content_inner">
		<header><h2 class="content">新建开发者</h2></header>
		<form name="adminForm" method="post" action="/developer/new">
			<div class="widget">
				<h3>基本资料</h3>
				<div class="widget-body" style="width: 60%">
					<ul class="wiki-meta">
						<li><label>名称：</label><input id="wiki_director" type="text" size="40" style="width: 30%" name="developer[name]"></li>
						<li><label>描述：</label><textarea id="wiki_content" style="width:40%" name="developer[desc]"  rows="10"></textarea></li>
						<li><label>状态：</label>
							<select name="developer[state]">
								<option value="1">正常</option>
								<option value="0">锁定</option>
							</select>
						</li>
						
						<li><label>播放源：</label>
						   <?php 
	                        $app_source=  sfConfig::get("app_vod_source");
	                       ?>
							 <ul  style="float: left; ">		  
								 <?php foreach ($app_source as $key => $field): ?>			  
						           <li style="float: left; height: 30px; line-height: 24px;width: 10px;">
						             <input style="float: left;top: 8px;" type="checkbox" name="developer[sources][]" value="">
						             <label style="float: left;display: inline-block;padding: 0;vertical-align: middle;width: 95px;"><?php echo $field; ?></label>
						           </li>
						          
						         <?php endforeach; ?>
							</ul> 	
					 	</li>
					 	<div style=" clear:both; float:none;}"></div>
						<li><input type="submit" value="提交" onclick="document.adminForm.submit();"/></li>
						<div style=" clear:both; float:none;}"></div>
					</ul>
				</div>
			</div>
		</form>
	</div>
</div>