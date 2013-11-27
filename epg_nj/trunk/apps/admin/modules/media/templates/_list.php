<?php if ($popup): ?>
<script type="text/javascript">
function selectFile(file) {
     file_url = $(file).parent().parent().find('#show_file_info').find('span:eq(0)').text();
     key = $(file).parent().parent().find('#show_file_info').find('span:eq(1)').text();
     category_id = $(file).parent().parent().find('#show_file_info').find('span:eq(2)').text();
    rel = $(file).parent().parent().find('#show_file_info').attr("rel");
    if ( rel == 0) {
        $(file).parent().parent().find('#show_file_info').attr("rel",'selected');
    }else{
        $(file).parent().parent().find('#show_file_info').attr("rel",'');
    }
    $('#file_info').find('span:eq(0)').text(file_url);
    $('#file_info').find('span:eq(1)').text(key);

    var href = "<?php echo url_for("media/CutPic")?>?url="+file_url+"&category_id="+category_id;
    $("#cut_pic").attr("href",href);
}
/**
 * 选中图片
 */
$(document).ready(function(){
    $('.img-preview').click(function(){
         selectFile(this);
         rel = $(this).parent().parent().find("#show_file_info").attr("rel");
         if (rel != 'selected') {
            $(this).css('border','none');
         } else {
            $(this).css('border','1px #FB7F2C solid');
         }
    });
    $('.img-preview').bind('dblclick',function(){   //原是live，因会重复添加两次图片，改用bind
        selectFile(this);
        oneFileInsert(self.parent);
    });
});

</script>
<?php else:?>
<script type="text/javascript">

