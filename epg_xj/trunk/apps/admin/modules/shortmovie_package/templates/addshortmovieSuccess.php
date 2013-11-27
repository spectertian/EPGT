<?php include_partial("wiki/screenshots"); ?>
<script language="javascript">

$(document).ready(function(){
    $('#sm_name').simpleAutoComplete('<?php echo url_for('short_movie/loadsm') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'smpid',
        max       : 20
    },function(date){
        var date = eval("("+date+")");
        var id = date.id;
        $('#sm_id').attr('value',id);
    });
});
</script>
    <div id="content">
        <div class="content_inner">
        <header>
          <h2>短视频包：<?php echo $smpname; ?></h2>
          <nav class="utility">
            <li class="back"><a class="toolbar"  href="/shortmovie_package/manage?id=<?php echo $smpid; ?>&smpname=<?php echo $smpname; ?>">返回管理界面</a></li>
            </nav>
        </header>
			<div class="table_nav">
			<?php include_partial('global/flashes')?>
				<form method="POST" action="<?php echo url_for('shortmovie_package/addshortmovie?smpid='.$smpid.'&smpname='.$smpname)?>">				
				 <div class="widget-body">
				   <ul class="wiki-meta">
					<li style="z-index: 100;"><label>短视频名称：</label><input name="sm_name" id="sm_name"  value="" type="text"><input name="smid" id="sm_id"  value="" type="hidden"></li>
					<li><input type='hidden' name='smpid' value="<?php echo $smpid; ?>" /><input type="submit" value="添加"></li>
				  </ul>
				</div>
				</form>
			</div>
			<div class="table_nav">
				<form method="POST" action="<?php echo url_for('shortmovie_package/addnewshortmovie?smpid='.$smpid.'&smpname='.$smpname)?>">				
				 <div class="widget">
                <h3>添加新短视频并关联此短视频包</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>名称：</label><input type='text' name='name' value=''></li>  
					 <li><label>url：</label><input type='text' name='url' value=''></li>
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
					 <li><label>来源：</label><input type='text' name='refer' value=''></li>
					 <li><label>作者：</label><input type='text' name='author' value=''></li>
					 <li>
					   <label>状态：</label>
					   发布<input type='radio' value='1' name='state' checked="checked" />
					   <!--<select name='state'>
					     <option value='0'>隐藏</option>
					     <option value='1'>发布</option>
					   </select>-->
					 </li>
					 <li>
					 	<label>视频封面:</label>
					 	<a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=smscreenshotAdds">上传封面</a></li>
					 </li>
					 <ul id="right">
		             </ul>
					 <li><input type='submit' value='保存' /></li>                   
				  </ul>
				</div>
              </div>
				</form>
			</div>
            <div class="clear"></div>
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