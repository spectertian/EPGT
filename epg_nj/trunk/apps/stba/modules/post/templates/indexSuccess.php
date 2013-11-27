<html>
<head>
<title>Service Web 服务</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="alternate" type="text/xml" href="/service.asmx?disco" />
<style type="text/css">    
    body { color: #000000; background-color: white; font-family: verdana; margin-left: 0px; margin-top: 0px; }
    #content { margin-left: 30px; font-size: 14px; padding-bottom: 2em; }
    a:link { color: #336699; font-weight: bold; text-decoration: underline; }
    a:visited { color: #6699cc; font-weight: bold; text-decoration: underline; }
    a:active { color: #336699; font-weight: bold; text-decoration: underline; }
    a:hover { color: cc3300; font-weight: bold; text-decoration: underline; }
    p { color: #000000; margin-top: 0px; margin-bottom: 12px; font-family: verdana; }
    pre { background-color: #e5e5cc; padding: 5px; font-family: courier new; font-size: x-small; margin-top: -5px; border: 1px #f0f0e0 solid; }
    td { color: #000000; font-family: verdana; font-size: .7em; }
    h2 { font-size: 1.5em; font-weight: bold; margin-top: 25px; margin-bottom: 10px; border-top: 1px solid #003366; margin-left: -15px; color: #003366; }
    h3 { font-size: 1.1em; color: #000000; margin-left: -15px; margin-top: 10px; margin-bottom: 10px; }
    ul { margin-top: 10px; margin-left: 20px; }
    ol { margin-top: 10px; margin-left: 20px; }
    li { margin-top: 10px; color: #000000; }
    font.value { color: darkblue; font: bold; }
    font.key { color: darkgreen; font: bold; }
    font.error { color: darkred; font: bold; }
    .heading1 { color: #ffffff; font-family: tahoma; font-size: 26px; font-weight: normal; background-color: #003366; margin-top: 0px; margin-bottom: 0px; margin-left: -30px; padding-top: 10px; padding-bottom: 3px; padding-left: 15px; width: 105%; }
    .button { background-color: #dcdcdc; font-family: verdana; font-size: 1em; border-top: #cccccc 1px solid; border-bottom: #666666 1px solid; border-left: #cccccc 1px solid; border-right: #666666 1px solid; }
    .frmheader { color: #000000; background: #dcdcdc; font-family: verdana; font-size: .7em; font-weight: normal; border-bottom: 1px solid #dcdcdc; padding-top: 2px; padding-bottom: 2px; }
    .frmtext { font-family: verdana; font-size: .7em; margin-top: 8px; margin-bottom: 0px; margin-left: 32px; }
    .frminput { font-family: verdana; font-size: 1em; }
    .intro { margin-left: -15px; }   
    b{font-size:14px;}        
</style>
</head>
 
<body>
    <div id="content"> 
      <p class="heading1">Service</p><br>
      <span>
          <h3>测试</h3>          
          将下面相关地址粘贴到输入框，然后单击调用即可。
          <form target="_blank" action='/post/index' method="POST">
					<table cellspacing="0" cellpadding="4" frame="box" bordercolor="#dcdcdc" rules="none" style="border-collapse: collapse;">
                        <tr>
							<td class="frmHeader" background="#dcdcdc" style="border-right: 2px solid white;">参数</td>
							<td class="frmHeader" background="#dcdcdc">值</td>
						</tr>
                        <tr>
                            <td class="frmText" style="color: #000000; font-weight: normal;">xmlString:</td>
                            <td><!--<input class="frmInput" type="text" size="50" name="xmlString">-->
                            <textarea name="xmlString" cols="100" rows="5" id="xmlString"></textarea>
                            </td>
                        </tr>                        
                        <tr>
                          <td></td>
                          <td align="right"> <input type="submit" value="调用"></td>
                        </tr>
                        </table> 
          </form>
          <span>
              <h3>各推荐系统网址</h3>
              <p style="font-size: 10px;">
  <font color="#ff0000">tcl</font><br />
  <b>VOD个性化推荐</b><br />
  <?php echo sfConfig::get('app_recommend_tclUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=6&uid=825010288699921&backurl=
  <br />
  <b>VOD热播</b><br />
  <?php echo sfConfig::get('app_recommend_tclUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.hotitem.v1&ctype=vod&period=monthly&count=10&backurl=
  <br />
  <b>VOD分类推荐</b><br />
  <?php echo sfConfig::get('app_recommend_tclUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=6&uid=825010288699921&genre=Movie
  <br />
  <b>EPG分类推荐</b><br />
  <?php echo sfConfig::get('app_recommend_tclUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=6&uid=825010288699921&genre=Movie
  <br />
  <b>关联推荐</b><br />
  <?php echo sfConfig::get('app_recommend_tclUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.corelation.v1&ctype=vod&count=6&cid=5237cc9c6dbde14a75cf9679&backurl=
  <br />
  <font color="#ff0000">运营中心</font>
  <br />
  <b>VOD个性化推荐</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&uid=99666611230068607_0&lang=zh&urltype=1&alg=CF&backurl=
  <br />
<b>VOD热播</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.toprating.v1&ctype=vod&postertype=1&count=10&uid=99666611230068607_0&lang=zh&urltype=1&user_weight=0.4&optr_weight=0.6&backurl=
  <br />
<b>VOD分类推荐（电视剧）</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&lang=zh&uid=99666611230068607_0&urltype=1&alg=RK&filter=Category6%3D%27%E7%94%B5%E8%A7%86%E5%89%A7%27&backurl=
  <br />
<b>VOD分类推荐（电影）</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&lang=zh&uid=99666611230068607_0&urltype=1&alg=RK&filter=Category6%3D%27%E7%94%B5%E5%BD%B1%27&backurl=
  <br />
<b>VOD分类推荐（体育）</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&lang=zh&uid=99666611230068607_0&urltype=1&alg=RK&filter=Category6%3D%27%E4%BD%93%E8%82%B2%27&backurl=
  <br />
<b>VOD分类推荐（综艺）</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&lang=zh&uid=99666611230068607_0&urltype=1&alg=RK&filter=Category6%3D%27%E7%BB%BC%E8%89%BA%27&backurl=
  <br />
<b>VOD分类推荐（卡通）</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&lang=zh&uid=99666611230068607_0&urltype=1&alg=RK&filter=Category6%3D%27%E5%8A%A8%E6%BC%AB%27&backurl=
  <br />
<b>VOD分类推荐（文化）</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&lang=zh&uid=99666611230068607_0&urltype=1&alg=RK&filter=Category6%3D%27%E6%96%87%E5%8C%96%27&backurl=
  <br />
<b>VOD分类推荐（综合）</b><br />
  <?php echo sfConfig::get('app_recommend_centerUrl');?>?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count=6&lang=zh&uid=99666611230068607_0&urltype=1&alg=RK&filter=Category6%3D%27%E6%96%B0%E9%97%BB%E6%97%B6%E7%A7%BB%27&backurl=
  <br />
  <font color="#ff0000">技术部</font>
  <br /><b>VOD个性化推荐</b><br />
  <?php echo sfConfig::get('app_recommend_tongzhouUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=6&uid=825010288699921&backurl=
  <br /><b>VOD热播</b><br />
  <?php echo sfConfig::get('app_recommend_tongzhouUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.hotitem.v1&ctype=vod&count=10&uid=825010288699921&backurl=
  <br /><b>VOD分类推荐</b><br />
  <?php echo sfConfig::get('app_recommend_tongzhouUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=vod&count=6&uid=825010288699921&genre=Movie&backurl=
  <br /><b>EPG分类推荐</b><br />
  <?php echo sfConfig::get('app_recommend_tongzhouUrl');?>?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=6&uid=825010288699921&genre=Movie&backurl=
  <br />
  <br />
  
              </p>

          </span>  
      </span>
	</div>
</body>
</html>