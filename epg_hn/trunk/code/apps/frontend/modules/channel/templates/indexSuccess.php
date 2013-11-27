<script type="text/javascript">
function addfavorite(channe_id){
    if (loginDialogStatus()){
    if(channe_id.lenth==0) alert("wiki id is null");
    $.ajax ( {
        type: "post",
        url: "<?php echo url_for('channel/channel_favorites');?>",
        data: {"channe_id": channe_id},
        success: function(m)
        {
                if(m==1){
                    alert("非法提交数据");
                }
                if(m==4){
                    alert("您没有用户登录");
                }
                if(m==2) {
                    alert("添加成功");
                    $('#fav_ a').addClass('active');
                }
                if(m==3){
                    alert("您曾经添加过了相应的数据");
                    $('#fav_'+channe_id+' a').addClass('active');
                }
        }
    })
    }
}
</script>
<div class="container epg">
  <div class="container-inner">
    <div class="main-bd">
      <h2>电视节目指南</h2>
      <?php include_component("channel","province");?>
      <!-- epg start -->
      <?php include_partial('mian_nav', array('active' => $active, 'mode' => $mode)) ?>
      <?php include_partial('index_sub_nav', array('top_active' => $top_active, 'mode'=> $mode,'location' => $location, 'type' => $type))?>
      <div class="epg-channel-list">
        <ul>
        <?php foreach ($channels as $channel): ?>
          <li class="clearfix">
            <div class="channel-info">
              <div class="channel-logo">
                  <span class="station">
                      <a href="<?php echo lurl_for("channel/show?id=".$channel->getId()) ?>">
                          <img alt="<?php echo $channel->getName() ?>" src="<?php echo thumb_url($channel->getLogo(), 44, 24) ?>"></a>
                  </span>
                  <span class="channel">
                    <a href="<?php echo lurl_for("channel/show?id=".$channel->getId()) ?>" title="<?php echo $channel->getName()?>"><?php echo $channel->getName() ?></a>
                  </span>
              </div>
              <div class="channel-menu">
                <ul>
                  <li><a href="<?php echo lurl_for("channel/show?id=".$channel->getId()) ?>" title="<?php echo $channel->getName()?>">节目表</a></li>
                  <li><a href="<?php echo lurl_for("channel/show?id=".$channel->getId()) ?>">栏目</a></li>
                </ul>
              </div>
              <div class="fav-channel" id="fav_<?php echo $channel->getId() ?>"><a href="javascript:addfavorite('<?php echo $channel->getId(); ?>')" class="popup-tip" title="收藏该频道">收藏</a></div>
            </div>
            <?php $programs = $channel->getNowPrograms();?>
            <?php if (count($programs) >= 1) :?>
            <div class="on-air info">
                <h3>
                <?php if($wiki = $programs[0]->getWiki()): ?>
                   <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>"><?php echo $programs[0]->getName(ESC_RAW) ?></a>
                <?php else: ?>
                    <?php echo $programs[0]->getName(ESC_RAW) ?>
                <?php endif;?>
                </h3>
              <div class="play-progress">
              <span class="start-time"><?php echo $programs[0]->getTime(); ?></span>
              <?php $all = strtotime($programs[0]->getEndTime()->format("Y-m-d H:i:s")) - strtotime($programs[0]->getTime());?>
              <?php $plan = time() - strtotime($programs[0]->getTime());?>
              <?php $width = round($plan/$all,2) * 100?>
              <span class="progress-bar"><span class="track" style="width:<?php echo ($width > 100) ? ($width - 100) : $width?>%"></span></span>
              <span class="end-time"><?php echo $programs[0]->getEndTime()->format("H:i"); ?></span></div> 
              <div class="desc">
              <?php if($wiki = $programs[0]->getWiki()): ?>
                <?php echo $wiki->getHtmlCache(90, ESC_RAW) ?>
                <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>">详细 &raquo;</a>
              <?php endif;?>
              </div>
              <div class="poster">
              <?php if($wiki = $programs[0]->getWiki()): ?>
                <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>" target="_blank">
                    <img alt="<?php echo $programs[0]->getName(ESC_RAW) ?>" src="<?php  echo thumb_url($wiki->getCover(), 90, 135);?>" />
                </a>
              <?php endif;?>
              </div>
            </div>
            <?php endif;?>
            <?php if (count($programs) > 1) :?>
            <div class="next-mod">
              <div class="next-hd">
                <h4>即将播放：</h4>
              </div>
              <div class="next-bd">
                <ul>
                <?php foreach($programs as $key => $program):?>
                <?php if (0 == $key)  continue;?>  
                  <li>
                  <span class="time"><?php echo date("H:i",strtotime($program->getTime())); ?> - <?php echo $program->getEndTime()->format("H:i"); ?></span> 
                  <span class="title">
                  <?php if($wiki = $program->getWiki()): ?>
                  <a href="<?php echo url_for("wiki/show?slug=".$wiki->getSlug()) ?>"><?php echo $program->getName() ?></a>
                  <?php else: ?>
                     <?php echo $program->getName(ESC_RAW)?>
                   <?php endif ?>
                  </span>
                  </li>
                <?php endforeach;?>
                </ul>
              </div>
            </div>
            <?php endif;?>
          </li>
            <?php endforeach;?>
        </ul>
      </div>
      <!-- epg end -->
    </div>
  </div>
</div>