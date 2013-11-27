/**
 * 用于模块添加 
 * Add by tianzhongsheng-ex@huan.tv
 * Time 2013-01-21 15:54:00
 */
    var stockarr = new Array();	//定义全局数组
    var stcokrow = new Array();	//用于行的区分
    var stcokdata = new Array();	//保存数据
    
    //关闭层
    function closediv(getdiv)
    {
    	$('#div_1').hide();
    	$('#div_2').hide();
    	return;
    }
    
    
    //初始化时间
    function cleardate()
    {
    	$('.datepicker_s').val('起始日期');
    	$('.datepicker_e').val('结束日期');
    	return;
    }
    function module_change()
    {
    	var module = $("#classes").val();	//类型
//    	alert(module);
    	if(module == 'prgrom')
    	{
//    		alert(module);
    		$("#list_channel").show();
    		$("#list_button").show();
    		
    		$("#list_vod").hide();
    		$("#list_theme").hide();
    		$("#list_ad").hide();
    		$("#list_shortmovie_package").hide();
    		return;
    	}
    	if(module == 'vod')
    	{
    		$("#list_vod").show();
    		$("#list_button").show();
    		
    		$("#list_channel").hide();
    		$("#list_theme").hide();
    		$("#list_ad").hide();
    		$("#list_shortmovie_package").hide();
    		return;
    		
    	}
    	if(module == 'theme')
    	{
    		$("#list_theme").show();
    		$("#list_button").show();
    		
    		$("#list_channel").hide();
    		$("#list_vod").hide();
    		$("#list_ad").hide();
    		$("#list_shortmovie_package").hide();
    		return;
    	}
    	
    	if(module == 'ad')
    	{
    		$("#list_ad").show();
    		$("#list_button").show();
    		
    		$("#list_channel").hide();
    		$("#list_vod").hide();
    		$("#list_theme").hide();
    		$("#list_shortmovie_package").hide();
    		return;
    	}

    	if(module == 'shortmovie_package')
    	{
    		$("#list_shortmovie_package").show();
    		$("#list_button").show();
    		
    		$("#list_ad").hide();
    		$("#list_channel").hide();
    		$("#list_vod").hide();
    		$("#list_theme").hide();
    		return;	
    	}	
    		return;
    }
    
    //弹出层
	function adds( )
	{
		if ($("#div_1").css("display") == "none")
		{
            $("#div_1").show();
            return;
        }
		
	}
	
	//
	function dataoutbegin()
	{
		$("#div_1").hide();
		$("#div_2").show();
		return;
	}
	
	//
	function cannel()
	{
		$("#div_1").hide();
		$("#div_2").hide();
		return;
	}

    //数据保存
	function dataout(geturl)
	{
		
		var start_time = $('.datepicker_s').val();
		var end_time   = $('.datepicker_e').val();
		var is_default   = $("#is_default").val();	
		var id = $("#ids").val();

		
		if(start_time=='起始日期' || end_time=='结束日期')
		{
			alert('请选择时间');
			return;
		}
		if(start_time > end_time)
		{
			alert('开始时间大于结束时间');
			return;
		}
		
		
		var name = $("#out_name").val();
		var category = $("#out_category").val();
		var template = JSON.stringify(stcokdata);
		if(category == undefined || category == 'defulit')
        {
        	alert('请选择类型');
        	return;
        }
		//if(name =='' || template == '' || is_default == '')	需求更改后，模板可以为空 Modify by tianzhongsheng-ex@huan.tv Time 2013-03-18 10:08:00
		if(name =='' || is_default == '')
		{
			alert('内容不能为空');
			return;
		}
//		alert(template);
		
		
		$.ajax({
			type:"post",
			url: geturl,
			data: {name:name,category:category,template:template,start_time:start_time,end_time:end_time,is_default:is_default,id:id},
			success: function(msg){
//						alert(msg);
						if(msg == 'true')
						{
							alert('存储成功');
							window.location.href='/category_recommends/list';
						}else if(msg == 'false')
						{
							alert('存储失败');
							$("#div_2").hide();
						}else
						{
							window.location.href='/category_recommends/edit/id/'+msg;
						}
						
					},
			});
		
	}

	//动态删除div
    function modifydiv(divname)
    {
    	if(confirm("是否删除？"))
		{
    		var div_arr = divname.split("_");
    		var k1 = div_arr['1'];
    		var k2 = div_arr['2'];
    		stockarr[k1][k2] = '';
    		$('#'+divname).remove();
    		$.each(stcokdata,function(key,val){
    			if(val['row']==k1 && val['column']==k2)
    			{
    				stcokdata[key] = '';
    				return;
    			}
    		});
    		return true;
   		}
        return;
	}
	
	//动态添加div
    function adddiv()
    {
    	
    	$("#div_1").hide();	//隐藏弹出层

    	var replace_stcok_data = {};	//保存数据中介
		
    	var classes = $("#classes").val();	//类型
        var row = $("#row").val();	//行
        var column = $("#column").val();	//列
       	var rownodename = 'stock_'+row;
        var nodename = 'stock_'+row+'_'+column;

		replace_stcok_data['mode'] = classes;
		replace_stcok_data['row'] = row;
		replace_stcok_data['column'] = column;
		
        if(classes == undefined || classes == 'defulit')
        {
        	alert('请选择模型');
        	return;
        }
		
		if(classes == 'prgrom')
		{
			var channel_id = $("#channel_id").val();
			var channel_name = $("#channel_name").val();
			var channel_img = $("#channel_img").val();
			var channel_imgurl = $("#channel_imgurl").val();
			
			replace_stcok_data['channel_code'] = channel_id;
			replace_stcok_data['name'] = channel_name;
			replace_stcok_data['img'] = channel_img;
			
			var title = channel_name;
			var img = channel_imgurl;
		}
		if(classes == 'vod')
		{
			var vod_id = $("#vod_id").val();
			var vod_name = $("#vod_name").val();
			var vod_img = $("#vod_img").val();
			var vod_imgurl = $("#vod_imgurl").val();
			
			replace_stcok_data['wiki_id'] = vod_id;
			replace_stcok_data['name'] = vod_name;
			replace_stcok_data['img'] = vod_img;
			
			var title = vod_name;
			var img = vod_imgurl;
			
			//用于图片的链接跳转
			var img_skip = "/wiki/edit/id/"+vod_id;
			
		}
		if(classes == 'theme')
		{
			var theme_id = $("#theme_id").val();
			var theme_name = $("#theme_name").val();
			var theme_img = $("#theme_img").val();
			var theme_imgurl = $("#theme_imgurl").val();
			
			replace_stcok_data['theme_id'] = theme_id;
			replace_stcok_data['name'] = theme_name;
			replace_stcok_data['img'] = theme_img;
			
			var title = theme_name;
			var img = theme_imgurl;
			
			//用于图片的链接跳转
			var img_skip = "/theme/edit/id/"+theme_id;
		}
		if(classes == 'ad')
		{
			var ad_id = $("#ad_id").val();
			var ad_name = $("#ad_name").val();
			var ad_img = $("#ad_img").val();
			var ad_url = $("#ad_url").val();
			var ad_imgurl = $("#ad_imgurl").val();
			
			replace_stcok_data['ad_id'] = ad_id;
			replace_stcok_data['name'] = ad_name;
			replace_stcok_data['img'] = ad_img;
			replace_stcok_data['ad_url'] = ad_url;
			
			var title = ad_name;
			var img = ad_imgurl;
			
			//用于图片的链接跳转
			var img_skip = "/simple_ad/edit/id/"+ad_id;
		}
		if(classes == 'shortmovie_package')
		{
			var shortmovie_package_id = $("#shortmovie_package_id").val();
			var shortmovie_package_name = $("#shortmovie_package_name").val();
			var shortmovie_package_img = $("#shortmovie_package_img").val();
			var shortmovie_package_imgurl = $("#shortmovie_package_imgurl").val();
			
			replace_stcok_data['shortmovie_package_id'] = shortmovie_package_id;
			replace_stcok_data['name'] = shortmovie_package_name;
			replace_stcok_data['img'] = shortmovie_package_img;
			
			var title = shortmovie_package_name;
			var img = shortmovie_package_imgurl;
			
			//用于图片的链接跳转
			var img_skip = "/shortmovie_package/edit/id/"+shortmovie_package_id;
		}
		
		//内容不能为空
		if(replace_stcok_data['name'] == '')
		{
			alert('内容不能为空');
			return ;
		}
		
        //判断是否需要创建行
		if($('#'+rownodename).length<1)
        {
			
			if(stcokrow.length<1)
			{
				stcokrow[row] = row;
				$("#stock").prepend('<div class="listwarp" id="'+rownodename+'" ><ul class="list" id="'+rownodename+'s"></ul></div>');
				stockarr[row] = new Array();	//创建二维数组
			}else
			{
				stcokrow[row] = row;
				var noemptyrowarr = new Array();
				$.each(stcokrow,function(key,val)
				{
					if(this != "[object Window]")
					{
						noemptyrowarr.push(val);
					}

				});
				
				//获取当前row的key
				var keyrow = 0;
				$.each(noemptyrowarr,function(key,val)
				{

					if(val == row )
					{
						keyrow = key;
					}

				});

				var rowamin = noemptyrowarr[keyrow-1];
				var rowmax = noemptyrowarr[keyrow+1];
				if(rowamin != undefined)
				{	
					var newrowname = 'stock_'+rowamin ;
					$('#'+newrowname).after('<div class="listwarp" id="'+rownodename+'" ><ul class="list" id="'+rownodename+'s"></ul></div>');
					stockarr[row] = new Array();	//创建二维数组
					//return;
				}
				if(rowmax != undefined)
				{	
					var newrowname = 'stock_'+rowmax ;
					$('#'+newrowname).before('<div class="listwarp" id="'+rownodename+'" ><ul class="list" id="'+rownodename+'s"></ul></div>');
					stockarr[row] = new Array();	//创建二维数组
					//return;
				}
			}
			
		}
		
        //判断节点是否存在
		if($('#'+nodename).length>0)
        {

	        alert('位置已存在，另换位置');
	        return;
	        
		}

		if(stockarr[row]=='' || column == 1)
		{
			stockarr[row][column] = column;
			$('#'+rownodename+'s').prepend('<li class="mvcover" id="'+nodename+'" ><a href="'+img_skip+'" target="_blank"><img src="'+img+'" alt="'+title+'"></a>位置:('+row+'_'+column+')'+title+'<a href="#" class="deldiv" name="deldiv"  onclick="modifydiv(\''+nodename+'\');" >删除</a></li>');
			
			
			//数据存储
			stcokdata.push(replace_stcok_data);
			return;
		}

		stockarr[row][column] = column;
		
		//数组去空
		var noemptyarr = new Array();
		$.each(stockarr[row],function(key,val)
		{
			if(this != "[object Window]")
			{
				noemptyarr.push(val);
			}

		});
		
		//获取当前clomun的key
		var keys = 0;
		$.each(noemptyarr,function(key,val)
		{
			if(val == column )
			{
				keys = key;
			}

		});
		

		var replamin = noemptyarr[keys-1];
		var replamax = noemptyarr[keys+1];

		if(replamin != undefined)
		{	
			var newnodename = 'stock_'+row+'_'+replamin ;
			$('#'+newnodename).after('<li class="mvcover" id="'+nodename+'" ><a href="'+img_skip+'" target="_blank"><img src="'+img+'" alt="'+title+'"></a>位置:('+row+'_'+column+')'+title+'<a href="#" class="deldiv" name="deldiv"  onclick="modifydiv(\''+nodename+'\');" >删除</a></li>');
			//数据存储
			stcokdata.push(replace_stcok_data);
			return;
		}
		if(replamax != undefined)
		{
			var newnodename = 'stock_'+row+'_'+replamax ;
			$('#'+newnodename).before('<li class="mvcover" id="'+nodename+'" ><a href="'+img_skip+'" target="_blank"><img src="'+img+'" alt="'+title+'"></a>位置:('+row+'_'+column+')'+title+'<a href="#" class="deldiv" name="deldiv"  onclick="modifydiv(\''+nodename+'\');" >删除</a></li>');
			//数据存储
			stcokdata.push(replace_stcok_data);
			return;
		}

    }