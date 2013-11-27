<header>
  <h2 class="content">电视频道列表</h2>
  <nav class="utility">
    <li class="app-add"><a href="#" onclick="javascript:submitform('batchPublish')" >发布</a></li>
    <li class="app-del"><a href="#"  onclick="javascript:submitform('batchUnPublish')" >取消发布</a></li>
    <li class="add"><a href="<?php echo url_for("channel/new")?>">添加</a></li>
    <li class="delete"><a class="toolbar" onclick="if (confirm('确定删除吗？')) {submitform('batchDelete')}" href="#">删除</a></li>
  </nav>
</header>
