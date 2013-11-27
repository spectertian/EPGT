<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<meta name=page-view-size content="1280*720" />
<script language="javascript" src="ui://over_all_widget/keyEvent1.0.js"></script>
<script language="javascript" src="ui://menu.js"></script>
<script type="text/javascript">
</script>
<style>
.com{position:absolute;width:120px;height:60px;top:0px;left:0px;-webkit-transition-duration:300ms;line-height:60px;}
.f0{left:0px}.f1{left:99px}.f2{left:240px}.f3{left:360px}.f4{left:480px;}.f5{left:600px;}.f6{left:720px;}.f7{left:841px;}.f8{left:960px;}.f9{left:1080px;}.f10{left:1200px;}
.subMenu{position:absolute;width:165px;height:130px;top:1px;left:0px;-webkit-transition-duration:300ms;}
.sub0{left:0px}.sub1{left:165px}.sub2{left:330px}.sub3{left:495px}.sub4{left:660px}.sub5{left:825px}.sub6{left:990px}.sub7{left:1155px}
</style>
</head>
<body leftmargin="0" topmargin="0" background="index_bg.gif" onload='init()' onunload = 'exit_page()' bgcolor="transparent" >
<!--logo-->
<div style="position:absolute; left:39px; top:27px;"><img src="logo.png" width="226" height="70"/></div>
<div style="position:absolute; left:270px; top:39px;"><img src="i_line.png" width="7" height="50"/></div>
<div style="position:absolute; left:284px; top:35px; width:230px; height:60px;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="color:#FFFFFF">
		<tr>
			<td width="24%" style="font-size:24px">智能<br />卡号</td>
			<td width="76%" id='ca_cardId' style="font-size:28px">55663428952</td>
		</tr>
	</table>
</div>
<!--weather-->
<div style="position:absolute; left:1045px; top:27px; width:186px; height:65px;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font-size:18px; color:#ffffff;">
		<tr>
			<td width="52%" align="right"><img src="wether.png" width="42" height="42" id='weather'/></td>
			<td width="48%" align="center">乌鲁木齐</td>
		</tr>
		<tr>
			<td align="center" id='tem'>无天气情况</td>
			<td align="center" id='curr_time'>18:15:40</td>
		</tr>
	</table>
</div>
<!--网络提示-->
<div style="position:absolute; left:977px; top:47px;"><img src="net_off.png" width="57" height="45" id='net_icon'/></div>
<!--小视频-->
<div style="position:absolute; left:52px; top:111px; width:435px; height:249px;visibility:hidden" id='music_bg'><img src="tv.jpg" width="435" height="249"/></div>
<!--小视频提示-->
<div style="position:absolute; left:52px; top:111px; width:435px; height:252px;visibility:hidden;z-index:10;background-color:#000000;" id='note' align='center'>
	<table width="90%" height="100%"><tr><td style="color:#FFFFFF;font-size:28px;" align='center' id='note_content'>test</td></tr></table>
</div>
<!--广告,1-->
<div style="position:absolute; left:436px; top:110px;z-index:11;"><img src="num_1.png" width="52" height="46"/></div>
<!--广告,2-->
<div style="position:absolute; left:525px; top:116px; width:319px; height:117px; background:url(adv_bg.png) no-repeat">
	<div style="position:absolute;left:1px;top:1px;width:319px;height:115px;"><img src="" width="319" height="115" id="imageAd_0"/></div>
	<div style="position:absolute; left:1px; top:0px; width: 317px;"><img src="shade1.png" width="319" height="47"/></div>
	<div style="position:absolute; left:273px; top:0px;"><img src="num_2.png" width="47" height="44"/></div>
</div>
<!--广告,3-->
<div style="position:absolute; left:905px; top:116px; width:319px; height:117px; background:url(adv_bg1.png) no-repeat">
	<div style="position:absolute; left:1px; top:1px; width:319px; height:115px;"><img src="" width="319" height="115" id="imageAd_1"/></div>
	<div style="position:absolute; left:1px; top:0px; width: 317px;"><img src="shade1.png" width="319" height="47"/></div>
	<div style="position:absolute; left:274px; top:0px;"><img src="num_3.png" width="46" height="45"/></div>
</div>
<!-- 主页广告代码 -->
<iframe id="indexAd0" style="display:none;"><!-- 图片广告2 --></iframe>
<iframe id="indexAd1" style="display:none;"><!-- 图片广告3 --></iframe>
<iframe id="indexAd2" style="display:none;"><!-- 走马灯广告 --></iframe>
<!--推荐-->
<div style="position:absolute; left:502px; top:273px; width:750px; height:110px;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
		<tr>
			<td width="150" align="center"><img src="app_ico1.png" width="100" height="100" id='recommend_0'/></td>
			<td width="150" align="center"><img src="app_ico1.png" width="100" height="100" id='recommend_1'/></td>
			<td width="150" align="center"><img src="app_ico1.png" width="100" height="100" id='recommend_2'/></td>
			<td width="150" align="center"><img src="app_ico1.png" width="100" height="100" id='recommend_3'/></td>
			<td width="150" align="center"><img src="app_ico1.png" width="100" height="100" id='recommend_4'/></td>
		</tr>
	</table>
	<div style="position:absolute; left:678px; top:-27px; width:70px; height:43px; background:url(app_tips.png) no-repeat">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="30" style="font-size:16px; color:#4a0505">
			<tr>
				<td align="center" id='email_num'>未读0</td>
			</tr>
		</table>
  </div>
