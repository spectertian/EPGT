<header>
  <h2 class="content"><?php echo $pageTitle?></h2>
  <nav class="utility">
   <!--<li class="app-add"><a class="toolbar" onclick="save_onekey();return false;" href="#">一键保存</a></li> -->
     <li class="app-add"><a href="javascript:submitform('/content/importCheck');">核对无误</a></li>
     <li class="app-del"><a href="javascript:submitform('/content/importError/error/1');">核对有误</a></li>
     <li class="add"><a href="javascript:submitform('/content/importError/error/0');">改为无错误</a></li>
     <li class="delete"><a href="javascript:submitform('/content/importDel');">删除</a></li>
  </nav>
</header>