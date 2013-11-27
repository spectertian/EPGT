<?php use_helper('Date');?>
<script type="text/javascript">
//全选
function checkAll(object)
{
    var flag    = object.checked;
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
</script>
<div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarList',array('pageTitle'=>'标签管理'))?>
            <div class="table_nav">              
              <div class="clear"></div>
            </div>			
			<?php include_partial('global/flashes')?>
            <?php include_partial('search', array('mc' => $mc))?>
            
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $pager->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $pager->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$value);?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>         
            <div class="clear"></div>   <br />
            <form action="#" id="adminForm" name="adminForm" method="post" >
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" style="width: 5%;"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /></th>
                  <th scope="col" style="width: 5%;">Id</th>
                  <th scope="col" style="width: 25%;">名称</th>
                  <th scope="col" style="width: 25%;">创建时间</th>
                  <th scope="col" style="width: 25%;">更新时间</th>
                  <th scope="col" style="width: 20%;">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /></th>
                  <th scope="col">Id</th>
                  <th scope="col">名称</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
              <?php if($pager->getLastPage()!=0): ?>
                <?php foreach ($pager->getResults() as $key => $rs): ?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                  <td><?php echo $rs->getId();?></td>
                  <td><?php echo $rs->getName();?></td>
                  <td><?php echo $rs->getCreatedAt();?></td>
                  <td><?php echo $rs->getUpdatedAt();?></td>
                  <td><a href="<?php echo url_for("tag/edit?id=".$rs->getId().'&page='.$pager->getPage())?>" class="recommend">编辑</a> | <a href="<?php echo url_for('tag/del?id='.$rs->getId().'&page='.$pager->getPage());?>"  onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                </tr>
                <?php endforeach;?>
              <?php else: ?>  
                <tr><td colspan="6">无匹配信息</td></tr>
              <?php endif; ?>
              </tbody>
            </table>    
            </form>      
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $pager->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $pager->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$value);?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("tag/index?mc=$mc".'&page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
          </div>
             
          <div class="clear"></div>
          
        </div>
      </div>