//封面裁剪弹出层
$(function(){
        $(".file-upload").fancybox({
		'width'				: 960,
		'height'			: 600,
		'autoScale'			: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'                  : 'iframe'
		//'autoDimensions'    : false
	});

        $(".img-preview").fancybox({
            'titlePosition'		: 'outside',
            'overlayColor'		: '#000',
            'overlayOpacity'	: 0.9
        });
})
</script>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function() {
    $("div.widget .paginator a").click(function() {
        var url = $(this).attr("href");
        $("#media_list").load(url, function() {
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        });
        return false;
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){

	$('#chaxun').click(function(){
		var source_name = $('#source_name').val();
		var urll = "<?php echo url_for('media/category_files?rand='.rand()) ?>";
        $("#media_list").load(urll,{source_name:source_name,popup:1}, function() {
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        });
        return false;
	})
	$('#chongshe').click(function(){
		$('#source_name').val('');
		$('#category_id_chaxun').val('');
		$('#page').val('');
		$('#popup').val('');
		$('#wiki_title').val('');
		var urll = "<?php echo url_for('media/category_files?rand='.rand()) ?>";
        $("#media_list").load(urll,{popup:0}, function() {
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        });
        return false;		
	})
	$('#chaxuntwo').click(function(){
		var wiki_title = $('#wiki_title').val();
		var urll = "<?php echo url_for('media/category_files?rand='.rand()) ?>";
        $("#media_list").load(urll,{wiki_title:wiki_title,popup:1}, function() {
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        });
        return false;	
	})
	$('#chongshetwo').click(function(){
		$('#source_name').val('');
		$('#category_id_chaxun').val('');
		$('#page').val('');
		$('#popup').val('');		
		$('#wiki_title').val('');
		var urll = "<?php echo url_for('media/category_files?rand='.rand()) ?>";
        $("#media_list").load(urll,{popup:0}, function() {
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        });
        return false;		
	})
	$('.deletepopup').click(function(){
		if(confirm('确定删除吗?')){
			var urll = "<?php echo url_for('media/delete');?>";
			var id = $(this).parent().find('input').val();
	        $("#media_list").load(urll,{popup:1,id:id}, function() {
	            tb_init('a.thickbox, area.thickbox, input.thickbox');
	        });
	        return false;		
		}
		else
			return false;
	})

})
</script>


<?php use_helper('Text') ?>
<div class="widget">
            <h3>文件列表</h3>
            <div class="widget-body file-manager">
              <ul>
              <?php foreach ($pager->getResults() as $attachment): ?>
                <li class="actived">
                  <div <?php  if($popup):?>style="height:110px"<?php endif;?> class="thumb">
                      <a href="<?php echo $attachment->getFileUrl(0,$_SERVER['HTTP_HOST']) ?>" onclick="return false;" class="img-preview" title="<?php echo $attachment->getSourceName() ?>">
                          <img src="<?php echo thumb_url($attachment->getFileName(),120,120,$_SERVER['HTTP_HOST'])?>" alt="<?php echo $attachment->getSourceName() ?>">
                      </a>
                  </div>
                  <?php if(!$popup):?><!-- 正常 -->
                  <div class="action"><input type="checkbox" value="<?php echo $attachment->getId() ?>" name="ids[]">
                      &nbsp;|&nbsp; <a href="<?php echo url_for("media/thumbnail?url=".$attachment->getFileUrl()."&category_id=".$attachment->getCategoryId())?>" onclick="return false;" class="file-upload">生成缩略图</a> &nbsp;
                      <!-- Modify by tianzhongsheng-ex@huan.tv 暂时关闭文件删除功能 Time 2013-04-27 10:35:00 
                      |&nbsp;  <a rel="#" href="<?php echo url_for('@attachments'). '/delete?id='.$attachment->getId().'&popup='.$popup ?>" class="delete"  onClick="return window.confirm('确定删除吗?');">删除 
                      -->
                    </a>
                  </div>
                  <?php else:?><!-- 弹出层 -->
                  <div class="action"  style="text-align: center;"><input class="idsdelete" type="checkbox" value="<?php echo $attachment->getId() ?>" name="ids[]">
                  <!-- Mmodify by tianzhongsheng-ex@huan.tv 暂时关闭文件删除功能 Time 2013-04-27 10:35:00
                  |&nbsp; <a style="color:red" rel="#" href="" class="deletepopup" >删除</a>
                  -->
                  </div>
                  <?php endif;?>
                  <div class="meta"><a href="<?php echo $attachment->getFileUrl() ?>" class="img-preview" onclick="return false;" title="<?php echo $attachment->getSourceName() ?>" ><?php echo truncate_text($attachment->getSourceName(),18)  ?></a></div>
                  <div id="show_file_info" style="display:none;" rel="0">
                    <span><?php echo $attachment->getFileUrl() ?></span>
                    <span><?php echo $attachment->getFileName() ?></span>
                    <span><?php echo $attachment->getCategoryId() ?></span>
                  </div>
                </li>
              <?php endforeach;?>
              </ul>

              <div class="paginator"style="float:left;margin-right:10px;">
			 
			  <label>文件名：</label>
			  <input name="source_name" id="source_name"  value="<?php echo $source_name;?>" type="text">
			  <a id='chongshe' href='javascript:void' style='color:green'>重设</a>
              <input type="button" value="查询" id="chaxun" > 
			  <input id='category_id_chaxun' type="hidden" name="category_id" value="<?php echo $category_id?>">
			  <input id='popup' type="hidden" name="popup" value="<?php echo $popup?>">
			   <label>移动到</label>
			  <select name="change_category_ida" id="change_category_ida">
                            <?php foreach( $categorys as $key => $category_name ): ?>
                                <option value="<?php echo $key ?>" <?php if($category_id==$key){ echo "selected";}?>>
                                    <?php echo $category_name ?>
                                </option>
                            <?php endforeach; ?>
              </select>
			  <span class="first-page">

                   <a href="<?php echo url_for('media/category_files?page='.$pager->getFirstPage()."&category_id=".$category_id."&popup=".$popup."&source_name=".$source_name."&wiki_title=".$wiki_title);?>">
                        最前页
                    </a>
                </span>
                <span class="prev-page">
                    <a href="<?php echo url_for('media/category_files?page='.$pager->getPreviousPage()."&category_id=".$category_id."&popup=".$popup."&source_name=".$source_name."&wiki_title=".$wiki_title);?>">
                    上一页
                    </a>
                </span>
                <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $key => $value):?>
                    <?php if ($value == $pager->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for('media/category_files?page='.$value."&category_id=".$category_id."&popup=".$popup."&source_name=".$source_name."&wiki_title=".$wiki_title);?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
                </span>
                <span class="next-page">
                    <a href="<?php echo url_for('media/category_files?page='.$pager->getNextPage()."&category_id=".$category_id."&popup=".$popup."&source_name=".$source_name."&wiki_title=".$wiki_title);?>">
                        下一页
                    </a>
                </span>
                <span class="last-page">
                    <a href="<?php echo url_for('media/category_files?page='.$pager->getLastPage()."&category_id=".$category_id."&popup=".$popup."&source_name=".$source_name."&wiki_title=".$wiki_title);?>">
                        最末页
                    </a>
                </span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
			<div class="paginator"style="float:left;margin-right:10px;">	
			  <label>节目名：</label>
			  <input name="wiki_title" id="wiki_title"  value="<?php echo $wiki_title;?>" type="text">
			  <a id='chongshetwo' href='javascript:void' style='color:green'>重设</a>
              <input type="button" value="查询" id="chaxuntwo" >
            </div>
              <div class="clear"></div>
            <div id="file_info" style="display:none;">
            <span>0</span>
            <span>0</span>
            </div>
          </div>