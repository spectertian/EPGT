<header>
  <h2 class="content">内容导入</h2>
  <nav class="utility">
   <li class="app-add"><a class="toolbar" onclick="save_onekey();return false;" href="#">一键保存</a></li>
   <?php if ($deleted): ?>
     <li class="delete"><a href="javascript:if(confirm('确定还原吗？')){submitform('importDelete');}">还原</a></li>
   <?php else: ?>
     <li class="delete"><a href="javascript:if(confirm('确定删除吗？')){submitform('importDelete');}">删除</a></li>
   <?php endif; ?>
  </nav>
</header>