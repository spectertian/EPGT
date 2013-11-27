	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content"><?php echo $PageTitle?></h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#settingForm').submit()">保存</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>

            <form method="POST" id="settingForm" name="settingForm" action="<?php echo url_for("setting/recommend");?>">
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $PageTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>点播：</label>
					 		<select name="vod">
					 				<option value="center" <?php echo $vodwho=='center'?'selected="selected"':''?>>运营中心</option>
					 				<option value="tongzhou" <?php echo $vodwho=='tongzhou'?'selected="selected"':''?>>技术部</option>
					 				<option value="tcl" <?php echo $vodwho=='tcl'?'selected="selected"':''?>>tcl</option>
			 				</select>
					 </li>
					 <li><label>相关推荐：</label>
					 		<select name="vodRelated">
					 				<option value="center" <?php echo $vodRelatedwho=='center'?'selected="selected"':''?>>运营中心</option>
					 				<option value="tongzhou" <?php echo $vodRelatedwho=='tongzhou'?'selected="selected"':''?>>技术部</option>
					 				<option value="tcl" <?php echo $vodRelatedwho=='tcl'?'selected="selected"':''?>>tcl</option>
			 				</select>
					 </li>
					 <li><label>直播：</label>
					 		<select name="live">
					 				<option value="center" <?php echo $livewho=='center'?'selected="selected"':''?>>运营中心</option>
					 				<option value="tongzhou" <?php echo $livewho=='tongzhou'?'selected="selected"':''?>>技术部</option>
                                    <option value="tcl" <?php echo $livewho=='tcl'?'selected="selected"':''?>>tcl</option>
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
