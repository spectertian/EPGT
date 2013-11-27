/*地址写法说明:

地址写法主要注意一点: 有些应用前端是标清的，所以要进行高标清的自适应，所以针对标清应用的地址写法:ui://loading.htm?+相应的应用的地址

*/
var stb_id = hardware.STB.serialNumber;



/*vod点播设置里面的点播模式,点播模式不一样，对应的双向主页地址不一样*/
var vod_type = iPanel.misc.getGlobal("vod_type");
if (typeof(vod_type) == "undefined" || vod_type == null || vod_type=="")
{
	vod_type = "family";
}

/*互动主页*/
var index_url = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?stbid=" + stb_id + "&xjurl=ui://index.htm";

/********看电视********/
var tstv_url = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=huifang&stbid=" + stb_id + "&type=mpeg4";
//"http://xinjiang-timeshift.wasu.cn:7080/timeShift/xinjiangcolour/index.jsp?stbid=" + stb_id + "&type=mpeg4";
/********互动电视********/
/*限时免费*/
var xsmf_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=xsmf&stbid=" + stb_id;
/*精彩预告*/
var jcyg_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=jcyg&stbid=" + stb_id;
/*影视首播*/
var yingshi_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=yssb&stbid=" + stb_id;
/*标清点播*/
var huashu_sd_index = "http://xinjiang-stbepg.wasu.cn:8080/tvportal/jump/jump_xj120e.jsp?stbid=" + stb_id + "&xjurl=ui://index.htm";
/*高清点播*/
var gqdb_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xj120egq.jsp?stbid=" + stb_id;
/*超清点播*/
var cqdb_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wggq&stbid=" + stb_id + "&xjurl=ui://index.htm";
/*3d点播*/
var threeD_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=3ddb&stbid=" + stb_id;
/*健康专区*/
var jkzq_index = "http://10.26.7.45:81/Health/JK_index.html";
/*儿童专区*/
var etzq_index = "http://10.26.7.45:81/HappyChildren/HappyChildren.html";
/*教育专区*/
var jyzq_index = "http://10.26.7.45:81/Education/Edu_index.html";
/*体育专区*/
var tyzq_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=nba&stbid=" + stb_id;

/********维语点播********/
/*维文点播*/
var index_weiwen_url ="http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=weiyu&stbid="+stb_id;
/*新片热映*/
var weiwen_xpry_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wydd&stbid=" + stb_id;
/*维文电视剧*/
var weiwen_dsj_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wyser&stbid=" + stb_id;
/*维文电影*/
var weiwen_dy_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wymov&stbid=" + stb_id;
/*维文少儿*/
var weiwen_shaoer_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wysr&stbid=" + stb_id;
/*维文新闻*/
var weiwen_xw_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wynews&stbid=" + stb_id;
/*维文文艺*/
var weiwen_wenyi_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wywy&stbid=" + stb_id;

/********哈语点播********/
/*哈语互动点播*/
var hayu_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=hayu&stbid=" + stb_id;
/*电视剧*/
var hayu_dianshiju = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=hyser&stbid=" + stb_id;
/*电影*/
var hayu_dianying = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=hymov&stbid=" + stb_id;
/*新闻*/
var hayu_xinwen = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=hynews&stbid=" + stb_id;
/*文艺*/
var hayu_wenyi = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=hyzy&stbid=" + stb_id;


/********全景新疆********/
/*大美新疆*/
var dmxj_index = "http://10.26.7.45:81/Beautiful_XJ/index.html";
/*走进新疆*/
var zjxj_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=zjxj&stbid=" + stb_id;
/*新疆农业*/
var xjny_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=xjny&stbid=" + stb_id;
/*新疆七坊街*/
var xjqfj_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=wdlyy&stbid=" + stb_id;
/*天山阅读*/
var tsyd_index = "http://10.60.6.212/tvshow";
/*天山书画院*/
var tsshy_index = "http://10.26.7.45:81/TSSHY/index.html";
/*红山问政*/
var hswz_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=hswz&stbid=" + stb_id;
/*美食新疆*/
var msxj_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=msxj&stbid=" + stb_id;

/********逛商城*******/
var dssc_index = "http://10.60.6.220:8080/iptvhd/index.html";


