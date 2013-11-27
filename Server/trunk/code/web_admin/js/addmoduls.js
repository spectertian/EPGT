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
    
    
    //弹出层
	function adds(programs )
	{
		if ($("#div_1").css("display") == "none")
		{
		
			var test =  $("#"+programs).val();
			var xx = $("#"+programs).attr("value")
			alert(xx);
			var ll = "#"+programs;
			alert(ll);
			alert(test);
	        return
	        
	        $.each(test,function(key,val)
					{
						alert('key:'+key)
						alert('val:'+val)
					});
	        
	        return;
			$("#programs_list_id").val(programs);
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
		
		var id = $("#ids").val();
		var date = $("#out_date").val();
		var template = JSON.stringify(stcokdata);
		var state   = $("#is_default").val();
		if(date =='' || template == '' || state == '')
		{
			alert('内容不能为空');
			return;
		}
//		alert(template);
		
		
		$.ajax({
			type:"post",
			url: geturl,
			data: {date:date,item:template,state:state},
			success: function(msg){
//						alert(msg);
						if(msg == 'true')
						{
							alert('存储成功');
							location.reload();
							//window.location.href='/yesterday_program/index';
						}else if(msg == 'false')
						{
							alert('存储失败');
							$("#div_2").hide();
						}else
						{
							location.reload();
							//window.location.href='/yesterday_program/index';
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
		
    	
    	
    	var recommended_language = $("#recommended_language").val();	//推荐语
        var video_url = $("#video_url").val();	//播放的url
        var programs_list_id = $("#programs_list_id").val();	//保存的节目列表id

        var test =  $("#"+programs_list_id).val();
        
        
        $.each(test,function(key,val)
				{
					alert('key:'+key)
					alert('val:'+val)
				});
        
        
        
        
        return;	//测试
        var row = $("#row").val();	//行
        var column = $("#column").val();	//列
       	var rownodename = 'stock_'+row;
        var nodename = 'stock_'+row+'_'+column;

		replace_stcok_data['row'] = row;
		replace_stcok_data['column'] = column;

		var vod_id = $("#vod_id").val();
		var vod_name = $("#vod_name").val();
		
		//	图片判断
		var size   = $("#is_size").val();
		if(size == 'wide')
		{
			var vod_img = $("#vod_img_screenshots").val();
			var vod_imgurl = $("#vod_imgurl_screenshotsurl").val();
		}
		
		if(size == 'petty')
		{
			var vod_img = $("#vod_img").val();
			var vod_imgurl = $("#vod_imgurl").val();
		}
		
		
		if(vod_name == '' || column == '' || row == '')
		{
			alert('内容不能为空');
			return;
		}
		
		replace_stcok_data['wiki_id'] = vod_id;
		replace_stcok_data['name'] = vod_name;
		replace_stcok_data['img'] = vod_img;
		replace_stcok_data['img_size'] = size;
		
		var title = vod_name;
		var img = vod_imgurl;
		
		//用于图片的链接跳转
		var img_skip = "/wiki/edit/id/"+vod_id;
			
		
		
		
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