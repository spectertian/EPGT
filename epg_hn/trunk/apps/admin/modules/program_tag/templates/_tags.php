<?php 
    $length = $tag_datas['tags']->count();
    foreach ($tag_datas['tags'] as $k => $tag):
?>
<?php if($tag) {?>
    <span id="tags<?php echo $tag_datas['relations'][$k]; ?>" rel="<?php echo $tag->getId(); ?>"><?php echo $tag->getName(); ?><a class="removeTags" title="删除标签--<?php echo $tag->getName(); ?>" href="javascript:tag_del(<?php echo $tag_datas['relations'][$k]; ?>);">x</a>,</span>
<?php }?>
<?php //echo ($k < $length - 1 ? '，' : ''); ?>
<?php endforeach; ?>