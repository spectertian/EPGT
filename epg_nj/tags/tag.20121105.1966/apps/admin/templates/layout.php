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
              <li <?php if( in_array("setting",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('setting/edit') ?>">热门搜索关键词</a></li>
<!--              <li><a href="index.php?option=com_config">站点设置</a></li>-->
            </menu>
          </li>
          <li>
            <div class="menu menu_category"><a href="#"><strong>分类管理</strong></a></div>
            <menu>
              <li <?php if( in_array("tv_station",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('@tv_station')?>">电视台</a></li>
              <li <?php if( in_array("channel",$action)&&!in_array("listimage",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('@channel')?>">电视频道</a></li>
            </menu>
          </li>
          <li class="actived">
            <div class="menu menu_content"><a href="#"><strong>内容管理</strong></a></div>
            <menu>
              <li <?php if( in_array("program",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('program/default')?>">电视节目</a></li>
              <li <?php if( in_array("wiki",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('wiki/index')?>">维基</a></li>
              <li <?php if( in_array("wiki_recommend",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('@wiki_recommend')?>">推荐维基</a></li>
              <li <?php if( in_array("video",$action) ):?>class="actived" <?php endif;?> ><a href="<?php echo url_for('video/index')?>">视频</a></li>
              <li <?php if( in_array("recommend", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('recommend/index')?>">推荐列表</a></li>
			  <li <?php if( in_array("theme", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('theme/index')?>">专题管理</a></li>
			  <li <?php if( in_array("channel_recommend", $action)) :?>class="actived" <?php endif;?> ><a href="<?php echo url_for('channel_recommend/index')?>">频道推荐</a></li>
            </menu>
          </li>
        </nav>
      </aside>
<?php echo $sf_content ?>

<SCRIPT type="text/javascript">
jQuery(document).ready(function() {
    $("menu").hide();
    $("nav").find("li").find('.actived').parent().show();
});

$("nav").find(".menu").click(function(){
	$("menu").hide("fast");
	//alert($(this).parent());
	$(this).parent("li").find("menu").slideDown();
});
</SCRIPT>
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