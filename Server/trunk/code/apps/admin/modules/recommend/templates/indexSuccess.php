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

function delRecommendRsId(rs_id) {
    if(rs_id.length==0){
        alert("recommend id is null");
    }else{
        $.ajax({
            type: "POST",
            data: {'id' : rs_id},
            url: '<?php echo url_for('recommend/delete')?>',
            success: function(msg) {
                if(msg==1) {
                    alert("删除成功!");
                    window.location.reload();
                }
                if(msg==2) {
                    alert("删除失败");
                }
            }
        });
    }
}
</script>
    <div id="content">
        <div class="content_inner">
<!--          <form action="" method="get">-->
            <?php include_partial('toolbarList',array('pageTitle'=>'推荐列表'))?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
            <?php include_partial('select', array('m' => $m,'show'=>$show))?>
            
<form method="post" name="adminForm" action="<?php echo url_for('recommend/index');?>/batch">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_id" width="150px">标题</th>
                  <th scope="col" class="list_scene">区域</th>
                  <th scope="col" class="list_ispublic">是否显示</th>
                  <th scope="col" class="list_sort">排序</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox" onclick="checkAll(this);"></th>
                  <th scope="col">标题</th>
                  <th scope="col">区域</th>
                  <th scope="col">是否显示</th>
                  <th scope="col">排序</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                  <?php foreach ($pager->getResults() as $i => $rs): ?>
                            <tr>
                              <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                              <td><a href="<?php echo url_for('recommend/edit?id='.$rs->getId());?>"><?php echo $rs->getTitle();?></a></td>
                              <td><?php 
                              $quyu=array('index'=>'首页','list'=>'列表','channel'=>'栏目','search'=>'搜索','indexhot'=>'热门排行','tcl_index_hotplay'=>'tcl首页热播推荐','hncatv_index_hotplay'=>'河南广电首页推荐');
                              echo $quyu[$rs->getScene()]; 
                              ?></td>
                              <td><?php if($rs->getIsPublic()==1){echo "显示";}else{echo "不显示";}?></td>
                              <td><?php echo $rs->getSort();?></td>
                              <td><?php $rs_created_at = $rs->getCreatedAt(); echo $rs_created_at->format("Y-m-d H:i:s");?></td>
                              <td><?php echo ($created_at = $rs->getCreatedAt()) ? $created_at->format("Y-m-d H:i:s") : $rs->getUpdatedAt()->format("Y-m-d H:i:s");?></td>
                              <td>
                                  
                                  <a href="<?php echo url_for("recommend/edit?id=".$rs->getId())?>" class="edit">编辑</a> | 
                                  <a href="javascript:if(confirm('确定删除吗？')){delRecommendRsId('<?php echo $rs->getId();?>')}" class="delete">删除</a>
                              </td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>
<input type="hidden" value="7ae5f9bb4952382f3637ea68bfafe589" name="_csrf_token">
<input type="hidden" value="" name="batch_action">
</form>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for("recommend/index?m=$m&s=$show&page=".$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>