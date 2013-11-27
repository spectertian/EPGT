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

function Publish(publish)
{
    $("#publish_off").val(publish);
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('theme/publish')?>";
    admin_form.submit();
}
</script>
<div id="content">
        <div class="content_inner">
            <?php include_partial('toolbarList')?>
            <div class="table_nav">              
              <div class="clear"></div>
            </div>			
			<?php include_partial('global/flashes')?>
            <?php include_partial('search', array('mc' => $mc,'page'=>$page,'pagegroup'=>$pagegroup,'pagetotal'=>$pagetotal,'sceneArray'=>$sceneArray,'scene'=>$scene,))?>
            <form action="#" id="adminForm" name="adminForm" method="post" >
            
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" style="width: 5%;"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /></th>
                  <th scope="col" class="list_id" style="width: 5%;">Id</th>
                  <th scope="col" class="list_model" style="width: 15%;">名称</th>
                  <th scope="col" class="list_model" style="width: 30%;">描述</th>
                  <th scope="col" class="list_modified_by" style="width: 10%;">发布</th>
                  <th scope="col" class="list_modified_by" style="width: 10%;">关联wiki</th>
                  <th scope="col" class="list_modified_by"  style="width: 15%;">创建时间</th>
                  <th scope="col" class="list_action" style="width: 10%;">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" class="list_checkbox"><input  id="sf_admin_list_batch_checkbox" type="checkbox" name="toggle" onclick="checkAll(this);" /></th>
                  <th scope="col" class="list_id">Id</th>
                  <th scope="col" class="list_model">名称</th>
                  <th scope="col" class="list_model">描述</th>
                  <th scope="col" class="list_modified_by">发布</th>
                  <th scope="col" class="list_modified_by">关联wiki</th>
                  <th scope="col" class="list_modified_by">创建时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($themes as $theme):?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $theme->getId();?>" name="ids[]"></td>
                  <td><a href="<?php echo url_for("theme/edit?id=".$theme->getId())?>"><?php echo $theme->getId()?></a></td>
                  <td><img src="<?php echo file_url($theme->getImg())?>" width="120px;"><br><a href="<?php echo url_for("theme/edit?id=".$theme->getId())?>"><?php echo $theme->getTitle()?></a>【<?php echo $sceneArray[$theme->getScene()]?>】</td>
                  <td><?php echo mb_substr($theme->getRemark(), 0, 80, 'utf-8');?></td>
                  <td>
                    <?php $value = $theme->getPublish()?>
                    <?php if ($value): ?>
                      <a href="<?php echo url_for("theme/publishoff?id=".$theme->getId())?>" onclick="if(!confirm('确定取消发布本专题？')) return false;"><?php echo image_tag('accept.png', array('alt' => __('Checked', array(), 'sf_admin'), 'title' => __('已发布：点击取消发布', array(), 'sf_admin'))) ?></a>
                    <?php else: ?>
                       <a href="<?php echo url_for("theme/publishon?id=".$theme->getId())?>" onclick="if(!confirm('确定发布本专题？')) return false;"><?php echo image_tag('delete.png', array('alt' => __('Unhecked', array(), 'sf_admin'), 'title' => __('未发布：点击发布', array(), 'sf_admin'))) ?></a>
                    <?php endif; ?>
                  </td>
                  <td><a href="<?php echo url_for('theme/listwikis').'?id='.$theme->getId();?>">管理</a></td>
                  <td><?php echo false !== strtotime($theme->getCreatedAt()) ? format_date($theme->getCreatedAt(), "y-M-d H:m:s") : '&nbsp;' ?> <br/>
				      <?php echo false !== strtotime($theme->getUpdatedAt()) ? format_date($theme->getUpdatedAt(), "y-M-d H:m:s") : '&nbsp;' ?>
				  </td>
                  <td><a href="<?php echo url_for("theme/edit?id=".$theme->getId())?>" class="recommend">编辑</a> | <a href="<?php echo url_for("theme/delete?id=".$theme->getId())?>" onclick="if(!confirm('确定删除吗？')) return false;">删除</a></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>    
            <input type="hidden" name="publish" value="0" id="publish_off" /> 
            </form>         
            <div class='paginator'>
              <?php if($page!=1): ?>
              <span class="first-page">
                <a href="/theme?page=1&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">最前页 </a>
              </span>
              <?php endif; ?>
              <?php if($page>1): ?>
              <span class="prev-page">
                <a href="/theme?page=<?php echo $page-1; ?>&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">上一页</a>
              </span>
              <?php endif; ?>
              <span class="pages">
              <?php foreach ($pagegroup as $v): ?>
              <?php 
                if($page ==$v) 
                  echo '<span class="present">'.$v.'</span>';
                else
                  echo '<a href="/theme?page='.$v.'&scene='.$scene.'&mc='.$mc.'">'.$v.'</a>';
              ?>
              <?php endforeach;?>
              </span>
              <?php if($page<$pagetotal): ?>
              <span class="next-page">
                <a href="/theme?page=<?php echo $page+1; ?>&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">下一页</a>
              </span>
              <?php endif; ?>
              <?php if($page!=$pagetotal): ?>
              <span class="last-page">
                <a href="/theme?page=<?php echo $pagetotal; ?>&scene=<?php echo $scene; ?>&mc=<?php echo $mc; ?>">最末页</a>
              </span>
              <?php endif; ?>
            </div>
            <div class="clear"></div>
          
        </div>
      </div>