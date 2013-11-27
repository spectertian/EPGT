<form name='serchform' method='get' action='/spservice/index'>
<span>名称：<input id='name__' type='text' name='name' value=''></span>
<span>类型tag:
  <select id='select_id' name='type_'>
    <option value=0>全部</option>
    <option value='cctv'>cctv</option>
    <option value='tv'>tv</option>
    <option value='hd'>hd</option>
    <option value='pay'>pay</option>
    <option value='local'>local</option>
    <option value='other'>other</option>
  </select>
</span>
<span>频道code:
	<select id="haveCode" name='haveCode'>
		<option <?php if($haveCode==0):?> selected <?php endif;?> value=0>全部</option>
		<option <?php if($haveCode==1):?> selected <?php endif;?> value=1>有code</option>
		<option <?php if($haveCode==2):?> selected <?php endif;?> value=2>无code</option>
	</select>
</span>
<span>监测epg:
	<select id="epg" name="epg">
		<option <?php echo ($epg==-1) ? 'selected=selected' : ''?> value="-1">全部</option>
		<option <?php echo ($epg==1) ? 'selected=selected' : ''?> value="1">监测中</option>
		<option <?php echo ($epg=='0') ? 'selected=selected' : ''?> value="0">未监测</option>
	</select>
</span>
<span>监测回看:
	<select id="epgbak" name="epgbak">
		<option <?php echo ($epgbak==-1) ? 'selected=selected' : ''?> value="-1">全部</option>
		<option <?php echo ($epgbak==1) ? 'selected=selected' : ''?> value="1">监测中</option>
		<option <?php echo ($epgbak=='0') ? 'selected=selected' : ''?> value="0">未监测</option>
	</select>
</span>
<span><input type='submit' value='查询'></span><!--<a href='javascript:void(0);' onclick='getremove();'>条件重置</a>-->
</form>