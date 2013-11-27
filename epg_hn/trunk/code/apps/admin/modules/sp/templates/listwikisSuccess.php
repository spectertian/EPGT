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
			<header>
				<h2 class="content"><?php echo $pageTitle;?></h2>
				<nav class="utility">
				  <li class="back"><a href="<?php echo url_for("sp/index")?>">运营商列表</a></li>
				</nav>
			</header>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $wiki->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $wiki->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$value);?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $wiki->getPage();?>/<?php echo $wiki->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>

          
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll();"></th>
                  <th scope="col" class="list_id">标题</th>
                  <th scope="col" class="list_model">模型</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                  <th scope="col" class="list_modified_by">最后修改</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"></th>
                  <th scope="col">标题</th>
                  <th scope="col">模型</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">最后修改</th>
                </tr>
              </tfoot>
              <tbody>
              <?php if(isset ($wiki)):?>
                <?php foreach ($wiki->getResults() as $key => $rs): ?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                  <td><a href="<?php echo url_for('wiki/edit?id='.$rs->getId());?>"><?php echo $rs->getTitle();?></a></td>
                  <td><?php echo $rs->getDisplayName();?></td>
                  <td><?php echo $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td><?php echo ($updated_at = $rs->getUpdatedAt()) ? $updated_at->format("Y-m-d H:i:s") : $rs->getCreatedAt()->format("Y-m-d H:i:s");?></td>
                  <td>
                  <?php if($rs->getAdminName()) :?>
                    <?php echo $rs->getAdminName()?>
                  <?php else:?>
                     暂无记录
                  <?php endif?>
                  </td>
                </tr>
                <?php endforeach;?>
              <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $wiki->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $wiki->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$value);?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('sp/listwikis?id='.$id.'&page='.$wiki->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $wiki->getPage();?>/<?php echo $wiki->getLastPage();?>)</span>
          </div>

            <div class="clear"></div>
<!--          </form>-->
        </div>
      </div>