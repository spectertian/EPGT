<?php if ($movies) :?>
      <?php foreach($movies as $movie) :?>
      <li><a href="<?php echo url_for("wiki/show?slug=".$movie->getSlug()) ?>"><img src="<?php echo thumb_url($movie->getCover(), 112, 152)?>" alt=""/><span><?php echo mb_strcut($movie->getTitle(), 0, 12, 'utf-8') ?></span></a></li>
      <?php endforeach;?>
<?php endif;?>