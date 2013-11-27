<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<script type="text/javascript">
<!--
	function submitform(action){
	    if (action) {
	            document.adminForm.action=action;
	    }
	    if(typeof document.adminForm.onsubmit == "function"){
	            document.adminForm.onsubmit();
	    }
	    document.adminForm.submit();
	}

	$('#goPage').click(function(){
		var page = $('#goPageNum').val();
		var url = "<?php echo url_for('attachments_pre/index');?>"+"?page="+page;
		window.location.href=url;
	});

	//全选
	function checkAll()
	{
	    var flag    = $("#sf_batch_checkbox").attr('checked');
	    var box     = $("input[type=checkbox]");
	    if (flag) {
	        box.attr('checked',false);
	    }else{
	        box.attr('checked',true);
	    }

	}
//-->
</script>

<div id="content">
	<div class="content_inner">
	<?php include_partial('global/flashes') ?>
		<header>
			<h2 class="content">图片审核</h2>
			<nav class="utility">
				<li class="app-add">
					<a class="toolbar" onclick="checkAll()" href="#">全选</a>
				</li>
                <li class="recommended">
                	<a class="toolbar" onclick="javascript:if(confirm('确定审核？')){submitform()}" href="#">批量审核</a>
                </li>
                <li class="recommended">
                	<a class="toolbar" onclick="javascript:if(confirm('确定删除？')){submitform('/attachments_pre/delete')}" href="#">批量删除</a>
                </li>
			</nav>
		</header>
		<div id="file-wrap">
		
			<div class="content_inner">
				<form id="adminForm" method="post" name="adminForm" action="/attachments_pre/verify">
					<input type="checkbox" id="sf_batch_checkbox" style="display: none;">
					<div id="media_list">
					  <div class="widget">
						<h3>图片列表</h3>
						<div class="widget-body file-manager">
							<ul>
							<?php foreach ($pager->getResults() as $attachment): ?>
								<li class="actived">
				                  <div class="thumb">
				                      <a class="file-upload" href="<?php echo  file_url('pre_'.$attachment->getFileName());?>" target="_black">
				                      	<img alt="<?php echo $attachment->getFileName();?>" src="<?php echo thumb_url('pre_'.$attachment->getFileName(),120,120,$_SERVER['HTTP_HOST']);?>">
				                      </a>
				                  </div>
				                  <div class="action"><input type="checkbox" name="ids[]" value="<?php echo $attachment->getFileName();?>">
				                      &nbsp;|&nbsp; <a class="file-upload" href="<?php echo url_for('attachments_pre/verify?page='.$page.'&name='.$attachment->getFileName())?>">审核</a>
				                      &nbsp;|&nbsp; <a class="file-upload" href="<?php echo url_for('attachments_pre/delete?page='.$page.'&name='.$attachment->getFileName())?>" onClick="return window.confirm('确定删除吗?');">删除</a>
				                  </div>
				                  <div rel="0" style="display:none;" id="show_file_info">
				                    <span><?php echo $attachment->getFileName();?></span>
				                  </div>
				                </li>
				            <?php endforeach;?>
							</ul>
					      </div>
					      <div class="paginator"style="float:left;margin-right:10px;">
								  <span class="first-page">
					
					                   <a href="<?php echo url_for('attachments_pre/index?page='.$pager->getFirstPage());?>">
					                        最前页
					                    </a>
					                </span>
					                <span class="prev-page">
					                    <a href="<?php echo url_for('attachments_pre/index?page='.$pager->getPreviousPage());?>">
					                    上一页
					                    </a>
					                </span>
					                <span class="pages">
					                  <?php foreach ($pager->getLinks(5) as $key => $value):?>
					                    <?php if ($value == $pager->getPage()):?>
					                        <span class="present"><?php echo $value;?></span>
					                    <?php else:?>
					                        <a href="<?php echo url_for('attachments_pre/index?page='.$value);?>"><?php echo $value;?></a>
					                    <?php endif;?>
					                <?php endforeach;?>
					                </span>
					                <span class="next-page">
					                    <a href="<?php echo url_for('attachments_pre/index?page='.$pager->getNextPage());?>">
					                        下一页
					                    </a>
					                </span>
					                <span class="last-page">
					                    <a href="<?php echo url_for('attachments_pre/index?page='.$pager->getLastPage());?>">
					                        最末页
					                    </a>
					                </span>
					                <span class="page-total">(页码 <?php echo $pager->getPage();?>/<?php echo $pager->getLastPage();?>)</span>
					                 跳转至<input type="text" id="goPageNum" style="width: 20px;"><input type="button" value="GO" id="goPage" >
					        </div>
					  </div>
					</div>
				</form>
			</div>
		
		</div>
	</div>
</div>