<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<!--[if lte IE 6]>
<script type="text/javascript" src="js/pngmin.js"></script>
<script>
	DD_belatedPNG.fix(".hd img,.listctn,.listctn img,.follow img,.owebsite");
</script>
<![endif]-->
<!--[if lt IE 9]>
<script src="js/html5.js"></script>
<![endif]-->
<title>EPG Plus</title>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.flippy.min.js"></script>
<link href="style/common.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
$(document).ready(function() {
    $("#body").html($("#div_index").html());    
});

function changeLink(key) {
    if($.browser.msie) {
       $("#body").html($("#div_"+key).html()); 
       changeNavCur(key);
    } else { 
        $("#body").flippy({
            direction: "left",
            duration: "750",
            verso:$("#div_"+key).html(),
            onStart:function(){
            },
            onFinish:function(){
                changeNavCur(key);
            }       
        });
    }
    return false;
}

function changeNavCur(cur) {
    $("#navbar").find("a").each(function(i,val){
        if($(this).attr("href") == "#"+cur) {
            if(!$(this).hasClass("cur")) {
                $(this).addClass("cur");
            }
        } else {
            $(this).removeClass("cur");
        }
    });
}
</script>
</head>
<body>
<div class="all">
	<header class="hd">
		<a href="./" title="sasaye.com" class="f_l"><img src="img/logo.png" alt=""></a>
		<nav id="navbar">
			<a href="#index" class="flippy cur" onclick="changeLink('index');"><span>[</span>首页<span>]</span></a>
			<a href="#epg" class="flippy" onclick="changeLink('epg');"><span>[</span>EPG Plus<span>]</span></a>
			<a href="#focale" class="flippy" onclick="changeLink('focale');"><span>[</span>focale视野<span>]</span></a>
			<a href="#video" class="flippy" onclick="changeLink('video');"><span>[</span>微视频<span>]</span></a>
			<a href="#movie" class="flippy" onclick="changeLink('movie');"><span>[</span>即刻·电影<span>]</span></a>
		</nav>
	</header>
    <div id="body"></div>
    <div id="div_index" style="display:none;">
        <div class="main">
            <ul class="listctn">
                <li>
                    <h3><span>EPG plus</span></h3>
                    <div class="f_c">
                        <img src="images/epg.jpg" alt="EPG plus" class="f_l">
                        <dl>
                            <dt>EPG plus-in for smart tv</dt>
                            <dd>
                                我们不是运营商<br>
                                但我们能帮你黏住用户<br>
                                <a href="#epg"  class="flippy"  onclick="changeLink('epg');">了解更多详情></a>
                            </dd>
                        </dl>
                    </div>
                </li>
                <li>
                    <h3><span>Focale 视野</span></h3>
                    <div class="f_c">
                        <img src="images/focale.jpg" alt="Focale 视野" class="f_r">
                        <dl>
                            <dt>All in one magazine</dt>
                            <dd>
                                电影/电视/时尚<br>
                                旅游/美食/各类新鲜资讯<br>
                                <a href="#focale" class="flippy" onclick="changeLink('focale');">了解更多详情></a>
                            </dd>
                        </dl>
                    </div>
                </li>
                <li>
                    <h3><span>微视频</span></h3>
                    <div class="f_c">
                        <img src="images/microVideo.jpg" alt="微视频" class="f_l">
                        <dl>
                            <dt>微视频·微直播</dt>
                            <dd>
                                智能电视时代<br>
                                节目订阅利器<br>
                                <a href="#video" class="flippy" onclick="changeLink('video');">了解更多详情></a>
                            </dd>
                        </dl>
                    </div>
                </li>
                <li>
                    <h3><span>即刻·电影</span></h3>
                    <div class="f_c">
                        <img src="images/fmovie.jpg" alt="即刻电影" class="f_r">
                        <dl>
                            <dt>即刻·电影</dt>
                            <dd>
                                关注稀缺精品电影<br>
                                <a href="#movie" class="flippy" onclick="changeLink('movie');">了解更多详情></a>
                            </dd>
                        </dl>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div id="div_epg" style="display:none;">    
        <div class="main">        
            <div class="epgbanner">
                <div>EPG Plus，看电视的好帮手<br>——快速、精准切台<br>——预定、收藏节目<br>——竞选推荐、积木预告</div>
            </div>
            <ul class="listepg">
                <li class="f_c">				
                    <img src="images/epgnav.jpg" alt="智能导航·正在看" class="f_l">
                    <dl>
                        <dt>智能导航·正在看</dt>
                        <dd>
                            一键呼出<br>
                            直播节目分类<br>
                            快速定位节目频道
                        </dd>
                    </dl>				
                </li>
                <li class="epglist f_c">
                    <img src="images/epglist.jpg" alt="节目表·节目预约" class="f_r">
                    <dl>
                        <dt>节目表·节目预约</dt>
                        <dd>
                            一周节目提前知<br>
                            频道节目精选推荐<br>
                            节目预约、精彩不错过
                        </dd>
                    </dl>
                </li>
                <li class="epgrec f_c">
                    <img src="images/epgrec.png" alt="精选推荐" class="f_l">
                    <dl>
                        <dt>精选推荐</dt>
                        <dd>
                            精选电视节目，精彩不容错过<br>
                            今日更新、专题推荐，精彩影视电视看
                        </dd>
                    </dl>
                </li>			
            </ul>
        </div>
    </div>
    <div id="div_focale" style="display:none;">
        <div class="focalebanner">		
        </div>
        <div class="bfocale">
            <div class="focale">
                <h3>FOCALE视野</h3><a href="http://weibo.com/focale" class="fweibo" target="_blank"><img src="img/fweibo.png"></a>
                <p>嫌电影杂志太枯燥，时尚杂志不实用，美食杂志难操作，旅游杂志广告太多...？就算你都不嫌，但是你有那么多时间一本一本的看过来吗？《FOCALE视野》融合了影视娱乐和吃喝玩乐等多种内容，扩大你的视野，让你一次看足多本杂志。在本期试刊号中，为您精选了多部热门电影和电视剧的资讯，结合时下最新的时尚和旅游等信息，创造全新的阅读体验。</p>
                <p>杂志创刊号即将上架，敬请期待！</p>
                <em>《FOCALE视野》试刊号现已登陆苹果应用程序商店，欢迎下载体验！</em>
                <a href=""><img src="img/appstore.png"></a>
            </div>
            <div class="adsc">
                <h3><span>广告合作</span></h3>
                <ul class="links">
                    <li><a href=""><img src="images/1.jpg"></a></li>
                    <li><a href=""><img src="images/2.jpg"></a></li>
                    <li><a href=""><img src="images/3.jpg"></a></li>
                </ul>			
            </div>
            <div class="flink bdb">
                <h3><span>友情链接</span></h3>
                <ul class="links">
                    <li><a href=""><img src="images/4.jpg"></a></li>
                    <li><a href=""><img src="images/5.jpg"></a></li>
                    <li><a href=""><img src="images/6.jpg"></a></li>
                    <li><a href=""><img src="images/7.jpg"></a></li>
                    <li><a href=""><img src="images/8.jpg"></a></li>
                    <li><a href=""><img src="images/9.jpg"></a></li>
                    <li><a href=""><img src="images/10.jpg"></a></li>
                    <li><a href=""><img src="images/11.jpg"></a></li>
                    <li><a href=""><img src="images/12.jpg"></a></li>
                </ul>
            </div>
        </div>
    </div> 
    <div id="div_movie" style="display:none;"> 
        <div class="moviebn">
            <dl>
                <dt>TV版</dt>
                <dd>
                    系统要求：<br>Android202或更高版本<a href="" class="download">立即下载</a><a href="http://weibo.com/jiapianfangying" class="followm" target="_blank">关注  即刻·电影</a>			
                </dd>			
                
            </dl>
        </div>
        <div class="main">
            <ul class="listmovie">
                <li class="f_c">				
                    <img src="images/fmovie.jpg" alt="全新分类·打造直播新体验" class="f_r">
                    <dl>
                        <dt>全新分类·打造直播新体验</dt>
                        <dd>
                            精选外语精致电影，划分<span>情感</span>、<span>轻松</span>、<span>奇幻</span>、<span>黑色</span>、<span>人物</span>和<span>获奖</span>六个频道，直接快速定位影片，连续播放不间断，打造电视直播新体验。
                        </dd>
                    </dl>				
                </li>
                <li class="mhd f_c">
                    <div>
                        <img src="images/pmhd.png" alt="中英双语字幕·720P高清影片" class="f_l">
                        <dl>
                            <dt>中英双语字幕·720P高清影片</dt>
                            <dd>
                                清晰、流畅的高清画面，逐句校准的精美字幕<br>
                                内容，享受美好的观影时刻
                            </dd>
                        </dl>
                    </div>				
                </li>
                <li class="f_c">
                    <img src="images/detail.jpg" alt="精彩看点·详尽影片信息" class="f_r">
                    <dl>
                        <dt>精彩看点·详尽影片信息</dt>
                        <dd>
                            影片详情，评价打分，亮点推荐应有尽有<br>
                            做最优质的内容推荐
                        </dd>
                    </dl>
                </li>			
            </ul>
        </div>
    </div>
    <div id="div_video" style="display:none;"> 
        <div class="vediobn">		
        </div>
        <div class="main">
            <ul class="listepg listvedio">
                <li class="version f_c">				
                    <img src="images/version.png" alt="版本信息" class="f_r">
                    <dl>
                        <dt>软件版本：V1.0</dt>
                        <dd>
                            更新日期：2013.08.01<br>
                            更新功能：<br>
                            1.精选视频推荐；<br>
                            2.个性频道定制；<br>
                            3.用户直播体验；<br>
                            <a href="" class="download">立即下载</a>
                        </dd>
                    </dl>				
                </li>
                <li class="rec f_c">
                    <img src="images/rec.png" alt="精选推荐·定制频道" class="f_l">
                    <dl>
                        <dt>精选推荐·定制频道</dt>
                        <dd>
                            精选各频道最新更新视频，新鲜资讯快速浏览；<br>
                            定制频道直接首页呈现，方便快速进入指定频道。
                        </dd>
                    </dl>
                </li>
                <li class="f_c">
                    <img src="images/channel.png" alt="频道中心·频道定制" class="f_r">
                    <dl>
                        <dt>频道中心·频道定制</dt>
                        <dd>
                            整合网络优质视频资讯，形成分类频道；<br>
                            通过频道管理定制喜欢的频道；<br>
                            方便下次快点打开频道。
                        </dd>
                    </dl>
                </li>
                <li class="live f_c">
                    <img src="images/live.png" alt="直播体验·节目列表" class="f_l">
                    <dl>
                        <dt>直播体验·节目列表</dt>
                        <dd>
                            视频播放为用户提供电视直播体验；<br>
                            通过上下左右和OK键进行操作。
                        </dd>
                    </dl>
                </li>
            </ul>
            <div class="adsc bdb">
                <h3><span>广告站点</span></h3>
                <div class="listad">
                    <ul class="links">
                        <li><a href="" title="百度"><img src="img/baidu.png" alt="百度"></a></li>
                        <li><a href="" title="爱奇艺"><img src="img/qiyi.png" alt="爱奇艺"></a></li>
                        <li><a href="" title="优酷"><img src="img/youku.png" alt="优酷"></a></li>
                    </ul>				
                </div>
                <div class="wel">欢迎更多优质视频站点合作</div>
            </div>
        </div>	
    </div>
</div>
<footer class="ft f_c">
	<div class="f_l">
		<nav>
			<a href="">关于我们</a>/<a href="">免责声明</a>/<a href="">合作伙伴</a>/<a href="">诚聘英才</a>/<a href="">联系我们</a>/<a href="">使用协议</a>/<a href="">帮助中心</a>/<a href="">客服热线:(86)-021-53080881</a>
		</nav>
		<p><span>©2013 欢网撒撒野工作室 版权所有</span> <span>粤ICP备：10042161号-19</span><span>京公网安备号：110105008803号</span> </p>
	</div>
	<div class="f_r">
		<span class="follow"><label>关注我们：</label><a href="" title="微博关注我们"><img src="img/weibo.png" alt=""></a><a href="" title="微信关注我们"><img src="img/weixin.png" alt=""></a></span><a href="" class="owebsite">欢网官网</a>|
	</div>
</footer>
</body>
</html>
