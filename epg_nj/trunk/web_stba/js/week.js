var MyUtil=function(){ 
    /***
     * 获得当前时间
     */ 
    this.getCurrentDate=function(){ 
        return new Date(); 
    }; 
    /***
     * 获得本周日期
     */ 
    this.getCurrentWeek=function(){ 
        var startStop=new Array(); //一周日期数组  
        var currentDate=this.getCurrentDate(); //获取当前时间  
        var week=currentDate.getDay(); //返回date是一周中的某一天  
        var month=currentDate.getDate(); //返回date是一个月中的某一天  
        var millisecond=1000*60*60*24; //一天的毫秒数
        var minusDay=week!=0?week-1:6; //减去的天数 
        var monday=new Date(currentDate.getTime()-(minusDay*millisecond));  //本周 周一  
		var Tuesday=new Date(monday.getTime()+(1*millisecond));
		var Wednesday=new Date(monday.getTime()+(2*millisecond));
		var Thursday=new Date(monday.getTime()+(3*millisecond));
		var Friday =new Date(monday.getTime()+(4*millisecond));
		var Saturday =new Date(monday.getTime()+(5*millisecond));
        var sunday=new Date(monday.getTime()+(6*millisecond)); 
        //添加本周时间  
        startStop.push(monday);//本周起始时间  
		startStop.push(Tuesday);//本周起始时间  
		startStop.push(Wednesday);//本周起始时间  
		startStop.push(Thursday);//本周起始时间  
		startStop.push(Friday);//本周起始时间  
		startStop.push(Saturday);//本周起始时间  
        startStop.push(sunday);//本周终止时间  
        return startStop; 
    }; 
    /**
     * 获得上一周的日期
     * **/ 
    this.getPreviousWeek=function(){ 
        var startStop=new Array(); //起止日期数组  
        var currentDate=this.getCurrentDate(); //获取当前时间  
        var week=currentDate.getDay();  //返回date是一周中的某一天  
        var month=currentDate.getDate(); //返回date是一个月中的某一天  
        var millisecond=1000*60*60*24; //一天的毫秒数  
        var minusDay=week!=0?week-1:6; //减去的天数  
        var currentWeekDayOne=new Date(currentDate.getTime()-(millisecond*minusDay)); //获得当前周的第一天  
        var priorWeekLastDay=new Date(currentWeekDayOne.getTime()-millisecond);  //上周周日即本周开始的前一天  
        var priorWeekFirstDay1=new Date(priorWeekLastDay.getTime()-(millisecond*6)); //上周周一
		var priorWeekFirstDay2=new Date(priorWeekLastDay.getTime()-(millisecond*5)); //上周周二 
		var priorWeekFirstDay3=new Date(priorWeekLastDay.getTime()-(millisecond*4)); //上周周三
		var priorWeekFirstDay4=new Date(priorWeekLastDay.getTime()-(millisecond*3)); //上周周四
		var priorWeekFirstDay5=new Date(priorWeekLastDay.getTime()-(millisecond*2)); //上周周五
		var priorWeekFirstDay6=new Date(priorWeekLastDay.getTime()-(millisecond*1)); //上周周六
        //添加至数组  
        startStop.push(priorWeekFirstDay1); 
		startStop.push(priorWeekFirstDay2); 
		startStop.push(priorWeekFirstDay3); 
		startStop.push(priorWeekFirstDay4); 
		startStop.push(priorWeekFirstDay5); 
		startStop.push(priorWeekFirstDay6); 
        startStop.push(priorWeekLastDay); 
        return startStop; 
    }; 
    /**
     * 获得自今天开始前6天的日期（一周节目用）
     * **/ 
    this.getSevenDays=function(){ 
        var startStop=new Array(); //起止日期数组  
        var currentDate=this.getCurrentDate(); //获取当前时间    
        var millisecond=1000*60*60*24; //一天的毫秒数  

        var priorWeekFirstDay1=new Date(currentDate.getTime()-(millisecond*6));      //当前日期-6
		var priorWeekFirstDay2=new Date(currentDate.getTime()-(millisecond*5));      //当前日期-5
		var priorWeekFirstDay3=new Date(currentDate.getTime()-(millisecond*4));      //当前日期-4
		var priorWeekFirstDay4=new Date(currentDate.getTime()-(millisecond*3));      //当前日期-3
		var priorWeekFirstDay5=new Date(currentDate.getTime()-(millisecond*2));      //当前日期-2
		var priorWeekFirstDay6=new Date(currentDate.getTime()-(millisecond*1));      //当前日期-1
        var priorWeekLastDay=new Date(currentDate.getTime());                        //当前日期
        //添加至数组  
        startStop.push(priorWeekFirstDay1); 
		startStop.push(priorWeekFirstDay2); 
		startStop.push(priorWeekFirstDay3); 
		startStop.push(priorWeekFirstDay4); 
		startStop.push(priorWeekFirstDay5); 
		startStop.push(priorWeekFirstDay6); 
        startStop.push(priorWeekLastDay); 
        return startStop; 
    }; 
}; 