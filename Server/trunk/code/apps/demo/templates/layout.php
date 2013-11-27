<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
<script type="text/javascript">
<!--
var isShow = false;			 // 是否弹出信息状态
// 弹出信息提示
function show(txt){
	isShow = true;
	$(".tips").show();
	$(".tips p").html(txt);
    showTimer = setTimeout("hide();",3000);
 
}
// 隐藏信息提示
function hide(){
 	$(".tips").hide();
	$(".tips p").html(" ");
	isShow = false;
}


$(document).ready(function() {
      $('.cls').click(function(){
         hide(); 
       });
});
</script> 

  </head>


  <body>
<?php $action = explode("/", $sf_request->getPathInfo());?>
	<div class="wapper">
    	<ul class="menu clr">
        	<li <?php if( in_array("index",$action) || $action[1]=="" ):?>class="this" <?php endif;?>><a href="<?php echo url_for('/default/index') ?>">正在播</a></li>
            <li <?php if( in_array("WillPlay",$action)):?>class="this" <?php endif;?>><a href="<?php echo url_for('/default/WillPlay') ?>">即将播</a></li>
            <li <?php if( in_array("ProgramList",$action)):?>class="this" <?php endif;?>><a href="<?php echo url_for('/default/ProgramList') ?>">节目表</a></li>
            <li <?php if( in_array("ChannelList",$action)):?>class="this" <?php endif;?>><a href="<?php echo url_for('/default/ChannelList') ?>">频道表</a></li>
            <li <?php if( in_array("SearchList",$action)):?>class="this" <?php endif;?>><a href="<?php echo url_for('/default/SearchList') ?>">搜索</a></li>
            <li <?php if( in_array("MyTv",$action)):?>class="this" <?php endif;?>><a href="<?php echo url_for('/default/MyTv') ?>">我的电视</a></li>
        </ul>
        
           <?php echo $sf_content ?>

        <div class="footer">
        	<p><span>· 选择</span><span>· 按键 (确定) 进入</span><span>· 云媒体首页</span> <span>· 按键 (上页/下页) 翻页</span> <span>· 帮助</span> </p>
        </div>


        <div class="tips" style="display:none">
        	<h2>提示信息</h2>
            <p>您已经到达最后一页。</p>
            <a href="#" class="cls">×</a>
        </div>
    </div>





  </body>
</html>
