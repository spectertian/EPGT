	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content"><?php echo $PageTitle?></h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#settingForm').submit()">保存</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>

            <form method="POST" id="settingForm" name="settingForm" action="<?php echo url_for("setting/edit");?>">
            <input type="hidden" value="<?php echo $key;?>" name="key"/>
            <input type="hidden" value="<?php echo $action;?>" name="caozuo"/>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $PageTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>关键词：</label><textarea name="value" id="value" cols="90" rows="10" style="width: 95%;"><?php echo $value?></textarea></li>
				     <li>说明：各关键词之间用英文状态下的逗号隔开（例：新闻联播,中央电视台）</li>
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
