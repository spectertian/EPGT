<div class="switch-location-mod">
  <div class="switch-location-hd"><a href="javascript:void(0)" class="popup-tip" title="切换位置"><?php echo $province?><b>&#9660;</b></a></div>
    <div class="switch-location-bd">
      <div class="close"><a href="javascript:void(0)">x</a></div>
      <ul>
      <?php 
        foreach ($allProvince as $key => $province):
            $url = '@location_channel_index?location='.$province;
            if ($sf_request->getParameter('action') == 'index') {
                $url = 'channel/index?type='.$sf_request->getParameter('type').'&mode='.$sf_request->getParameter('mode', 'list').'&location='.$province;
            } elseif ($sf_request->getParameter('action') == 'tag') {
                $url = 'channel/tag/?tag='.$sf_request->getParameter('tag', '电视剧') .'&mode='.$sf_request->getParameter('mode', 'list'). '&date='.$sf_request->getParameter('date', 'today').'&location='.$province;
            }
      ?>
      <li><a href="<?php echo url_for($url)?>" title="<?php echo $key?>"><?php echo $key?></a></li>
      <?php endforeach;?>
      </ul>
    </div>
  </div>