<script type="text/javascript">
$(document).ready(function(){
	//wiki_title下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-08-14 13:20:00
    $('#wiki_title').simpleAutoComplete('<?php echo url_for('videos_zhui/loadWiki') ?>',{
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
				     <input type='hidden' name='id' value='<?php echo $id; ?>' >
				     <li><label>tvsou_id&nbsp;:&nbsp;&nbsp;</label><?php echo $videos_zhuis->getWikiId(); ?></li>
					 <li><label>维基名称：</label><?php echo $videos_zhuis->getWikiName(); ?></li>
					 <li><label>总集数&nbsp;:&nbsp;&nbsp;</label>
					 	<input id='total' type='text' name='total' value='<?php echo $videos_zhuis->getTotal(); ?>'>
					 </li>
					 <?php foreach ($videos_zhuis->getSource() as $k => $v) :?>
						 <li><label><?php echo $videoClass[$k];?>抓取地址：&nbsp;&nbsp;&nbsp;</label>
						 <input id='<?php echo $k.'_url';?>' type='text' name="<?php echo $k.'_url';?>" value='<?php echo $v['url']; ?>'>
						 <?php //echo $v['url'];?>
						 </li>
					 <?php endforeach;?>
					 <li><label>已抓取集数&nbsp;:&nbsp;&nbsp;</label><?php echo $videos_zhuis->getLocal(); ?></li>
					 <li><label>状态：</label>
					 抓取中 :<input type='radio' name='state' value='1' <?php if($videos_zhuis->getState() == '1' || $videos_zhuis->getState() == '0') echo 'checked' ;?> > &nbsp;&nbsp;&nbsp;&nbsp;
					 抓取完成 :<input type='radio' name='state' value='2' <?php if($videos_zhuis->getState() == '2') echo 'checked' ;?> >
					 </li>
					 <li><label>最后更新时间：</label><?php echo $videos_zhuis->getUpdatedAt()?$videos_zhuis->getUpdatedAt()->format("Y-m-d H:i:s"):$videos_zhuis->getCreatedAt()->format("Y-m-d H:i:s"); ?></li>
				  </ul>
				</div>
              </div>
            </div> 
			</form>
        </div>
      </div>
