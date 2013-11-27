<div class="container movie">
  <div class="container-inner">
    <?php include_partial('nav_tool', array('wiki' => $wiki, 'related_programs' => array()))?>
        <h2>节目归档</h2>
        <div class="filter-option">
          <div class="filter-option-hd">
            <dl>
              <dt>筛选条件</dt>
            </dl>
          </div>
          <div class="filter-option-bd">
            <dl>
              <dt>按年份：</dt>
              <?php foreach($archiveDate['years'] as $y) :?>
              <dd><a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style .'&year='. $y)?>" <?php echo ($y == $year) ? 'class="active"' : ''?>><?php echo $y?></a></dd>
              <?php endforeach;?>
            </dl>
            <dl>
              <dt>按月份：</dt>
              <dd><a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style.'&year='.$year .'&month=all')?>" <?php echo ('all' == $month) ? 'class="active"' : ''?>>全部</a></dd>
              <?php foreach($archiveDate['months'] as $m) :?>
              <dd><a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style.'&year='.$year .'&month='.$m)?>" <?php echo ($m == $month) ? 'class="active"' : ''?>><?php echo $m?></a></dd>
              <?php endforeach;?>
            </dl>
          </div>
        </div>
        <div class="filter-result">
          <div class="filter-result-hd">
            <div class="view-as"> <span class="label">浏览方式：</span>
              <ul>
                <li class="tile"><a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style=tile&year='.$year .'&month='.$month. '&page='. $page)?>" class="<?php echo ($style == 'tile') ? 'active' : ''?> popup-tip" title="平铺">平铺</a></li>
                <li class="list"><a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style=list&year='.$year .'&month='.$month. '&page='. $page)?>" class="<?php echo ($style == 'list') ? 'active' : ''?> popup-tip" title="列表">列表</a></li>
              </ul>
            </div>
          </div>
          <?php include_partial('archive_'.$style, array('archivePager' => $archivePager, 'wiki' => $wiki))?>
          <div class="pagination">
              <a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style.'&year='.$year .'&month='.$month .'&page='. $archivePager->getPreviousPage())?>" title="跳转至<?php echo $archivePager->getPreviousPage()?>页" class="page-prev">上一页</a>
              <?php if ($archivePager->getPage() == $archivePager->getFirstPage()) :?>
              <span class="page-cur"><?php echo $archivePager->getPage()?></span>
              <?php else :?>
              <a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style.'&year='.$year .'&month='.$month .'&page='. $archivePager->getFirstPage())?>" title="跳转至第1页">1</a>
              <?php endif ;?>
              <span class="dots">...</span>
              <?php foreach($archivePager->getLinks(5) as $link) :?>
              <?php if ($archivePager->getPage() == $link) :?>
              <span class="page-cur"><?php echo $archivePager->getPage()?></span>
              <?php else :?>
              <a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style.'&year='.$year .'&month='.$month. '&page='. $link)?>" title="跳转至第<?php echo $link?>页"><?php echo $link?></a>
              <?php endif;?>
              <?php endforeach;?>
              <span class="dots">...</span>
              <?php if ($archivePager->getPage() == $archivePager->getLastPage()) :?>
              <span class="page-cur"><?php echo $archivePager->getPage()?></span>
              <?php else :?>
              <a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style.'&year='.$year .'&month='.$month . '&page='. $archivePager->getLastPage())?>" title="跳转至<?php echo $archivePager->getLastPage()?>页"><?php echo $archivePager->getLastPage()?></a>
              <?php endif ;?>
              <a href="<?php echo url_for('@archive?slug='.$wiki->getSlug().'&style='.$style.'&year='.$year .'&month='.$month . '&page='. $archivePager->getNextPage())?>" title="跳转至<?php echo $archivePager->getNextPage()?>页" class="page-next">下一页</a>
          </div>
        </div>
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
        <?php include_component('wiki', 'hot_broadcast')?>
        <?php include_component('wiki', 'related_movies')?>
      </aside>
    </div>
  </div>
</div>