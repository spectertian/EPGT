<script type="text/javascript">
$(document).ready(function() {
    /*$("#channel-cat-content").list({
        direction: 'H',
        up: function(event) {
            $("#menu-bar").data('ui').focus();
        },
        enter: function(event, item) {
            type = item.attr('rel');
            if (type) {
                $("#channel-programs-sizer").load("<?php echo url_for('tv_station/show'); ?>", {"type": type}, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#channel-programs-sizer").removeClass("display-none");
//                    $(".channel-item:first").data("ui").focus();
                    //$("#channel_programs_back").list('focus');
                });
            }
        }
    });*/
    var scrollHeight = 560;

    var $laar = $('.channel-list-new .larr');
    var $raar = $('.channel-list-new .rarr');

    $('#channel-cat-contentbb').grid({
        coords: [ 4, 6 ],
        init: function(event, opts) {
            //var opts = instance.options;
            if (opts.currentPage == 0) {
                $laar.addClass('display-none');
            } else {
                $laar.removeClass("display-none");
            }
            if (opts.pages == 0) {
                $raar.addClass("display-none");
            } else {
                $raar.removeClass("display-none");
            }
        },
        enter: function(event, item) {
            type = item.attr('rel');
            //if (type) {
            var channel_id = item.attr('channel_id');
            $("#channel-programs-sizer").load("<?php echo url_for('tv_station/show'); ?>", {"type": type, "channel_id": channel_id}, function() {
                $("#main-nav-sizer").addClass("display-none");
                $("#channel-programs-sizer").removeClass("display-none");
//                    $(".channel-item:first").data("ui").focus();
                //$("#channel_programs_back").list('focus');
            });
            //}
        },

        upBorde: function( event, ui ) {
            $('#menu-bar').data('ui').focus();
        },
        rightBorde: function( event, ui ) {
            var opts = ui.instance.options;
            var i = ( opts.currentPage + 1 ) * opts.coords[0] * opts.coords[1];
            var $i = ui.instance.$items.eq(i);
            if($i.length){
                opts.currentPage++;
                ui.$item = $i;
                ui.instance.goTo(event, ui );
                $(this).css('top', function(index, val) {
                    return (parseFloat(val) - scrollHeight) + 'px';
                });
                if (opts.currentPage == opts.pages) {
                    $raar.addClass('display-none');
                } else {
                    $raar.removeClass('display-none');
                }
                if (opts.currentPage == 0) {
                    $laar.addClass('display-none');
                } else {
                    $laar.removeClass('display-none');
                }
            }
        },
        leftBorde: function( event, ui ) {
            var opts = ui.instance.options;
            if ( opts.currentPage - 1 < 0 ) return;
            var i = ( opts.currentPage - 1 ) * opts.coords[0] * opts.coords[1];
            var $i = ui.instance.$items.eq(i + opts.coords[1] - 1);
            if($i.length){
                opts.currentPage--;
                ui.$item = $i;
                ui.instance.goTo(event, ui );
                $(this).css('top', function(index, val) {
                    return (parseFloat(val) + scrollHeight) + 'px';
                });
                if (opts.currentPage == opts.pages) {
                    $raar.addClass('display-none');
                } else {
                    $raar.removeClass('display-none');
                }
                if (opts.currentPage == 0) {
                    $laar.addClass('display-none');
                } else {
                    $laar.removeClass('display-none');
                }
            }
        }
    });
});
</script>
<div class="channel-list-new">
    <div class="item-list" id="channel-cat-contentbb" style="top:0px">
        <ul>
            <li class="action" channel_id="119" rel="local">
                <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
                深圳卫视
            </li>
            <li class="action" channel_id="102" rel="tv">
                <div class="icon"><img src="public/icon/guangdong.png" width="200" height="108" alt=""></div>
                广东卫视
            </li>
            <li class="action" channel_id="38" rel="tv">
              <div class="icon"><img src="public/icon/beijing.png" width="200" height="108" alt=""></div>
              北京卫视
            </li>
            <li class="action" channel_id="871" rel="tv">
                <div class="icon"><img src="public/icon/dongfang.png" width="200" height="108" alt=""></div>
                东方卫视
            </li>
            <li class="action" channel_id="870" rel="tv">
                <div class="icon"><img src="public/icon/dongnan.png" width="200" height="108" alt=""></div>
                东南卫视
            </li>
            <li class="action" channel_id="332" rel="tv">
                <div class="icon"><img src="public/icon/hunan.png" width="200" height="108" alt=""></div>
                湖南卫视
            </li>
            <li class="action" channel_id="867" rel="tv">
                <div class="icon"><img src="public/icon/anhui.png" width="200" height="108" alt=""></div>
                安徽卫视
            </li>
            <li class="action" channel_id="382" rel="tv">
                <div class="icon"><img src="public/icon/jiangsu.png" width="200" height="108" alt=""></div>
                江苏卫视
            </li>
            <li class="action" channel_id="434" rel="tv">
                <div class="icon"><img src="public/icon/jiangxi.png" width="200" height="108" alt=""></div>
                江西卫视
            </li>
            <li class="action" channel_id="696" rel="tv">
                <div class="icon"><img src="public/icon/sichuan.png" width="200" height="108" alt=""></div>
                四川卫视
            </li>
            <li class="action" channel_id="52" rel="tv">
                <div class="icon"><img src="public/icon/chongqing.png" width="200" height="108" alt=""></div>
                重庆卫视
            </li>
            <li class="action" channel_id="506" rel="tv">
                <div class="icon"><img src="public/icon/ningxia.png" width="200" height="108" alt=""></div>
                宁夏卫视
            </li>
            <li class="action" channel_id="513" rel="tv">
                <div class="icon"><img src="public/icon/qinghai.png" width="200" height="108" alt=""></div>
                青海卫视
            </li>
            <li class="action" channel_id="520" rel="tv">
                <div class="icon"><img src="public/icon/shandong.png" width="200" height="108" alt=""></div>
                山东卫视
            </li>
            <li class="action" channel_id="608" rel="tv">
                <div class="icon"><img src="public/icon/shanxi.png" width="200" height="108" alt=""></div>
                山西卫视
            </li>


        <li class="action" channel_id="753" rel="tv">
          <div class="icon"><img src="public/icon/tianjin.png" width="200" height="108" alt=""></div>
          天津卫视
        </li>
        <li class="action" channel_id="762" rel="tv">
          <div class="icon"><img src="public/icon/xizang.png" width="200" height="108" alt=""></div>
          西藏卫视
        </li>
        <li class="action" channel_id="799" rel="tv">
          <div class="icon"><img src="public/icon/xinjiang.png" width="200" height="108" alt=""></div>
          新疆卫视
        </li>
        <li class="action" channel_id="817" rel="tv">
          <div class="icon"><img src="public/icon/yunnan.png" width="200" height="108" alt=""></div>
          云南卫视
        </li>
        <li class="action" channel_id="833" rel="tv">
          <div class="icon"><img src="public/icon/zhejiang.png" width="200" height="108" alt=""></div>
          浙江卫视
        </li>
        <li class="action" channel_id="496" rel="tv">
          <div class="icon"><img src="public/icon/neimenggu.png" width="200" height="108" alt=""></div>
          内蒙古卫视
        </li>
        <li class="action" channel_id="464" rel="tv">
          <div class="icon"><img src="public/icon/liaoning.png" width="200" height="108" alt=""></div>
          辽宁卫视
        </li>
        <li class="action" channel_id="88" rel="tv">
          <div class="icon"><img src="public/icon/gansu.png" width="200" height="108" alt=""></div>
          甘肃卫视
        </li>
        <li class="action" channel_id="151" rel="tv">
          <div class="icon"><img src="public/icon/guangxi.png" width="200" height="108" alt=""></div>
          广西卫视
        </li>
        <li class="action" channel_id="169" rel="tv">
          <div class="icon"><img src="public/icon/guizhou.png" width="200" height="108" alt=""></div>
          贵州卫视
        </li>
        <li class="action" channel_id="192" rel="tv">
          <div class="icon"><img src="public/icon/hebei.png" width="200" height="108" alt=""></div>
          河北卫视
        </li>
        <li class="action" channel_id="219" rel="tv">
          <div class="icon"><img src="public/icon/henan.png" width="200" height="108" alt=""></div>
          河南卫视
        </li>
        <li class="action" channel_id="277" rel="tv">
          <div class="icon"><img src="public/icon/heilongjiang.png" width="200" height="108" alt=""></div>
          黑龙江卫视
        </li>
        <li class="action" channel_id="297" rel="tv">
          <div class="icon"><img src="public/icon/hubei.png" width="200" height="108" alt=""></div>
          湖北卫视
        </li>
        <li class="action" channel_id="629" rel="tv">
          <div class="icon"><img src="public/icon/shaanxi.png" width="200" height="108" alt=""></div>
          陕西卫视
        </li>






        <li class="action" channel_id="362" rel="tv">
          <div class="icon"><img src="public/icon/jilin.png" width="200" height="108" alt=""></div>
          吉林卫视
        </li>
        <li class="action" channel_id="184" rel="tv">
          <div class="icon"><img src="public/icon/lvyou.png" width="200" height="108" alt=""></div>
          旅游卫视
        </li>
        <li class="action" channel_id="1" rel="cctv">
          <div class="icon"><img src="public/icon/cctv1.png" width="200" height="108" alt=""></div>
          CCTV-1
        </li>
        <li class="action" channel_id="2" rel="cctv">
          <div class="icon"><img src="public/icon/cctv2.png" width="200" height="108" alt=""></div>
          CCTV-2
        </li>
        <li class="action" channel_id="3" rel="cctv">
          <div class="icon"><img src="public/icon/cctv3.png" width="200" height="108" alt=""></div>
          CCTV-3
        </li>
        <li class="action" channel_id="4" rel="cctv">
          <div class="icon"><img src="public/icon/cctv4.png" width="200" height="108" alt=""></div>
          CCTV-4 亚洲
        </li>
        <li class="action" channel_id="5" rel="cctv">
          <div class="icon"><img src="public/icon/cctv4.png" width="200" height="108" alt=""></div>
          CCTV-4 欧洲
        </li>
        <li class="action" channel_id="6" rel="cctv">
          <div class="icon"><img src="public/icon/cctv4.png" width="200" height="108" alt=""></div>
          CCTV-4 美洲
        </li>
        <li class="action" channel_id="7" rel="cctv">
          <div class="icon"><img src="public/icon/cctv5.png" width="200" height="108" alt=""></div>
          CCTV-5
        </li>
        <li class="action" channel_id="8" rel="cctv">
          <div class="icon"><img src="public/icon/cctv6.png" width="200" height="108" alt=""></div>
          CCTV-6
        </li>
        <li class="action" channel_id="9" rel="cctv">
          <div class="icon"><img src="public/icon/cctv7.png" width="200" height="108" alt=""></div>
          CCTV-7
        </li>
        <li class="action" channel_id="10" rel="cctv">
          <div class="icon"><img src="public/icon/cctv8.png" width="200" height="108" alt=""></div>
          CCTV-8
        </li>
        <li class="action" channel_id="11" rel="cctv">
          <div class="icon"><img src="public/icon/cctv9.png" width="200" height="108" alt=""></div>
          CCTV-9
        </li>
        <li class="action" channel_id="12" rel="cctv">
          <div class="icon"><img src="public/icon/cctv10.png" width="200" height="108" alt=""></div>
          CCTV-10
        </li>
        <li class="action" channel_id="13" rel="cctv">
          <div class="icon"><img src="public/icon/cctv11.png" width="200" height="108" alt=""></div>
          CCTV-11
        </li>




        <li class="action" channel_id="14" rel="cctv">
          <div class="icon"><img src="public/icon/cctv12.png" width="200" height="108" alt=""></div>
          CCTV-12
        </li>
        <li class="action" channel_id="15" rel="cctv">
          <div class="icon"><img src="public/icon/cctv_news.png" width="200" height="108" alt=""></div>
          CCTV-新闻频道
        </li>
        <li class="action" channel_id="16" rel="cctv">
          <div class="icon"><img src="public/icon/cctv_kids.png" width="200" height="108" alt=""></div>
          CCTV-少儿频道
        </li>
        <li class="action" channel_id="17" rel="cctv">
          <div class="icon"><img src="public/icon/cctv_music.png" width="200" height="108" alt=""></div>
          CCTV-音乐频道
        </li>
        <li class="action" channel_id="120" rel="local">
          <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
          深视都市频道
        </li>
        <li class="action" channel_id="121" rel="local">
          <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
          深视电视剧频道
        </li>
        <li class="action" channel_id="122" rel="local">
          <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
          深视财经生活
        </li>
        <li class="action" channel_id="123" rel="local">
          <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
          深视娱乐频道
        </li>
        <li class="action" channel_id="124" rel="local">
          <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
          深视体育健康
        </li>
        <li class="action" channel_id="125" rel="local">
          <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
          深视少儿频道
        </li>
        <li class="action" channel_id="126" rel="local">
          <div class="icon"><img src="public/icon/shenzhen.png" width="200" height="108" alt=""></div>
          深视公共频道
        </li>
        <li class="action" channel_id="113" rel="local">
          <div class="icon"><img src="public/icon/tvs.png" width="200" height="108" alt=""></div>
          南方经济频道
        </li>
        <li class="action" channel_id="114" rel="local">
          <div class="icon"><img src="public/icon/tvs.png" width="200" height="108" alt=""></div>
          南方都市频道
        </li>
        <li class="action" channel_id="115" rel="local">
          <div class="icon"><img src="public/icon/tvs.png" width="200" height="108" alt=""></div>
          南方综艺频道
        </li>
        <li class="action" channel_id="116" rel="local">
          <div class="icon"><img src="public/icon/tvs.png" width="200" height="108" alt=""></div>
          南方影视频道
        </li>



        <li class="action" channel_id="117" rel="local">
          <div class="icon"><img src="public/icon/tvs.png" width="200" height="108" alt=""></div>
          南方少儿频道
        </li>
        <li class="action" channel_id="103" rel="local">
          <div class="icon"><img src="public/icon/guangdong.png" width="200" height="108" alt=""></div>
          广东珠江频道
        </li>
        <li class="action" channel_id="105" rel="local">
          <div class="icon"><img src="public/icon/guangdong.png" width="200" height="108" alt=""></div>
          广东公共频道
        </li>
        <li class="action" channel_id="104" rel="local">
          <div class="icon"><img src="public/icon/gdtiyu.png" width="200" height="108" alt=""></div>
          广东体育频道
        </li>
        </ul>
    </div>
    <div class="larr">&lt;</div>
    <div class="rarr">&gt;</div>
</div>

<!--<div class="prog-list hover" id="channel-cat-content">
   <ul>
     <li class="action prog-1" rel="local">
        <div class="title">本地</div>
      </li>
      <li class="action prog-2" rel="cctv">
        <div class="title">央视</div>
      </li>
      <li class="action prog-3" rel="tv">
        <div class="title">卫视</div>
      </li>
      <li class="action prog-4" rel="edu">
        <div class="title">教育</div>
      </li>
      <li class="action prog-5 disabled">
        <div class="title">数字</div>
      </li>
      <li class="action prog-6 disabled">
        <div class="title">高清</div>
      </li>
   </ul>
</div>-->
