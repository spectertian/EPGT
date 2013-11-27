<script type="text/javascript">
$(document).ready(function(){
	//wiki_title下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-08-14 13:20:00
    $('#wiki_name_re').simpleAutoComplete('<?php echo url_for('videos_zhui/loadWiki') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_name',
        max       : 20
    }, function (date){
    	 var date = eval("("+date+")");
         var id = date.id;
         var wiki_name = date.title;
         var director = date.director;
         $('#wiki_id').attr('value',id);
         $('#wiki_name').attr('value',wiki_name);
         $('#director').attr('value',director);
    });
});
function save()
{
	$('#adForm').submit();return;
}
</script>
  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content"><?php echo $pageTitle?></h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="save();">保存</a></li>
				  <li class="back"><a href="/videos_zhui/index">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			
            <form method="POST" id="adForm" name="adForm" action="">
            
            <div  width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
				     <li><label>wiki_id:&nbsp;&nbsp;</label><input id="wiki_id" type='text' name='wiki_id' ></li>
				     <li><label>wiki_name:&nbsp;&nbsp;</label><input id="wiki_name_re" type='text' name='wiki_name_re' ><input id="wiki_name" type='hidden' name='wiki_name' ></li>
				     <li><label>导演:&nbsp;&nbsp;</label><input id="director" type='text' name='director' disabled="disabled" ></li>
				     <li><label>总集数:&nbsp;&nbsp;</label><input  type='text' name='total' ></li>
				     <?php foreach($videoClass as $k => $v):?>
				     <li><label><?php echo $v;?>抓取地址:&nbsp;&nbsp;</label><input  type='text' name='<?php echo $k;?>' ></li>
				     <?php endforeach; ?>
				  </ul>
				</div>
              </div>
            </div> 
			</form>
        </div>
      </div>
