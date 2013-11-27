<div class="container search">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <section id="section">
        <h2>搜索<?php echo $q;?></h2>
        <div class="search-mod">
          <div class="search-tab">
            <ul>
               <li><a href="<?php echo url_for('search/index?sort='.$sort.'&style='.$style.'&q='.$q.'&gcurrent=0'); ?>" <?php if($gcurrent == "0"): ?>class="active"<?php endif; ?> >综合 (<?php echo $count['0']?>)</a></li>
              <li><a href="<?php echo url_for('search/index?sort='.$sort.'&style='.$style.'&q='.$q.'&gcurrent=1'); ?>" <?php if($gcurrent == "1"): ?>class="active"<?php endif; ?> >影视剧 (<?php echo $count['1']?>)</a></li>
              <li><a href="<?php echo url_for('search/index?sort='.$sort.'&style='.$style.'&q='.$q.'&gcurrent=2'); ?>" <?php if($gcurrent == "2"): ?>class="active"<?php endif; ?>>栏目 (<?php echo $count['2']?>)</a></li>
              <li><a href="<?php echo url_for('search/index?sort='.$sort.'&style='.$style.'&q='.$q.'&gcurrent=3'); ?>" <?php if($gcurrent == "3"): ?>class="active"<?php endif; ?>>人物 (<?php echo $count['3']?>)</a></li>
            </ul>
          </div>
          <div class="search-bd">
            <form method="get" action="<?php echo url_for("search/index"); ?>">
              <input type="text" name="q" value="<?php echo $q; ?>">
              <input type="submit" value="搜索">
            </form>
          </div>
        </div>
        <div class="filter-result">
          <div class="filter-result-hd">
            <div class="sort-by"> <span class="label">排序方式：</span>
              <ul>
                <li><a href="<?php echo url_for('search/index?page='.$page.'&style='.$style."&sort=2&q=".$q."&gcurrent=".$gcurrent);?>" <?php if($sort==2): ?>class="active"<?php endif;?> title="评价">评价</a></li>
                <li ><a href="<?php echo url_for('search/index?page='.$page.'&style='.$style."&sort=1&q=".$q."&gcurrent=".$gcurrent);?>" <?php if($sort==1): ?>class="active"<?php endif;?> title="相关度">相关度</a></li>
              </ul>
            </div>
            <!-- 
            <div class="view-as"> <span class="label">浏览方式：</span>
              <ul>
                <li class="tile"><a href="<?php echo url_for('search/index?page='.$page.'&style=list&q='.$q."&sort=".$sort."&gcurrent=".$gcurrent);?>" <?php if($style=='list'): ?>class="active popup-tip"<?php else: ?>class="popup-tip"<?php endif;?> title="海报">海报</a></li>
                <li class="list"><a href="<?php echo url_for('search/index?page='.$page.'&style=title&q='.$q."&sort=".$sort."&gcurrent=".$gcurrent);?>" <?php if($style=='title'): ?>class="active popup-tip"<?php else: ?>class="popup-tip"<?php endif;?> title="列表">列表</a></li>
              </ul>
            </div>
            -->
          </div>

          <?php include_partial('search_title',array('wiki_pager'=>$wiki_pager,'wikimodel'=>$wikimodel)); ?>
          <?php if($wiki_pager->count()!=0):?>
          <div class="pagination"> 
              <?php if ($wiki_pager->getPage() == $wiki_pager->getFirstPage()) :?>
              <span class="page-cur"><?php echo $wiki_pager->getPage()?></span>
              <?php else:?>
              <a href="<?php echo url_for("search/index"."?page=".$wiki_pager->getPreviousPage()."&style=".$style."&sort=".$sort."&q=".$q."&gcurrent=".$gcurrent); ?>" title="跳转至18页" class="page-prev">上一页</a> 
              <?php endif;?>
              <span class="dots">...</span>
                <?php foreach ($wiki_pager->getLinks(5) as $page): ?>
		<?php if ($page == $wiki_pager->getPage()): ?>
                    <span class="page-cur"><?php echo $page; ?></span> 
                <?php else: ?>    
                <a href="<?php echo url_for("search/index"."?page=".$page."&style=".$style."&sort=".$sort."&q=".$q."&gcurrent=".$gcurrent); ?>" title="跳转至第<?php echo $page; ?>页"><?php echo $page; ?></a>
                <?php endif; ?>
            <?php endforeach; ?> 
             <span class="dots">...</span>
            <?php if ($wiki_pager->getPage() == $wiki_pager->getLastPage()) :?>
                <span class="page-cur"><?php echo $wiki_pager->getPage()?></span>
            <?php else:?>
                <a href="<?php echo url_for("search/index"."?page=".$wiki_pager->getLastPage()."&style=".$style."&sort=".$sort."&q=".$q."&gcurrent=".$gcurrent); ?>" title="跳转至<?php echo $wiki_pager->getLastPage(); ?>页" class="page-next"><?php echo $wiki_pager->getLastPage();?></a>
            <?php endif;?>
                   <a href="<?php echo url_for("search/index"."?page=".$wiki_pager->getNextPage()."&style=".$style."&sort=".$sort."&q=".$q."&gcurrent=".$gcurrent); ?>" title="跳转至<?php echo $wiki_pager->getNextPage(); ?>页" class="page-next">下一页</a>
            </div>
        <?php endif;?>
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
        <?php include_component('channel', 'hotplay')?>
      </aside>
    </div>
  </div>
</div>