<ul class="item_list">
    <li <?php if($_GET['type'] == 'reservation' || $_GET['type'] == ''):?> class="this" <?php endif;?> ><a href="<?php echo url_for('/default/MyTv').'?type=reservation';?>">我的预约</a></li>
    <li <?php if($_GET['type'] == 'collect'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/MyTv').'?type=collect';?>">我的收藏</a></li>
    <li <?php if($_GET['type'] == 'channel'):?> class="this" <?php endif;?>><a href="<?php  echo url_for('/default/MyTv').'?type=channel';?>">喜爱的频道</a></li>
 
</ul>