<script type="text/javascript">

$(document).ready(function(){

    $('.datepicker_s').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });

    $('.datepicker_e').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });
    
});

//
////
//$('.datepicker_s').live('click', function() {
////		alert('sss');
//	// Live handler called. 
//	});
//
//$('.datepicker_e').live('click', function() {
//	// Live handler called. 
//	});

function mySubmit(flag)
{  
	var start_time = $('.datepicker_s').val();
	var end_time   = $('.datepicker_e').val();
	$('#re_start_time').val(start_time);
	$('#re_end_time').val(end_time);

	if(start_time=='起始日期')
	{
		$('#re_start_time').attr("value","") ;
	}
	if(end_time=='结束日期')
	{
		$('#re_end_time').attr("value","");
	}
	return true;

      
}  

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
	$('#adminForm').submit();
}
</script>
    <div id="content">
      <div class="content_inner">
        <header>
          <h2 class="content">分类推荐</h2>
          <nav class="utility">
            <li class="add"><a href="<?php echo url_for("category_recommends/index")?>">添加</a></li>
            <li class="delete"><a class="toolbar" onclick="javascript:submitform('batchDelete')" href="#">删除</a></li>
            </nav>
        </header>
        <!--  
         <form action="/category_recommends/batchDelete" id='adminForm'  method="post">
         -->
            <?php //include_partial('toolbarlist',array('pageTitle'=>$pageTitle))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
			<?php include_partial('select', array('name'=>$name,'category'=>$category,'start_time'=>$start_time,'end_time'=>$end_time))?>
		<form action="/category_recommends/batchDelete" id='adminForm'  method="post">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/category_recommends/list?page='.$pager->getFirstPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/category_recommends/list?page='.$pager->getPreviousPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/category_recommends/list?page='.$page.'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/category_recommends/list?page='.$pager->getNextPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/category_recommends/list?page='.$pager->getLastPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_name">名称</th>
                  <th scope="col" class="list_category">分类标签</th>
                  <th scope="col" class="list_is_default">是否默认</th>
                  <th scope="col" class="list_start_time">起始时间</th>
                  <th scope="col" class="list_end_time">结束时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_name">名称</th>
                  <th scope="col" class="list_category">分类标签</th>
                  <th scope="col" class="list_is_default">是否默认</th>
                  <th scope="col" class="list_start_time">起始时间</th>
                  <th scope="col" class="list_end_time">结束时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><?php echo $rs->getName();?></td>
                              <td><?php echo $rs->getCategory();?></td>
                              <td><?php echo $rs->getIsDefault()?'是':'否';?></td>
                              <td><?php echo $rs->getStartTime();?></td>
                              <td><?php echo $rs->getEndTime(); ?></td>
                              <td><a href="<?php echo "/category_recommends/edit/id/".$rs->getId()?>" target="_blank" >编辑</a>&nbsp;&nbsp;<a href="<?php echo "/category_recommends/preview/id/".$rs->getId()?>" target="_blank" >预览</a>&nbsp;&nbsp;<a href="<?php echo "/category_recommends/delete?id=".$rs->getId()?>" onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo '/category_recommends/list?page='.$pager->getFirstPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo '/category_recommends/list?page='.$pager->getPreviousPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo '/category_recommends/list?page='.$page.'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo '/category_recommends/list?page='.$pager->getNextPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo '/category_recommends/list?page='.$pager->getLastPage().'&name='.$name.'&category='.$category.'&start_time='.$start_time.'&end_time='.$end_time;?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
</form>
        </div>
      </div>