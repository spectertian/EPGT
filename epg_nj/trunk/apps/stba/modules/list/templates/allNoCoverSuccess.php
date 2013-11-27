<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>无图片节目列表</title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <style type="text/css">
    h3{text-align: center;}
    li{list-style: none outside none;}
  	.content ul{width: 1104px;}
    .content li{ float: left;padding: 10px;position: relative;height: 170px;width: 100px;}
	.content li span{position: absolute;left: 10px;top: 155px;}
  </style>
 </head>

 <body>
 	<h3><?php echo $date;?>无图片节目列表</h3>
	
	<div id='content' class="content">
	<ul>
	<?php foreach ($wikisArr as $value) :?>
		<li>
			<img alt="" src="<?php echo thumb_url($value['cover'], 105, 140,'122.193.13.36')?>">
			<span><?php echo $value['name']?></span>
		</li>
	<?php endforeach;?>
	</ul>
	</div>
 </body>
</html>