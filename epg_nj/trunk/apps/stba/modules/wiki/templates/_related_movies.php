<?php if ($movies) :?>
    <?php if($refer=='local'||$refer=='tcl'):?>
          <?php $i = 0 ;?>
          <?php foreach($movies as $movie) :?>
          <?php if($i < 4) :?>
          <li><a href="<?php echo url_for("wiki/show?id=".$movie->getId()) ?>"><img src="<?php echo thumb_url($movie->getCover(), 112, 152)?>" alt=""/><span><i><big><?php echo $movie->getTitle() ?></big></i></span></a></li>
          <?php endif;?>
          <?php $i++;?>
          <?php endforeach;?>
    <?php elseif($refer=='center'):?>
          <?php $i = 0 ;?>
          <?php foreach($movies as $movie) :?>
          <?php 
              $posters=explode(';',$movie['poster']);
              $poster = $posters[1];
              $poster = str_replace('{URLTYPE=1}','',$poster);
          ?>
          <?php if($i < 4) :?>
          <li><a href="<?php echo $movie['url']."&param2=bokong&param3=Ranking"; ?>"><img src="<?php echo $poster;?>" alt="<?php echo $movie['Title'];?>"/><span><i><big><?php echo $movie['Title']; ?></big></i></span></a></li>
          <?php endif;?>
          <?php $i++;?>
          <?php endforeach;?>
    <?php else:?>
          <?php $i = 0 ;?>
          <?php foreach($movies as $movie) :?>
          <?php if($i < 4) :?>
          <li><a href="<?php echo $movie['url']; ?>"s><img src="<?php echo $movie['poster'];?>" alt="<?php echo $movie['Title'];?>"/><span><i><big><?php echo $movie['Title']; ?></big></i></span></a></li>
          <?php endif;?>
          <?php $i++;?>
          <?php endforeach;?>
    <?php endif;?>
<?php endif;?>