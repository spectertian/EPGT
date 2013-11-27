	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content"><?php echo $PageTitle?></h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#settingForm').submit()">保存</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>

            <form method="POST" id="settingForm" name="settingForm" action="<?php echo url_for("setting/page");?>">
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $PageTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>应急页面：</label>
					 		<select name="page">
					 				<option value="-1" <?php echo $page==-1?'selected="selected"':''?>>正常</option>
					 				<option value="1" <?php echo $page==1?'selected="selected"':''?>>固定点播推荐</option>
                                    <option value="2" <?php echo $page==2?'selected="selected"':''?>>系统维护</option>
                                    <option value="3" <?php echo $page==3?'selected="selected"':''?>>空白</option>
			 				</select>
					 </li>
                  </ul>
				  <ul id="right">
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
