<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>本天节目列表</title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <style type="text/css">
    h3{text-align: center;}
    li{list-style: none outside none;}
  	.content ul{width: 1104px;}
    .content li{ float: left;padding: 10px;position: relative;height: 190px;width: 100px;}
	/*.content li span{position: absolute;left: 10px;top: 155px;}*/
  </style>
 </head>

 <body>
 	<h3><?php echo $date;?>所有节目列表</h3>
	<div style="padding-left: 50px; padding-top: 20px;">
		<span>
	<?php
	
	$totalPage = ceil($totalNum/200);
	
	for ($i = 1; $i <= $totalPage; $i++):
	?>
	<?php if ($page == $i):?>
		<span><?php echo $i;?></span>
	<?php else :?>
		<a href="/list/all/page/<?php echo $i;?>/date/<?php echo $date;?>"><?php echo $i;?></a>
	<?php endif;?>
	<?php endfor;?>
		</span>
	</div>
	
	<div id='content' class="content">
	<ul>
	<?php foreach ($wikisArr as $data) :?>
		<li>
			<a href="http://image.epg.huan.tv/1/1/1/<?php echo $data['cover'];?>" target="_blank"><img alt="" src="<?php echo thumb_url($data['cover'], 105, 140)?>" border="0"></a>
			<span><?php echo $data['name']?></span><br />
            <a href="/list/getImage?key=<?php echo $data['cover'];?>" target="_blank">重新获取图片</a>
		</li>
	<?php endforeach;?>
	</ul>
	</div>
 </body>
</html>