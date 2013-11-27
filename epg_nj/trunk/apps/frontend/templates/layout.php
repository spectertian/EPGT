<!doctype html>
<html>
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
<link rel="shortcut icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script src="<?php echo javascript_path("html5.js"); ?>" type="text/javascript"></script>
<script src="<?php echo javascript_path("selectivizr.js"); ?>" type="text/javascript"></script>
<![endif]-->
</head>
<?php if (has_slot('v_Movie')) :?>
<?php include_slot('v_Movie')?>
<?php else :?>
<body>
<?php endif;?>
<header id="header">
  <div class="header-inner">
    <div id="logo"><a href="<?php echo url_for("default");?>" title="我爱电视">我爱电视</a></div>
    <form method="get" action="<?php echo url_for("search/index");?>" id="search-form">
      <span class="glass" id="search-button"><i></i></span>
      <?php $q = $sf_request->hasParameter('q') ? $sf_request->getParameter('q', '搜索') : '搜索'?>
      <input type="text" id="search-query" name="q" onFocus="if(this.value == '<?php echo $q?>') {this.value = '';}" onBlur="if(this.value == '') {this.value = '<?php echo $q?>';}" value="<?php echo $q?>" placeholder="<?php echo $q?>">
    </form>
    <nav id="global-nav" role="navigation">
      <ul>
        <li class="menu">
          <div class="menu-hd"><a href="#">浏览发现<b>&#9660;</b></a></div>
          <div class="menu-bd">
            <ul>
              <li><a href="#">热播电视剧</a></li>
              <li><a href="#">综艺节目更新</a></li>
              <li><a href="#">全球新片</a></li>
              <li><a href="#">即将上映</a></li>
              <li><a href="#">票房榜</a></li>
              <li><a href="#">典藏佳片</a></li>
              <li><a href="#">独立电影</a></li>
            </ul>
          </div>
        </li>
        <li class="menu">
          <div class="menu-hd"><a href="<?php echo url_for('list/index?type=电视剧')?>">节目检索<b>&#9660;</b></a></div>
          <div class="menu-bd">
            <ul>
              <li><a href="<?php echo url_for('list/index?type=电视剧')?>">电视剧</a></li>
              <li><a href="<?php echo url_for('list/index?type=电影')?>">电影</a></li>
              <li><a href="<?php echo url_for('list/index?type=体育')?>">体育</a></li>
              <li><a href="<?php echo url_for('list/index?type=娱乐')?>">娱乐</a></li>
              <li><a href="<?php echo url_for('list/index?type=少儿')?>">少儿</a></li>
              <li><a href="<?php echo url_for('list/index?type=科教')?>">科教</a></li>
              <li><a href="<?php echo url_for('list/index?type=财经')?>">财经</a></li>
              <li><a href="<?php echo url_for('list/index?type=综合')?>">综合</a></li>
            </ul>
          </div>
        </li>
        <li class="menu last-child">
          <div class="menu-hd"><a href="#">电视节目指南<b>&#9660;</b></a></div>
          <div class="menu-bd">
            <ul>
              <li>
                <h4><a href="<?php echo lurl_for("channel/index?type=all&mode=tile")?>">正在播放</a></h4>
                <ul>
                  <li><a href="<?php echo lurl_for("channel/index?type=local&mode=tile")?>">本地</a></li>
                  <li><a href="<?php echo lurl_for("channel/index?type=cctv&mode=tile")?>">央视</a></li>
                  <li><a href="<?php echo lurl_for("channel/index?type=tv&mode=tile")?>">卫视</a></li>
                </ul>
              </li>
              <li>
                <h4><a href="<?php echo url_for("channel/tag?tag=电视剧&mode=tile");?>">分类预告</a></h4>
                <ul>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=电视剧&mode=tile")?>">电视剧</a></li>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=电影&mode=tile")?>">电影</a></li>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=体育&mode=tile")?>">体育</a></li>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=娱乐&mode=tile")?>">娱乐</a></li>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=少儿&mode=tile")?>">少儿</a></li>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=科教&mode=tile")?>">科教</a></li>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=财经&mode=tile")?>">财经</a></li>
                  <li><a href="<?php echo lurl_for("channel/tag?tag=综合&mode=tile")?>">综合</a></li>
                </ul>
              </li>
              <li>
								<h4><a href="<?php echo lurl_for('@location_channel_index')?>">频道索引</a></h4>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>
    <div id="session">
      <?php if ($sf_user->isAuthenticated()): ?>
      <div class="menu loggedin">
          <div class="menu-hd">
              <a href="<?php echo url_for('user/user_feed')?>" class="profile-links" title="<?php echo $sf_user->getAttribute("nickname"); ?>">
                  <img src="<?php echo thumb_url($sf_user->getAttribute("avatar"), 24, 24)?>" width="24" height="24">
                  <span class="screen-name"><?php echo mb_strimwidth($sf_user->getAttribute("nickname"), 0, 10, '...', 'UTF-8'); ?></span><b>&#9660;</b>
              </a>
          </div>
          <div class="menu-bd">
          <ul>
            <li><a href="<?php echo url_for('user/user_feed')?>">我的主页</a></li>
            <li><a href="<?php echo url_for('user/cliplist?type=default')?>">我的片单</a></li>
            <li><a href="<?php echo url_for('user/updateInfo')?>">设置</a></li>
            <li><a href="<?php echo url_for('user/logout')?>">退出</a></li>
          </ul>
          </div>
      </div>
      <?php else: ?>
      <div class="logged">
        <ul>
          <li><a href="<?php echo url_for('user/reg') ?>">免费注册</a></li>
          <li><a href="<?php echo url_for('user/login?url='."http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']) ?>">登录</a></li>
        </ul>
      </div>
      <?php endif; ?>
    </div>
  </div>
