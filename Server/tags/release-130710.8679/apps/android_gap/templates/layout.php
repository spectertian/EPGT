<!DOCTYPE HTML>
<html>
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
<?php use_helper('Common') ?>
<?php
$allProvince=getprovince();
$province=$sf_user->getAttribute('province')?$sf_user->getAttribute('province'):'北京';
?>
</head>

<body>
	<div class="pagewarp" data-role="page">
    	<nav class="nav clr" data-role="header">
    		<h1><a href="#"><?php echo $province;?></a></h1>
            <form name="" method="get" class="search">
            	<ul>
                	<li>
                    	<label id="src"><input type="text"/></label>
                    </li>
                    <li><a href="#" title="个人中心" class="usercentent"></a></li>
                    <li><a href="#" title="播放记录" class="playslists"></a></li>
                    <li><a href="#" title="遥控器" class="ctrls"></a></li>
                </ul>
            </form>
            <ul class="choicecity">
                <?php foreach($allProvince as $key=>$value): ?>
            	<li><a href="<?php echo url_for("live/index?location=".$value) ?>"><?php echo $key ?></a></li>
                <?php endforeach;?>
            </ul>            
   		</nav>
        <?php echo $sf_content ?>
           
    </div>
</body>
</html>
