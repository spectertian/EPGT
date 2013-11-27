<script language="javascript">

$(document).ready(function(){


	//数据灌入到全局的js当中  Edit by  tianzhongsheng-ex@huan.tv Time 2013-01-17 14:46:00
	$.ajax({
		type:"post",
		url: '/category_recommends/edit',
		data: {ids:'<?php echo $ids;?>'},
		success: function(msg){
					//alert(msg);
					var encoded = jQuery.parseJSON( msg );
					//alert(encoded);
					$.each(encoded,function(key,val)
							{
								//alert(key);
								stcokrow[key] = key;
								stockarr[key] = new Array();
								$.each(val,function(k,v)
								{
									//alert(k);
									//alert(v['column']);
									stockarr[key][v['column']] = v['column'];
									var replace_stcok_data = {};	//保存数据中介
									$.each(v,function(s,w)
									{
										replace_stcok_data[s] = w;

									});
									//数据存储
									stcokdata.push(replace_stcok_data);

								});

							});
				},
		});
	//wike下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-01-24 11:11:00
    $('#channel_name').simpleAutoComplete('<?php echo url_for('category_recommends/loadChannel') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'channel_name',
        max       : 20
    }, function (date){
        var date = eval("("+date+")");
        var id = date.id;
		var img = date.img;
		var imgurl = date.imgurl;
        $('#channel_id').attr('value',id);
        $('#channel_img').attr('value',img);
        $('#channel_imgurl').attr('value',imgurl);
    });


    //vod下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-01-24 13:20:00
    $('#vod_name').simpleAutoComplete('<?php echo url_for('category_recommends/loadWiki') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'wiki_title',
        max       : 20
    },function (date){
        var date = eval("("+date+")");
        var id = date.id;
		var img = date.img;
		var imgurl = date.imgurl;
        $('#vod_id').attr('value',id);
        $('#vod_img').attr('value',img);
        $('#vod_imgurl').attr('value',imgurl);
    });

    //theme下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-01-24 13:20:00
    $('#theme_name').simpleAutoComplete('<?php echo url_for('category_recommends/loadTheme') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'theme_name',
        max       : 20
    },function (date){
        var date = eval("("+date+")");
        var id = date.id;
		var img = date.img;
		var imgurl = date.imgurl;
        $('#theme_id').attr('value',id);
        $('#theme_img').attr('value',img);
        $('#theme_imgurl').attr('value',imgurl);
    });


    //ad下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-01-24 13:20:00
    $('#ad_name').simpleAutoComplete('<?php echo url_for('category_recommends/loadAd') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'ad_name',
        max       : 20
    },function (date){
        var date = eval("("+date+")");
        var id = date.id;
		var img = date.img;
		var imgurl = date.imgurl;
		var url = date.url;
		
        $('#ad_id').attr('value',id);
        $('#ad_img').attr('value',img);
        $('#ad_imgurl').attr('value',imgurl);
        $('#ad_url').attr('value',url);
    });
    //shortmovie_package下拉列表 Modify by tianzhongsheng-ex@huan.tv Time 2013-02-20 16:19:00
    $('#shortmovie_package_name').simpleAutoComplete('<?php echo url_for('category_recommends/loadShortMoviePackage') ?>',{
        autoCompleteClassName: 'autocomplete',
        autoFill: false,
        selectedClassName: 'sel',
        attrCallBack: 'rel',
        identifier: 'short_name',
        max       : 20
    },function (date){
        var date = eval("("+date+")");
        var id = date.id;
		var img = date.img;
		var imgurl = date.imgurl;
        $('#shortmovie_package_id').attr('value',id);
        $('#shortmovie_package_img').attr('value',img);
        $('#shortmovie_package_imgurl').attr('value',imgurl);
        
    });

    $('.datepicker_s').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });

    $('.datepicker_e').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0,
        model:false
    });
    
});

