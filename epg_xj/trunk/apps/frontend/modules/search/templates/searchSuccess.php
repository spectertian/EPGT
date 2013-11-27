<div id="content">
  <div id="content-inner">
    <div id="content-inner-wrapper">
<!--      <div class="eyecatch"><img src="public/eyecatch.jpg" width="980" height="100" alt=""></div>-->
      <div class="path-navi"><a href="<?php echo url_for('default/index')?>">首页</a> &gt; 搜索“<?php echo $q; ?>”的结果</div>
      <div id="content-inner-main">
        <div id="wide">
          <div class="module search-results">
            <h2>搜索"<?php echo $q; ?>"的结果</h2>
            <div class="search-form">
              <form name="form1" method="get" action="<?php echo url_for("search/search"); ?>">
                <input type="text" name="q" value="<?php echo $sf_request->getGetParameter("q"); ?>" class="">
                <input type="submit" name="submit" value="搜索">
              </form>
            </div>
            <ul>
              <?php if ($wiki_pager->count() <= 0): ?>
              <li class="no-match">
                没有找到您搜索的内容...
              </li>
              <?php endif; ?>
              
              <?php foreach ($wiki_pager as $wiki): ?>
              <li>
                <div class="<?php echo $wiki->getModel() == "actor" ? "portrait" : "poster" ?>">
                    <?php if($wiki->getCover()):?>
                    <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>">
                        <img src="<?php echo thumb_url($wiki->getCover(), 75, 110)?>" width="75" height="110" alt="<?php echo $wiki->getTitle()?>">
                    </a>
                    <?php endif;?>
                </div>
                <div class="details">
                  <h3>
                      <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug())?>" target="_blank"><span class="title"><?php echo $wiki->getTitle(); ?></span></a>
                      <?php if ($wiki->getModel() == "actor"): ?>
                      <small class="people">[<a href="#">人物</a>]</small>
                      <?php endif; ?>
                  </h3>
                  <p><?php echo mb_strimwidth($wiki->getContent(), 0, 300, "...", "utf-8"); ?></p>
                </div>
                <div class="clear"></div>
              </li>
              <?php endforeach; ?>
            </ul>
            <div class="page-navi">
              <span class="prev"><a href="<?php echo url_for("search/search")."?page=".$wiki_pager->getPreviousPage()."&q=".$q; ?>">上一页</a></span>
              <?php foreach ($wiki_pager->getLinks(5) as $page): ?>
                  <?php if ($page == $wiki_pager->getPage()): ?>
                  <span class="active"><?php echo $page; ?></span>
                  <?php else: ?>
                  <span><a href="<?php echo url_for("search/search")."?page=".$page."&q=".$q; ?>"><?php echo $page; ?></a></span>
                  <?php endif; ?>
              <?php endforeach; ?>
              <span class="next"><a href="<?php echo url_for("search/search")."?page=".$wiki_pager->getNextPage()."&q=".$q; ?>">下一页</a></span>
            </div>
          </div>
        </div>
        <div id="narrow">
          &nbsp;
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
