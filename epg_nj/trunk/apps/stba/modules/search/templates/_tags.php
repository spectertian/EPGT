<?php foreach($tags as $tag):?>
    <li><a href="javascript:void(0);" onclick="goSearch('<?php echo $tag?>');"><?php echo $tag?></a></li>
<?php endforeach;?>