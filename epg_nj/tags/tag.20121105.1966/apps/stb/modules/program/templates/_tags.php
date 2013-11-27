<?php $index = 0; ?>
<?php foreach($tags as $tag):?>
<?php if($index < 6) :?>
							<li><a href="<?php echo url_for('search/list?q=tag:'.$tag) ?>"><?php echo $tag?></a></li>
<?php endif;?>
<?php $index ++;?>
<?php endforeach;?>
