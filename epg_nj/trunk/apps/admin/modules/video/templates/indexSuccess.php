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
//清楚搜索结果重新加载
function clearSearch(button){
    var form = $(button).parents();
    form.children('input[type=text]').val('');
    form.submit();
}
</script>

      <div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarList',array("pageTitle"=>$pageTitle,'model'=>$model))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
              <?php include_partial('search', array('q' => $q,'m'=>$m))?>
              <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('video/index?page='.$pager->getFirstPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('video/index?page='.$pager->getPreviousPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">上一页</a></span>
                <span class="pages">
                   <?php $links  = $pager->getLinks(5);?>
                    <?php foreach ($links as  $link):?>
                        <?php if ($link == $pager->getPage()):?>
                            <span class="present"><?php echo $link;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('video/index?page='.$link . ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>"><?php echo $link;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('video/index?page='.$pager->getNextPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('video/index?page='.$pager->getLastPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
              <div class="clear"></div>
            </div>
            </form>
            <form method="post" name="adminForm" action="<?php echo url_for('video/delete');?>">
                <input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
                <input type="hidden" value="" name="batch_action">
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll(this);" name="toggle" id="sf_admin_list_batch_checkbox"></th>
                  <th scope="col" class="list_id" style="width: 50%;">标题</th>
                  <th scope="col" class="list_created_at" style="width: 15%;">创建时间</th>
                  <th scope="col" class="list_updated_at" style="width: 15%;">更新时间</th>
                  <th scope="col" class="list_updated_at" style="width: 15%;">紧急下线</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox" onclick="checkAll(this);" name="toggle" id="sf_admin_list_batch_checkbox"></th>
                  <th scope="col" class="list_id">标题</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                  <th scope="col" class="list_updated_at">紧急下线</th>
                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($pager->getResults() as $wiki):?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $wiki->getId();?>" name="id[]"></td>
                  <td><a href="<?php echo url_for('video/show?id='.$wiki->getId());?>"><?php echo $wiki->getTitle() .' | '. $wiki->getDisplayName();?></a></td>
                  <td><?php echo $wiki->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><?php echo ($updated_at = $wiki->getUpdatedAt()) ? $updated_at->format("Y-m-d H:i:s") : $wiki->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><a href="<?php echo url_for('video/offline?id='.$wiki->getId());?>" onclick="return confirm('确定下线吗？这将删除其视频');">下线</a></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
            <div class="paginator">
                <span class="first-page"><a href="<?php echo url_for('video/index?page='.$pager->getFirstPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('video/index?page='.$pager->getPreviousPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">上一页</a></span>
                <span class="pages">
                   <?php $links  = $pager->getLinks(5);?>
                    <?php foreach ($links as  $link):?>
                        <?php if ($link == $pager->getPage()):?>
                            <span class="present"><?php echo $link;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('video/index?page='.$link . ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>"><?php echo $link;?></a>
                        <?php endif;?>
                    <?php endforeach;?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('video/index?page='.$pager->getNextPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('video/index?page='.$pager->getLastPage(). ($q ? "&q=".$q : ""). ($m ? "&m=".$m : ""));?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>
            <div class="clear"></div>
          </form>
        </div>
      </div>
	  