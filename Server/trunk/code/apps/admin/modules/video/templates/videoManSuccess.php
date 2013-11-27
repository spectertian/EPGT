<script>
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
    document.adminForm.submit();
}

function (){
	
}
</script>
<?php include_partial("screenshots"); ?>
<div id="content">
    <div class="content_inner">
        <header>
        <h2 class="content">列表：<?php echo $pageTitle;?></h2>
            <nav class="utility">
            <?php 
                preg_match('/.*(baidu).*/', $site,$match);
                if(!$match):
            ?>
            <li class="add"><a class="toolbar add-videolist" href="<?php echo url_for('video/AddVideo?id='.$wiki_id)?>">添加</a></li>
            <?php endif; ?>
            <li class="back"><a href="<?php echo ("/wiki/edit?id=".$wiki_id)?>">返回WIKI</a></li>
        	<li class="delete"><a class="toolbar" onclick="javascript:if(confirm('确定还是删除？')){submitform()}" href="###">删除</a></li>
            </nav>
        </header>
      <?php include_partial('global/flashes') ?>
        <div class="table_nav">
          <div class="paginator">
            <span class="first-page"><a href="<?php echo ('/video/videoMan?site='.$site.'&wiki_id='.$wiki_id.'&page='.$video->getFirstPage(). ($model ? "&model=".$model : ""));?>">最前页</a></span>
            <span class="prev-page"><a href="<?php echo ('/video/videoMan?site='.$site.'&wiki_id='.$wiki_id.'&page='.$video->getPreviousPage(). ($model ? "&model=".$model : ""));?>">上一页</a></span>
            <span class="pages">
              <?php $links  = $video->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $video->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo ('/video/videoMan?site='.$site.'&wiki_id='.$wiki_id.'&page='.$value . ($model ? "&model=".$model : ""));?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
            </span>
            <span class="next-page"> <a href="<?php echo ('/video/videoMan?site='.$site.'&wiki_id='.$wiki_id.'&wiki_id='.$wiki_id.'&page='.$video->getNextPage(). ($model ? "&model=".$model : ""));?>">下一页</a></span>
            <span class="last-page"><a href="<?php echo ('/video/videoMan?site='.$site.'&wiki_id='.$wiki_id.'&page='.$video->getLastPage(). ($model ? "&model=".$model : ""));?>">最末页</a></span>
            <span class="page-total">(页码 <?php echo $video->getPage();?>/<?php echo $video->getLastPage();?>)</span>
          </div>
          <div class="clear"></div>
        </div>
<table cellspacing="0">
          <thead>
            <tr>
              <th scope="col" class="list_checkbox" style="width: 5%;"><input type="checkbox" onclick="checkAll();" name="toggle" id="sf_admin_list_batch_checkbox"></th>
              <th scope="col" class="list_id" style="width: 25%;">标题</th>
              <th scope="col" class="list_id" style="width: 10%;">类型</th>
              <th scope="col" class="list_id" style="width: 10%;">剧集</th>
              <th scope="col" class="list_created_at" style="width: 25%;">创建时间</th>
              <th scope="col" class="list_operation" style="width: 20%;">操作</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll();" name="toggle" id="sf_admin_list_batch_checkbox"></th>
              <th scope="col" class="list_id">标题</th>
              <th scope="col" class="list_id"">类型</th>
              <th scope="col" class="list_id">剧集</th>
              <th scope="col" class="list_created_at">创建时间</th>
              <th scope="col" class="list_operation">操作</th>
            </tr>
          </tfoot>
        <tbody>
            <?php foreach ($video->getResults() as $rs): ?>
            <tr>
            <form action='/video/delVideo' method='post' id='adminForm' name='adminForm'>
              <input type='hidden' name='ref' value='<?php echo $site ?>'>
              <input type='hidden' name='wiki_id' value='<?php echo $wiki_id ?>'>
              <td> <input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
              <td id='title<?php echo $rs->getId(); ?>'><a href="<?php echo $rs->getUrl()?>" target="_blank">
                   <?php echo $rs->getTitle();?>
                  </a>
                  <?php if(isset($show_mark)):?>
                    | <?php echo $rs->getMark()?>
                  <?php endif;?>
              </td>
              <td>
                  <?php if ($rs->getModel()=='teleplay') echo '电视剧'; else echo '栏目';  ?>
              </td>
              <!--<?php $wiki = $rs->getWiki()?>
              <td id="<?php echo $rs->getId();?>" class="sf_admin_text sf_admin_list_th_wiki"><?php if(!empty ($wiki)):?><?php echo  $wiki->getTitle() .'|'. $wiki->getDisplayName()?><?php endif;?></td>
              -->
              <td id='mark<?php echo $rs->getId(); ?>'><?php echo $rs->getMark(); ?></td>
              
              <td><?php echo $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
              <td>
                  <a class="delete" href="<?php echo ("/video/delVideo?id=".$rs->getId().'&model='.$model.'&wiki_id='.$rs->getWikiId().'&ref='.$rs->getReferer());?>" onclick="return confirm('确定删除吗?')">删除</a>
                  <span class='change' id='change<?php echo $rs->getId(); ?>'><a href='javascript:void(0)' onclick='editvideo("<?php echo $rs->getId() ?>");'>修改</a></span>
              </td>
                <script>
                function editvideo(param){
                	var status = '';
                	$('.change').each(function(){
                	    if($(this).html() == '<a id="savetext" href="###">保存</a>'){
                	        status = 'yes';
                    	}
                    })
                    if(status == 'yes'){return false;}
                    var changetext = $('#change'+param).html();
                    var title = $('#title'+param).find('a').text().replace(/^\s+|\s+$/g, "");
                    var orgtitle = $('#title'+param).html();
                    var mark  = $('#mark'+param).text();
                    var orgmark  = $('#mark'+param).html();
                    $('#title'+param).html("<input id='tvalue"+param+"' type='text' name='title' value='"+title+"'>");
                    $('#mark'+param).html("<input id='mvalue"+param+"' type='text' name='mark' value='"+mark+"'>");
                    $('#change'+param).html('<a id="savetext" href="###">保存</a>');
                    
                    $(function(){
                        $('#savetext').click(function(){
                            //save
                            var tvalue = $('#tvalue'+param).val();
                            var mvalue = $('#mvalue'+param).val();
                            $.post(
                                    '/video/videoAjaxEdit'
                                    ,{
                                        id:param,
                                        title:tvalue,
                                        mark:mvalue
                                     }
                                    ,function(state){
                                        //alert(state);
                                        location.reload();
                                    	//alert('2');
                                     }
                                    ,'text')
                            //changetext
                        	//$('#change'+param).html(changetext);
                        	//$('#title'+param).html(orgtitle);
                        	//$('#title'+param).find('a').text(tvalue);
                        	//$('#mark'+param).html(orgmark);
                        	//$('#mark'+param).text(mvalue);
                        })
                    });
                }
                </script>
            </tr>
            <?php endforeach;?>
          </tbody>
          </table>
          </form>