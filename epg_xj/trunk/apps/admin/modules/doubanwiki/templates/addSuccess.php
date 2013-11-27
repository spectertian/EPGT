<script type="text/javascript">
$(document).ready(function(){
	//wiki_title下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-08-14 13:20:00
    $('#wiki_title').simpleAutoComplete('<?php echo url_for('tvsoumatch_wiki/loadWiki') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_title',
        max       : 20
    }, function (date){
    	 var date = eval("("+date+")");
         var id = date.id;
         $('#wiki_id').attr('value',id);
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
				  <li class="back"><a href="/tvsoumatch_wiki/index">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
			
            <form method="POST" id="adForm" name="adForm" action="">
            
            <div  width:65%;">
              <div class="widget">
                <h3>基本资料</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
				     <li><label>tvsou_id:&nbsp;&nbsp;</label><input  type='text' name='tvsou_id' ></li>
					 <li><label>维基名称&nbsp;&nbsp;</label>
					 	<input id='wiki_title' type='text' name='wiki_title'>
					 	<input id='wiki_id' type="hidden" name='wiki_id'>
					 </li>
				  </ul>
				</div>
              </div>
            </div> 
			</form>
        </div>
      </div>
