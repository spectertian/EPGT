<header>
  <h2 class="content"><?php echo $pageTitle?></a></h2>
  <nav class="utility">
    <li class="add"><a href="<?php echo url_for("spservice/add")?>">添加</a></li>

    <li class="delete"><a class="toolbar" onclick="batchDelete();return false;" href="#">删除</a></li>

  </nav>
</header>
