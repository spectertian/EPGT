//********************文字滚动
var inter,ln=0;
function _textScroll(tag,num) { 
	return function() 
	{ 
		textScroll(tag,num); 
	} 
} 

function textScroll(tag,num){
    textnum=tag.html().length;
	kuan=20*textnum;
    if(ln<-kuan)
    {
        //clearInterval(inter);
        tag.css("left",0);
        ln=0;
    }else{
        if(textnum>num){
            tag.css("left",ln--);
        }
	}
}
//注意findsub，是第三个参数
function scroll(object,num){
    var findsub=arguments[2]?arguments[2]:"b";   
    object.find("a").each(function(){
		$(this).mouseover(function(){
			inter=setInterval(_textScroll($(this).find(findsub),num),10);
		})
		$(this).mouseout(function(){
			clearInterval(inter);
			$(this).find(findsub).css("left",0);
			ln=0;
		})
	})
}
//和函数scroll(object,num)的功能一样
$.fn.scroll = function(findsub,num) {
    this.find("a").each(function(){
		$(this).mouseover(function(){
			inter=setInterval(_textScroll($(this).find(findsub),num),10);
		})
		$(this).mouseout(function(){
			clearInterval(inter);
            $(this).find(findsub).css("left",0);
			ln=0;
		})
	})
};
//********************图层滚动
function scrollSingle(object){
    object.find("li").each().mouseover(function(){
        num=object.find("li").length-1;
		$(this).keydown(function(event){
			if(event.keyCode==0x25){
				var $last=object.find("li").eq(num);
                object.prepend($last);
                object.find("a")[0].focus();
                event.preventDefault();
			}
			if(event.keyCode==0x27){
				var $last=object.find("li").eq(0);
                object.append($last);
                object.find("a")[0].focus();
                event.preventDefault();
			}
		});
    });
}
//和函数scrollSingle(object,num)的功能一样
$.fn.scrollul = function(object) {
    this.find("li").each().mouseover(function(){
        //object.find("a")[0].focus();  //防止焦点跑到其他位置
        num=object.find("li").length-1;
		this.onkeydown=function(event){  //用$(this).keydown()方法会出错
			if(event.keyCode==37){  //左键
				var $last=object.find("li").eq(num);  //之前写法
                object.prepend($last);                //之前写法
                /*js写法
    			var $last=object.find("li")[0][num];
    			object.find("li")[0].parentNode.insertBefore($last,object.find("li")[0]);
                */
                object.find("a")[0].focus();
                event.preventDefault();
                //return false;
			};
			if(event.keyCode==39){  //右键
				var $last=object.find("li").eq(0);  //之前写法
                object.append($last);               //之前写法
                /*js写法
                object[0].appendChild(object.find("li")[0]);
                */
                object.find("a")[0].focus();
                event.preventDefault();
                //return false;
			}
		};
    });   
};