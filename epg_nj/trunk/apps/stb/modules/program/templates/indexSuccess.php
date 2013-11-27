<?php foreach($tags as $tag):?>
							<li><a href="<?php echo url_for('search/list?q=tag:'.$tag) ?>"><?php echo $tag?></a></li>
<?php endforeach;?>