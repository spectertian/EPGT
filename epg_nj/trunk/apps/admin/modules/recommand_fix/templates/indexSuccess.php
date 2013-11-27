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
    if (action) {
        document.adminForm.action=action;
    }
    document.adminForm.submit();
    
}
</script>
    <div id="content">
        <div class="content_inner">
            <form action="<?php echo url_for('wordsLog/batchDelete');?>" id="adminForm" name="adminForm" method="post" >
            <?php include_partial('toolbarlist',array('pageTitle'=>$pageTitle))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
			类型:
	 		<select name="type">
	 				<option value="">全部</option>
                    <?php foreach($arr_type as $key=>$value):?>
	 				<option value="<?php echo $key;?>" <?php echo $key==$type?'selected="selected"':''?>><?php echo $value;?></option>
	 				<?php endforeach;?>
			</select>
			<input type="submit" onclick="submitform('<?php echo url_for('recommand_fix/index')?>')" value="查询">

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('recommand_fix/index?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('recommand_fix/index?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('recommand_fix/index?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('recommand_fix/index?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('recommand_fix/index?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" style="width: 5%;"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id" style="width: 10%;">类型</th>
                  <th scope="col" class="list_model" style="width: 65%;">标题/海报/播放地址</th>
                  <th scope="col" class="list_updated_at" style="width: 10%;">创建时间</th>
                  <th scope="col" class="list_action" style="width: 10%;">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col" class="list_id">类型</th>
                  <th scope="col" class="list_model">标题/海报/播放地址</th>
                  <th scope="col" class="list_updated_at">创建时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php 
                  foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><?php echo $arr_type[$rs->getType()];?></td>
                              <td><?php echo $rs->getTitle();?><br /><?php echo $rs->getPoster();?><br /><?php echo $rs->getUrl();?></td>
                              <td><?php echo ($created_at = $rs->getCreatedAt()) ? $created_at->format("Y-m-d H:i:s") : $rs->getUpdatedAt()->format("Y-m-d H:i:s");?></td>
                              <td><a href="<?php echo url_for("recommand_fix/edit?id=".$rs->getId())?>" class="recommend">编辑</a> | <a href="<?php echo url_for("recommand_fix/delete?id=".$rs->getId())?>" onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('recommand_fix/index?page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('recommand_fix/index?page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('recommand_fix/index?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('recommand_fix/index?page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('recommand_fix/index?page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
          </form> 
        </div>
      </div>