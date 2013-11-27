<script type="text/javascript">
  $(document).ready(function(){
	$("#browser").treeview({
		animated: "fast"
	});
	$('#bantchdeletepopup').click(function(){
		if(confirm('确定删除吗?')){
			var arr = new Array();
			var y = -1;
			$(".idsdelete").each(function(i){
				if(this.checked)
				{
					y+=1;
					arr[y]=$(this).val();
				}
			});
			if(y>=0)
			{
				var urll = "<?php echo url_for('media/batchDeletePopup');?>";
		        $("#media_list").load(urll,{popup:1,ids:arr}, function() {
		            tb_init('a.thickbox, area.thickbox, input.thickbox');
		        });
			}

	        return false;		
		}
		else
			return false;
	})	
  });
  function submitform(action){
	    if (action) {
	            document.adminForm.batch_action.value=action;
	    }
	    if(typeof document.adminForm.onsubmit == "function"){
	            document.adminForm.onsubmit();
	    }
	    document.adminForm.submit();
	}
</script>
<body>
  <div id="file-wrap">
    <header>
      <h2 class="content">文件管理</h2>
      <nav class="utility">
		<!-- Modify by tianzhongsheng-ex@huan.tv 暂时关闭文件删除功能 Time 2013-04-27 10:35:00 
        <li class="delete" style="background: url('/images/ico_delete.png') no-repeat scroll center 0 transparent;"><a id="bantchdeletepopup" href="#">批量删除</a></li>
		-->
        <li class="add"><a href="#" onclick="oneFileInsert(self.parent);">插入台标</a></li>
        <li class="add"><a href="#" onclick="insert_file(self.parent);">插入文件</a></li>
        <li class="canvas"><a id="cut_pic" href="#">封面裁切</a></li>
      </nav>
    </header>
    <div class="inner">
      <aside>
        <?php include_partial('media/categorys', array("popup" => true)) ?>
      </aside>
      <div id="file-content">
        <div class="content_inner">
            <div id="media_list"><?php include_component("media", "list", array("popup" => true)) ?></div>
        </div>
        <br>
        <?php include_partial('media/link_uploader',array('categorys'=>$categorys,"show" => "no")) ?>
      </div>
    </div>
  </div>
</body>
</html>
