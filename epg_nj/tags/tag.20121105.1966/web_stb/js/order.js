function orderAdd(channelname,programsName,starttime,channelCode){
    try {
        starttime=starttime.replace("*"," ");
        starttime=new Date(starttime);
        //alert(starttime);
		for(var i = 0; i < SerList.length; i++) {
			    var ser = SerList.getAt(i);				
				if(ser.name ==channelname){
                    programs=ser.getPrograms(0);
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
                                    alert('预约成功');
                                }else if(or==-5){
                                    alert('已经预约过该节目');
                                }else{
                                    alert('预约失败');
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