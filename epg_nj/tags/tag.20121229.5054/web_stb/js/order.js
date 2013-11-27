function orderAdd(channelname,programsName,starttime,channelCode){
    try {
        var ordersCount = Orders.getOrderCount(Orders.ORDER_TYPE_EPG);
        if(ordersCount>=8){
            showTip('最多只可预约8个节目');   
        }else{        
            starttime=starttime.replace("*"," ");
            starttime=new Date(starttime);
            now=new Date();
            var year = now.getFullYear();       //年
            var month = now.getMonth() + 1;     //月
            var day = now.getDate();            //日   
            var todays=year + '/'+month+'/'+day+ ' 00:00:00';  
            var today=new Date(todays);       
            var micros=starttime.getTime()-today.getTime();
            var daynum=Math.floor(micros/(24*3600*1000));
    		for(var i = 0; i < SerList.length; i++) {
    			    var ser = SerList.getAt(i);				
    				if(ser.name ==channelname){
    				    /*
    				    programs=new Array();
                        programs0=ser.getPrograms(0); //当天节目
                        programs1=ser.getPrograms(1); //明天节目
                        programs2=ser.getPrograms(2); //后天节目
                        programs=programs.concat(programs0); //合并数组
                        programs=programs.concat(programs1); //合并数组
                        programs=programs.concat(programs2); //合并数组
                        */
                        programs=ser.getPrograms(daynum); //当天节目
                        //alert(programs.length);
                        for(var j = 0; j < programs.length; j++) {
                				//if(programs[j].name ==programsName){
                				//alert(programs[j].startTime);
                				if(starttime>=programs[j].startTime && starttime<programs[j].endTime){
                                    var location = programs[j].getLocation();
                                	var order = new Order(location);                         	
                                	var or=Orders.add(order);
                                    Orders.save();
                                    if(or==0){
                                        orderAjax(channelCode,programsName,starttime,channelname)
                                        showTip('预约成功');          
                                    }else if(or==-5){
                                        showTip('已经预约过该节目');      
                                    }else{
                                        showTip('预约失败');      
                                    }
                				}
                		}
    				}
    		} 
        }    
	}catch(err) {
		alert("没有发现中间件！");
	}   
}
function orderAjax(channel_code,programsName,starttime,channelname){
    var userId=hardware.smartCard.serialNumber;
    $.ajax({
        url: '<?php echo url_for('wiki/orderAdd')?>',
        type: 'post',
        data: {'user_id': userId,'channel_code': channel_code,'program_name':programsName,'start_time':starttime,'channel_name':channelname},
        success: function(data){
            /*
            if (data == 1) {
                alert('预约成功');
            }
            */
        }       
    });
}