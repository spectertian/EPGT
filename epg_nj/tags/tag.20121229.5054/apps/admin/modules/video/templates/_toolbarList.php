
<header>
  <h2 class="content"><?php echo $pageTitle;?></h2>
  <nav class="utility">
    <li class="view-recommended"><a href="<?php echo url_for('video/index') ?>">所有列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=vod') ?>">vod列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=ppv') ?>">pptv列表</a></li>
    <li class="view-recommended"><a href="<?php echo url_for('video/temp/?site=1905') ?>">1905列表</a></li>
<!--    <li class="delete"><a href="javascript:submitform('delete');">删除</a></li>-->
  </nav>
</header>