</header>
<?php echo $sf_content ?>
<footer id="footer">
  <div class="footer-inner">
    <div class="sitemap clearfix" role="navigation sitemap">
	  <!--
      <dl>
        <dt>浏览发现</dt>
        <dd><a href="#">热播电视剧</a></dd>
        <dd><a href="#">综艺节目更新</a></dd>
        <dd><a href="#">全球新片</a></dd>
        <dd><a href="#">即将上映</a></dd>
        <dd><a href="#">票房榜</a></dd>
        <dd><a href="#">典藏佳片</a></dd>
        <dd><a href="#">独立电影</a></dd>
      </dl>
	  -->
      <dl>
        <dt>电视节目指南</dt>
        <dd><a href="<?php echo url_for('channel/index')?>">正在播放</a></dd>
        <dd><a href="<?php echo url_for('channel/tag?tag=电视剧&mode=tile')?>">分类预告</a></dd>
        <dd><a href="<?php echo lurl_for('channel/channel_index')?>">频道索引</a></dd>
      </dl>
      <dl class="genres" style="width:130px;">
        <dt>节目分类</dt>
        <dd><a href="<?php echo url_for('list/index?type=电视剧')?>">电视剧</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影')?>">电影</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=体育')?>">体育</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=娱乐')?>">娱乐</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=少儿')?>">少儿</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=科教')?>">科教</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=财经')?>">财经</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=综合')?>">综合</a></dd>
      </dl>
      <dl class="genres" style="width:180px;">
        <dt>电视剧</dt>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=爱情&area=全部&time=全部') ?>">爱情</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=喜剧&area=全部&time=全部') ?>">喜剧</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=动画&area=全部&time=全部') ?>">动画</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=经典&area=全部&time=全部') ?>">经典</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=科幻&area=全部&time=全部') ?>">科幻</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=动作&area=全部&time=全部') ?>">动作</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=青春&area=全部&time=全部') ?>">青春</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=剧情&area=全部&time=全部') ?>">剧情</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=悬疑&area=全部&time=全部') ?>">悬疑</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=惊悚&area=全部&time=全部') ?>">惊悚</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=记录片&area=全部&time=全部') ?>">记录片</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=励志&area=全部&time=全部') ?>">励志</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=恐怖&area=全部&time=全部') ?>">恐怖</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=战争&area=全部&time=全部') ?>">战争</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电视剧&tag=文艺&area=全部&time=全部') ?>">文艺</a></dd>
      </dl>
      <dl class="genres" style="width:180px;">
        <dt>电影</dt>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=喜剧&area=全部&time=全部')?>">喜剧</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=古装&area=全部&time=全部')?>">古装</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=搞笑&area=全部&time=全部')?>">搞笑</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=港台&area=全部&time=全部')?>">港台</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=日韩&area=全部&time=全部')?>">日韩</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=爱情&area=全部&time=全部')?>">爱情</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=警匪&area=全部&time=全部')?>">警匪</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=动作&area=全部&time=全部')?>">动作</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=功夫&area=全部&time=全部')?>">功夫</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=犯罪&area=全部&time=全部')?>">犯罪</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=剧情&area=全部&time=全部')?>">剧情</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=军事&area=全部&time=全部')?>">军事</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=武侠&area=全部&time=全部')?>">武侠</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=战争&area=全部&time=全部')?>">战争</a></dd>
        <dd><a href="<?php echo url_for('list/index?type=电影&tag=传奇&area=全部&time=全部')?>">传奇</a></dd>
      </dl>
      <dl style="width:140px;">
        <dt>个人中心</dt>
        <dd><a href="<?php echo url_for("user/user_feed");?>">我的主页</a></dd>
        <dd><a href="<?php echo url_for("user/cliplist");?>">我的片单</a></dd>
        <dd><a href="<?php echo url_for("user/user_feed");?>">我的动态</a></dd>
        <dd><a href="<?php echo url_for("user/user_channel");?>">我的频道</a></dd>
        <dd><a href="<?php echo url_for("user/updateInfo");?>">设置</a></dd>
      </dl>
    </div>
    <div class="links">
			<a href="#">关于我们</a> / 
			<a href="#">联系我们</a> / 
			<a href="#">合作伙伴</a> / 
			<a href="#">诚聘英才</a> / 
			<a href="#">帮助中心</a> / 
			<a href="#">免责声明</a> / 
			<a href="#">隐私声明</a></div>
    <div class="copyright">
      <p>&copy; 2011 我爱EPG huan.tv 版权所有 粤ICP备：B2-20100181号 京公网安备号：110105008803号</p>
    </div>
  </div>
