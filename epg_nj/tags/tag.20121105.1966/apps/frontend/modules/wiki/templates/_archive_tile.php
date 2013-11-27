  <div class="filter-result-bd filter-result-tile clearfix">
    <ul>
      <?php foreach($archivePager->getResults() as $wikiMeta) :?>
      <li>
        <div class="program">
            <div class="poster">
                <a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug(). '&time=' .$wikiMeta->getMark())?>">
                    <img src="<?php echo $wikiMeta->getOneScreenshot(100, 150) ?>" width="100" height="150" alt="<?php echo $wikiMeta->getTitle()?>">
                </a>
            </div>
            <h3 class="title">
                <a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug(). '&time=' .$wikiMeta->getMark())?>"><?php echo $wikiMeta->getTitle()?></a>
            </h3>
            <?php if($guests = $wikiMeta->getGuests()): $i= 0 ?>
            <div class="text-block">
                <span class="label">嘉宾：</span>
                <?php foreach($guests as $guest) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="#" title="<?php echo $tag?>" property="v:starring"><?php echo $guest?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <!--
            <div class="rating"><span class="rating-num"><strong>9</strong>.8</span> 分 &#47; 15662评价</div>
            -->
        </div>
      </li>
      <?php endforeach;?>
    </ul>
  </div>