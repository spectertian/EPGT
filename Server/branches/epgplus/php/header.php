<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<!--[if lte IE 6]>
<script type="text/javascript" src="js/pngmin.js"></script>
<script>
	DD_belatedPNG.fix(".hd img,.listctn,.listctn img,.follow img,.owebsite");
</script>
<![endif]-->
<!--[if lt IE 9]>
<script src="js/html5.js"></script>
<![endif]-->
<title><?php echo $menus[$pagekey]['title'];?></title>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.flippy.min.js"></script>
<link href="style/common.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
$(document).ready(function() {
    $("#body").html($("#div_index").html());
    $(".flippy").click(function(){
        key = $(this).attr("href");
        $("#body").flippy({
            direction: "left",
            duration: "750",
            verso:$("#div_"+key).html(),
            onStart:function(){
            },
            onFinish:function(){
            }       
         });
        return false;
    });
});
</script>
</head>
<body>
<div class="all">
	<header class="hd">
		<a href="./" title="sasaye.com" class="f_l"><img src="img/logo.png" alt=""></a>
		<nav>
<?php 
    foreach($menus as $key => $menu) {
        $class = ($pagekey == $key) ? "cur" : "";
        echo '			<a href="'.$key.'" class="flippy '.$class.'" id="m_'.$key.'"><span>[</span>'.$menu['title'].'<span>]</span></a>';
    }
?>
		</nav>
	</header>