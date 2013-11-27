<?php use_javascript('jquery.jeditable.js')?>
<?php use_stylesheet('auto_complete.css')?>
<script type="text/javascript">
$(document).ready(function() {
  $(".sf_admin_list_th_wiki").editable("<?php echo url_for('video/ajaxsave')?>", {
      name: 'wiki_id',
      type  : 'text',
      loadtype: 'POST',
      select : true,
      submitdata: {'model': '<?php echo $model?>' },
      submit : '修改',
      cancel : '取消'
  });
  
  $(".sf_admin_list_th_wiki").bind('keyup', function(){
    var input = $(this).find('input[name=wiki_id]');
    $.post("<?php echo url_for('video/loadWiki') ?>",
           {"query": input.val() },
           function(html){
               $(".sf_admin_list_th_wiki").find('div').remove();
               input.after(html);
           }
       );
  })
});

function checkAll()
{
    var flag    = $("#sf_admin_list_batch_checkbox").attr('checked');
    var box     = $("input[type=checkbox]");
    if (flag) {
        box.attr('checked',true);
    }else{
        box.attr('checked',false);
    }
}

function submitform(action){
    if (action) {
        document.adminForm.batch_action.value=action;
    }
    if (typeof document.adminForm.onsubmit == "function") {
        document.adminForm.onsubmit();
    }
    document.adminForm.submit();
}
//清楚搜索结果重新加载
function clearSearch(button){
    var form = $(button).parents();
    form.children('input[type=text]').val('');
    form.submit();
}

</script>
  <div id="content">
    <div class="content_inner">
      <?php include_partial('toolbarList',array("pageTitle"=>$pageTitle))?>
      <?php include_partial('global/flashes') ?>
        <div class="table_nav">
          <form method="get" action="">
            <label>名称：</label>
            <input name="q" value="<?php echo $q?>" type="text">
            <input type="submit" value="查询">&nbsp;
            <input type="button" value="清空" onClick="clearSearch(this)">&nbsp;
         <form>
          <a<?php echo ('film' == $model) ? ' class="active"' : ''?>  href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getPage().'&model=film')?>"><strong>电影</strong></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a<?php echo ('teleplay' == $model) ? ' class="active"' : ''?> href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getPage().'&model=teleplay')?>"><strong>电视剧</strong></a>&nbsp;&nbsp;|&nbsp;&nbsp;
          <a<?php echo ('television' == $model) ? ' class="active"' : ''?> href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getPage().'&model=television')?>"><strong>栏目</strong></a>
          <div class="paginator">
            <span class="first-page"><a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getFirstPage(). ($model ? "&model=".$model : ""));?>">最前页</a></span>
            <span class="prev-page"><a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getPreviousPage(). ($model ? "&model=".$model : ""));?>">上一页</a></span>
            <span class="pages">
              <?php $links  = $video->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $video->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for('video/temp?site='.$site.'&page='.$value . ($model ? "&model=".$model : ""));?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
            </span>
            <span class="next-page"> <a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getNextPage(). ($model ? "&model=".$model : ""));?>">下一页</a></span>
            <span class="last-page"><a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getLastPage(). ($model ? "&model=".$model : ""));?>">最末页</a></span>
            <span class="page-total">(页码 <?php echo $video->getPage();?>/<?php echo $video->getLastPage();?>)</span>
          </div>
          <div class="clear"></div>
        </div>
        <form method="post" name="adminForm" id="adminForm" action="<?php echo url_for('video/delete');?>">
        <table cellspacing="0">
          <thead>
            <tr>
              <th scope="col" class="list_checkbox" style="width: 5%;"><input type="checkbox" onclick="checkAll();" name="toggle" id="sf_admin_list_batch_checkbox"></th>
              <th scope="col" class="list_id" style="width: 20%;">标题</th>
              <th scope="col" class="list_id" style="width: 35%;">维基</th>
              <th scope="col" class="list_id" style="width: 20%;">查看维基</th>
              <th scope="col" class="list_created_at" style="width: 15%;">创建时间</th>
              <th scope="col" class="list_operation" style="width: 5%;">操作</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll();" name="toggle" id="sf_admin_list_batch_checkbox"></th>
              <th scope="col" class="list_id">标题</th>
              <th scope="col" class="list_id">维基</th>
              <th scope="col" class="list_id">查看维基</th>
              <th scope="col" class="list_created_at">创建时间</th>
              <th scope="col" class="list_operation">操作</th>
            </tr>
          </tfoot>
          <tbody>
            <?php foreach ($video->getResults() as $rs): ?>
            <tr>
              <td> <input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
              <td><a href="<?php echo $rs->getUrl()?>" target="_blank">
                   <?php echo $rs->getTitle();?>
                  </a>
                  <?php if(isset($show_mark)):?>
                    | <?php echo $rs->getMark()?>
                  <?php endif;?>
                  <?php if(isset($showCollectionNumber)):?>
                    | 共<?php echo count($rs->getVideos())?>集
                  <?php endif;?>
              </td>
              <?php $wiki = $rs->getWiki()?>
              <td id="<?php echo $rs->getId();?>" class="sf_admin_text sf_admin_list_th_wiki"><?php if(!empty ($wiki)):?><?php echo  $wiki->getTitle() .'|'. $wiki->getDisplayName()?><?php endif;?></td>
              <td>
              <?php if(!empty($wiki)):?>
                    <a  target="_blank" href="<?php echo url_for("wiki/edit?id=".$rs->getWikiId())?>"><?php echo  $wiki->getTitle()?></a>
              <?php else:?>
                  暂时没有关联
              <?php endif;?>
              </td>
              <td><?php echo $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
              <td><a class="delete" href="<?php echo url_for("video/delete?id=".$rs->getId().'&model='.$model)?>" onclick="return confirm('确定删除吗?')">删除</a></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
          <div class="paginator">
            <span class="first-page"><a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getFirstPage(). ($model ? "&model=".$model : ""));?>">最前页</a></span>
            <span class="prev-page"><a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getPreviousPage(). ($model ? "&model=".$model : ""));?>">上一页</a></span>
            <span class="pages">
              <?php $links  = $video->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $video->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for('video/temp?site='.$site.'&page='.$value . ($model ? "&model=".$model : ""));?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
            </span>
            <span class="next-page"> <a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getNextPage(). ($model ? "&model=".$model : ""));?>">下一页</a></span>
            <span class="last-page"><a href="<?php echo url_for('video/temp?site='.$site.'&page='.$video->getLastPage(). ($model ? "&model=".$model : ""));?>">最末页</a></span>
            <span class="page-total">(页码 <?php echo $video->getPage();?>/<?php echo $video->getLastPage();?>)</span>
          </div>
          <div class="clear"></div>
      </form>
    </div>
  </div>