 <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">添加新分类</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#category_recommendForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("category_recommend/index")?>">返回列表</a></li>
				</nav>
			</header>

			 <div style="float:left; width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
				<form method="POST" action="<?php echo url_for('category_recommend/add')?>" id="addForm" name="addForm">	
				<div class="widget-body">
				  <ul class="wiki-meta">
                  
					 <li><label>分类名称：</label><input type="text" name="category"></li>
					 <li><label>模型：</label><input type="text" name="template"></li>					       
        
					 <li><label>是否默认：</label><input type="radio" name="is_default" value=true id='y' checked>true&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  name="is_default" id='n' value=0>false</li>
					 <li><label>开始时间：</label><input type="text" name="start_time"> <?php //echo input_date_tag('dateofbrith','2005-05-03','rich=true')?></li>
					 <li><label>结束时间：</label><input type="text" name="end_time"></li>
					 <li><input type="submit" value="提交"></li>
				  </ul>                  
				</div>
				</form>
              </div>
            </div> 
			</form>
		</div>

</div>