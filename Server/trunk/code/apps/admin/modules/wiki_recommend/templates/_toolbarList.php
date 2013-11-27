<header>
  <h2 class="content"><?php echo $pageTitle ?></h2>
  <nav class="utility">
    <li class="view-recommended"><a href="<?php echo url_for('wiki_recommend')?>">查看已推荐</a></li>
    <li class="recommended"><a href="javascript:submitform('recommend');">批量推荐</a></li>
    <li class="add"><a href="<?php echo url_for('wiki/new?model=film');?>">电影</a></li>
    <li class="add"><a href="<?php echo url_for('wiki/new?model=teleplay');?>">电视剧</a></li>
    <li class="add"><a href="<?php echo url_for('wiki/new?model=television');?>">栏目</a></li>
    <li class="add"><a href="<?php echo url_for('wiki/new?model=actor');?>">艺人</a></li>
    <li class="delete"><a href="javascript:if(confirm('确定删除吗？')){submitform('delete');}">删除</a></li>
  </nav>
</header>