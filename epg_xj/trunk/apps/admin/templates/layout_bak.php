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
      <h1><a href="#">Mozitek Internal System</a></h1>
      <div class="user_info">
        <p>您好，<a href="#"><?php echo $sf_user->getAttribute('username'); ?></a> | <a href="<?php echo url_for('admin/logout') ?>">登出</a></p>
      </div>
      <div class="clear"></div>
    </header>
    <div id="inner">
      <aside id="aside">
        <nav>
          <li>
            <div class="menu menu_system"><a href="#"><strong>系统管理</strong></a></div>
            <menu>
              <li <?php if( in_array("admin",$action) &&  !in_array("dashboard",$action)):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('admin/index') ?>">权限管理</a></li>
              <li <?php if( in_array("media",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('media/index') ?>">文件管理</a></li>
              <li <?php if( in_array("setting",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('setting/edit') ?>">关键字设定</a></li>
              <li <?php if( in_array("memcache",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('memcache/index') ?>">缓存管理</a></li>
<!--              <li><a href="index.php?option=com_config">站点设置</a></li>-->
            </menu>
          </li>
          <li>
            <div class="menu menu_category"><a href="#"><strong>分类管理</strong></a></div>
            <menu>
              <li <?php if( in_array("tv_station",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('@tv_station')?>">电视台</a></li>
              <li <?php if( in_array("channel",$action)&&!in_array("listimage",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('@channel')?>">电视频道</a></li>
              <li <?php if( in_array("reportchannel",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('reportchannel/index')?>">频道别名</a></li>
              <!--<li <?php if( in_array("listimage",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('channel/listimage')?>">电视频道台标查看</a></li>-->
              <!--<li <?php //if( in_array("program_index",$action) ):?>class="actived" <?php //endif;?> ><a href="<?php //echo url_for('@program_index')?>">节目模板</a></li>-->
            </menu>
          </li>
          <li class="actived">
            <div class="menu menu_content"><a href="#"><strong>内容管理</strong></a></div>
            <menu>
              <li <?php if( in_array("program",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('program/default')?>">电视节目</a></li>
              <li <?php if( in_array("program_sport",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('program_sport/default')?>">体育节目</a></li>
              <li <?php if( in_array("program_live",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('program_live/index')?>">直播监控</a></li>
<!--              <li <?php if( in_array("program_template",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('@program_template')?>">节目模板</a></li>-->
              <li <?php if( in_array("wiki",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('wiki/index')?>">维基</a></li>
              <li <?php if( in_array("wiki_recommend",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('@wiki_recommend')?>">推荐维基</a></li>
              <li <?php if( in_array("video",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('video/index')?>">视频</a></li>
              <li <?php if( in_array("recommend", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('recommend/index')?>">推荐列表</a></li>
      			  <li <?php if( in_array("theme", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('theme/index')?>">专题管理</a></li>
      			  <li <?php if( in_array("channel_recommend", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('channel_recommend/index')?>">频道推荐</a></li>
      			  <li <?php if( in_array("sp", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('sp/index')?>">运营商管理</a></li>
              <li <?php if( in_array("terminal", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('terminal/index')?>">终端类型管理</a></li>
              <li <?php if( in_array("stat", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('stat/index')?>">访问统计</a></li>
              <li <?php if( in_array("wiki_package", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('wiki_package/index')?>">维基包</a></li>
            </menu>
          </li>
          <li class="actived">
            <div class="menu menu_content"><a href="#"><strong>内容导入管理</strong></a></div>
            <menu>
              <li <?php if( in_array("video_crawler",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('video_crawler/index')?>">视频抓取</a></li>
            </menu>
          </li>
        </nav>
      </aside>
<?php echo $sf_content ?>

      <div class="clear"></div>
      <div class="top"><a href="#">回顶部</a></div>
    </div>
  </div>
  <footer id="footer">
    <p class="version"><em><strong>Version 2.0.1</strong></em></p>
    <div class="clear"></div>
  </footer>
</body>
</html>