</footer>
<div class="tooltip" id="tooltip">
  <div class="tooltip-mod">
    <div class="arrow-img"></div>
    <div id="wiki-info">
        <div class="tooltip-hd">
          <h3></h3>
        </div>
        <div class="loading">
          <div class="loading-tip">载入中 ...</div>
        </div>
    </div>
  </div>
</div>
<?php if (!$sf_user->isAuthenticated()): ?>
<div id="login-dialog" class="dialog common-form" style="display:none">
  <div class="dialog-hd">
    <h3>您现在进行的操作需要登录！</h3>
    <div class="close"><a href="javascript:void(0)">x</a></div>
  </div>
  <div class="dialog-bd">
    <div class="notice error" style="display:none"></div>
    <form method="post" style="margin:0;" onSubmit="return login(this)">
      <ul>
        <li class="text-field clearfix">
          <div class="input">
            <label for="username">用户名</label>
            <input type="text" tabindex="1" name="username" id="username" maxlength="30">
          </div>
        </li>
        <li class="text-field clearfix">
          <div class="input">
            <label for="password">密码</label>
            <input type="password" tabindex="2" name="password" id="password" maxlength="20">
          </div>
        </li>
        <li class="checkbox-field clearfix">
          <input type="checkbox" tabindex="3" name="remember" id="remember">
          <label for="remember" class="remember">下次自动登陆</label> <span style="line-height:28px;">|
          </span> <a style="margin-left:5px; line-height:28px;" href="<?php echo url_for('user/lostPassword')?>">忘记密码了？</a>
        </li>
        <li class="submit-field">
          <input type="submit" tabindex="4" name="" value="登录">
          <a href="<?php echo url_for('user/reg') ?>" tabindex="5" style="margin-left:25px; font-size:14px;">立即注册</a> </li>
      </ul>
    </form>
  </div>
</div>
<?php endif;?>
<?php if ($sf_user->hasFlash('success')): ?>
	<div class="global-notice done"><span class="text-info"><?php echo $sf_user->getFlash('success') ?></span></div>
<?php endif;?>
<?php if ($sf_user->hasFlash('error')): ?>
	<div class="global-notice error"><span class="text-info"><?php echo $sf_user->getFlash('error') ?></span></div>
<?php endif;?>
<script type="text/javascript">
//loadWiki
function loadWiki(slug, time ) {
    var url = "<?php echo url_for('@wiki_show?slug=')?>" + slug;
    $.get(url, { 'time': time },
       function(html){
          $('#wiki-info').html(html);
       }
    );
}

//登录
function login(form) {
	var notices = {
                'username':{
                    'required' : '请输入用户名或邮箱!'
                },
                'password':{
                    'required' : '请输入密码!',
                    'invalid': '请输入 5-20 个字符的密码!'
                },
                'error' : '你的用户名和密码不符，请再试一次！'
            };
    var uname = $.trim(form.username.value);
    var passwd = $.trim(form.password.value);
    var v = true;
    
    if (uname.length == 0) {
        $('.notice').text(notices.username.required).show();
        form.username.focus();
        return false;
    }
    if (passwd.length == 0) {
        $('.notice').text(notices.password.required).show();
        form.password.focus();
        return false;
    }
    if ((5 > passwd.length) || (passwd.length > 20)) {
        $('.notice').text(notices.password.invalid).show();
        form.password.focus();
        return false;
    }
    
    $.ajax({
        url : '<?php echo url_for('@login')?>',
        type : 'post',
        dataType: 'json',
        data: {'username': uname, 'password': passwd, 'remember' : form.remember.value},
        async : false,
        success: function(m){
            if (m == 1) {
                window.location.reload();
            } else {
                $('.notice').text(notices.error).show();
                form.password.value = '';
                form.password.focus();
                v = false;
            }
        }
    });
    
    setInterval(function(){$('.notice').fadeOut('slow')},3000);
    if (!v) {
        return false;
    }
}
</script>
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fb2e9fb8b11f961c0749d5a54cde7b470' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>

