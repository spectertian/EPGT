<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="page-view-size" content="1280*720" />
<script type="text/javascript">
<!--

function init(){
}
//-->
</script>
<style>
.com{position:absolute;width:270px;height:40px;top:0px;left:2px;-webkit-transition-duration:300ms;line-height:40px;color:#FFFFFF;font-size:24px}
.f0{top:2px}.f1{top:40px}.f2{top:78px}.f3{top:117px}.f4{top:159px;}.f5{top:198px;}.f6{top:236px;}.f7{top:278px;}
</style>

<title>无标题文档</title>
</head>

<body leftmargin="0" topmargin="0"  bgcolor="transparent" onLoad="init()" onUnload="exitPage()">
<!-- 锁定提示 -->
<div style="position:absolute;background:url(lock_bg.png) center no-repeat; width:588px;height:298px;top:195px;left:311px;-webkit-transition-duration:300ms;opacity:0;" id="lock">
	<table height="87%" border="0" cellpadding="0" cellspacing="0" style="width:100%;height:100%;font-size:26px;">
		<tr>
			<td height="76" colspan="2" align="center" style="font-size:30px;color:white;" id="lock_info_0">请输入密码解锁频道</td>
		</tr>
		<tr>
			<td width="40%" align="right" height="97" id="lock_info_1">请输入密码：</td>
			<td id="lock_password" style="background:url(lock_input.png) left no-repeat;padding-left:10px;">&nbsp;</td>
		</tr>
		<tr>
			<td height="43" colspan="2" align="center" id="lock_tips">&nbsp;</td>
		</tr>
		<tr>
			<td height="70px" colspan="2" style="font-size:20px;text-align:center;" id="lock_info_2">按 [确认]键解锁，[0~9]键输入，[返回]键删除</td>
		</tr>
  </table>
</div>

<!--channelList-->
<div style="position:absolute; left:985px; top:103px; width:294px; height:414px; background:url(music_list.png) no-repeat;-webkit-transition-duration:300ms;opacity:1;" id='channel_list'>	
	<div style="position:absolute; left:7px; top:5px; width:278px; height:86px;"><img src="pop9.png" width="278" height="86"  id="play_ad_8"/></div>
	<div style="position:absolute; left:5px; top:94px; width: 284px;"><img src="select.png" width="284" height="41"/></div>
	<div style="position:absolute; left:27px; top:100px;"><img src="s_left.png" width="24" height="32"/></div>
	<div style="position:absolute; left:223px; top:100px;"><img src="s_right.png" width="24" height="32"/></div>
	<div style="position:absolute; left:277px; top:135px;"><img src="bar.png" width="9" height="266"/></div>
	<div style="position:absolute; left:276px; top:136px; width:12px; height:50px;" id='channelList_progress'><img src="p_t.png" width="11" height="6"/><img src="p_c.png" width="11" height="38"/><img src="p_b.png" width="11" height="6"/></div>
	<div style="position:absolute; left:54px; top:98px; width:170px; height:33px; line-height:33px; text-align:center; font-size:28px; color:#ffffff;" id='currType'>
	广播</div>	
	<div style="position:absolute; left:4px; top:134px; width:270px;height:280px;overflow:hidden;z-index:3">
		<div class="com f0" id="channelList_0">频道1</div>
		<div class="com f1" id="channelList_1">频道1</div>
		<div class="com f2" id="channelList_2">频道1</div>
		<div class="com f3" id="channelList_3">频道1</div>
		<div class="com f4" id="channelList_4">频道1</div>
		<div class="com f5" id="channelList_5">频道1</div>
		<div class="com f6" id="channelList_6">频道1</div>
		<div class="com f7" id="channelList_7">频道1</div>
    </div>  
	<div style="position:absolute; left:5px; top:134px; z-index:2; width: 270px;" id='channelList_focus'>
		<img src="focus6.png" width="270" height="39"/>	
	</div>
</div>

<!--pf 条-->
<div style="position:absolute; left:40px; top:570px; width:1200px; height:155px; background:url(pf_bar.png) no-repeat;-webkit-transition-duration:300ms;opacity:1;" id='navigator'>
	<div style="position:absolute; left:213px; top:13px; width:140px; height:64px;" id="pf_channel_num"><img src="s_num0.png" width="46" height="64"/><img src="s_num1.png" width="46" height="64"/><img src="s_num2.png" width="46" height="64"/></div>
	<div style="position:absolute; left:364px; top:38px; width: 64px;">
		<table><tr><td><img src="v_ico1.png" id='love_icon' width="28px" height="24px"/><img src="v_ico2.png" id='lock_icon' width="23px" height="28px"/></td></tr></table>
    </div>  
	<div style="position:absolute; left:215px; top:82px; width:222px; height:50px; line-height:50px; font-size:32px; color:#ffffff;" id='channel_name'></div>
	<div style="position:absolute; left:440px; top:0px; width:452px; height:65px; font-size:28px; color:#ffffff;">
		<table width="453" cellpadding="0px" cellspacing="0px" style="font-size:26px; color:#ffffff;" height="100%">
		<tr>
		  	<td width="132">正在播放：</td>
			<td id='curr_programe' width="268"></td>
		</tr>
	  </table>
    </div>	
	<div style="position:absolute; left:454px; top:69px; width:419px; height:7px; background:url(bar2.png) no-repeat" id='curr_progress'></div>
	<img style="position:absolute; left:380px; top:9px; visibility:hidden" id='isTstv' width='28px' height='24px' src='tstv.png'></img>
    <div style="position:absolute; left:440px; top:88px; width:452px; height:46px; line-height:46px; font-size:26px; color:#ffffff;" id='f_program'></div>
	<div style="position:absolute; left:25px; top:5px; width:152px; height:142px;">
	  <div id='play_ad_1' style="position:absolute; left:2px; top:-2px; font-size:30px; color:#ffffff; white-space:nowrap; text-overflow:ellipsis; overflow:hidden; line-height:50px; width:144px; height:50px" align="center">天山云</div>
		<div style="position:absolute; left:7px;top:54px;font-size:26px; color:#ffffff;" align="center">提醒您时间</div>
		<div style="position:absolute; left:12px; top:98px; font-size:30px; color:#ffffff; width: 122px;" id='curr_time' align="center">08:10:10</div>
	</div>
	<div style="position:absolute; left:907px; top:4px; width:287px; height:142px;"><img src="" width="287" height="142" id='play_ad_0'/></div>
</div>

<!--声道-->
<div style="position:absolute;left:746px;top:3px;width:214px;height:87px; background:url(sd_bg.png) no-repeat;-webkit-transition-duration:300ms; opacity:0;" id='sound_mode'>

<!--文字广告啊-->
<div style="position:absolute; left:6px; top:9px; width:112px; height:30px; line-height:30px; font-size:20px; color:#ffffff; overflow:hidden; word-break:break-all;" id="play_ad_2" align="center">天山云</div>
<div style="position:absolute; left:10px; top:48px; width:109px; height:30px; line-height:30px; font-size:20px; color:#ffffff; overflow:hidden; word-break:break-all;">提醒您声道</div>
<div style="position:absolute; left:128px; top:0px;"><img src="midd_sd.png" width="86" height="86" id='mode_img'/></div></div>

<!-- 频道号 -->
<div id="channelNumber" style="position:absolute; left:321px; top:205px; width:569px; height:264px;-webkit-transition-duration:300ms;opacity:0;z-index:9;">
	<table style="position:absolute; left:4px; top:2px; width:563px; height:264px;"><tr><td id="input" width="100%" height="100%" align="center" valign="middle"></td></tr></table>
</div>

<!--CA提示，无信息提示，智能卡提示等等-->
<div style="position:absolute;background:url(lock_bg.png) center no-repeat; width:588px;height:298px;top:195px;left:311px;z-index:8;opacity:1;-webkit-transition-duration:300ms;" id="tips">
	<div style="position:absolute; left:46px; top:0px; width:509px; height:55px;font-size:30px;color:white;" align="center" id='tips_title'>提示标题</div>
	<div style="position:absolute; left:21px; top:59px; width:549px; height:200px;" align="center" >
	 <table width='100%' height='100%%'>
	 <tr>
	     <td height="147" align="center" valign="middle" id='tips_content' style=" font-size:28px; color:#000000;"> 提示内从</td>
	 </tr>
	 </table>
  </div>
  <div style="position:absolute; left:42px; top:261px; width:508px; height:30px;font-size:22px;color:000000;visibility:hidden" align="center" id='tips_exit'>[退出键]退出</div>
</div>

<!--网络角标提示-->
<div id="net_tips" style="position:absolute; left:835px; top:87px; width:211px; height:75px; z-index:3; background:url(vod_tip_2.png) no-repeat; color:#ffffff; visibility: hidden;font-size:24px;line-height:75px" align="center">
<table width="100%" height="100%" border="0" cellspacing="0">
    <tr>
	 <td style='font-size:24px; color:#FFFFFF;' align="center" id="net_text">请插上网线</td>
    </tr>
  </table>
</div>

<!--email-->
<!--osd-->
<!--
<div id="CAnewEmailInfo"  style="position:absolute;top:24px; left:936px; visibility:hidden;" ><img src="email.png"></div>
-->
<!--
<div id="CAfullEmailInfo"  style="position:absolute; top:18px; left:924px; visibility:visible;"><img src="CAemailFull.png"></div>
-->
<div id="CAUpScrollInfo" style="position:absolute; top:0px; left:0px; height:60px; width:1280px; visibility:hidden;background-image:url(osd.png);">
<div id='up_osd_text' style="position:absolute; left:10px; width:1262px; height:78px; font-size:32px; line-height:60px; color:#FFFFFF; top: 0px;"></div>
</div>

<div style="position:absolute; left:1px; top:579px; width:1280px; height:140px; background:url(email_bar.png) no-repeat;visibility:hidden;-webkit-transition-duration:300ms;" id='email_tips'>
	<div style="position:absolute; left:20px; top:-15px;"><img src="email.png" width="128" height="75"/></div>
		<div style="position:absolute; left:151px; top:3px; width:1083px; height:60px; line-height:60px; font-size:22px; color:#e3e3e3" id='email_title'>
		邮件主题：热烈祝贺新疆广电，天山云电视开通    2012.12.34  05:35		</div>
		<div style="position:absolute; left:1144px; top:87px; width:135px; height:23px; font-size:20px; color:#e3e3e3">[退出键] 退出 </div>
		<div style="position:absolute; left:30px; top:66px; width:1115px; height:69px;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="font-size:21px; color:#9cc4dc">
			<tr>
			<td width="16%">尊敬的用户: </td>
			<td width="84%" id='email_content'>畅享新疆广电新生活，创高清视窗新感受，畅享新疆广电新生活，创高清视窗新感受</td>
			</tr>
		</table>
  </div>
</div>

<div id="CAdownScrollInfo" style="position:absolute; top:642px; left:0px; height:78px; width:1280px; visibility:hidden;background-image:url(osd.png);-webkit-transition-duration:300ms;z-index:10">
<div id='down_osd_text' style="position:absolute;left:8px;width:1263px;height:78px;font-size:32px;line-height:60px;color:#FFFFFF; top: 0px;"></div>
</div>


<!-- 广告代码 -->
<iframe id="playAd0" style="display:none;"></iframe>
<iframe id="playAd1" style="display:none;"></iframe>
<iframe id="playAd2" style="display:none;"></iframe>

<!-- 付费/未授权 视频广告代码 -->
<div style="position:absolute;left:0px;top:0px;width:947px;height:536px;visibility:hidden;" id="ca_video_ad">
	<img src="" width="947" height="536" id="play_ad_3"/>
</div>
<!--付费的文字广告-->
<div style="position:absolute;left:0px;top:536px;width:947px;height:184px;visibility:hidden;background:url(pop6.jpg) center no-repeat;" id='pay_txt_ad'>
	<div id="play_ad_4_0" style="position:absolute;top:64px;left:265px;line-height:30px;width:69px;height:30px;font-size:24px;color:#ff6600;"></div>
	<div id="play_ad_4_1" style="position:absolute;top:64px;left:520px;line-height:30px;width:300px;height:30px;font-size:24px;color:#ff6600;"></div>
</div>
<!--付费的图片广告/未授权的图片广告-->
<div style="position:absolute;left:947px;top:0px;width:333px;height:720px;visibility:hidden;" id='ca_pic_ad'>
	<img src="" width="333" height="720" id="play_ad_5"/>
</div>
<!--未授权的图片广告-->
<div style="position:absolute;left:0px;top:536px;width:947px;height:184px;visibility:hidden;" id='authoriza_ad'>
	<img src ='' width="947" height="184" id="play_ad_7">
</div>


<!--湖南卫视的Pf Bar-->
<div style="position:absolute; left:40px; top:720px; width:1200px; height:200px;-webkit-transition-duration:300ms;opacity:0;" id='navigator_spec'>
	<div style="position:absolute; width:1200px; height:200px;left:0px; top:0px; "><img src='pf_bar_sepc2.png' width=1200px height=200px id='play_ad_6'></div>
	<div style="position:absolute; left:173px; top:58px; width:180px; height:64px;" id="pf_channel_num_spec"><img src="s_num0.png" width="46" height="64"/><img src="s_num1.png" width="46" height="64"/><img src="s_num2.png" width="46" height="64"/></div>

	<div style="position:absolute; left:323px; top:83px; width: 64px;">
		<table><tr><td><img src="v_ico1.png" id='love_icon_spec' width=28px height=24px/><img src="v_ico2.png" id='lock_icon_spec' width=23px height=28px/></td></tr></table>
  </div>
  
	<img style="position:absolute; left:357px; top:59px; visibility:hidden" width='28px' height='24px' src='tstv.png' id='isTstv_spec' ></img>
	
  <div style="position:absolute; left:177px; top:131px; width:196px; height:50px; line-height:50px; font-size:32px; color:#ffffff;" id='channel_name_spec'></div>

	<div style="position:absolute; left:414px; top:63px; width:441px; height:46px;">
	  <table width="441" cellpadding="0px" cellspacing="0px" style="font-size:26px; color:#ffffff;" height="100%">
		<tr>
		  	<td width="143">正在播放：</td>
			<td id='curr_programe_spec' width="257"></td>
		</tr>
	  </table>
  </div>

	<div style="position:absolute; left:416px; top:113px; width:420px; height:6px; background:url(bar2.png) no-repeat" id='curr_progress_spec'></div>
  
  	<div style="position:absolute; left:413px; top:134px; width:434px; height:46px; line-height:46px; font-size:26px; color:#ffffff;" id='f_program_spec'></div>
	
	<div style="position:absolute; left:11px; top:167px; font-size:30px; color:#ffffff; width: 122px;" id='curr_time_spec' align="center">08:10:10</div>
</div>

<iframe id="playAd3" style="display:none;"></iframe>
<iframe id="playAd4" style="display:none;"></iframe>
<iframe id="playAd5" style="display:none;"></iframe>

<!--特殊频道Pf广告-->
<iframe id="playAd6" style="display:none;"></iframe>
<!--未授权广告-->
<iframe id="playAd7" style="display:none;"></iframe>
<!--列表广告-->
<iframe id="playAd8" style="display:none;"></iframe>

<!--指纹-->
<div id="cardId" style="position:absolute;top:30px;left:0px;width:100px;height:40px;background-color:#FFFFFF;color:#000000;line-height:40px;text-align:center;visibility:hidden;font-size:24px;z-index:99"></div>

<!--tips反L广告时提示需要挂在左上角-->
<div id="L_tips" style="position:absolute;top:0px;left:0px;width:500px;height:82px;background-image:url(l-tips.png);color:#FFFFFF;line-height:70px;text-align:center;font-size:24px;visibility:hidden">提示</div>

<div id="cardId_tips" style="position:absolute;top:76px;left:0px;width:212px;height:71px;background-image:url(vod_tip_2.png);text-align:center;visibility:hidden"><table width="90%" height="100%" style="color:#FFFFFF;font-size:20px">
	<tr><td width="28%">智能<br>
	  卡号</td><td width="72%" id="ca_cardId" style="font-size:24px"> </td>
	</tr></table>
</div>

<!--pushmail新消息提醒-->
<div style="position:absolute; left:993px; top:595px; width:227px; height:81px; background:url(x_panel2.png) no-repeat;-webkit-transition-duration:300ms;z-index:101;visibility:hidden" id='push_mail_email'>
	<div style="position:absolute; left:1px; top:-18px;"><img src="email_ico.png" width="72" height="72"/></div>
	<div style="position:absolute; left:85px; top:-1px; width:139px; height:50px; line-height:50px; font-size:28px; color:#ffffff;">新消息(<span id='push_mail_num'>2</span>)</div>
<div style="position:absolute; left:95px; top:40px;"><img src="x_num1.png" width="125" height="31"/></div>
</div>

<!--pushmail的滚动提醒-->
<div style="position:absolute; left:0px; top:605px; width:1280px; height:64px; background:url(x_panel1.png) no-repeat; font-size:30px; color:#ffffff; line-height:64px; text-align:center;-webkit-transition-duration:300ms;z-index:100;visibility:hidden" id='push_mail_osd'>滚动提示：热烈祝贺新疆广电，天山云电视正式开通，新老客户认为好    2012.12.34  05:35 </div>

</body>
</html>
