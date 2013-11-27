<div class="epg-sub-nav">
    <div  class="epg-channel epg-option">
        <ul>
            <li class="today <?php echo ('today' == $date ) ? 'active' : ''?>"><a href="<?php echo url_for('channel/tag/?tag='. $tag .'&mode='.$mode. '&date=today&location='.$location)?>" title="今天">今天 (<?php echo date('n月d日', time())?>)</a></li>
            <li class="tomorrow <?php echo ('tomorrow' == $date ) ? 'active' : ''?>"><a href="<?php echo url_for('channel/tag/?tag='. $tag .'&mode='.$mode.'&date=tomorrow&location='.$location)?>" title="明天">明天 (<?php echo date('n月d日', time()+86400)?>)</a></li>
            <li class="day-after-tomorrow <?php echo ('day-after-tomorrow' == $date)  ? 'active' : ''?>"><a href="<?php echo url_for('channel/tag/?tag='. $tag .'&mode='.$mode.'&date=day-after-tomorrow&location='.$location)?>" title="后天">后天 (<?php echo date('n月d日', time()+172800)?>)</a></li>
      </ul>
    </div>
    <div class="view-as"> <span class="label">浏览方式：</span>
      <ul>
        <li class="tile"><a href="<?php echo url_for('channel/tag/?tag='. $tag .'&mode=tile&location='.$location)?>" <?php echo ('tile' == $mode) ? 'class="active"' : ''?> title="平铺">平铺</a></li>
        <li class="list"><a href="<?php echo url_for('channel/tag/?tag='. $tag .'&mode=list&location='.$location)?>" <?php echo ('list' == $mode) ? 'class="active"' : ''?> title="列表">列表</a></li>
      </ul>
    </div>
</div>
