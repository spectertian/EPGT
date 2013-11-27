	  <?php include_partial("wiki/screenshots"); ?>
	  <script>
    	  function adddate(){
			$('#adForm').submit();return;
  		  }
	  </script>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">添加短视频包</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="adddate();">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("shortmovie_package/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			
            <form method="POST" id="adForm" name="adForm" action="/shortmovie_package/add">
            
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>名称：</label><input type='text' name='name' value=''></li>
					 <li><label>描述：</label><input type='text' name='desc' value=''></li>   
					 <li>
					   <label>标签：</label><input type='text' name='tag[]' value='' id='wiki_tagss'>
					   <div id="dm_postWrite_tag" class="pub_dropmenu" style="display: none;" onblur="$('#dm_postWrite_tag').slideUp('fast');">
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">电视剧</span>
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">电影</span>
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">体育</span>
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">娱乐</span>
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">少儿</span>
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">科教</span>
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">财经</span>
                        <span style="font-weight: bold; color: rgb(51, 51, 51);">综合</span>
                        <hr>
                        <span style="color: rgb(51, 51, 51);">偶像</span>
                        <span style="color: rgb(51, 51, 51);">喜剧</span>
                        <span style="color: rgb(51, 51, 51);">爱情</span>
                        <span style="color: rgb(51, 51, 51);">都市</span>
                        <span style="color: rgb(51, 51, 51);">古装</span>
                        <span style="color: rgb(51, 51, 51);">武侠</span>
                        <span>历史</span>
                        <span>警匪</span>
                        <span style="color: rgb(51, 51, 51);">家庭</span>
                        <span>神话</span>
                        <span style="color: rgb(51, 51, 51);">剧情</span>
                        <span>犯罪</span>
                        <span>情景</span>
                        <span>伦理</span>
                        <span>悬疑</span>
                        <span>军事</span>
                        <span>励志</span>
                        <span>刑侦</span>
                        <span>乡村</span>
                        <span>谍战</span>
                        <span style="color: rgb(51, 51, 51);">宫廷</span>
                        <span style="color: rgb(51, 51, 51);">商战</span>
                        <span style="color: rgb(51, 51, 51);">纪实</span>
                        <span>科幻</span>
                        <span style="color: rgb(51, 51, 51);">冒险</span>
                        <span style="color: rgb(51, 51, 51);">传记</span>
                        <span style="color: rgb(51, 51, 51);">歌舞</span>
                        <span style="color: rgb(51, 51, 51);">戏剧</span>
                        <span>动作</span>
                        <span style="color: rgb(51, 51, 51);">战争</span>
                        <span>恐怖</span>
                        <span>惊悚</span>
                        <span>记录</span>
                        <span>枪战</span>
                        <span>时尚</span>
                        <span>温情</span>
                        <span>青春</span>
                        <span>文艺</span>
                        <span>动漫</span>
                        <span>教育</span>
                        <span>灾难</span>
                        <span style="color: rgb(51, 51, 51);">魔幻</span>
                        <span style="color: rgb(51, 51, 51);">经典</span>
                        <span style="color: rgb(51, 51, 51);">动画</span>
                        <span style="color: rgb(51, 51, 51);">儿童</span>
                        <span style="color: rgb(51, 51, 51);">革命</span>
                        <a href="#" class="pub_dm_close" onclick="$('#dm_postWrite_tag').slideUp('fast'); return false;"> X </a>
                       </div>
					 </li>
					 <li>
					   <label>状态：</label>
					   <select name='state'>
					     <option value='0'>隐藏</option>
					     <option value='1'>发布</option>
					   </select>
					 </li>
					 <li>
					 	<label>视频封面:</label>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=smpscreenshotAdds">上传封面</a></li>
					 </li>                     
					<ul id="right">
		            </ul> 
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
<script  type="text/javascript">
$('#wiki_tagss').click(function(){
	$('#dm_postWrite_tag').slideDown('fast');
	return false;
});

$('#dm_postWrite_tag').find('span').hover(function(){
    $(this).css({'color': 'red'});
    $(this).click(function() {
        var tags = $('#wiki_tagss').val().split(/[,]+/g);
        var t = [];
        var new_tag =$(this).text();
        for (var i=0; i< tags.length; i++) {
            if (tags[i]=='') {
                continue;
            }
            if (tags[i] == new_tag) {
                return;
            }
            t.push(tags[i]);
        }
        if (t.length >= 20) {
            alert('最多可以填入20个标签');
            return;
        }
        tags = t;
        tags.push(new_tag);

        $('#wiki_tagss').val(tags.join(',')).change();
    })
},
function() {
    $(this).css({'color': '#333333'});
});
</script>