/********云娱乐*******/
/*盛大游戏*/
var sdyx_index = "ui://loading.htm?http://10.48.179.113/gate_xj/login?stbId=" + stb_id + "&returnUrl=ui://index.htm"
/*希沃游戏*/
var xwyx_index = "http://10.26.100.80:8089/seewoser/index.jsp?stbid=" + stb_id ;
/*3D游戏*/
var tgyx_index = "http://10.60.6.205:8881/gp3-tv2A/index.html";
var tmpVersion = iPanel.misc.revision;
tmpVersion = tmpVersion.substring(0,tmpVersion.indexOf("."));
if(tmpVersion == "20432") tgyx_index = "http://10.60.6.205:8881/gp3-tv2C/index.html";
/*九城游戏*/
var jiuc_index = "ui://loading.htm?http://10.26.7.60/hw720p/Default.aspx";
/*九城动漫*/
var jcdm_index="ui://loading.htm?http://10.26.7.60/IPTVRead/default_direct.aspx?stbid=" + stb_id;
/*卡拉OK*/
var kalaok_index = "http://125.210.177.63:8080/gq30/portal.jsp?stbid=" + stb_id;
/*彩虹音乐*/
var caihong_index = "http://xinjianghdtv-stbepg.wasu.cn:8080/tvportal/jump/jump_xjlmu.jsp?code=chyy&stbid=" + stb_id;

/********智慧新疆********/
/*高考查询*/
var gkcx_index = "http://10.26.7.45:81/other/score/index.action";
/*盛大文学*/
var sdwx_index = "http://10.60.6.215/mybook/TVBook/Start?stbid=" + stb_id + "&returnUrl=ui://index.htm";
/*交通违章*/
var jtwz_index="http://10.26.7.45:81/other/car/index.html";
/*家政服务*/
var jzfw_index = "http://10.26.7.45:81/other/jiazheng/index.html";
/*航班查询*/
var hbcx_index="http://10.26.7.45:81/other/air_index.jsp";
/*招聘信息*/
var zpxx_index = "http://10.26.7.45:81/other/pserson.action";
/*便民信息*/
var bmxx_index="http://10.26.7.45:81/other/yxfood.action?start=0&limit=5";
/*股票地址*/
var stock_index = "ui://loading.htm?dvb://65535.65535.951/";
/*彩票中心*/
var cpzx_index="http://10.60.6.214/lottery/";
/*天气预报*/
var weather_index = "http://10.60.6.250/itime_weather/html/index.htm";
/*头条新闻*/
var sinatvnews_index = "http://10.60.6.250/itime_sinatvnews/html/index.htm";
/*名人名言录*/
var mingren_index = "http://10.60.6.250/wisdom/html/index.htm";
/*历史上的今天*/
var lishi_index = "http://10.60.6.250/itime_history_today/html/index.htm";
/*百花谱*/
var baihua_url = "http://10.60.6.250/flowers/html/index.htm";
/*家庭盆栽*/
var jiating_url = "http://10.60.6.250/potting/html/index.htm";
/*传统节日溯源*/
var chuantong_url = "http://10.60.6.250/festivalsource/html/index.htm";
/*56民族风情*/
var mingzhu_url = "http://10.60.6.250/56ethnic_customs/html/index.htm";
/*中国名画欣赏*/
var minghua_url = "http://10.60.6.250/itime_chinamastergallery/html/index.htm";
/*魔幻厨房*/
var magickitchen_index = "http://10.60.6.204:8080/magickitchen/html/index.htm";
/*24节气*/
var ershisijieqi_index = "http://10.60.6.204:8080/itime_24solarterm/html/index.htm";
/*个股一点通*/
var gegu_index = "ui://loading.htm?http://218.108.247.81/dapan.php?code=&userID=&cityID=&ordertype=&product=&cjIndexPage=http://125.210.229.35:80/lwms-tv/finance.jsp?ext=1&vflag=29&stbid=" + stb_id;
/*资讯门户*/
var zxmh_index="http://10.60.6.214:7070/xinjiangweb/";
var cfxj_index="http://125.210.229.35/lwms-tv/finance.jsp?ext=1&vflag=503&grouptype=1&stbid=&cache=nocache";


