<!--<script type="text/javascript">
    $("#file-upload").fancybox({
		'width'				: 960,
		'height'			: 600,
		'autoScale'			: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'                  : 'iframe'
		//'autoDimensions'    : false
	});
</script>-->
<header class="toolbar">
  <h2 class="content">节目单</h2>
  <nav class="utility">
    <!--
    <li class="app-add"><a class="toolbar addNewProgram" href="#" onclick="return false;" title="添加节目">添加节目</a></li>
    <li class="app-add"> <a href="#" onclick="return false;" class="toolbar publish">发布</a></li>
    <li class="app-del"><a href="#" onclick="return false;" class="toolbar unpublish">取消发布</a></li>
    -->
    <li class="app-add"> <a href="#" onclick="save_onekey();return false;" class="toolbar">一键保存</a></li>
    <li class="delete"><a class="toolbar" href="#">删除</a></li>
  </nav>
</header>