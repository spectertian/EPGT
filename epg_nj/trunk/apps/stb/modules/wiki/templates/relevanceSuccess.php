<?php if ($movies) :?>
          <?php $i = 0 ;?>
          <?php foreach($movies as $movie) :?>
          <?php if($i < 4) :?>
          <li><a href="<?php echo $movie['url']; ?>"><img src="<?php echo $movie['poster'];?>" alt="<?php echo $movie['Title'];?>"/><span><i><big><?php echo $movie['Title']; ?></big></i></span></a></li>
          <?php endif;?>
          <?php $i++;?>
          <?php endforeach;?>
<?php endif;?>