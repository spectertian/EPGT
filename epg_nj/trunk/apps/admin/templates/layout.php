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
        <p>您好，<a href="#"><?php echo $sf_user->getAttribute('username'); ?></a> | <a href="<?php echo url_for('/admin/dashboard') ?>">返回首页</a> | <a href="<?php echo url_for('admin/logout') ?>">登出</a></p>
      </div>
      <div class="clear"></div>
    </header>
    <div id="sidebar">
        <div id="sidebar-wrapper">
			<ul id="main-nav"> 	
                <?php if ($sf_user->hasCredential(array('admin','attachments_pre','media','setting_recommend','setting_page','setting_autohidden','crontabLog','queueLog'),false)): ?>			
				<li> 
					<a href="#" class="nav-top-item ">系统管理</a>
					<ul>
						
                        <?php if ($sf_user->hasCredential('admin')): ?><li><a <?php if( in_array("admin",$action) &&  !in_array("dashboard",$action)):?>class="current" <?php endif;?> href="<?php echo url_for('admin/index') ?>">权限管理</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('attachments_pre')): ?><li><a <?php if( in_array("attachments_pre",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('attachments_pre/index') ?>">图片审核</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('media')): ?><li><a <?php if( in_array("media",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('media/index') ?>">文件管理</a></li><?php endif;?>
						<!--<li><a <?php if( in_array("setting",$action)&&in_array("edit",$action)&&$sf_request->getParameter('key')=='' ):?>class="current" <?php endif;?> href="<?php echo url_for('setting/edit') ?>">热门关键词</a></li>-->
                        <?php if ($sf_user->hasCredential('setting_recommend')): ?><li><a <?php if( in_array("setting",$action)&&in_array("recommend",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('setting/recommend') ?>">推荐系统切换</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('setting_page')): ?><li><a <?php if( in_array("setting",$action)&&in_array("page",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('setting/page') ?>">应急页面切换</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('setting_autohidden')): ?><li><a <?php if( in_array("setting",$action)&&in_array("autohidden",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('setting/autohidden') ?>">自动隐藏时间设置</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('crontabLog')): ?><li><a <?php if( in_array("crontabLog",$action)):?>class="current" <?php endif;?> href="<?php echo url_for('crontabLog/index') ?>">计划任务日志</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('queueLog')): ?><li><a <?php if( in_array("queueLog",$action)):?>class="current" <?php endif;?> href="<?php echo url_for('queueLog/index') ?>">消息队列失败日志</a></li><?php endif;?>
					</ul>
				</li>
                <?php endif;?>
                <?php if ($sf_user->hasCredential(array('words','setting_wordsCheck','wordsLog','wordsWiki'),false)): ?>	
				<li> 
					<a href="#" class="nav-top-item ">敏感词管理</a>
					<ul>
						<?php if ($sf_user->hasCredential('words')): ?><li><a <?php if( in_array("words",$action)):?>class="current" <?php endif;?> href="<?php echo url_for('words/index') ?>">敏感词</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('setting_wordsCheck')): ?><li><a <?php if( in_array("setting",$action)&&in_array("wordsCheck",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('setting/wordsCheck') ?>">敏感词审核状态</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('wordsLog')): ?><li><a <?php if( in_array("wordsLog",$action)):?>class="current" <?php endif;?> href="<?php echo url_for('wordsLog/index') ?>">敏感词日志</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('wordsWiki')): ?><li><a <?php if( in_array("wordsWiki",$action)):?>class="current" <?php endif;?> href="<?php echo url_for('wordsWiki/index') ?>">检查所有wiki</a></li><?php endif;?>
					</ul>
				</li>	
                <?php endif;?>	
                <?php if ($sf_user->hasCredential(array('tv_station','channel','spservice'),false)): ?>			
				<li> 
					<a href="#" class="nav-top-item">分类管理</a>
					<ul>
						<?php if ($sf_user->hasCredential('tv_station')): ?><li><a <?php if( in_array("tv_station",$action) ):?> class="current" <?php endif;?> href="<?php echo url_for('@tv_station')?>">电视台管理</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('channel')): ?><li><a <?php if( in_array("channel",$action)&&!in_array("listimage",$action)&&!in_array("count",$action) ):?> class="current" <?php endif;?> href="<?php echo url_for('@channel')?>">电视频道管理</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('spservice')): ?><li><a <?php if( in_array("spservice",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('spservice/index') ?>">NIT管理</a></li><?php endif;?>
					</ul>
				</li>
                <?php endif;?>	
                <?php if ($sf_user->hasCredential(array('count_videoCount','count_videoPlayCount','count_cdiCount','count_vodCount','count_injectCount','count_programCount','count_wikiCount','count_fileCount','count_liveLog','count_offlineLog'),false)): ?>	
				<li> 
					<a href="#" class="nav-top-item">数据统计及日志</a>
					<ul>
						<?php if ($sf_user->hasCredential('count_videoCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("videoCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/videoCount')?>">上线电视剧错误日志</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_videoPlayCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("videoPlayCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/videoPlayCount')?>">视频播放错误日志</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_cdiCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("cdiCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/cdiCount')?>">上下线影片数量统计</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_vodCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("vodCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/vodCount')?>">影片点播量统计</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_injectCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("injectCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/injectCount')?>">ADI文件入库统计</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_programCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("programCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/programCount')?>">节目统计</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_wikiCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("wikiCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/wikiCount')?>">维基统计</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_fileCount')): ?><li><a <?php if( in_array("count",$action)&&in_array("fileCount",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/fileCount')?>">图片统计</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_liveLog')): ?><li><a <?php if( in_array("count",$action)&&in_array("liveLog",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/liveLog')?>">直播频道点击统计</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_offlineLog')): ?><li><a <?php if( in_array("count",$action)&&in_array("offlineLog",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/offlineLog')?>">紧急下线日志</a></li><?php endif;?>
                        
					</ul>
				</li>	
                <?php endif;?>	
                <?php if ($sf_user->hasCredential(array('program','program_week','cpg','wiki','wiki_unaudited','video','count_epgLog'),false)): ?>				
				<li> 
					<a href="#" class="nav-top-item">内容管理</a>
					<ul>
						<?php if ($sf_user->hasCredential('program')): ?><li><a <?php if( in_array("program",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('program/index')?>">电视节目管理</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('program_week')): ?><li><a <?php if( in_array("programWeek",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('programWeek/index')?>">大网节目管理</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('cpg')): ?><li><a <?php if( in_array("cpg",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('cpg/index')?>">回看节目管理</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('wiki')): ?><li><a <?php if( in_array("wiki",$action)&&!in_array("unaudited", $action) ):?>class="current" <?php endif;?> href="<?php echo url_for('wiki/index')?>">维基(媒资)管理</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('wiki_unaudited')): ?><li><a <?php if( in_array("unaudited",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('wiki/unaudited')?>">未审核维基</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('video')): ?><li><a <?php if( in_array("video",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('video/index')?>">视频管理</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('count_epgLog')): ?><li><a <?php if( in_array("count",$action)&&in_array("epgLog",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('count/epgLog')?>">EPG发送频道查询</a></li><?php endif;?>
                        <!--
						<li><a <?php if( in_array("wikis",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('imgcheck/wikis')?>">wiki图片检查</a></li>
						<li><a <?php if( in_array("programs",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('imgcheck/programs')?>">节目图片检查</a></li>
                        -->
					</ul>
				</li>	
                <?php endif;?>	
                <?php if ($sf_user->hasCredential(array('wiki_recommend','channel_recommend','recommand_fix'),false)): ?>				
				<li> 
					<a href="#" class="nav-top-item">推荐管理</a>
					<ul>
                    
						<?php if ($sf_user->hasCredential('wiki_recommend')): ?><li><a <?php if( in_array("wiki_recommend",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('@wiki_recommend')?>">推荐维基管理</a></li><?php endif;?>
						<!--<li><a <?php if( !in_array("setting",$action)&&in_array("recommend", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('recommend/index')?>">场景推荐管理</a></li>
						<li><a <?php if( in_array("theme", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('theme/index')?>">专题推荐管理</a></li>
                        -->
						<?php if ($sf_user->hasCredential('channel_recommend')): ?><li><a <?php if( in_array("channel_recommend", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('channel_recommend/index')?>">频道推荐管理</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('recommand_fix')): ?><li><a <?php if( in_array("recommand_fix", $action)) :?>class="current" <?php endif;?>  href="<?php echo url_for('recommand_fix/index')?>">固定点播推荐</a></li><?php endif;?>
					</ul>
				</li>	
                <?php endif;?>		
                <?php if ($sf_user->hasCredential(array('content_inject','content_import','content_cdi'),false)): ?>		
				<li> 
					<a href="#" class="nav-top-item">内容接入管理</a>
					<ul>
						<?php if ($sf_user->hasCredential('content_inject')): ?><li><a <?php if( in_array("inject",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('content/inject')?>">CMS内容导入</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('content_import')): ?><li><a <?php if( in_array("import",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('content/import')?>">CMS内容处理</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('content_cdi')): ?><li><a <?php if( in_array("cdi",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('content/cdi')?>">上下线内容查看</a></li><?php endif;?>
                        <!--<li><a <?php if( in_array("temp",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('content/temp')?>">CMS临时内容处理</a></li>-->
					</ul>
				</li>	
                <?php endif;?>	
                <?php if ($sf_user->hasCredential(array('check_interface','check_log','check_epgbak','check_epg','check_epgWeek','check_epgBochu'),false)): ?>	
				<li> 
					<a href="#" class="nav-top-item">系统监测管理</a>
					<ul>
						<?php if ($sf_user->hasCredential('check_interface')): ?><li><a <?php if( in_array("check",$action)&&in_array("interface",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('check/interface')?>">接口监测</a></li><?php endif;?>
						<?php if ($sf_user->hasCredential('check_log')): ?><li><a <?php if( in_array("check",$action)&&in_array("log",$action) ):?>class="current" <?php endif;?> href="<?php echo url_for('check/log')?>">接口监测日志</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('check_epgbak')): ?><li><a <?php if( in_array("check",$action)&&in_array("epgbak",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('check/epgbak')?>">回看节目单监测</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('check_epg')): ?><li><a <?php if( in_array("check",$action)&&in_array("epg",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('check/epg')?>">欢网节目单监测</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('check_epgWeek')): ?><li><a <?php if( in_array("check",$action)&&in_array("epgWeek",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('check/epgWeek')?>">大网节目单监测</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('check_epgBochu')): ?><li><a <?php if( in_array("check",$action)&&in_array("epgBochu",$action) ):?>class="current" <?php endif;?>  href="<?php echo url_for('check/epgBochu')?>">以播出为准节目单监测</a></li><?php endif;?>
					</ul>
				</li>
                <?php endif;?>	
                <?php if ($sf_user->hasCredential(array('spservice_getSpLogo','post','memadmin','memcache'),false)): ?>	
				<li> 
					<a href="#" class="nav-top-item">其他管理</a>
					<ul>
						<?php if ($sf_user->hasCredential('spservice_getSpLogo')): ?><li><a  href="<?php echo "http://".$_SERVER['HTTP_HOST']."/spservice/getSpLogo"?>" target="_blank">查看所有台标</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('post')): ?><li><a  href="<?php echo "http://".$_SERVER['HTTP_HOST'].":8082/post"?>" target="_blank">测试推荐接口</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('memadmin')): ?><li><a  href="<?php echo "http://".$_SERVER['HTTP_HOST']."/memadmin/"?>" target="_blank">管理memcache</a></li><?php endif;?>
                        <?php if ($sf_user->hasCredential('memcache')): ?><li><a  href="<?php echo "http://".$_SERVER['HTTP_HOST']."/memcache.php"?>" target="_blank">查看memcache</a></li><?php endif;?>
					</ul>
				</li>
                <?php endif;?>					
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