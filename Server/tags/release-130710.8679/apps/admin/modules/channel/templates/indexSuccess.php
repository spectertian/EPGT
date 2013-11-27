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


$(document).ready(function(){
    $("#logo_photo").mouseover(function(){
        $(this).parents("TD").find("DIV:eq(0)").show();
    }).mouseout(function(){
        $(this).parents("TD").find("DIV:eq(0)").hide();
    });
});
</script>
<script>
    $(function(){
        $('#got').change(function(){
            var url = $('#got').val();
            window.open(url);
            location.href = location.href;
        })

    })
</script>
    <div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarList')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
             <?php include_partial('search', array('form' => $filters, 'configuration' => $configuration)) ?>
              <div class="paginator">
                <span class="first-page"> <a href="<?php echo url_for('@channel') ?>?page=1">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('@channel') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a></span>
                <span class="pages">
                    <?php foreach ($pager->getLinks() as $page): ?>
                        <?php if ($page == $pager->getPage()): ?>
                         <span class="present"><?php echo $page ?></span>
                        <?php else: ?>
                          <a href="<?php echo url_for('@channel') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('@channel') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('@channel') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
              <div class="clear"></div>
            </div>
            </form>
            <form action="<?php echo url_for('channel_collection', array('action' => 'batch')) ?>" name="adminForm" method="post">
            <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
              <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
            <?php endif; ?>
            <input type="hidden" name="batch_action" value="" />
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll();" /></th>
                  <th scope="col" class="list_id">ID</th>
                  <th scope="col" class="list_id">所属电台</th>
                  <th scope="col" class="list_model">频道名称</th>
                  <th scope="col" class="list_created_at">发布</th>
                  <th scope="col" class="list_created_at">推荐</th>
                  <th scope="col" class="list_updated_at">台标</th>
                  <th scope="col" class="list_modified_by">
                      <?php if ('sort_id' == $sort[0]): ?>
                        <?php echo link_to(__('排序', array(), 'messages'), '@channel', array('title'=>'小靠前','query_string' => 'sort=sort_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
                        <?php echo image_tag('icon/sort_'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'))) ?>
                      <?php else: ?>
                        <?php echo link_to(__('排序', array(), 'messages'), '@channel', array('title'=>'小靠前','query_string' => 'sort=sort_id&sort_type=asc')) ?>
                      <?php endif; ?>
                  </th>
                  <th scope="col" class="list_updated_at">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll();" /></th>
                  <th scope="col" class="list_id">ID</th>
                  <th scope="col" class="list_id">所属电台</th>
                  <th scope="col" class="list_model">频道名称</th>
                  <th scope="col" class="list_created_at">发布</th>
                  <th scope="col" class="list_created_at">推荐</th>
                  <th scope="col" class="list_updated_at">台标</th>
                  <th scope="col" class="list_modified_by">
                        <?php if ('sort_id' == $sort[0]): ?>
                        <?php echo link_to(__('排序', array(), 'messages'), '@channel', array('title'=>'小靠前','query_string' => 'sort=sort_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
                        <?php echo image_tag('icon/sort_'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'))) ?>
                      <?php else: ?>
                        <?php echo link_to(__('排序', array(), 'messages'), '@channel', array('title'=>'小靠前','query_string' => 'sort=sort_id&sort_type=asc')) ?>
                      <?php endif; ?>
                  </th>
                  <th scope="col" class="list_updated_at">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if (count($pager->getResults())>0): ?>
                <?php foreach ($pager->getResults() as $i => $channel): $odd = fmod(++$i, 2) ? '0' : '1' ?>
                <tr>
                  <td><input type="checkbox" name="ids[]" value="<?php echo $channel->getPrimaryKey() ?>" class="sf_admin_batch_checkbox" /></td>
                  <td class="sf_admin_text sf_admin_list_td_id"><?php echo link_to($channel->getId(), 'channel_edit', $channel) ?></td>
                  <td><?php echo $channel->getTvStation() ?></td>
                  <td><?php echo link_to($channel->getName(), 'channel_edit', $channel) ?></td>
                  <td class="sf_admin_list_td_publish" style="cursor:pointer;">
                    <?php if ($value = $channel->getPublish()): ?>
                      <?php echo image_tag('accept.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('Checked', array(), 'sf_admin'))) ?>
                    <?php else: ?>
                      <?php echo image_tag('delete.png', array('alt' => __('Unhecked', array(), 'sf_admin'), 'title' => __('UnChecked', array(), 'sf_admin'))) ?>
                    <?php endif; ?>
                  </td>
                  <td class="sf_admin_list_td_recommend" style="cursor:pointer;">
                    <?php if ($value = $channel->getRecommend()): ?>
                      <?php echo image_tag('accept.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('Checked', array(), 'sf_admin'))) ?>
                    <?php else: ?>
                      <?php echo image_tag('delete.png', array('alt' => __('Unhecked', array(), 'sf_admin'), 'title' => __('UnChecked', array(), 'sf_admin'))) ?>
                    <?php endif; ?>
                  </td>
                  <td><?php echo get_partial('channel/has_logo', array('type' => 'list', 'channel' => $channel)) ?></td>
                  <td class="sf_admin_text sf_admin_list_td_sort_id"><?php echo $channel->getSortId() ?></td>
                  <!--  
                      <td><?php echo get_partial('channel/program_1', array('type' => 'list', 'channel' => $channel)) ?></td>
                  -->
                  <td>
                      <select id='got'>
                          <option value=''>请选择</option>
                          <option value='<?php echo url_for('@program').'?channel_id='.$channel->getId();?>'>查看节目</option>
                          <option value='<?php echo url_for('program/tvsou/').'?channel_code='.$channel->getCode(); ?>'>TVsou对比</option>
                      </select>
                  </td>
                </tr>
                <?php endforeach;?>
                <?php else: ?>  
                <tr><td colspan="8">无匹配信息</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
              <div class="paginator">
                <span class="first-page"> <a href="<?php echo url_for('@channel') ?>?page=1">最前页</a></span>
                <span class="prev-page"><a href="<?php echo url_for('@channel') ?>?page=<?php echo $pager->getPreviousPage() ?>">上一页</a></span>
                <span class="pages">
                    <?php foreach ($pager->getLinks() as $page): ?>
                        <?php if ($page == $pager->getPage()): ?>
                         <span class="present"><?php echo $page ?></span>
                        <?php else: ?>
                          <a href="<?php echo url_for('@channel') ?>?page=<?php echo $page ?>"><?php echo $page ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </span>
                <span class="next-page"><a href="<?php echo url_for('@channel') ?>?page=<?php echo $pager->getNextPage() ?>">下一页</a></span>
                <span class="last-page"><a href="<?php echo url_for('@channel') ?>?page=<?php echo $pager->getLastPage() ?>">最末页</a></span>
                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
              </div>
            <div class="clear"></div>
          </form>
        </div>
      </div>