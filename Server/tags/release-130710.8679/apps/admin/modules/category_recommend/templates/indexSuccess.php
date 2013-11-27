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
 </script>
 
 <div id="content">
	<?php //include_partial('select', array('m' => $m,'j'=>$j))?>
	<div class="content_inner">
	 <header>
          <h2 class="content">分类推荐</h2>
          <nav class="utility">
            <li class="add"><a href="<?php echo url_for("category_recommend/add")?>">添加</a></li>
       
            <li class="delete"><a class="toolbar" onclick="javascript:submitform('batchDelete')" href="#">删除</a></li>
         
            </nav>
        </header>
<form method="post" name="adminForm" action="">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("category_recommend/index?page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("category_recommend/index?page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("category_recommend/index?page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("category_recommend/index?page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("category_recommend/index?page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>
		</div>

            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id">分类</th>
                  <th scope="col" class="list_model">模型</th>
				  <th scope="col" class="list_default_at">默认</th>
                  <th scope="col" class="list_startime_at">开始时间</th>
                  <th scope="col" class="list_endtime_at">结束时间</th>
                </tr>
              </thead>
			  <?php foreach ($pager->getResults() as $key => $rs): ?>
				 <?php $category = $rs; ?>
                        
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getCategory();?>" name="id[]"></td>
                              <td><?php echo $category->getCategory();?></td>
                              <td><?php echo $category->getTemplate();?></td>
                              <td><?php echo $category->getIs_detault();?></td>
							  <td><?php echo $category->getStartime();?></td>
							  <td><?php echo $category->getEndtime();?></td>
                            </tr>
                        
             <?php endforeach;?>
              
            </table> 
			 
      </div>
 </form>

	 