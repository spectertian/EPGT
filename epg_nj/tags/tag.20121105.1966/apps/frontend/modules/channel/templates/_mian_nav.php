<div class="epg-main-nav">
    <ul>
      <li class="<?php echo ('now' == $active ) ? 'active ' : ''?> on-air-next"><a href="<?php echo lurl_for("channel/index?type=all&mode=$mode")?>">正在播放</a></li>
      <li <?php echo ('电视剧' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=电视剧&mode=$mode")?>" title="电视剧">电视剧</a></li>
      <li <?php echo ('电影' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=电影&mode=$mode")?>" title="电影">电影</a></li>
      <li <?php echo ('体育' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=体育&mode=$mode")?>" title="体育">体育</a></li>
      <li <?php echo ('娱乐' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=娱乐&mode=$mode")?>" title="娱乐">娱乐</a></li>
      <li <?php echo ('少儿' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=少儿&mode=$mode")?>" title="少儿">少儿</a></li>
      <li <?php echo ('科教' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=科教&mode=$mode")?>" title="科教">科教</a></li>
      <li <?php echo ('财经' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=财经&mode=$mode")?>" title="财经">财经</a></li>
      <li <?php echo ('综合' == $active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/tag?tag=综合&mode=$mode")?>" title="综合">综合</a></li>
    </ul>
</div>