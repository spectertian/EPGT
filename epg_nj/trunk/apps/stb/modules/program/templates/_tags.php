<?php $index = 0; ?>
<?php foreach($tags as $tag):?>
<?php if($index < 6) :?>
    <li><a href="javascript:void(0);" onclick="goSearch('<?php echo $tag?>')"><span><?php echo mb_strcut($tag,0,15,'utf-8');?></span></a></li>
<?php endif;?>
<?php $index ++;?>
<?php endforeach;?>