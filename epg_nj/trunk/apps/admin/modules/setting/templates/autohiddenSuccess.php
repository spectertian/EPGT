	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content"><?php echo $PageTitle?></h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#settingForm').submit()">保存</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>

            <form method="POST" id="settingForm" name="settingForm" action="<?php echo url_for("setting/autohidden");?>">
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $PageTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>隐藏时间：</label>
                            <input type="text" name="value" value="<?php echo $value;?>"/>
					 </li>
					 <li><label>提示：</label>
                            1000为1秒，如设置5秒隐藏，则值为1000*5=5000
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