</div>
<!--推荐的焦点框-->
<div style="position:absolute; left:516px; top:267px; -webkit-transition-duration:300ms;opacity:0" id='recommend_focus'>
	<img src="focus2.png" width="119" height="118"/>
</div>
<!--主菜单 看电视/听广播/读报刊....-->
<div style="position:absolute; left: 12px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_1.png" id='img_0'/>
</div>
<div style="position:absolute; left: 146px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_2.png" id='img_1'/>
</div>
<div style="position:absolute; left: 285px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_3.png" id='img_2'/>
</div>
<div style="position:absolute; left: 418px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_4.png" id='img_3'/>
</div>
<div style="position:absolute; left: 546px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_5.png" id='img_4'/>
</div>
<div style="position:absolute; left: 666px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_6.png" id='img_5'/>
</div>
<div style="position:absolute; left: 786px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_7.png" id='img_6'/>
</div>
<div style="position:absolute; left: 906px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_8.png" id='img_7'/>
</div>
<div style="position:absolute; left: 1026px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_9.png" id='img_8'/>
</div>
<div style="position:absolute; left: 1146px; top: 429px; width:120px" align="center">
	<img src="mianMenu0_10.png" id='img_9'/>
</div>
<!--主菜单 焦点-->
<div style="position:absolute; left:-3px; top:414px; -webkit-transition-duration:300ms;opacity:0;z-index:21;" id="mainMenu_focus">
    <img src="focus.png" width="150" height="78"/>
</div>
<!--子菜单 电视直播\高清看吧\名语点播-->
<!--左右滚动提示-->
<div style="position:absolute; left:29px; top:571px;">
    <img src="s_left.png" width="24" height="32"/>
</div>
<div style="position:absolute; left:1232px; top:571px;">
    <img src="s_right.png" width="24" height="32"/>
</div>
<div style="position:absolute;left:57px;top:529px;width:1155px;height:127px;-webkit-transition-duration:300ms;opacity:0;overflow:hidden;" id="subMenu">
	<div class="subMenu sub0" id='subMenu_0' align="center"><img src=" " width="108" height="127" id="subImg_0"/></div>
	<div class="subMenu sub1" id='subMenu_1' align="center"><img src=" " width="108" height="127" id="subImg_1"/></div>
	<div class="subMenu sub2" id='subMenu_2' align="center"><img src=" " width="108" height="127" id="subImg_2"/></div>
	<div class="subMenu sub3" id='subMenu_3' align="center"><img src=" " width="108" height="127" id="subImg_3"/></div>
	<div class="subMenu sub4" id='subMenu_4' align="center"><img src=" " width="108" height="127" id="subImg_4"/></div>
	<div class="subMenu sub5" id='subMenu_5' align="center"><img src=" " width="108" height="127" id="subImg_5"/></div>
	<div class="subMenu sub6" id='subMenu_6' align="center"><img src=" " width="108" height="127" id="subImg_6"/></div>
	<div class="subMenu sub7" id='subMenu_7' align="center"><img src=" " width="108" height="127" id="subImg_7"/></div>
</div>
<!--子菜单 焦点-->
<div style="position:absolute; left:63px; top:520px;-webkit-transition-duration:300ms;opacity:0" id="subMenu_focus"><img src="focus3.png" width="126" height="125"/></div>
<!--滚动条-->
<div style="position:absolute; left:85px; top:670px; width:950px; height:30px;overflow:hidden;">
	<img src="voide_ico.png" width="26" height="22" style="position:absolute; left:13px; top:2px;"/>
	<div style="position:absolute; left:44px; top:0px; height:30px;width:900px;;font-size:22px; color:#9cc4dc; width: 905px;-webkit-transition-duration:300ms;" align="left" id="marquee_ad0">test</div>
	<div style="position:absolute; left:46px; top:-30px; height:30px;width:900px;;font-size:22px; color:#9cc4dc; width: 905px;-webkit-transition-duration:300ms;" align="left" id="marquee_ad1">test</div>
</div>
<!--无网络提示-->
<div style="position:absolute; left:324px; top:202px; width:632px; height:361px; -webkit-transition-duration:300ms;background:url(gq_tips0.png)  center no-repeat;opacity:0;z-index:100;" align="center" id='tips'>
	<table width="585" height="87%">
		<tr><td height="50" colspan="2">&nbsp;</td>
		<tr>
			<td height="218" colspan="2" align="center" style="font-size:28px;color:#FFFFFF" id='tips_msg'>提醒</td>
		</tr>
  </table>
</div>

</body>
</html>