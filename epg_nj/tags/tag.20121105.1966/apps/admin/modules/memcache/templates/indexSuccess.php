<script type="text/javascript">
function submitform(){
    document.memcacheForm.action="<?php echo url_for("memcache/delete");?>";
    document.memcacheForm.submit();
}
</script>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">缓存管理</h2>
			</header>
			<?php include_partial('global/flashes')?>

            <form method="POST" id="memcacheForm" name="memcacheForm" action="<?php echo url_for("memcache/index");?>">
            <div style="float:left; width:100%;">
              <div class="widget">
                <h3>缓存管理</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>Key值：</label><input name="key" id="key" style="width: 95%;" value="<?php echo $key?>"/></li>
                     <li><label>md5加密:</label><input type="radio" name="md5" id="radio" value="1"  checked="checked"/>是<input type="radio" name="md5" id="radio2" value="0" />否</li>
                     <li><label>提示:</label>部分key值如下：</li>
                     <li>getWeiShiChannels(电视频道缓存)---缓存接口GetChannelList的数据</li>
                     <li>getThemeByPageAndSize,1,8(专题列表)---缓存接口GetThemeList的数据，后边的1,8分别对应页数和每页条数（可选值：页数1-3条数：8或10）</li>
                     <li>getThemes(专题列表)---缓存接口GetThemeList的数据</li>
                     <li>GetAllChannelProgram四川2012-08-08(获取获取所有频道的节目列表)---四川2012-08-08根据实际情况而变，四川可为空</li>
                     <li>其他key值请联系开发人员获取</li>
                     <li><input type="submit" name="button" id="button" value="查询" /><input type="button" name="button2" id="button2" value="删除" onclick="submitform()"/></li>
                     <li>
                     <?php 
                     echo "<pre>"; 
                     print_r($value);
                     ?>
                     </li>
                  </ul>

				</div>
              </div>
            </div> 
			</form>

        </div>
      </div>
