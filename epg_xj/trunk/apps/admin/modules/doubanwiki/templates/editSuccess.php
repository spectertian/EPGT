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
				     <li><label>douban_id&nbsp;:&nbsp;&nbsp;</label><?php echo $doubanwiki->getDoubanId(); ?></li>
					 <li><label>名称：</label><?php echo $doubanwiki->getName(); ?></li>
					 <li><label>维基名称&nbsp;:&nbsp;&nbsp;</label>
					 	<input id='wiki_title' type='text' name='wiki_title' value='<?php echo $doubanwiki->getWikiTitle(); ?>'>
					 	<input id='wiki_id' type="hidden" name='wiki_id' value='<?php echo $doubanwiki->getWikiId(); ?>'>
					 </li>
					 <li><label>导演：</label>
					     <?php 
                                      //echo implode($rs->getDirectors(),',');
                                      $directors = $doubanwiki->getDirectors();
                                      //print_r($directors);exit;
                                      if(!empty($directors)){
                                          foreach($directors as $v){
                                              if(isset($v['name'])){
                                                  echo $v['name'].' ';
                                              }
                                          }
                                      }
                                  ?>
					 </li>
					 <li><label>Title：</label><input type='text' name='title' value='<?php echo $doubanwiki->getTitle(); ?>'></li>
					 <li><label>又名：</label><input type='text' name='aka' value='<?php echo $doubanwiki->getAka(); ?>'></li>
					 <li><label>类型：</label><input type='text' name='subtype' value='<?php echo $doubanwiki->getSubtype(); ?>'></li>
					 <li><label>演员：</label><input type='text' name='casts' value='<?php
    					 $casts = $doubanwiki->getCasts();
    					 //print_r($directors);exit;
    					 if(!empty($casts)){
    					     foreach($casts as $v){
    					         if(isset($v['name'])){
    					             echo $v['name'].' ';
    					         }
    					     }
    					 }
					 ?>'></li>
					 
					 <li><label>最后更新时间：</label><?php echo $doubanwiki->getUpdatedAt()?$doubanwiki->getUpdatedAt()->format("Y-m-d H:i:s"):$doubanwiki->getCreatedAt()->format("Y-m-d H:i:s"); ?></li>
				  </ul>
				</div>
              </div>
            </div> 
			</form>
        </div>
      </div>