</script>
	<?php include_partial('global/flashes') ?> 
	<div id="warp">
    
      <div class="r">
            	<header>
                    <h2 class="content"><?php echo $PageTitle; ?>:<?php echo $name; ?></h2>
                    <nav class="utility">
                    	<li class="add"><a href="#" class="toolbar" onclick = "adds();">添加</a></li>
                        <li class="save"><a href="#" onclick ="dataoutbegin();" class="toolbar">保存</a></li>
                        <li class="back"><a href="<?php echo url_for("category_recommends/list");?>">返回列表</a></li>
                    </nav>
                </header>
				<?php include_partial('global/flashes') ?>
				<div id="stock">
					<?php $re =array(); ?>
					<?php if($templates):?>
						<?php foreach($templates as $k => $v):?>
								<div class="listwarp" id="<?php echo 'stock_'.$k; ?>"><ul class="list" id="<?php echo 'stock_'.$k.'s'; ?>">
							<?php foreach ($v as $k1 => $v1 ):?>
								<?php $nodename = 'stock_'.$v1['row'].'_'.$v1['column'] ?>
									<li class="mvcover"  id="<?php echo $nodename; ?>" >
									<?php $repalce_mode = $skip_url[$v1['mode']]; ?>
										<a href="<?php echo $repalce_mode['0'],$v1[$repalce_mode['1']]; ?>" target="_blank" >
											<img src="<?php echo file_url($v1['img']); ?>" alt="<?php echo $v['name']; ?>">
										</a>
									位置:(<?php echo $v1['row'].'_'.$v1['column']; ?>)<?php echo $v1['name']; ?><a href="#" class="deldiv" name="deldiv"  onclick="modifydiv('<?php echo  $nodename; ?>');" >删除</a></li>
							<?php endforeach; ?>
								</ul></div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
		<div id="div_2" style="display: none">
		<!--  <div id="div_2" style="display: none">-->
		<form name="" method="get" id="" class="listitem" action="">
		<input type ="hidden" name="ids"  id="ids"  value="<?php echo $ids; ?>" />
        	<ul>
                <li>
                	<h2>数据保存</h2>
                </li>
				<li><label>类型:</label>
				<select id='out_category' ">
				<option value="defulit">请选择类型</option>
					<?php 
						foreach ($classesArray as $k=>$v)
						{
							echo "<option value=\"{$k}\"";
							 if($k==$category)
								{
								 	echo 'selected ="true"' ;
								}
							echo ">{$v}</option>";
						}
					?>
				</select>
                </li>
                <li id="out_list" ><label>名称:</label>
					<input name="out_name" id="out_name"  value="<?php echo $name;?>" type="out_name">(*必填*)
				</li>
				<li id="out_list" ><label>选择时间</label>
					<input type="button" name="start_time"  maxlengtjh="10"  value="<?php echo $start_time; ?>" class="datepicker_s">&nbsp;——&nbsp;
					<input type="button" name="end_time"   maxlengtjh="10"  value="<?php echo $end_time; ?>" class="datepicker_e">&nbsp;&nbsp;<a href='#' onclick='cleardate()'>重置</a>
				</li>
				<li><label>是否默认:</label>
				<select id='is_default' ">
					<option value="yes" 
					<?php 
						 if($is_default == 1)
						{
						 	echo 'selected ="true" ';
						}
					?>
					>是</option>
					<option value="no"
					<?php 
						 if($is_default != 1)
						{
						 	echo 'selected ="true" ';
						}
					?>
					>否</option>
				</select>
                </li>
				
                <li id="list_button_2" ><input type="button" value="保存" class="btn" onclick = "dataout('<?php echo url_for("category_recommends/getdata");?>');" /><input type="reset" value="重置"  class="btn"/></li>
           		<li><label ><a href="#" onclick= "closediv('div_2')"><font color="red">关闭页面</font></a></label></li>
            </ul>
            
        </form>
		</div>
        <div id="div_1" style="display: none">
        <form name="" method="get" id="" class="listitem" action="">
        	<ul>
            	<li>
                	<h2>创建新的模板</h2>
                </li>
                <li><label>模型:</label>
				<select id='classes' onchange ="module_change();">
				<option value="defulit">请选择模型</option>
					<?php 
						foreach ($moldArray as $k=>$v)
						{
							echo "<option value=\"{$k}\">".$v."</option>";
						}
					?>
				</select>
                </li>
                <li>
                	<label >位置(行):</label><input type="text" name="row" value="1" id="row">
                </li>
                <li>
                	<label>位置(列):</label><input type="text" name="column" value="1" id="column">
                </li>
                <li id="list_channel" style="display: none"><label>节目名称:</label>
					<input name="channel_name" id="channel_name"  value="" type="text">
					<input name="channel_id" id="channel_id"  value="" type="hidden">
					<input name="channel_img" id="channel_img"  value="" type="hidden">
					<input name="channel_imgurl" id="channel_imgurl"  value="" type="hidden">
				</li>
				<li id="list_vod" style="display: none"><label>点播名称:</label>
					<input name="vod_name" id="vod_name"  value="" type="text">
					<input name="vod_id" id="vod_id"  value="" type="hidden">
					<input name="vod_img" id="vod_img"  value="" type="hidden">
					<input name="vod_imgurl" id="vod_imgurl"  value="" type="hidden">
				</li>
				<li id="list_theme" style="display: none"><label>主题名称:</label>
					<input name="theme_name" id="theme_name"  value="" type="text">
					<input name="theme_id" id="theme_id"  value="" type="hidden">
					<input name="theme_img" id="theme_img"  value="" type="hidden">
					<input name="theme_imgurl" id="theme_imgurl"  value="" type="hidden">
				</li>
				<li id="list_ad" style="display: none"><label>广告名称:</label>
					<input name="ad_name" id="ad_name"  value="" type="text">
					<input name="ad_id" id="ad_id"  value="" type="hidden">
					<input name="ad_img" id="ad_img"  value="" type="hidden">
					<input name="ad_imgurl" id="ad_imgurl"  value="" type="hidden">
					<input name="ad_url" id="ad_url"  value="" type="hidden">
				</li>
				<li id="list_shortmovie_package" style="display: none"><label>短视频包名称:</label>
					<input name="shortmovie_package_name" id="shortmovie_package_name"  value="" type="text">
					<input name="shortmovie_package_id" id="shortmovie_package_id"  value="" type="hidden">
					<input name="shortmovie_package_img" id="shortmovie_package_img"  value="" type="hidden">
					<input name="shortmovie_package_mgurl" id="shortmovie_package_imgurl"  value="" type="hidden">
				</li>
                <li id="list_button" style="display: none"><input type="button" value="添加" class="btn" onclick="adddiv();" /><input type="reset" value="重置" class="btn"/></li>
                <li><label ><a href="#" onclick= "closediv('div_1')"><font color="red">关闭页面</font></a></label></li>
           		
            </ul>
            
        </form>
		</div>
    </div>

<script>

</script>