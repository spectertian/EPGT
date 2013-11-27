/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$('body').everyTime('1s','A',function(){
    var hour_int    = Number(hour_str);
    var min_int     = Number(min_str);
    var sce_int     = Number(sce_str);

    sce_int  = sce_int + 1;

    //秒钟部分
    if (sce_int >= 60) {
       sce_int = 0;
       min_int = min_int + 1;
    }
    sce_str = sce_int;

    
    //分钟部分
    if (min_int == 60){
        min_str   = '00';
        hour_int  = hour_int + 1 ;
    }else if(min_int < 10) {
        min_str = '0' + min_int;
    }else{
        min_str = min_int;
    }
    //小时部分
    if(hour_int == 24){
        hour_str = '00';
    }else if(hour_int < 10) {
        hour_str = '0' + hour_int;
    }else{
        hour_str = hour_int;
    }
    str = hour_str + ":" + min_str;
    $(".time").html(str);
});


