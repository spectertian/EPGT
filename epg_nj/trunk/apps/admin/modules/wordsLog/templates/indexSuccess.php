<script type="text/javascript">
//全选
function checkAll(object)
{
    var flag    = $(object).attr('checked');
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


$(document).ready(function(){
	var sensitive  = $(".sensitive");
   
	sensitive.live('click', function() {
	    var postvalue  = $("#postValue").size();
        if($(this).html() == ''&&postvalue==0)
        {
            var log_id  = $.trim($(this).parent().find('.sf_admin_batch_checkbox').attr('value'));
            var wiki_id  = $.trim($(this).parent().find('.fromid').text());
            $(this).html('<input id="postValue" style="width:70px;" value="" onblur="ajax_update(\''+log_id+'\');">');
            $("#postValue").focus();
        }
    });
});

function ajax_update(log_id)
{
    var word  = $.trim($("#postValue").parent().parent().find('.word').text());
    var reword   = $("#postValue").val();
    if(reword==''){
        alert("请输入替换的词");
    }else{
        $.ajax({
            url: '<?php echo url_for('wordsLog/ajaxUpdate');?>',
            type: 'post',
            dataType: 'json',
            data: { 'log_id': log_id,'sensitive': word,'resensitive': reword },
            success:function(data)
            {
                if(data.code == 1)
                {
                    $("#postValue").parent().parent().find('.status').html('已替换');
                    $("#postValue").parent().html(reword);
                }else{
                    alert(data.msg);
                    $("#postValue").parent().html('');
                }
            },error:function()
            {
            }
        });
    }
}
</script>
    <div id="content">
        <div class="content_inner">
          <form action="<?php echo url_for('wordsLog/batchDelete');?>" id="adminForm" name="adminForm" method="post" >
            <?php include_partial('toolbarlist')?>
            <?php include_partial('global/flashes') ?>
            <div class="table_nav">
			替换状态：
	 		<select name="status">
	 				<option value="-1" <?php echo $status==-1?'selected="selected"':''?>>全部</option>
	 				<option value="1" <?php echo $status==1?'selected="selected"':''?>>已替换</option>
	 				<option value="0" <?php echo $status==0?'selected="selected"':''?>>未替换</option>
			</select>
			<input type="submit" onclick="submitform('<?php echo url_for('wordsLog/index')?>')" value="查询">
            <font color="#ff0000">注意：如果敏感词是以,隔开，则替换词也要以,隔开；（,为英文状态下输入）</font>
            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('wordsLog/index?page='.$pager->getFirstPage()."&status=$status");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('wordsLog/index?page='.$pager->getPreviousPage()."&status=$status");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('wordsLog/index?page='.$page."&status=$status");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('wordsLog/index?page='.$pager->getNextPage()."&status=$status");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('wordsLog/index?page='.$pager->getLastPage()."&status=$status");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
            </div>

              <div class="clear"></div>
            </div>


            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" class="list_checkbox" width="5%"><input type="checkbox"  id="sf_admin_list_batch_checkbox" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from" width="20%">wiki_id</th>
                  <th scope="col" class="list_from" width="10%">敏感词</th>
                  <th scope="col" class="list_word" width="15%">原内容</th>
                  <th scope="col" class="list_reword" width="15%">替换内容</th>
                  <th scope="col" class="list_from" width="10%">替换词</th>
                  <th scope="col" class="list_reword" width="5%">状态</th>
                  <th scope="col" class="list_created_at" width="10%">时间</th>
                  <th scope="col" class="list_action" width="10%">操作</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col"><input type="checkbox"  id="sf_admin_list_batch_checkbox1" onclick="checkAll(this);"></th>
                  <th scope="col" class="list_from">wiki_id</th>
                  <th scope="col" class="list_word">敏感词</th>
                  <th scope="col" class="list_word">原内容</th>
                  <th scope="col" class="list_reword">替换内容</th>
                  <th scope="col" class="list_word">替换词</th>
                  <th scope="col" class="list_reword">状态</th>
                  <th scope="col" class="list_created_at">时间</th>
                  <th scope="col" class="list_action">操作</th>
                </tr>
              </tfoot>
              <tbody>
                <?php if(isset ($pager)):?>
                    <?php 
                    foreach ($pager->getResults() as $i => $rs): 
                        $words='';
                        $content = $rs->getWord();
                        foreach($patterns as $value){
                            $value = str_replace('/','',$value);
                            $contenta=' '.$content; //解决敏感词在第一个位置的问题
                            if(strpos($contenta,$value)){
                                $words.=$value.',';
                            }
                        }    
                        $words=rtrim($words,',');             
                    ?>
                            <tr>
                              <td class="logid"><input type="checkbox" class="sf_admin_batch_checkbox" value="<?php echo $rs->getId();?>" name="ids[]"></td>
                              <td class="fromid"><a href="<?php echo url_for('wiki/edit?id='.$rs->getFromId())?>" target="_blank"><?php echo $rs->getFromId();?></a></td>
                              <td class="word"><?php echo $words;?></td>
                              <td><a title="<?php echo $content;?>" href="<?php echo url_for('wordsLog/view?id='.$rs->getId())?>" target="_blank"><?php echo mb_strcut($content, 0, 30, 'utf-8');?></a></td>
                              <td><a title="<?php echo $rs->getReWord();?>" href="<?php echo url_for('wordsLog/viewRe?id='.$rs->getId())?>" target="_blank"><?php echo mb_strcut($rs->getReWord(), 0, 30, 'utf-8');?></a></td>
                              <td class="sensitive"><?php echo $rs->getReSensitive();?></td>
                              <td class="status"><?php echo $rs->getStatus()==1?'已替换':'未替换';?></td>
                              <td><?php echo ($created_at = $rs->getCreatedAt()) ? $created_at->format("Y-m-d H:i:s") : $rs->getUpdatedAt()->format("Y-m-d H:i:s");?></td>
                              <td><a href="<?php echo url_for("wordsLog/verify?id=".$rs->getId())?>">替换</a> | <a href="<?php echo url_for("wordsLog/delete?id=".$rs->getId())?>" onclick="if(!confirm('确定通过吗？')) return false;">通过</a></td>
                            </tr>
                   <?php endforeach;?>
                <?php endif;?>
              </tbody>
            </table>

            <div class="paginator">
              <span class="first-page">
                  <a href="<?php echo url_for('wordsLog/index?page='.$pager->getFirstPage()."&status=$status");?>">
                  最前页
                  </a>
              </span>
              <span class="prev-page">
                 <a href="<?php echo url_for('wordsLog/index?page='.$pager->getPreviousPage()."&status=$status");?>">上一页</a>
              </span>
              <span class="pages">
                  <?php foreach ($pager->getLinks(5) as $page ):?>
                        <?php if ($page == $pager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('wordsLog/index?page='.$page."&status=$status");?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              </span>
              <span class="next-page"><a href="<?php echo url_for('wordsLog/index?page='.$pager->getNextPage()."&status=$status");?>">下一页</a></span>
              <span class="last-page"><a href="<?php echo url_for('wordsLog/index?page='.$pager->getLastPage()."&status=$status");?>">最末页</a></span>
              <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
        </div>

            <div class="clear"></div>
            <input type="hidden" name="batch_action" value="0" id="batch_action" />
        </form>
        </div>
      </div>