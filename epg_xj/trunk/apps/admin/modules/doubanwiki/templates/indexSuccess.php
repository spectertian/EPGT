<script type="text/javascript">

//全选
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
	return;
}
$(function(){
	$('#zouzhe').click(function(){
	    var title = $('#title_').val();
	    var douban_status = $('#douban_status').val();
	    
		if(title=='' && douban_status==''){
			alert('请选择条件！！！');
		}else{
		    window.location.href='/doubanwiki/index?title='+title+'&douban_status='+douban_status;
		}
    })
})

function goSave(){
	   var checks = "";
	    $("input[name='id[]']").each(function(){
	        if($(this).attr("checked") == true){
	            checks += $(this).val() + ",";
	        }
	    });
	    if(checks!=''){
	    	$.post(
	 	    	   "/doubanwiki/save",
	 	    	   { checks: checks },
	     		   function(s){
	                    location.reload();
	     		   },
	     		   "text"
		    );
		}
	    
}
</script>
    <div id="content">
      <div class="content_inner">
        
        <header>
          <h2 class="content"><?php echo $pageTitle; ?></h2>
          <nav class="utility">
          	<li class="save"><a href="javascript:void(0)" onclick="goSave();">编辑确认</a></li>
            </nav>
        </header>
        状态：<select id='douban_status'>
            <option value='' <?php if ($douban_status=='') echo 'selected';?>>请选择</option>
            <option value='1' <?php if ($douban_status=='1') echo 'selected';?>>无匹配</option>
            <option value='2' <?php if ($douban_status=='2') echo 'selected';?>>自动匹配</option>
            <option value='3' <?php if ($douban_status=='3') echo 'selected';?>>编辑确认</option>
        </select>&nbsp;&nbsp;&nbsp;&nbsp;
        名称：<input type='text' id='title_' name='title' value='<?php echo $title ?>'><button id='zouzhe' style='width:40px'>查询</button>
            <script>
              $(function(){
          	      $('.wikiinfo').each(function(){
        	    	  $(this).keypress( function(e) {
                          var key = window.event ? e.keyCode : e.which;
                          if(key.toString() == "13"){
                                    return false;
                          }
                      });
              	      var j = $(this).attr('attrval');
            	      $(this).click(function(){
            	    	  $(this).attr('style','');
          	    	      $(this).focus();
          	    	      var me = $(this);
            	    	  $(this).simpleAutoComplete('<?php echo url_for('doubanwiki/loadWiki') ?>',{
              	    	        autoCompleteClassName: 'autocomplete',
              	    	        autoFill: false,
              	    	        selectedClassName: 'sel',
              	    	        attrCallBack: 'rel',
              	    	        identifier: 'wiki_title',
              	    	        max       : 20
              	    	    }, function (date){
              	    	    	 var date = eval("("+date+")");
              	    	         var id = date.id;
              	    	         $('#wiki_id_'+j).val(id);
              	    	    });
                	  });
            	      $(this).blur(function(){

            	    	  var wiki_id = $('#wiki_id_'+j).val();
            	    	  var douban_id = $('#douban_id_'+j).val();
            	    	  if($('#wiki_id_'+j).val()!=''){
                	    	  $.ajax({
              	    	        url: '/doubanwiki/index',
              	    	        type: 'post',
              	    	        dataType: 'text',
              	    	        data: {'wiki_id': wiki_id,'douban_id': douban_id},
              	    	        success: function(data){
              	    				//location.reload();
              	    	        	$("#show_"+j).fadeIn(1000);
              	    	        	$("#show_"+j).fadeOut(1000);
              	    	        },
              	    	      });
            	    	  }
            	    	  $(this).attr('style','border:none');
                	  })
              	  })
              })
              </script>
		
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getFirstPage(); ?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getPreviousPage(); ?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$page; ?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getNextPage(); ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getLastPage(); ?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_name">名称</th>
                  <th scope="col" class="list_name">导演演员</th>
                  <th scope="col" class="list_category">维基名称</th>
                  <th scope="col" class="list_start_time">更新时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_name">名称</th>
                  <th scope="col" class="list_name">导演演员</th>
                  <th scope="col" class="list_category">维基名称</th>
                  <th scope="col" class="list_start_time">更新时间</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  $j = 0;
                  foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><?php echo '<font color="green">名称: </font>'.$rs->getTitle(); ?><br>
                                  <?php 
                                      
                                      if($aka = $rs->getAka()){
                                          echo '<font color="green">别名: </font>';
                                          foreach($aka as $k=>$v){
                                              echo $v.'  ';
                                          }
                                      }  
                                  ?>
                              </td>
                              <td>
                                  <font color="green">导演: </font>
                                  <?php 
                                      $directors = $rs->getDirectors();
                                      if(!empty($directors)){
                                          foreach($directors as $v){
                                              if(isset($v['name'])){
                                                  echo $v['name'].' ';
                                              }
                                          }
                                      }else{
                                          echo "<font color='red'>暂无信息</font>";
                                      }
                                  ?><br>
                                  <font color="green">演员: </font>
                                  <?php 
                                      $casts = $rs->getCasts();
                                      if(!empty($casts)){
                                          foreach($casts as $v){
                                              if(isset($v['name'])){
                                                  echo $v['name'].' ';
                                              }
                                          }
                                      }else{
                                          echo "<font color='red'>暂无信息</font>";
                                      }
                                  ?>
                              </td>
                              <td>
                                  <input  attrval='<?php echo $j; ?>' class='wikiinfo' style='border:none' name='wikiinfo' type='text'  value='<?php echo $rs->getWikiTitle(); ?>' >
                                  <span id="show_<?php echo $j; ?>" style='display:none'><font color='green'>关联成功</font></span>
                                  <input  id='wiki_id_<?php echo $j; ?>' name='wiki_id' value='' type='hidden'>
                                  <input  id='douban_id_<?php echo $j; ?>' name='douban_id' value='<?php echo $rs->getDoubanId(); ?>' type='hidden'>
                              </td>
                              <td><font color="green">导演: </font>
                              <?php 
                                  $director = $rs->getWikiDirector();
                                      if(!empty($director)){
                                          foreach($director as $val){
                                              echo $val.'  ';
                                          }
                                      }else{
                                          echo "<font color='red'>暂无信息</font>";
                                      }
                              ?>  <br>
                                  <font color="green">演员: </font>
                                  <?php 
                                      $starring = $rs->getWikiStarring();
                                      if(!empty($starring)){
                                          foreach($starring as $val){
                                              echo $val.'  ';
                                          }
                                      }else{
                                          echo "<font color='red'>暂无信息</font>";
                                      }
                                  ?>
                              </td>
                            </tr>
                            <?php $j++; ?>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getFirstPage(); ?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getPreviousPage(); ?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$page; ?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getNextPage(); ?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/doubanwiki/index?title='.$title.'&douban_status='.$douban_status.'&page='.$pager->getLastPage(); ?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
        </div>
      </div>