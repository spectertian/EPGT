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
            <input type="hidden" value="<?php echo $HotSearchKey;?>" name="key[]"/>
            <input type="hidden" value="<?php echo $hot_action;?>" name="caozuo[]"/>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $HotSearchTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>关键词：</label><textarea name="value[]" id="value" cols="90" rows="7" style="width: 95%;"><?php echo $hot_value?></textarea></li>
				     <li>说明：各关键词之间用英文状态下的逗号隔开（例：新闻联播,中央电视台）</li>
                  </ul>
				  <ul id="right">
                  </ul>
				</div>
              </div>
            </div> 
            <div style="width:33%; float:right;">
              <div class="widget">
                <h3>辅助函数</h3>                
              </div>
            </div>
            <input type="hidden" value="<?php echo $DefaultCollectionChannelKey;?>" name="key[]"/>
            <input type="hidden" value="<?php echo $default_action;?>" name="caozuo[]"/>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $DefaultCollectionChannelTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>关键词：</label><textarea name="value[]" id="value" cols="90" rows="7" style="width: 95%;"><?php echo $default_value?></textarea></li>
				     <li>说明：关键词为电视频道的code并且关键字之间用英文状态下的逗号隔开（例：cctv1,0d7b5dfe999fc5fd0140863f6e8910a5）</li>
                  </ul>
				  <ul id="right">
                  </ul>
				</div>
              </div>
            </div> 
 
			
            <input type="hidden" value="<?php echo $SportSearchKey;?>" name="key[]"/>
            <input type="hidden" value="<?php echo $sport_action;?>" name="caozuo[]"/>
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $SportSearchTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>关键词：</label><textarea name="value[]" id="value" cols="90" rows="7" style="width: 95%;"><?php echo $sport_value?></textarea></li>
				     <li>说明：各关键词之间用英文状态下的逗号隔开（例：拳击,跳远）</li>
                  </ul>
				  <ul id="right">
                  </ul>
				</div>
              </div>
            </div> 
			</form>  
        </div>
      </div>
