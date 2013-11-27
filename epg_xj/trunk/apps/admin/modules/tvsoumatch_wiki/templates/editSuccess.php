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
				     <input type='hidden' name='id' value='<?php echo $id; ?>' >
				     <li><label>tvsou_id&nbsp;:&nbsp;&nbsp;</label><a target="_blank" href="http://jq.tvsou.com/introhtml/<?php echo substr($tvsoumatch_wikis->getTvsouId(),0,-2);?>/index_<?php echo $tvsoumatch_wikis->getTvsouId();?>.htm"><?php echo $tvsoumatch_wikis->getTvsouId(); ?></a></li>
					 <li><label>Tvsou节目名称：</label><?php echo $tvsoumatch_wikis->getTvsouTitle(); ?></li>
					 <li><label>维基名称&nbsp;:&nbsp;&nbsp;</label>
					 	<input id='wiki_title' type='text' name='wiki_title' value='<?php echo $tvsoumatch_wikis->getWikiTitle(); ?>'>
					 	<input id='wiki_id' type="hidden" name='wiki_id' value='<?php echo $tvsoumatch_wikis->getWikiId(); ?>'>
					 </li>
					 <li><label>作者：</label><?php $names = $tvsoumatch_wikis->getAuthor();echo $names['user_name']; ?></li>
					 <li><label>最后更新时间：</label><?php echo $tvsoumatch_wikis->getUpdatedAt()?$tvsoumatch_wikis->getUpdatedAt()->format("Y-m-d H:i:s"):$tvsoumatch_wikis->getCreatedAt()->format("Y-m-d H:i:s"); ?></li>
				  </ul>
				</div>
              </div>
            </div> 
			</form>
        </div>
      </div>
