<div class="autocomplete">
<ul>
<?php if(!$wikis):?>
    <li rel="0">暂无匹配数据</li>
<?php else: ?>
    <?php foreach( $wikis as $wiki ): ?>
    <li rel="<?php echo $wiki->getId()?>"><?php echo $wiki->getTitle()."|".$wiki->getDisplayName() ?></li>
    <?php endforeach; ?>
<?php endif ?>
</ul>
</div>
<script type="text/javascript">
$('.autocomplete > ul > li').hover(function(){
        var div = $(this).parent().parent();
        $(this).addClass('sel');
        $(this).click(function(){
        div.prev().val($(this).attr('rel'));
        div.remove();
        })
    },
    function(){
        $(this).removeClass('sel');
    }
);
</script>