<html>
<head>
<meta http-equiv="Content-Type" content="1280*720; text/html; charset=GBK"/>
<title>提示信息页面</title>
</head>
<style type="text/css">
body { background-color: transparent;}
</style>
<script type="text/javascript">
function load(){
   history.back();
}
document.onkeydown = keyEvent;
//按menu键退出浏览器
function keyEvent(e)
{
	keyword=e.which;
	switch(keyword)
	{
		/**获取返回键**/
		case '0x0280':
			history.back();
			return false;
			break ;
		default:
			break ;
	}
}

</script>
<body onload="setTimeout('load()',5000)">
   <div style="position:absolute; width:657px; height:387px; left:365px; top:150px;background:url(/img/bplaytipbox_bg.png) no-repeat left top;" >
	<div style=" position:absolute; width:583px; height:200px; left:38px; top:74px;font-size:24px;" id="confirm_message">
        您查看的视频资源不存在，5秒钟后系统将回到您 浏览的上一个页面。
	</div>
   </div>
</body>
</html>