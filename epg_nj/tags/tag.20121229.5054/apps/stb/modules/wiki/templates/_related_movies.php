<?php if ($movies) :?>
    <?php if($refer=='local'||$refer=='tcl'):?>
          <?php $i = 0 ;?>
          <?php foreach($movies as $movie) :?>
          <?php if($i < 4) :?>
          <li><a href="<?php echo url_for("wiki/show?slug=".$movie->getSlug()) ?>"><img src="<?php echo thumb_url($movie->getCover(), 112, 152)?>" alt=""/><span><i><big><?php echo $movie->getTitle() ?></big></i></span></a></li>
          <?php endif;?>
          <?php $i++;?>
          <?php endforeach;?>
    <?php else:?>
          <?php $i = 0 ;?>
          <?php foreach($movies as $movie) :?>
          <?php if($i < 4) :?>
          <li><a href="<?php echo $movie['url']; ?>"><img src="<?php echo $movie['poster'];?>" alt="<?php echo $movie['Title'];?>"/><span><i><big><?php echo mb_strcut($movie['Title'],0,12,'utf-8'); ?></big></i></span></a></li>
          <?php endif;?>
          <?php $i++;?>
          <?php endforeach;?>
    <?php endif;?>
<?php endif;?>