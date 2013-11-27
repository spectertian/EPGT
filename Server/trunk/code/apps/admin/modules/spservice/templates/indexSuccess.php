<style>
.show{display:on;}
.hide{display:none;}
</style>
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

function batchDelete()
{
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('spservice/batchDelete')?>";
    admin_form.submit();
}

function batchSendCms()
{
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('spservice/batchSendCms')?>";
    admin_form.submit();
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
</script>
<script>
var innnerHtm;
$(function() {
    $('.jc').each(function(){
    	var id_ = $(this).attr('val');
  	  $(this).click(function(){
	      var breakif = '';
	  	  $('._status_').each(function(){
	    	  if($(this).attr('status') == 'on'){
	      		breakif = 'yes';
	  	  	}
	  	  });
	  	  
	  	  if(breakif == ''){
	    	  var id = $(this).attr('val');
	    	  var text = $(this).attr('text');
	      	//$(param).html("<input id='_spcode_' name='_spcode_' value='"+text+"'>");
	      	$('.jc_'+id).removeClass('hide');
	      	$('.jc_'+id).addClass('show');
	      	$(this).removeClass('show');
	      	$(this).addClass('hide');
            innnerHtm = $('#'+id).html();
	      	var types = $.trim($('.cao_'+text).eq(0).html());
	        $('#'+id).html('<a style="color:red" href="###" onclick="save_(\''+id+'\');">保存</a> | <a style="color:#999" href="###" onclick="canel_(\''+id+'\',\''+types+'\',\''+text+'\');">取消</a>');
	        $('#'+id).attr('status','on');
	        $('#_spcode_').val('').focus().val(text);
	  	  }
  	  });

    	$("#_spcode_"+id_).bind('keyup change',function(){
    		var name = $(this).val();
    		//alert(name);
    		if(name != ''){
   		    $.ajax({
  		        url: '<?php echo url_for('spservice/GetSpcodeByName')?>',
  		        type: 'post',
  		        dataType: 'json',
  		        data: {'name': name},
  		        success: function(data){
  		        	var newArr = [];
  	  		      $.each(data,function(i,j){
    	  		    	newArr.push(j);
  	  	  		  });
  	  	  		  showc_("_spcode_"+id_,newArr);
  		        	$("#_spcode_"+id_).autocomplete({
  		                source: newArr
  		          });
  		        }
  		    });
    		}
      });
    });
});

function showc_(id,data){
	$("#"+id).autocomplete({
        source: data
  });
}

function save_(id){
	var name = $("#_spcode_"+id).val();
	   $.ajax({
	        url: '<?php echo url_for('spservice/SaveChannelCode')?>',
	        type: 'post',
	        dataType: 'text',
	        data: {'name': name,'id':id},
	        success: function(data){
		        if(data=='1'){
		        	window.location.reload();
			      }else if(data=='2'){
			    	  alert('保存失败！');
				    }
	        }
	    });
}

function toAction(obj){
    var juge = obj.val();
    var channelId = obj.attr("channelid");
    switch (juge){
        case "edit":
            //alert(channelId);
            window.location.href = "/spService/edit?id="+channelId;
            break;
        case "delete":
            if(confirm('确定删除吗？')){window.location.href = "/spService/delete?id="+channelId;return false;}
            break;
        case "showProgram":
            window.open("/program?channel_code="+obj.attr("channelcode"));
            break;
    }

}

function canel_(id,type,code){
	var myDate = new Date();
	var mycurrte = myDate.getFullYear()+'-'+(myDate.getMonth()+1)+'-'+myDate.getDate(); 
	$('#'+id).html(innnerHtm);
	$('#'+id).attr('status','');
	$('#show'+id).removeClass('hide');
	$('#show'+id).addClass('show');
	$('.jc_'+id).removeClass('show');
	$('.jc_'+id).addClass('hide');
	//window.location.reload();
}
</script>
    <div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarlist',array('pageTitle'=>$pageTitle))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
              <?php include_partial('search', array('haveCode'=>$haveCode)); ?>
 <form action=""  method="post" name='adminForm' id='adminForm'>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('/spService/index?page='.$pager->getFirstPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('/spService/index?page='.$pager->getPreviousPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('/spService/index?page='.$page.'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('/spService/index?page='.$pager->getNextPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('/spService/index?page='.$pager->getLastPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" style='width:5%'><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_action" style='width:10%'>SP_CODE</th>
                  <th scope="col" class="list_model" style='width:15%'>名称</th>
                  <th scope="col" class="list_model" style='width:10%'>频道ID</th>
                  <th scope="col" class="list_updated_at" style='width:21%'>channel_code</th>
                  <th scope="col" class="list_updated_at" style='width:10%'>更新时间</th>
                  <th scope="col" class="list_action" style='width:15%'>操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_action">SP_CODE</th>
                  <th scope="col" class="list_model">名称</th>
                  <th scope="col" class="list_model">频道ID</th>
                  <th scope="col" class="list_updated_at">channel_code</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  foreach ($pager->getResults() as $i => $rs):?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td><a href="<?php echo '/sSservice/edit?id='.$rs->getId();?>"><?php echo $rs->getSpCode();?></a></td>
                              <td><?php echo $rs->getName();?>
                                  <span style='padding-left:10px'>
                                  <?php if ($rs->getTags()): ?>
                                  <?php $jj=0; ?>
                                  <?php foreach($rs->getTags() as $t): ?>
                                   <?php
                                        if($jj==0){
                                          $type=$t;
                                        }
                                   ?>
                                   <span class='cao_<?php echo $rs->getChannelCode(); ?>' style='color:red;padding-left:10px'>
                                     <?php echo $t; ?>
                                   </span>
                                  <?php $jj++;?>
                                  <?php endforeach; ?>
                                  <?php endif;?>
                                  </span>
                              </td>
                              <td><?php echo $rs->getChannelID();?></td>
                              <td>
                                <li style='width:150px' id='show<?php echo $rs->getId(); ?>' style='display: on' class='jc show' text='<?php echo $rs->getChannelCode(); ?>' val='<?php echo $rs->getId(); ?>'><?php if($rs->getChannelCode()){echo ($rs->getChannelCode());}else{echo '暂无';} ?></li>
                                <li class='jc_<?php echo $rs->getId(); ?> hide' text='<?php echo $rs->getChannelCode(); ?>' val='<?php echo $rs->getId(); ?>'><input id='_spcode_<?php echo $rs->getId(); ?>' name='_spcode_' type='text' value='<?php echo ($rs->getChannelCode()); ?>'/></li>
                              </td>
                              <td><?php echo ($updated_at = $rs->getUpdatedAt()) ? $updated_at->format("Y-m-d H:i:s") : $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                              <td>
                                  <li id='<?php echo $rs->getId(); ?>'>
                                  <select channelid='<?php echo $rs->getId(); ?>' channelcode="<?php echo $rs->getChannelCode(); ?>" onchange="toAction($(this))">
                                      <option>请选择</option>
                                      <option value="edit">编辑</a></option>
                                      <option value="delete">删除</a></option>
                                      <option value="showProgram">查看节目</option>
                                  </select>
                                  </li>
                              </td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
                
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('/spservice/index?page='.$pager->getFirstPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('spservice/index?page='.$pager->getPreviousPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('/spservice/index?page='.$page.'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('/spservice/index?page='.$pager->getNextPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('/spservice/index?page='.$pager->getLastPage().'&name='.$name.'&type_='.$type_.'&haveCode='.$haveCode);?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>
<script>
$(function(){
	var type = "<?php echo $type_; ?>";
	var name = '<?php echo $name; ?>';
	$("#select_id ").val(type);
	$("#name__ ").val(name);
});

function getremove(){
	$("#select_id ").val('0');
	$("#name__ ").val('');
	$("#haveCode ").val('0');
}
</script>