<div class="container search">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <section id="section">
        <h2>搜索</h2>
        <div class="search-mod">
          <div class="search-tab">
            <ul>
               <li><a href="<?php echo url_for('search/index?gcurrent=0'); ?>" <?php if($gcurrent == "0"): ?>class="active"<?php endif; ?> >综合</a></li>
              <li><a href="<?php echo url_for('search/index?gcurrent=1'); ?>" <?php if($gcurrent == "1"): ?>class="active"<?php endif; ?> >影视剧</a></li>
              <li><a href="<?php echo url_for('search/index?gcurrent=2'); ?>" <?php if($gcurrent == "2"): ?>class="active"<?php endif; ?>>栏目</a></li>
              <li><a href="<?php echo url_for('search/index?gcurrent=3'); ?>" <?php if($gcurrent == "3"): ?>class="active"<?php endif; ?>>人物</a></li>
            </ul>
          </div>
          <div class="search-bd">
            <form method="get" action="<?php echo url_for("search/index"); ?>">
              <input type="text" name="q" value="">
              <input type="submit" value="搜索">
            </form>
          </div>
        </div>
        <div class="filter-result">
          <div class="filter-result-hd">
            <div class="sort-by"> <span class="label">排序方式：</span>
              <ul>
                <li><a href="<?php echo url_for('search/index?gcurrent='.$gcurrent);?>" <?php if($sort==2): ?>class="active"<?php endif;?> title="评价">评价</a></li>
                <li ><a href="<?php echo url_for('search/index?gcurrent='.$gcurrent);?>" <?php if($sort==1): ?>class="active"<?php endif;?> title="最新">最新</a></li>
              </ul>
            </div>
            <!-- 
            <div class="view-as"> <span class="label">浏览方式：</span>
              <ul>
                <li class="tile"><a href="<?php echo url_for('search/index?gcurrent='.$gcurrent);?>" <?php if($style=='list'): ?>class="active popup-tip"<?php else: ?>class="popup-tip"<?php endif;?> title="海报">海报</a></li>
                <li class="list"><a href="<?php echo url_for('search/index?gcurrent='.$gcurrent);?>" <?php if($style=='title'): ?>class="active popup-tip"<?php else: ?>class="popup-tip"<?php endif;?> title="列表">列表</a></li>
              </ul>
            </div>
            -->
          </div>
			<div>请输入搜索关键字</div>  
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
        <?php include_component('channel', 'hotplay')?>
      </aside>
    </div>
  </div>
</div>