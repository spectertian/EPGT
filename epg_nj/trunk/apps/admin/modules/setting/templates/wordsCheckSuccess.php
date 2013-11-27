	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content"><?php echo $PageTitle?></h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#settingForm').submit()">保存</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>

            <form method="POST" id="settingForm" name="settingForm" action="<?php echo url_for("setting/wordsCheck");?>">
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $PageTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>审核状态：</label>
					 		<select name="status">
					 				<option value="自动审核" <?php echo $status=='自动审核'?'selected="selected"':''?>>自动审核</option>
					 				<option value="半自动审核" <?php echo $status=='半自动审核'?'selected="selected"':''?>>半自动审核</option>
					 				<option value="人工审核" <?php echo $status=='人工审核'?'selected="selected"':''?>>人工审核</option>
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
