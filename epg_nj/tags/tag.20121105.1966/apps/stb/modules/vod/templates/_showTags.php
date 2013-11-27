<?php if($wikiTagsRepons): ?>
    <?php $i = 0;?>
	<?php foreach($wikiTagsRepons as $key => $tags) :?>
    <?php if($i <= 10):?>
	<?php if ($tags == '全部'):?>
	<li><h3><a href="javascript:void(0);" <?php if($mytag=="全部" || $mytag=="" ):?>  class="there" <?php endif;?>  onclick="listForm('tag','全部');">全部</a></h3></li>
  	<?php else: ?>
  	<li><a href="javascript:void(0);"  <?php  if($mytag==$tags):?> class="there" <?php endif;?>  onclick="listForm('tag','<?php echo $tags?>');"><?php echo $tags;?></a></li>
  	<?php endif;?>
    <?php endif;?> 
    <?php $i++;?>               
 	<?php endforeach;?>
<?php endif; ?>