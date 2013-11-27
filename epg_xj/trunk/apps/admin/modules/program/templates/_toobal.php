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
  <nav class="utility"><!--
    <li class="save"><a href="#" onclick="javascript:submitform('batchSave')">存为模板</a></li>
    <li class="app-add"> <a class="toolbar thickbox" href="#" title="请选择节目单模板">使用模板</a></li>
    -->
    <li class="app-add"><a class="toolbar" href="<?php echo url_for("program/countDay") ?>" title="节目统计">节目统计</a></li>
    <li class="app-add"><a class="toolbar" href="<?php echo url_for("program/countTime") ?>" title="时间段统计">时间段统计</a></li>    
    <li class="app-add"><a class="toolbar" href="<?php echo url_for("program/epgUpdate") ?>" title="epg更新">epg更新</a></li>
    <li class="app-add"><a class="toolbar" href="<?php echo url_for("program/channelUpdate") ?>" title="查看所有更新">tvsou更新</a></li>
    <li class="app-add"><a class="toolbar" href="<?php echo url_for("program/channelGet") ?>" title="待抓取频道">待抓取频道</a></li>
    <li class="app-add"><a class="toolbar thickbox" href="<?php echo url_for("television/index") ?>?KeepThis=true&TB_iframe=true&height=400&width=1000" title="栏目">栏目</a></li>
    <li class="app-add"><a class="toolbar addNewProgram" href="#" onclick="return false;" title="添加节目">添加节目</a></li>
    <li class="app-add"> <a href="#" onclick="return false;" class="toolbar publish">发布</a></li>
    <li class="app-del"><a href="#" onclick="return false;" class="toolbar unpublish">取消发布</a></li>
    <li class="app-add"> <a href="#" onclick="save_onekey();return false;" class="toolbar">一键保存</a></li>
    <li class="delete"><a class="toolbar" href="#">删除</a></li>
  </nav>
</header>