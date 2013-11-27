//********************文字滚动
var inter=0,ln=0,inter1=0,ln1=0;
function _textScroll(tag,num) { 
    var step=arguments[2]?arguments[2]:3;
	return function() 
	{ 
		textScroll(tag,num,step); 
	} 
} 

function textScroll(tag,num){
    var step=arguments[2]?arguments[2]:3;
    textnum=tag.html().length;
	kuan=26*(textnum-num+1);
    //ln=parseInt(tag.css("left"));
    if(ln<-kuan){
        clearInterval(inter);
        tag.css("left",0);
        ln=0;
    }else{
        if(textnum>num){
            ln=ln-step;
            tag.css("left",ln);
        }
	}
}

function _textScroll1(tag,num) { 
    var step=arguments[2]?arguments[2]:3;
	return function() 
	{ 
		textScroll1(tag,num,step); 
	} 
} 

function textScroll1(tag,num){
    var step=arguments[2]?arguments[2]:3;
    textnum=tag.html().length;
	kuan=26*(textnum-num+1);
    //ln=parseInt(tag.css("left"));
    if(ln1<-kuan){
        clearInterval(inter1);
        tag.css("left",0);
        ln1=0;
    }else{
        if(textnum>num){
            ln1=ln1-step;
            tag.css("left",ln1);
        }
	}
}
//注意findsub，是第三个参数
function scroll(object,num){
    var findsub=arguments[2]?arguments[2]:"b";   
    var step=arguments[3]?arguments[3]:3;   
    object.find("a").each(function(){
		$(this).mouseover(function(){
			inter=setInterval(_textScroll($(this).find(findsub),num,step),10);
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
    var step=arguments[2]?arguments[2]:3;
    this.find("a").each(function(){
		$(this).mouseover(function(){
			inter=setInterval(_textScroll($(this).find(findsub),num,step),10);
		})
		$(this).mouseout(function(){
			clearInterval(inter);
            $(this).find(findsub).css("left",0);
			ln=0;
		})
	})
};
//和函数scroll(object,num)的功能一样
$.fn.scroll1 = function(findsub,num) {
    var step=arguments[2]?arguments[2]:3;
    this.find("a").each(function(){
		$(this).mouseover(function(){
			inter1=setInterval(_textScroll1($(this).find(findsub),num,step),10);
		})
		$(this).mouseout(function(){
			clearInterval(inter1);
            $(this).find(findsub).css("left",0);
			ln1=0;
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