<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" id="minwidth" >
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
</head>
<?php $action = explode("/", $sf_request->getPathInfo());?>
<body>
  <div id="wrap">
    <header id="header">
      <h1><a href="#">Huan Epg Admin System</a></h1>
      <div class="user_info">
        <p>您好，<a href="#"><?php echo $sf_user->getAttribute('username'); ?></a> | <a href="<?php echo url_for('admin/logout') ?>">登出</a></p>
      </div>
      <div class="clear"></div>
    </header>
    <div id="sidebar">
        <div id="sidebar-wrapper">
			<ul id="main-nav"> 				
				<li> 
					<a href="#" class="nav-top-item ">系统管理</a>
					<ul>
						<li><a <?php if( in_array("admin",$action) &&  !in_array("dashboard",$action)):?>class="current" <?php endif;?> href="<?php echo url_for('admin/index') ?>">权限管理</a></li>
						<li><a <?php if( in_array("media",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('media/index') ?>">文件管理</a></li>
						<li><a <?php if( in_array("setting",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('setting/edit') ?>">热门关键词</a></li>
						<li><a <?php if( in_array("memcache",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('memcache/index') ?>">缓存管理</a></li>
						<li><a <?php if( in_array("developer",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('developer/index') ?>">开发者管理</a></li>
						<li><a <?php if( in_array("user_behavior",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('user_behavior/index') ?>">用户行为管理</a></li>
						<!--  <li><a href="#">站点设置</a></li>-->
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item">分类管理</a>
					<ul>
						<li><a <?php if( in_array("tv_station",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('@tv_station')?>">电视台</a></li>
              			<li><a <?php if( in_array("channel",$action)&&!in_array("listimage",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('@channel')?>">电视频道</a></li>
              			<li><a <?php if( in_array("reportchannel",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('reportchannel/index')?>">频道别名</a></li>
					
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item">内容管理</a>
					<ul>
						<li><a <?php if( in_array("program",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('program/index')?>">电视节目</a></li>
			            <li><a <?php if( in_array("program_sport",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('program_sport/index')?>">体育节目</a></li>
			            <li><a <?php if( in_array("program_live",$action) && !in_array("play",$action)):?>class="current" <?php endif;?>  href="<?php echo url_for('program_live/index')?>">直播监控</a></li>
			            <li><a <?php if( in_array("play",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('program_live/play')?>">播放监控</a></li>
						<li><a <?php if( in_array("wiki",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('wiki/index')?>">维基管理</a></li>
			            <li><a <?php if( in_array("video",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('video/index')?>">维基视频管理</a></li>
			            <li><a <?php if( in_array("short_movie",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('short_movie/index')?>">短视频管理</a></li>
		      			<li><a <?php if( in_array("sp", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('sp/index')?>">运营商管理</a></li>
			            <!--<li><a <?php if( in_array("terminal", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('terminal/index')?>">终端类型管理</a></li>-->
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item">推荐管理</a>
					<ul>
						  <li><a <?php if( in_array("wiki_package", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('wiki_package/index')?>">维基包</a></li>
			              <li><a <?php if( in_array("wiki_recommend",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('@wiki_recommend')?>">维基推荐</a></li>
			              <li><a <?php if( in_array("theme", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('theme/index')?>">专题推荐</a></li>
			              <li><a <?php if( in_array("recommend", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('recommend/index')?>">区域推荐</a></li>
		      			  <li><a <?php if( in_array("channel_recommend", $action)) :?>class="current" <?php endif;?> href="<?php echo url_for('channel_recommend/index')?>">频道推荐</a></li>
						  <!--<li><a <?php if( in_array("category_recommend", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('category_recommend/index')?>">分类推荐</a></li>-->
						  <li><a <?php if( in_array("category_recommends", $action)) :?>class="current" <?php endif;?> href="<?php echo url_for('category_recommends/list')?>">分类推荐</a></li>
						  <li><a <?php if( in_array("shortmovie_package", $action)) :?>class="current" <?php endif;?> href="<?php echo url_for('shortmovie_package/index')?>">短视频包</a></li>
						  <li><a <?php if( in_array("yesterday_program", $action)) :?>class="current" <?php endif;?> href="<?php echo url_for('yesterday_program/index')?>">昨日回顾</a></li>
						  <li><a <?php if( in_array("nextweek_program", $action)) :?>class="current" <?php endif;?> href="<?php echo url_for('nextweek_program/index')?>">下周预告</a></li>
						  <li><a <?php if( in_array("program_rec", $action)) :?>class="current" <?php endif;?> href="<?php echo url_for('program_rec/index')?>">节目直播</a></li>
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item">其他管理</a>
					<ul>
						<li><a <?php if( in_array("video_crawler",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('video_crawler/index')?>">百度视频抓取</a></li>
						<li><a <?php if( in_array("stat", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('stat/index')?>">访问统计</a></li>
						<li><a <?php if( in_array("simple_ad", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('simple_ad/index')?>">广告管理</a></li>
                        <li><a href="/memcache.php" target="_blank">Memcache监控</a></li>
                        <li><a <?php if( in_array("queue", $action)) :?>class="current" <?php endif;?> href="<?php echo url_for('queue/index')?>">消息队列监控</a></li>
					</ul>
				</li>
				<li> 
					<a href="#" class="nav-top-item">内容接入管理</a>
					<ul>
						<li><a <?php if( in_array("inject",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('content/inject')?>">CMS内容导入</a></li>
						<li><a <?php if( in_array("import",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('content/import')?>">CMS内容处理</a></li>
					</ul>
				</li>	
			</ul> 			
        </div>
    </div>
    <div id="inner">
        <?php echo $sf_content ?>
        <div class="clear"></div>
    </div>
  </div>
  <footer id="footer">
    <p class="version"><em><strong>Version 2.0.1 <?php echo $sf_request->getPathInfo();?></strong></em>  <a href="#" class="top">回顶部</a></p>
    <div class="clear"></div>
  </footer>
</body>
</html>