/********云应用********/
/*中行家具银行*/
var zhonghang_index = "ui://loading.htm?http://10.60.6.213/";
/*应用商店*/
var yysd_index = "http:// 10.26.7.47:8080/btopinterface_ott/app/iptv/appShop/base";
/*计量转换*/
var jiliangzhuanhuan_index = "http://10.60.6.204:8080/unit_converter/html/index.htm";
/*汉语字典*/
var hanyuzidian_index = "http://10.60.6.204:8080/dict/html/index.htm";
/*车标大全*/
var chebiao_index = "http://10.60.6.250/itime_vehiclelogo_club/html/index.htm";
/*电视日历*/
var calendar_index = "http://10.60.6.250/i_calendar/html/index.htm";
/*科学计算器*/
var kexuejisuanqi_index = "http://10.60.6.250/calc/html/index.htm";

/********营业厅********/
/*电视营业厅*/
var dianshi_cx="http://10.60.6.211:8080/payment/index.htm?1&0";
/*电视缴费*/
var dianshi_jf="http://10.60.6.211:8080/payment/index.htm?2&0";
/*通讯费缴纳*/
var txf_jn="http://10.60.6.211:8080/payment/index.htm?2&2";
/*信用卡还款*/
var xyk_hk="http://10.60.6.211:8080/payment/index.htm?2&3";
/*产品订购*/
var dianshi_dg="http://10.60.6.211:8080/payment/index.htm";


/*热门推荐*/
/*pic : 对应的图标的名称 ; url : 对应的推荐的地址 ; name : 对应的应用名称 ; type : 对应的应用的类型*/
var recommend = [{"pic":"app_ico1.png","url":xwyx_index,"name":"西沃游戏","lockPos":10,"type":"DTVM-H"},
					{"pic":"app_ico2.png","url":jyzq_index,"name":"教育专区","lockPos":7,"type":"DTVM-H"},
					{"pic":"app_ico3.png","url":etzq_index,"name":"儿童专区","lockPos":6,"type":"DTVM-H"},
					{"pic":"app_ico4.png","url":sdwx_index,"name":"盛大文学","lockPos":11,"type":"DTVM-H"},
					{"pic":"app_ico5.png","url":"ui://system/epg.htm","name":"节目指南","type":"DTVM-H"}
				];



/*一级菜单*/
/*这里描述一级菜单功能*/
/*
默认处理:
	点击看电视 : 进入直播
	点击互动电视/维语点播/哈语点播 : 进入二级菜单的第一个应用
	其余 : 进入二级菜单
*/
var mainMenu_content = ["看电视","互动电视","维语点播","哈语点播","全景新疆","云商城","云娱乐","云资讯","云应用","营业厅"];
/*默认的一级菜单的图标*/
var mainMenu = ["mianMenu0_1.png","mianMenu0_2.png","mianMenu0_3.png","mianMenu0_4.png","mianMenu0_5.png","mianMenu0_6.png","mianMenu0_7.png","mianMenu0_8.png","mianMenu0_9.png","mianMenu0_10.png"];
/*当某个一级菜单为焦点时的图标*/
var mainMenu_focus = ["mianMenu1_1.png","mianMenu1_2.png","mianMenu1_3.png","mianMenu1_4.png","mianMenu1_5.png","mianMenu1_6.png","mianMenu1_7.png","mianMenu1_8.png","mianMenu1_9.png","mianMenu1_10.png"];
/*当焦点从一级菜单移动到二级菜单时,对应一级菜单的图标*/
var mainMenu_blur = ["mianMenu2_1.png","mianMenu2_2.png","mianMenu2_3.png","mianMenu2_4.png","mianMenu2_5.png","mianMenu2_6.png","mianMenu2_7.png","mianMenu2_8.png","mianMenu2_9.png","mianMenu2_10.png"];



