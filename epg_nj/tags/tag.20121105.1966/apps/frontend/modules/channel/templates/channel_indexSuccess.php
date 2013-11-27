<script type="text/javascript">
$(function(){
    // tab-mod
    $('.tab-mod .tab-hd li a').click(function(){
        $(this).addClass('active').parent('.tab-mod .tab-hd li').siblings().children('a').removeClass();
        $(".tab-mod .tab-bd").eq($('.tab-mod .tab-hd li a').index(this)).show().siblings('.tab-bd').hide();
    });
})
</script>
<div class="container channel-index">
  <div class="container-inner">
    <div class="main-bd">
      <h2>频道索引</h2>
      <?php include_component("channel","province");?>
      <div class="switch-channel-mod">
        <div class="switch-channel-bd tab-mod" style="display:block;">
          <div class="tab-hd">
            <ul>
              <li><a href="javascript:void(0)" class="active">全部频道</a></li>
              <li><a href="javascript:void(0)">本地频道</a></li>
              <li><a href="javascript:void(0)">央视频道</a></li>
              <li><a href="javascript:void(0)">各省卫视</a></li>
              <li><a href="javascript:void(0)">我的频道</a></li>
            </ul>
          </div>
          <div id="tab1" class="tab-bd" style="display:block;">
            <div class="channel-list">
              <h3>本地（<?php echo $province;?>）频道</h3>
              <ul>
              <?php if(!is_null($local_station)) :?>
              <?php foreach($local_station as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 44, 24) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?>
              <?php endif?>
              </ul>
            </div>
            <div class="channel-list">
              <h3>央视频道</h3>
              <ul>
              <?php if(!is_null($cctv_station)) :?>
              <?php foreach($cctv_station as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 42, 22) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?>
              <?php endif?>
              </ul>
            </div>
            <div class="channel-list">
              <h3>各省卫视</h3>
              <ul>
             <?php if(!is_null($tv_station)) :?>
              <?php foreach($tv_station as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 42, 22) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?>
              <?php endif?>
              </ul>
            </div>
            <div class="channel-list">
              <h3>我的频道</h3>
              <ul>
              <?php if(!is_null($mytv)) :?>
              <?php foreach($mytv as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 42, 22) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?>
              <?php endif?>
              </ul>
            </div>
          </div>
          <div id="tab2" class="tab-bd" style="display:none;">
            <div class="channel-list">
              <ul>
              <?php if(!is_null($local_station)) :?>
              <?php foreach($local_station as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 42, 22) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?>
              <?php endif?>
              </ul>
            </div>
          </div>
          <div id="tab3" class="tab-bd" style="display:none;">
            <div class="channel-list">
              <ul>
              <?php if(!is_null($cctv_station)) :?>
              <?php foreach($cctv_station as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 42, 22) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?>
              <?php endif?>
              </ul>
            </div>
          </div>
          <div id="tab4" class="tab-bd" style="display:none;">
            <div class="channel-list">
              <ul>
             <?php if(!is_null($tv_station)) :?>
              <?php foreach($tv_station as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 42, 22) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?>
              <?php endif?>
              </ul>
            </div>
          </div>
          <div id="tab5" class="tab-bd" style="display:none;">
            <?php if(!is_null($mytv)) :?>
            <div class="channel-list">
              <ul>
              <?php foreach($mytv as $key => $station) :?>
                <li>
                  <div class="channel-logo">
                  <span class="station">
                  <a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><img src="<?php echo thumb_url($station->getLogo(), 42, 22) ?>" alt="<?php echo $station->getName()?>"></a></span> <span class="channel"><a href="<?php echo lurl_for("channel/show?id=".$station->getId())?>"><?php echo $station->getName()?></a>
                  </span>
                  </div>
                </li>
              <?php endforeach?> 
              </ul>
              <?php endif?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>