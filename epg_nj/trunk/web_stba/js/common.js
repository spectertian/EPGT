function jsonToString(obj) {  
    var THIS = this;  
    switch (typeof (obj)) {  
        case 'string':  
            return '"' + obj.replace(/(["\\])/g, '\\$1') + '"';  
        case 'array':  
            return '[' + obj.map(THIS.jsonToString).join(',') + ']';  
        case 'object':  
            if (obj instanceof Array) {  
                var strArr = [];  
                var len = obj.length;  
                for (var i = 0; i < len; i++) {  
                    strArr.push(THIS.jsonToString(obj[i]));  
                }  
                return '[' + strArr.join(',') + ']';  
            }  
            else if (obj == null) {  
                return 'null';  
  
            }  
            else {  
                var string = [];  
                for (var property in obj)  
                    string.push(THIS.jsonToString(property) + ':' + THIS.jsonToString(obj[property]));  
                return '{' + string.join(',') + '}';  
            }  
        case 'number':  
            return obj;  
        case false:  
            return obj;  
    }  
} 

function percentAge(starttime,endtime)
{
    var timestamp = Math.ceil(new Date().getTime()/1000);
    var all = endtime-starttime;
	var plan = timestamp-starttime;
    var num = Math.ceil((plan/all).toFixed(2)*100);    
	return num > 100 ? 100 : num;
}

function stringToJSON(obj) {     
    return eval('(' + obj + ')');  
}

//@2012-11-05新增，字符串截取，中文、英文、字符等
//@author gaobo
function setStringCut(str,len,suffix)
{
	var strlen = 0; 
	var s = "";
	var stringlength = str.length;
	if(stringlength <= len){
		return str;
	}
	for(var i = 0;i < stringlength;i++){
		if(str.charCodeAt(i) > 128){
			strlen += 2;
		}else{ 
			strlen++;
		}
		s += str.charAt(i);
		if(strlen >= len){ 
			return s+suffix ;
		}
	}
	//return s;
} 

jLim.extend({
    VK_LEFT: 0x25,
    VK_UP: 0x26,
    VK_RIGHT: 0x27,
    VK_DOWN: 0x28,
    VK_0: 0x30,
    VK_1: 0x31,
    VK_2: 0x32,
    VK_3: 0x33,
    VK_4: 0x34,
    VK_5: 0x35,
    VK_6: 0x36,
    VK_7: 0x37,
    VK_8: 0x38,
    VK_9: 0x39,  
});

(function(window,$) {
    defaults = {speed: 10, step: 20, width: 150};
    $.fn.animateNav = function(options) {
        var navTimer , navDiv = $(this);
        var flag = true;
        var opts = $.extend({}, defaults, options);
        this.each(function(){ 
            var ext = $(this);
            var ext_num = ext.find("li").length;
            this.first = ext.find("a").get(0);
            this.index = 0;
                      
            this.moveRight = function() {
                var left = parseInt(ext.style("left"));
                if(left >  (0 - opts.width + opts.step)) {
                    left = left - opts.step;
                    ext.style("left",left+"px");
                }else{   
                    flag = true;
                    clearInterval(navTimer);
                    //clone当前节点到最后
                    var ext_first = ext.find("li").eq(0);//.clone();                           
                    ext.append(ext_first); 
                        
                    //ext.find("li").eq(0).remove();
                    if(typeof(opts.scroll) == "object") {
                        ext.textScroll(opts.scroll);
                    }
                    ext.style("left",0);
                    
                    //this.test();                    
                }               
            };
            
            this.moveLeft = function() {
                var left = parseInt(ext.style("left"));
                if(left < (0 - opts.step)) {
                    left = left + opts.step;
                    ext.style("left",left+"px");
                }else{
                    flag = true;
                    clearInterval(navTimer);
                    //ext.find("li").eq(ext_num-1).remove();
                    if(typeof(opts.scroll) == "object") {
                        ext.textScroll(opts.scroll);
                    }
                    ext.style("left",0);
                    //this.test();
                }
            };
            
            this.test = function() {
                var txt = '';
                ext.find("li").each(function(){
                    txt += $(this).find("a").html()+" ";
                });
                alert(txt);
            };
            
            this.keyDown = function(evt) {                                                   
                var _self = this;
                var evtcode = evt.which ? evt.which : evt.code;
                if(!flag) return false;
                clearInterval(navTimer);                
                switch (evtcode) {
                    case 0x27:    
                    case 31:    //点播传过来的右键
                        //移除当前焦点
                        var ext_cura = ext.find("a").eq(0);
                        ext_cura.removeClass("there");
                        ext_cura.removeAttr("href"); 
                        //激活下一个节点激活
                        var ext_secnd = ext.find("a").eq(1);
                        ext_secnd.attr("href",ext_secnd.attr("title"));
                        ext_secnd.addClass("there");
                        ext_secnd[0].focus();
                        ext_secnd.keydown(function(evt) { _self.keyDown(evt);});
                        flag = false;
                        navTimer = setInterval(function(){_self.moveRight()},opts.speed);
                        evt.preventDefault();
                        break;		
                    case 0x25: 
                    case 30:   //点播传过来的左键
                        //移除当前焦点
                        var ext_cura = ext.find("a").eq(0);
                        ext_cura.removeClass("there");
                        ext_cura.removeAttr("href"); 
                        //clone最后一个节点到最前面
                        var ext_last = ext.find("li").eq(ext_num-1);                        
                        //ext.prepend(ext_last.clone());
                        ext.prepend(ext_last);
                        //激活上一个节点
                        var ext_lasta = ext.find("a").eq(0);
                        ext_lasta.addClass("there"); //lfc后加的
                        ext_lasta.attr("href",ext_lasta.attr("title"));
                        ext_lasta[0].focus();
                        ext_lasta.keydown(function(evt) { _self.keyDown(evt);});
                        ext.style("left","-"+opts.width+"px");
                        flag = false;
                        navTimer = setInterval(function(){_self.moveLeft()},opts.speed);
                        evt.preventDefault();
                        break;
                }
            };
            
            this.init = function() {                                    
                var _self = this;
                ext.style("left",0);
                ext.find("a").each(function(i){                    
                    $(this).attr("title",$(this).attr("href"));
                    if(i > 0) {
                        $(this).removeAttr("href");
                    }                    
                });
                $(this.first).keydown(function(evt) { _self.keyDown(evt);}); 
                if(typeof(opts.scroll) == "object") {
                    ext.textScroll(opts.scroll);
                }
            }
            
            this.init();
        });
    }; 
})(window, jLim);

(function(window,$) {    
    defaults = {elem: "b", strnum: 8, strbold: 20, width: 208, step: 2, speed: 200};
    $.fn.textScroll = function(options) {
        var opts = $.extend({}, defaults, options);
        var navTimer, ln = 0;
        this.find("a").each(function(){ 
            var ext = $(this); 
            var ext_elem = ext.find(opts.elem).eq(0);
            var txtNum = ext_elem.html().length;
            var txtWidth = txtNum * opts.strbold;
            ext_elem.style("left",0);          
            this.textMove = function(elem) {  
                var _self = this; 
                var left = parseInt(ext_elem.style("left"));
                if(left > -txtWidth) {
                    ext_elem.style("left",left-opts.step+"px");
                }else{
                    ext_elem.style("left",opts.width+"px");
                }
                //$("#test").html(txtWidth + "" + left);
            }
            $(this).mouseover(function(){ 
                var _self = this;
                if(txtNum > opts.strnum) {
                    navTimer = setInterval(function(){_self.textMove();},opts.speed);
                }
            });
            $(this).mouseout(function(){ 
                var _self = this;
                ext_elem.style("left",0);
                clearInterval(navTimer);
            });
        });
    };
})(window, jLim);

Date.prototype.format = function(format){
    var o = {
      "M+" :  this.getMonth()+1,  //month
      "d+" :  this.getDate(),     //day
      "h+" :  this.getHours()<10?"0"+this.getHours():this.getHours(),    //hour
      "m+" :  this.getMinutes()<10?"0"+this.getMinutes():this.getMinutes(),  //minute
      "s+" :  this.getSeconds()<10?"0"+this.getSeconds():this.getSeconds(), //second
      "q+" :  Math.floor((this.getMonth()+3)/3),  //quarter
      "S"  :  this.getMilliseconds() //millisecond
    }
 
    if(/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
 
    for(var k in o) {
        if(new RegExp("("+ k +")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
        }
    }
    return format;
}