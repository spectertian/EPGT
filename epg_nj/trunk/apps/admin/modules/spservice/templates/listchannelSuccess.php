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

$(document).ready(function(){
    $('#channel_name').simpleAutoComplete('<?php echo url_for('sp/loadChannel') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'code',
        max       : 20
    },function(date){
        var date = eval("("+date+")");
        var code = date.code;
        $('#channel_code').attr('value',code);
    });
});
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
          <div class="clear"></div>
          <div style="z-index: 100;">
          <form method="post" action="<?php echo url_for('sp/addChannel') ?>">
          <label>频道名称：</label>
          <input name="channel_name" id="channel_name"  value="" type="text">
          <input name="channel_code" id="channel_code"  value="" type="hidden">
          <input name="signal" id="signal"  value="<?php echo $id;?>" type="hidden">
          <input type="submit" value="添加"> 
          </form>
          </div>            
            <div class="table_nav">
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $pager->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $pager->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$value);?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>

          
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_id">频道名称</th>
                  <th scope="col" class="list_created_at">创建时间</th>
                  <th scope="col" class="list_updated_at">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col">频道名称</th>
                  <th scope="col">创建时间</th>
                  <th scope="col">更新时间</th>
                  <th scope="col">操作</th>
                </tr>
              </tfoot>
              <tbody>
              <?php if(isset ($pager)):?>
                <?php foreach ($pager->getResults() as $key => $rs): ?>
                <tr>
                  <td><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="id[]"></td>
                  <td><?php echo $rs->getName();?></td>
                  <td><?php echo $rs->getCreatedAt();?></td>
                  <td><?php echo $rs->getUpdatedAt();?></td>
                  <td><a href="<?php echo url_for('sp/delChannel?code='.$rs->getCode().'&id='.$id.'&page='.$pager->getPage());?>">删除</a></td>
                </tr>
                <?php endforeach;?>
              <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getFirstPage());?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getPreviousPage());?>">上一页</a>
              </span>
              <span class="pages">
                <?php $links    = $pager->getLinks(5);?>
                <?php foreach ($links as $key => $value):?>
                    <?php if ($value == $pager->getPage()):?>
                        <span class="present"><?php echo $value;?></span>
                    <?php else:?>
                        <a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$value);?>"><?php echo $value;?></a>
                    <?php endif;?>
                <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getNextPage());?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('sp/listchannel?id='.$id.'&page='.$pager->getLastPage());?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
          </div>


<!--          </form>-->
        </div>
      </div>