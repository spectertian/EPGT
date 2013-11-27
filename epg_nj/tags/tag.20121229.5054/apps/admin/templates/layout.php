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
						<!--  <li><a href="#">站点设置</a></li>-->
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item<?php if(currentNav == "分类管理"):?>current<?php endif;?>">分类管理</a>
					<ul>
						<li><a <?php if( in_array("tv_station",$action) ):?> class="current" <?php endif;?> href="<?php echo url_for('@tv_station')?>">电视台管理</a></li>
						<li><a <?php if( in_array("channel",$action)&&!in_array("listimage",$action) ):?> class="current" <?php endif;?> href="<?php echo url_for('@channel')?>">电视频道管理</a></li>
						<li><a <?php if( in_array("spservice",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('spservice/index') ?>">NIT管理</a></li>
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item">内容管理</a>
					<ul>
						<li><a <?php if( in_array("program",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('program/default')?>">电视节目管理</a></li>
						<li><a <?php if( in_array("cpg",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('cpg/index')?>">回看节目管理</a></li>
                        <li><a <?php if( in_array("wiki",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('wiki/index')?>">维基(媒资)管理</a></li>
						<li><a <?php if( in_array("video",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('video/index')?>">视频管理</a></li>
						<li><a <?php if( in_array("wikis",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('imgcheck/wikis')?>">wiki图片检查</a></li>
						<li><a <?php if( in_array("programs",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('imgcheck/programs')?>">节目图片检查</a></li>
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item">推荐管理</a>
					<ul>
						<li><a <?php if( in_array("wiki_recommend",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('@wiki_recommend')?>">推荐维基管理</a></li>
						<li><a <?php if( in_array("recommend", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('recommend/index')?>">场景推荐管理</a></li>
						<li><a <?php if( in_array("theme", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('theme/index')?>">专题推荐管理</a></li>
						<li><a <?php if( in_array("channel_recommend", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('channel_recommend/index')?>">频道推荐管理</a></li>
					</ul>
				</li>				
				<li> 
					<a href="#" class="nav-top-item">内容接入管理</a>
					<ul>
						<li><a <?php if( in_array("inject",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('content/inject')?>">CMS内容导入</a></li>
						<li><a <?php if( in_array("import",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('content/import')?>">CMS内容处理</a></li>
						<li><a <?php if( in_array("temp",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('content/temp')?>">CMS临时内容处理</a></li>
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