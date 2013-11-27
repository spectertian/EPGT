<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#loading-message").ajaxStart(function(){
                    $(this).removeClass("display-none");
                }).ajaxStop(function(){
                    $(this).addClass("display-none");
                });
                
                $("#nav-panel").load("<?php echo url_for('tcl/menu'); ?>", function() {
                    $("#menu-bar").data('ui').focus();
                });
                $("#search-panel").load("<?php echo url_for('tcl/search'); ?>");
                $("#content-panel").load("<?php echo url_for('tcl/homepage'); ?>");
            });
        </script>
    </head>
    <body>
        <div class="page" id="main-nav-sizer">
            <div class="header">
                <div class="navi" id="nav-panel">
                    <!-- 导航栏 -->
                </div>
                <div class="search action" id="search-panel">
                    <!-- 搜索框 -->
                </div>
            </div>
            <div id="content-panel"><!-- 内容区 --></div>
        </div>
        <div class="page display-none" id="channel-programs-sizer"></div>
        <div class="page display-none" id="wiki-sizer"></div>
        <div class="page display-none" id="search-sizer"></div>

        <div id="loading-message" class="display-none">
            <div class="popup-loading"></div>
            <div class="popup-loading-bg"></div>
        </div>
        <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-15948108-7']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    </body>
</html>
