<?php if ($movies) :?>
                        <ul class="clr">
                        <?php foreach($movies as $movie) :?>
                        	<li><a href="<?php echo url_for("wiki/show?slug=".$movie->getSlug()) ?>" title="<?php echo $movie->getTitle()?>"><img src="<?php echo thumb_url($movie->getCover(), 60, 90)?>" alt="<?php echo $movie->getTitle()?>" /><?php echo mb_substr($movie->getTitle(), 0, 8, 'utf-8') ?></a></li>
                        <?php endforeach;?>	
                        </ul>
<?php endif;?>
