<script type="text/javascript">
$(document).ready(function() {
    var wiki_id = '';
    var back_target = ''
    $('.left-col').list({
        direction: 'V',
        focus: function(event) {
            $(this).addClass('hover');
            $(this).find('.button').removeClass('display-none');
            var title = $(this).find('div.title').text();
            $('#footer_info_title').text(title);
            $('.info').removeClass('display-none');
        },
        blur: function(event) {
            $(this).removeClass('hover');
            $(this).find('.button').addClass('display-none');
        },
        right: function(event) {
            $('#index-right-col').grid('focus');
        },
        enter: function(event, item) {
            wiki_id = item.attr('rel');
            back_target = '.left-col';
            var popup = $('#popup');
            popup.removeClass('display-none');
            popup.list('focus');
            popup.data('back', $(this));
        },
        change: function(event, ui) {
            ui.to.find('.button').removeClass('display-none');
            if(ui.from) {
                ui.from.find('.button').addClass('display-none');
            }
        },
        up: function( event, ui ) {
            $('#menu-bar').data('ui').focus();
        }
    });

    $('#index-right-col').grid({
        coords: [ 3, 2 ],
        rightPass: true,
        change: function(event, ui) {
            ui.to.find('.button').removeClass('display-none');
            if(ui.from) {
                ui.from.find('.button').addClass('display-none');
            }
            var title = ui.to.find('div.title').text();
            $('#footer_info_title').text(title);
            $('.info').show();
        },

        enter: function(event, item) {
            wiki_id = item.attr('rel');
            back_target = '#index-right-col';
            var popup = $('#popup');
            popup.removeClass('display-none');
            popup.list('focus');
            popup.data('back', $(this));
        },

        leftBorde: function(event) {
            $('.left-col').list('focus');
            $(this).find('.button').addClass('display-none');
        },

        upBorde: function( event, ui ) {
            $('#menu-bar').data('ui').focus();
        }

    });

    $('#popup').list({
        direction: 'V',
        enter: function(event, item) {
            var href = item.attr('href');
            if(href == 'closePoup') {
                back = $(this).data('back');
                $('#popup').addClass('display-none');
                back.data('ui').focus();
            } else if (href == 'showDetail') {
                $('#popup').addClass('display-none');
                var wiki_url = '<?php echo url_for('wiki/show?id='); ?>' + wiki_id;
                $("#wiki-sizer").load(wiki_url, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#wiki-sizer").attr('return_target', back_target);
                    $("#wiki-sizer").removeClass("display-none");

                    $('#footer_back').list('focus');
                });
            }
        }
    })
});
</script>
<div class="epg-home">
    
  <div class="left-col">
    <div class="still"><img src="public/index_001.jpg" width="" height="" alt=""></div>
    <div class="title">来不及说我爱你</div>
    <div class="details">
      <ul>
        <li>导演: <strong><span class="no-warp">曾丽珍</span></strong></li>
        <li>主演: <strong><span class="no-warp">李小冉</span> / <span class="no-warp">钟汉良</span> / <span class="no-warp">孙玮</span> / <span class="no-warp">谭凯</span> / <span class="no-warp">寇振海</span> / <span class="no-warp">归亚蕾</span></strong></li>
        <li>地区: <strong><span class="no-warp">内陆</span></strong></li>
        <li>集数: <strong><span class="no-warp">18</span></strong></li>
        <li>分类: <strong><span class="no-warp">爱情剧</span></strong></li>
      </ul>
      <div class="button hover action display-none" rel="52517" >播放节目</div>
    </div>
  </div>
    
    <div class="right-col">
    <ul id="index-right-col">
      <li class="action" rel="52090">
          <div class="cover"><img src="public/index_003.jpg" width="" height="" alt=""></div>
          <div class="details">
            <div class="title">杜拉拉升职记</div>
            <ul>
              <li>导演: <strong><span class="no-warp">陈铭章</span></strong></li>
              <li>主演: <strong><span class="no-warp">王珞丹</span> / <span class="no-warp">李光洁</span> / <span class="no-warp">李彩桦</span> / <span class="no-warp">陈慧珊</span> / <span class="no-warp">叶童</span></strong></li>
            </ul>
            <div class="button display-none">播放节目</div>
          </div>
        </li>
      <li class="action" rel="52691">
        <div class="cover"><img src="public/index_004.jpg" width="" height="" alt=""></div>
        <div class="details">
          <div class="title">恋爱通告</div>
          <ul>
            <li>导演: <strong><span class="no-warp">王力宏</span></strong></li>
            <li>主演: <strong><span class="no-warp">王力宏</span> / <span class="no-warp">刘亦菲</span> / <span class="no-warp">曾轶可</span> / <span class="no-warp">乔振宇</span> / <span class="no-warp">陈汉典</span></strong></li>
          </ul>
          <div class="button display-none">播放节目</div>
        </div>
      </li>
      <li class="action" rel="52923">
        <div class="cover"><img src="public/index_005.jpg" width="" height="" alt=""></div>
        <div class="details">
          <div class="title">绿色空间</div>
          <ul>
            <li>主持人: <strong><span class="no-warp">白桦</span></strong></li>
            <li>地区: <strong><span class="no-warp">内陆</span></strong></li>
          </ul>
          <div class="button display-none">播放节目</div>
        </div>
      </li>
      <li class="action" rel="52893">
        <div class="cover"><img src="public/index_006.jpg" width="" height="" alt=""></div>
        <div class="details">
          <div class="title">王刚讲故事</div>
          <ul>
            <li>主持人: <strong><span class="no-warp">王刚</span></strong></li>
            <li>地区: <strong><span class="no-warp">内陆</span></strong></li>
          </ul>
          <div class="button display-none">播放节目</div>
        </div>
      </li>
      <li class="action" rel="52880">
        <div class="cover"><img src="public/index_007.jpg" width="" height="" alt=""></div>
        <div class="details">
          <div class="title">非诚勿扰</div>
          <ul>
            <li>主持人: <strong><span class="no-warp">孟非</span> / <span class="no-warp">乐嘉</span> / <span class="no-warp">黄菡</span></strong></li>
            <li>嘉宾: <strong><span class="no-warp">蓝显丽</span> / <span class="no-warp">梁嘉敏</span> / <span class="no-warp">谢小兰</span></strong></li>
          </ul>
          <div class="button display-none">播放节目</div>
        </div>
      </li>
      <li class="action" rel="52905">
        <div class="cover"><img src="public/index_008.jpg" width="" height="" alt=""></div>
        <div class="details">
          <div class="title">动画城</div>
          <ul>
            <li>主持人: <strong><span class="no-warp">小鹿姐姐</span> / <span class="no-warp">哆来咪</span> / <span class="no-warp">小蜻蜓</span></strong></li>
            <li>地区: <strong><span class="no-warp">内陆</span></strong></li>
          </ul>
          <div class="button display-none">播放节目</div>
        </div>
      </li>
    </ul>
    </div>
    
</div>
<div class="footer">
<div class="info display-none">
  <span id="footer_info_title"></span>
  <span class="progress">
    <span class="track" style="width:20%"></span>
  </span>
  <span>00:30/01:30</span>
</div>
<div class="help">
  <span>按</span>
  <span class="arrows">&lt; &gt;</span>
  <span>键选择，按</span>
  <span class="button">OK</span>
  <span>键确认</span>
</div>
</div>

<div id="popup" class="display-none">
    <div class="popup">
        <div class="play">
          <ul>
            <li class="disabled ">播放节目</li>
            <li class="disabled">片库资源</li>
            <li class="action" href="showDetail">节目详情</li>
            <li class="action" href="closePoup">取消</li>
          </ul>
        </div>
    </div>
    <div class="popup-bg"></div>
</div>