/*二级菜单*/
/*二级菜单与一级菜单对应，每个一级菜单对应一系列子菜单*/
/*pic : 对应的图标的名称 ; url : 对应的推荐的地址 ; name : 对应的应用名称 ; type : 对应的应用的类型*/
var subMenu = [
	[/*看电视*/
		{"url":"ui://play_kanba.html?0","pic":"icon_gqkb.png","name":"高清看吧","type":"DTVM-H"},				/*高清看吧*/
		{"url":"ui://play_tuwen.html","pic":"icon_twpd.png","name":"图文频道","type":"DTVM-H"},							                /*图文频道*/
		{"url":tstv_url,"pic":"icon_dshf.png","name":"电视回放","type":"DTVM-H"},							          /*电视回放*/
		{"url":"ui://mosaic/mosaic.htm?2","pic":"icon_tydsq.png","name":"体育电视墙","type":"DTVM-H"},	/*体育电视墙*/
		{"url":"ui://mosaic/mosaic.htm?1","pic":"icon_etdsq.png","name":"儿童电视墙","type":"DTVM-H"},	/*儿童电视墙*/
		{"url":"ui://mosaic/mosaic.htm?0","pic":"icon_dydsq.png","name":"电影电视墙","type":"DTVM-H"},	/*电影电视墙*/
		{"url":"ui://3D","pic":"icon_3Dzb.png","name":"3D直播","type":"DTVM-H"},								        /*3D直播*/
		{"url":"ui://music","pic":"icon_gbdt.png","name":"广播电台","type":"DTVM-H"}							    	/*广播电台*/
	],
	
	[/*互动电视*/
		{"url":xsmf_index,"pic":"icon_xsmf.png","name":"限时免费","type":"DTVM-H"},											/*限时免费*/
		{"url":jcyg_index,"pic":"icon_jcyg.png","name":"精彩预告","type":"DTVM-H"},											/*精彩预告*/
		{"url":yingshi_index,"pic":"icon_yssb.png","name":"影视首播","lockPos":1,"type":"DTVM-H"},			/*影视首播*/
		{"url":huashu_sd_index,"pic":"icon_bqdb.png","name":"标清点播","type":"DTVM-H"},						  	/*标清点播*/
		{"url":gqdb_index,"pic":"icon_gqdb.png","name":"高清点播","lockPos":2,"type":"DTVM-H"},					/*高清点播*/
		{"url":cqdb_index,"pic":"icon_cqdb.png","name":"超清点播","lockPos":3,"type":"DTVM-H"},	        /*超清点播*/
		{"url":threeD_index,"pic":"icon_3Ddb.png","name":"3D点播","lockPos":4,"type":"DTVM-H"},					/*3D点播*/
		{"url":jkzq_index,"pic":"icon_jkzq.png","name":"健康专区","lockPos":5,"type":"DTVM-H"},	        /*健康专区*/
		{"url":etzq_index,"pic":"icon_etzq.png","name":"儿童专区","lockPos":6,"type":"DTVM-H"},	         /*儿童专区*/
		{"url":jyzq_index,"pic":"icon_jyzq.png","name":"教育专区","lockPos":7,"type":"DTVM-H"},          /*教育专区*/
		{"url":tyzq_index,"pic":"icon_tyzq.png","name":"体育专区","lockPos":8,"type":"DTVM-H"}							/*体育专区*/
	],

	[/*维语点播*/
		{"url":index_weiwen_url,"pic":"icon_wyhddb.png","name":"维语点播","type":"DTVM-H"},					/*维语点播*/
		{"url":weiwen_xpry_index,"pic":"icon_sfzq.png","name":"新片热映","type":"DTVM-H"},							/*新片热映*/
		{"url":weiwen_dsj_index,"pic":"icon_dsglm.png","name":"电视剧栏目","type":"DTVM-H"},					  /*电视剧栏目*/
		{"url":weiwen_dy_index,"pic":"icon_dylm.png","name":"电影栏目","type":"DTVM-H"},							  /*电影栏目*/
		{"url":weiwen_shaoer_index,"pic":"icon_selm.png","name":"少儿栏目","type":"DTVM-H"},				   	/*少儿栏目*/
		{"url":weiwen_xw_index,"pic":"icon_xwlm.png","name":"新闻栏目","type":"DTVM-H"},						    /*新闻栏目*/
		{"url":weiwen_wenyi_index,"pic":"icon_wylm.png","name":"文艺栏目","type":"DTVM-H"}					  	/*文艺栏目*/
	],

	[/*哈语点播*/
		{"url":hayu_index,"pic":"icon_hydb.png","name":"哈语点播","type":"DTVM-H"},								      /*哈语点播*/
		{"url":hayu_dianshiju,"pic":"icon_hydsj.png","name":"电视剧","type":"DTVM-H"},						    	/*电视剧*/
		{"url":hayu_dianying,"pic":"icon_hydy.png","name":"电影","type":"DTVM-H"},							      	/*电影*/
		{"url":hayu_xinwen,"pic":"icon_hyxw.png","name":"新闻","type":"DTVM-H"},								        /*新闻*/
		{"url":hayu_wenyi,"pic":"icon_hyzy.png","name":"文艺","type":"DTVM-H"}								        	/*文艺*/
	],

	[/*全景新疆*/
		{"url":dmxj_index,"pic":"icon_dmxj.png","name":"大美新疆","type":"DTVM-H"},							        /*大美新疆*/
		{"url":zjxj_index,"pic":"icon_zjxj.png","name":"走进新疆","type":"DTVM-H"},								       /*走进新疆*/										
		{"url":xjny_index,"pic":"icon_xjny.png","name":"新疆农业","type":"DTVM-H"},							        /*新疆农业*/
		{"url":xjqfj_index,"pic":"icon_xjqfj.png","name":"新疆七坊街","type":"DTVM-H"},							    /*新疆七坊街*/							
		{"url":tsyd_index,"pic":"icon_tsyd.png","name":"天山阅读","type":"DTVM-H"},								      /*天山阅读*/										
		{"url":tsshy_index,"pic":"icon_tsshy.png","name":"天山书画院","type":"DTVM-H"},							    /*天山书画院*/
		{"url":hswz_index,"pic":"icon_hswz.png","name":"红山问政","type":"DTVM-H"},								      /*红山问政*/						
		{"url":msxj_index,"pic":"icon_msxj.png","name":"美食新疆","type":"DTVM-H"}							        /*美食新疆*/							
	],

	[/*智慧新疆*/
		{"url":zxmh_index,"pic":"icon_zxmh.png","name":"资讯门户","type":"DTVM-H"},						/*资讯门户*/
		{"url":cfxj_index,"pic":"icon_cfxj.png","name":"财富新疆","type":"DTVM-H"},					          	/*财富新疆*/
		{"url":gkcx_index,"pic":"icon_gkcx.png","name":"高考查询","type":"DTVM-H"},					          	/*高考查询*/
		{"url":sdwx_index,"pic":"icon_sdwx.png","name":"盛大文学","lockPos":11,"type":"DTVM-H"},	      /*盛大文学*/	
		{"url":jtwz_index,"pic":"icon_wzcx.png","name":"违章查询","type":"DTVM-H"},											/*违章查询*/
		{"url":jzfw_index,"pic":"icon_jzxx.png","name":"家政服务","type":"DTVM-H"},									    /*家政服务*/
		{"url":hbcx_index,"pic":"icon_hbcx.png","name":"航班查询","type":"DTVM-H"},				          		/*航班查询*/
		{"url":zpxx_index,"pic":"icon_zpxx.png","name":"招聘信息","type":"DTVM-H"},						      		/*招聘信息*/
		{"url":bmxx_index,"pic":"icon_bmxx.png","name":"便民信息","type":"DTVM-H"},								      /*便民信息*/
		{"url":stock_index,"pic":"icon_dpzs.png","name":"大盘走势","type":"DTVM-H"},              			/*大盘走势*/
		{"url":cpzx_index,"pic":"icon_cpzx.png","name":"彩票中心","type":"DTVM-H"},		                	/*彩票中心*/	
		{"url":weather_index,"pic":"icon_tqyb.png","name":"天气预报","type":"DTVM-H"},					    		/*天气预报*/
		{"url":sinatvnews_index,"pic":"icon_ttxw.png","name":"头条新闻","type":"DTVM-H"},			  				/*头条新闻*/
		{"url":gegu_index,"pic":"icon_ggydt.png","name":"个股一点通","type":"DTVM-H"},					    	/*个股一点通*/
		{"url":mingren_index,"pic":"icon_mrmy.png","name":"名人名言录","type":"DTVM-H"},								/*名人名言录*/	
		{"url":lishi_index,"pic":"icon_ls.png","name":"历史上的今天","type":"DTVM-H"},					 			/*历史上的今天*/	
		{"url":minghua_url,"pic":"icon_zgmhxs.png","name":"中国名画欣赏","type":"DTVM-H"},								/*中国名画欣赏*/	
		{"url":ershisijieqi_index,"pic":"icon_essjq.png","name":"24节气","type":"DTVM-H"}		/*24节气*/
		
	],
	[/*云商城*/ 
		{"url":"","pic":"icon_dssc.png","name":"电视商城","type":"DTVM-H"},										            /*电视商城*/
		{"url":"","pic":"icon_shenghuo.png","name":"生活","type":"DTVM-H"},											          /*生活*/
		{"url":"","pic":"icon_shishang.png","name":"时尚","type":"DTVM-H"},											  	      /*时尚*/
		{"url":"","pic":"icon_jiadian.png","name":"家电","type":"DTVM-H"},												        /*家电*/
		{"url":"","pic":"icon_muying.png","name":"母婴","type":"DTVM-H"},												          /*母婴*/
		{"url":"","pic":"icon_zonghe.png","name":"综合","type":"DTVM-H"},										              /*综合*/
        {"url":"","pic":"icon_zkzq.png","name":"折扣专区","type":"DTVM-H"} 												      	/*折扣专区*/
	],

	[/*云娱乐*/
		{"url":sdyx_index,"pic":"icon_sdyx.png","name":"盛大游戏","lockPos":9,"type":"DTVM-H"},						 /*盛大游戏*/
		{"url":xwyx_index,"pic":"icon_xwyx.png","name":"希沃游戏","lockPos":10,"type":"DTVM-H"},           /*希沃游戏*/
		{"url":tgyx_index,"pic":"icon_dgyx.png","name":"动感游戏","type":"DTVM-H"},									       /*动感游戏*/
		{"url":jiuc_index,"pic":"icon_jcyx.png","name":"九城游戏","type":"DTVM-H"},								        /*九城游戏*/
		{"url":jcdm_index,"pic":"icon_jcdm.png","name":"九城动漫","type":"DTVM-H"},					    	  		  /*九城动漫*/	
  	{"url":kalaok_index,"pic":"icon_klok.png","name":"卡拉OK","type":"DTVM-H"},								       	/*卡拉OK*/
		{"url":caihong_index,"pic":"icon_chyx.png","name":"彩虹音乐","type":"DTVM-H"}						  		   /*彩虹音乐*/
	],

		
	
	[/*云应用*/
		{"url":zhonghang_index,"pic":"icon_jgyh.png","name":"中行家居银行","type":"DTVM-H"},						/*中行家居银行*/
		{"url":yysd_index,"pic":"icon_yysd.png","name":"应用商店","type":"DTVM-H"},							  	    /*应用商店*/
		{"url":"ui://localDisk.htm","pic":"icon_USBbf.png","name":"USB播放","type":"DTVM-H"},						/*USB播放*/
		{"url":jiliangzhuanhuan_index,"pic":"icon_jlzh.png","name":"计量转换","type":"DTVM-H"},					/*计量转换*/	
		{"url":hanyuzidian_index,"pic":"icon_hyzd.png","name":"汉语字典","type":"DTVM-H"},							/*汉语字典*/					
		{"url":chebiao_index,"pic":"icon_cbdq.png","name":"车标大全","type":"DTVM-H"},									/*车标大全*/
		{"url":calendar_index,"pic":"icon_dsrl.png","name":"电视日历","type":"DTVM-H"},					  			/*电视日历*/
		{"url":kexuejisuanqi_index,"pic":"icon_jsq.png","name":"计算器","type":"DTVM-H"}						        	/*计算器*/	
	],
	
	[/*营业厅*/
		{"url":"ui://system/systemSetIndex.htm","pic":"icon_xtsz.png","name":"系统设置","type":"DTVM-H"},/*系统设置*/
		{"url":"ui://system/channelManager.htm","pic":"icon_pdsz.png","name":"频道设置","type":"DTVM-H"},/*频道设置*/
		{"url":"ui://system/epg.htm","pic":"icon_jmzn.png","name":"节目指南","type":"DTVM-H"},					 /*节目指南*/
		{"url":"ui://emailList.htm","pic":"icon_smxx.png","name":"市民信箱","type":"DTVM-H"},						/*市民信箱*/
		{"url":dianshi_cx,"pic":"icon_dsyyt.png","name":"电视营业厅","type":"DTVM-H"},			       		         	/*电视营业厅*/
		{"url":dianshi_jf,"pic":"icon_dsjf.png","name":"电视缴费","type":"DTVM-H"},				     			   	/*电视缴费*/
		{"url":txf_jn,"pic":"icon_hfjn.png","name":"通讯费缴纳","type":"DTVM-H"},								        /*通讯费缴纳*/
		{"url":xyk_hk,"pic":"icon_xykhd.png","name":"信用卡还款","type":"DTVM-H"},								    	/*信用卡还款*/  
		
		{"url":dianshi_dg,"pic":"icon_cpdg.png","name":"产品订购","type":"DTVM-H"},							       	/*产品订购*/
		{"url":"ui://system/kidsLock.htm","pic":"icon_ets.png","name":"儿童锁","type":"DTVM-H"}				   /*儿童锁*/
	]
];