function getEPGOrders() {
  var ordersCount = Orders.getOrderCount(Orders.ORDER_TYPE_EPG);
  for(var i = 0; i < ordersCount; i++ ) {
    var order = Orders.getAt(i,Orders.ORDER_TYPE_EPG);
    //alert(order.serviceName);
    //alert(order.name);
  }
}

function checkFocus(target) {
	var obj = $(target);
	if(!obj.hasfocus) {
		obj.focus();
	}
}

function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

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
    var num= Math.ceil((plan/all).toFixed(2)*100);
	return num;
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

(function(window,$) {
  $.fn.splotNav = function(options) {
    var navList = null;
    var navIndex = 0;
    var navDiv = $(this);
    var opts = $.extend({}, $.fn.splotNav.defaults, options);
    navList = eachLi(navDiv);
    redrawNav(navDiv); 
    var ss = $(navDiv).find("a").get(0);      
    $(ss).keydown(function(evt){
      var evtcode = evt.which ? evt.which : evt.code;
      switch (evtcode) {
        case jLim.VK_RIGHT: 
          navIndex = setIndex(navIndex + 1);
          redrawNav(navDiv);
          evt.preventDefault();
          break;		
        case jLim.VK_LEFT:
          navIndex = setIndex(navIndex - 1);
          redrawNav(navDiv);
          evt.preventDefault();
          break;
      }
    });
  
    function eachLi(nav) {
      $this = $(nav).find("li");
      var eachList = [];
      $this.each(function (index) {
        var thisa = $(this).find("a").get(0);      
        eachList.push({title:$(thisa).html(),url:$(thisa).attr("href") ? $(thisa).attr("href") : $(thisa).attr("title")});
      });
      return eachList;
    };
  
    function redrawNav(div) {
      var navArr = [];
      var as = $(div).find("a");
      for(var iii = 0; iii < as.length; iii++) {
        var ii = setIndex(iii + navIndex);
        var a = $(as).get(iii);
        if(iii == 0) {
          $(a).attr("href",navList[ii].url);
          $(a).html(navList[ii].title);
        } else {
          $(a).attr("title",navList[ii].url);
          $(a).removeAttr("href");
          $(a).html(navList[ii].title);
        }
      };     
    };
  
    function setIndex(index) {
      index = index < 0 ? index + navList.length : index;
      index = index >= navList.length ? index - navList.length : index;
      return index;
    };  
  };
  
  $.fn.splotNav.defaults = {foreground: 'red',background: 'yellow'};      
})(window, jLim);

(function(window,$) {
  $.fn.spanMarq = function(options) {
    var opts = $.extend({}, $.fn.spanMarq.defaults, options);
    var timer = null;
    var cur = null;
    this.each(function() {
      $this = $(this);
      $span = $this.find("a");
      $span.each(function (){
        var aa = $(this).find("span").get(0);
        if($(aa).html().length > opts.miniLength) {
          $(this).mouseover(function() {
            $.fn.spanMarq.cur = this;
            var aa = $($.fn.spanMarq.cur).find("span").get(0);
            $.fn.spanMarq.fulltext = $(aa).html(); 
            $.fn.spanMarq.timer = setInterval("marqMoveStart()",300);
          });
          $(this).mouseout(function() {
            marqMoveStop();
          });
        }
      });     
    });     
  };
  
  $.fn.spanMarq.defaults = {speed: 5, miniLength: 7};
  $.fn.spanMarq.timer = null; 
  $.fn.spanMarq.cur = null; 
  $.fn.spanMarq.fulltext = null;
})(window, jLim);

function marqMoveStart() {
  var cur = $.fn.spanMarq.cur;
  var aa = $(cur).find("span").get(0);
  var html = $(aa).html();
  if(html.length > 1) {
    $(aa).html(html.substr(1,html.length));
  }else {
    $(aa).html($.fn.spanMarq.fulltext);
  }
};
    
function marqMoveStop() {
  var cur = $.fn.spanMarq.cur;
  var aa = $(cur).find("span").get(0);
  $(aa).html($.fn.spanMarq.fulltext);
  $.fn.spanMarq.fulltext = null;
  clearInterval($.fn.spanMarq.timer);
};

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