<header>
  <h2 class="content">专题列表</h2>
  <nav class="utility">
    <li class="add"><a href="<?php echo url_for("theme/add")?>">添加</a></li>
    <li class="app-add"> <a href="#" onclick="Publish(1);return false;" class="toolbar publish">批量发布</a></li>
    <li class="app-del"><a href="#" onclick="Publish(0);return false;" class="toolbar unpublish">取消发布</a></li>    
	<!--
    <li class="delete"><a class="toolbar" onclick="javascript:submitform('batchDelete')" href="#">删除</a></li>
	-->
  </nav>
</header>
