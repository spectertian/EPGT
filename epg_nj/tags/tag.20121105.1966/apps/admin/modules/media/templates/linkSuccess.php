<script type="text/javascript">
  $(document).ready(function(){
	$("#browser").treeview({
		animated: "fast"
	});
  });

</script>
<body>
  <div id="file-wrap">
    <header>
      <h2 class="content">文件管理</h2>
      <nav class="utility">
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
