<div class="epg-sub-nav">
    <div  class="epg-channel epg-option">
        <ul>
            <li <?php echo ('all' == $top_active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/index?type=all&mode=$mode")?>" title="所有频道">所有频道</a></li>
            <li <?php echo ('local' == $top_active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/index?type=local&mode=$mode")?>" title="本地">本地</a></li>
            <li <?php echo ('cctv' == $top_active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/index?type=cctv&mode=$mode")?>" title="央视">央视</a></li>
            <li <?php echo ('tv' == $top_active ) ? 'class="active"' : ''?>><a href="<?php echo lurl_for("channel/index?type=tv&mode=$mode")?>" title="卫视">卫视</a></li>
            <li  style="display:none"><div class="fav-channel" ><a href="#" >私人频道</a></div></li>
        </ul>
    </div>
    <div class="view-as"> <span class="label">浏览方式：</span>
      <ul>
          <li class="tile"><a href="<?php echo lurl_for("channel/index?type=$type&mode=tile")?>" <?php if($mode == "tile"):?>class="active" <?php endif;?> title="平铺">平铺</a></li>
          <li class="list"><a href="<?php echo lurl_for("channel/index?type=$type&mode=list")?>" <?php if ($mode == "list"): ?>class="active" <?php endif; ?> title="列表">列表</a></li>
      </ul>
    </div>
</div>