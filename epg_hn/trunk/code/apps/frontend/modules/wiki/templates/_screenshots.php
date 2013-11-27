    <?php if($wiki->getScreenshots()): ?>
    <div class="stills">
      <ul>
        <?php $i = 0;?>
        <?php foreach($wiki->getScreenshots() as $screenshot) : $i++?>
        <li>
            <a href="<?php echo thumb_url($screenshot, 600, 600) ?>" rel="stills" title="<?php printf('%s%d', $wiki->getTitle(), $i)?>">
                <img src="<?php echo thumb_url($screenshot, 150, 150) ?>" alt="<?php printf('%s%d', $wiki->getTitle(), $i)?>">
            </a>
        </li>
        <?php endforeach;?>
      </ul>
      <?php if(4 < $wiki->getScreenshotsCount()) :?>
      <div class="more">(全部 <?php echo $wiki->getScreenshotsCount()?> 张剧照) <a href="javascript:void(0)">展开</a></div>
      <?php endif;?>
    </div>
    <?php endif;?>
<script type="text/javascript">
$(function(){
    // colorbox
    $("a[rel='stills']").colorbox();
    // colorbox zoom in
    $("#cboxContent").prepend("<div class='zoomin' STYLE='BACKGROUND:#F00; POSITION:ABSOLUTE; BOTTOM:40px; RIGHT:10px;'><a href='#' target='_blank'>查看原图</a></div>");
    // more or less stills
    $('.stills .more a').toggle(function (){
        $(this).addClass('active').empty().append('收缩');
        $(this).parents('.stills').children('.stills ul').css({ 'height': 'auto' });
    },function (){
        $(this).removeClass('active').empty().append('展开');
        $(this).parents('.stills').children('.stills ul').css({ 'height': '110px' });
    });
})
</script>
      