<html>
<head>
<title>Service Web 服务</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="alternate" type="text/xml" href="/service.asmx?disco" />
<style type="text/css">    
    body { color: #000000; background-color: white; font-family: verdana; margin-left: 0px; margin-top: 0px; }
    #content { margin-left: 30px; font-size: .70em; padding-bottom: 2em; }
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
</style>
</head>
 
<body>
    <div id="content"> 
      <p class="heading1">Service</p><br>
      <span>
          <h3>获取inject数据</h3>          
          若要使用 HTTP POST 协议对操作进行测试，请单击“调用”按钮。
          <form target="_blank" action='/inject/inject' method="POST">
					<table cellspacing="0" cellpadding="4" frame="box" bordercolor="#dcdcdc" rules="none" style="border-collapse: collapse;">
                        <tr>
							<td class="frmHeader" background="#dcdcdc" style="border-right: 2px solid white;">参数</td>
							<td class="frmHeader" background="#dcdcdc">值</td>
						</tr>
                        <tr>
                            <td class="frmText" style="color: #000000; font-weight: normal;">xmlString:</td>
                            <td><!--<input class="frmInput" type="text" size="50" name="xmlString">-->
                            <textarea name="xmlString" cols="100" rows="10" id="xmlString"></textarea>
                            </td>
                        </tr>                        
                        <tr>
                          <td></td>
                          <td align="right"> <input type="submit" value="调用" class="button"></td>
                        </tr>
                        </table> 
          </form>
      </span>
	</div>
</body>
</html>