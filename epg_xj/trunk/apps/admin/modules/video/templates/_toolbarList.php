
<header>
  <h2 class="content"><?php echo $pageTitle;?></h2>
  <nav class="utility">
    <li class="view-recommended"><a href="<?php echo url_for('video/index') ?>">所有列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=qiyi') ?>">奇艺列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=sina') ?>">新浪列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=sohu') ?>">搜狐列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=youku') ?>">优酷列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=tps') ?>">tps列表</a></li>
<!--    <li class="delete"><a href="javascript:submitform('delete');">删除</a></li>-->
  </nav>
</header>
