<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<link rel="shortcut icon" href="/favicon.ico" />
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate"/>
<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT"/>
<meta http-equiv="expires" content="0"/>
</head>
<body onload="initPage();" onunload="exitPage();" onkeydown=" return eventHandler(event);">
<?php echo $sf_content ?>
<div class="tipc">
    <h2><a href="#">按<确认>关闭</a><span>系统提示</span></h2>
    <p id="tipInfo"></p>
</div>
</body>
</html>