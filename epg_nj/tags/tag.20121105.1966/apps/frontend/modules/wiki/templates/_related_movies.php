<?php if ($movies) :?>
<div class="mod" id="related-film">
  <div class="hd">
    <h3><?php echo $modeltext?></h3>
    <div class="more"><!--<a href="#">更多&gt;&gt;</a>--></div>
  </div>
  <div class="bd clearfix">
    <ul>
      <?php foreach($movies as $movie) :?>
      <li>
          <div class="poster">
          <a href="<?php echo url_for("wiki/show?slug=".$movie->getSlug()) ?>">
              <img src="<?php echo thumb_url($movie->getCover(), 60, 90)?>" alt="<?php echo $movie->getTitle()?>" width="60" height="90">
          </a>
          </div>
          <h4><a href="<?php echo url_for("wiki/show?slug=".$movie->getSlug()) ?>" title="<?php echo $movie->getTitle()?>"><?php echo $movie->getTitle() ?></a></h4>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
<?php endif;?>