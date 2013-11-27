$(document).ready(function() {

	// settings notice
	setTimeout(function(){$('.global-notice').fadeOut('slow')},3000);
    
    // feed-act-del
	$('.act-comment .act-del a, .act-queue .act-del a, .act-like .act-del a, .act-dislike .act-del a, .act-watched .act-del a').click(function (){
		$(this).parents('.feed-bd li').css({ 'background': '#f3f3f3' }).fadeOut('slow');
	});
	$('.reply-list .act-del a').click(function (){
		$(this).parents('.reply-list li').fadeOut('slow');
	});
    
    // disabled signup
	$("#ButtonAcceptTerms").attr("disabled", "disabled").addClass('disabled');
	$("#CheckBoxTerms").click(function() {
		var checked_status = this.checked;
		if (checked_status == true) {
			$("#ButtonAcceptTerms").removeAttr("disabled").removeClass('disabled');
		}
		else {
			$("#ButtonAcceptTerms").attr("disabled", "disabled").addClass('disabled');
		}
	});
    
    // global-nav
    $('.menu').hover(function (){
        $(this).children('.menu-bd').show();
    },function (){
        $(this).children('.menu-bd').hide();
    });

    $('#login-dialog .close a').click(function (){
        $(this).parents('#login-dialog').hide();
    });
    // switch-location
    $(document).click(function() {
        $('.switch-location-bd').hide();
    });
    $('.switch-location-hd a').click(function(event) {
        $('.switch-location-bd').toggle();
        event.stopPropagation();
    });
    $('.switch-location-bd').click(function(event) {
        $('.switch-location-bd').show();
        event.stopPropagation();
    });
    $('.switch-location-bd .close a').click(function(event) {
        $(this).parents('.switch-location-bd').hide();
        event.stopPropagation();
    });

    $('#search-button').bind('click', function() {
       var form = $(this).parent();
       var q = $.trim($('input[name=q]', form).val());
       if (q.length == 0 || q == '搜索') {
           return;
       } else {
           form.submit();
       }
    });
});

//js 判断一个字符串值是否存在某个数组中
function inArray(obj, arr){
    if (typeof obj == 'string') {
        for(var i in arr) {
            if(arr[i] == obj) {
                return true
            }
        }
    }
    return false;
}


//登陆框
function loginDialogStatus() {
    var dialog = $('#login-dialog');
    if (dialog.attr('id') == "login-dialog") {
        dialog.show();
        return false;
    }
    return true;
}

function toolTiper(element, tipTop, tipLeft, tipRight) {
    var element = (typeof element == 'object')  ? element : $(element);
    var delayTime = [];
    var toolTip = $('#tooltip');
    var arrowImage = $('.arrow-img');
    element.each(function(index) {
        $(this).hover(function() {
            var top = $(this).offset().top;
            var left = $(this).offset().left;
            var slug = $(this).attr("slug");
            var time = $(this).attr("time")
            delayTime[index] = setTimeout(function() {
                if ((left + tipLeft + toolTip.width()) < $(document).width()) {
                    arrowImage.css({'background-position': '0 0', 'left': '8px'});
                    toolTip.css({'top':top + tipTop, 'left':left + tipLeft});
                } else {
                    toolTip.css({'top':top + tipTop, 'left':left - tipRight} );
                    arrowImage.css({'background-position': '-8px 0', 'left': '457px'});
                }
                loadWiki(slug, time);
                toolTip.show();
            }, 800);

        }, function() {
            clearTimeout(delayTime[index]);
            toolTip.hide();
        })
    });

    toolTip.hover(function (){
        $(this).show();
    },function (){
        $(this).hide();
        $('#wiki-info').html('<div class="tooltip-hd"><h3></h3></div><div class="loading"><div class="loading-tip">载入中 ...</div></div>');
    });
}

// jquery.gotop.js
(function($){
    var goToTopTime;
    $.fn.goToTop=function(options){
            var opts = $.extend({},$.fn.goToTop.def,options);
            var $window=$(window);
            $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            $(this).hide();
            var $this=$(this);
            clearTimeout(goToTopTime);
            goToTopTime=setTimeout(function(){
                    var controlLeft;
                    if ($window.width() > opts.pageHeightJg * 2 + opts.pageWidth) {
                            controlLeft = ($window.width() - opts.pageWidth) / 2 + opts.pageWidth + opts.pageWidthJg;
                    }else{
                            controlLeft = $window.width()- opts.pageWidthJg-$this.width();
                    }
                    var cssfixedsupport=$.browser.msie && parseFloat($.browser.version) < 7;
                    var controlTop=$window.height() - $this.height()-opts.pageHeightJg;
                    controlTop=cssfixedsupport ? $window.scrollTop() + controlTop : controlTop;
                    var shouldvisible=( $window.scrollTop() >= opts.startline )? true : false;

                    if (shouldvisible){
                            $this.stop().show(opts.showBtntime);
                    }else{
                            $this.stop().hide(opts.showBtntime);
                    }
                    $this.css({
                            position: cssfixedsupport ? 'absolute' : 'fixed',
                            top: controlTop,
                            right: 20
                    });
            },500);
            $(this).click(function(event){
                    $body.stop().animate( {scrollTop: $(opts.targetObg).offset().top}, opts.duration);
                    $(this).blur();
                    event.preventDefault();
                    event.stopPropagation();
            });
    };
    $.fn.goToTop.def={
            pageWidth: 980,
            pageWidthJg: 20,
            pageHeightJg: 30,
            startline: 200,
            duration: 200,
            showBtntime: 100,
            targetObg: "body"
    };
})(jQuery);

$(function(){
    $('<a href="javascript:void(0)" class="go-top">回到顶部</a>').appendTo("body");
    $('.go-top').goToTop({});
    $(window).bind('scroll resize',function(){
        $('.go-top').goToTop({});
    });
});

// jquery.tipsy.js
(function($) {
    $.fn.tipsy = function(options) {
        options = $.extend({}, $.fn.tipsy.defaults, options);
        return this.each(function() {
            var opts = $.fn.tipsy.elementOptions(this, options);
            $(this).hover(function() {
                $.data(this, 'cancel.tipsy', true);
                var tip = $.data(this, 'active.tipsy');
                if (!tip) {
                    tip = $('<div class="tipsy"><div class="tipsy-inner"/></div>');
                    tip.css({position: 'absolute', zIndex: 100000});
                    $.data(this, 'active.tipsy', tip);
                }
                if ($(this).attr('title') || typeof($(this).attr('original-title')) != 'string') {
                    $(this).attr('original-title', $(this).attr('title') || '').removeAttr('title');
                }
                var title;
                if (typeof opts.title == 'string') {
                    title = $(this).attr(opts.title == 'title' ? 'original-title' : opts.title);
                } else if (typeof opts.title == 'function') {
                    title = opts.title.call(this);
                }
                tip.find('.tipsy-inner')[opts.html ? 'html' : 'text'](title || opts.fallback);
                var pos = $.extend({}, $(this).offset(), {width: this.offsetWidth, height: this.offsetHeight});
                tip.get(0).className = 'tipsy'; // reset classname in case of dynamic gravity
                tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).appendTo(document.body);
                var actualWidth = tip[0].offsetWidth, actualHeight = tip[0].offsetHeight;
                var gravity = (typeof opts.gravity == 'function') ? opts.gravity.call(this) : opts.gravity;
                switch (gravity.charAt(0)) {
                    case 'n':
                        tip.css({top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}).addClass('tipsy-north');
                        break;
                    case 's':
                        tip.css({top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}).addClass('tipsy-south');
                        break;
                    case 'e':
                        tip.css({top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}).addClass('tipsy-east');
                        break;
                    case 'w':
                        tip.css({top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}).addClass('tipsy-west');
                        break;
                }
                if (opts.fade) {
                    tip.css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: 0.8});
                } else {
                    tip.css({visibility: 'visible'});
                }
            }, function() {
                $.data(this, 'cancel.tipsy', false);
                var self = this;
                setTimeout(function() {
                    if ($.data(this, 'cancel.tipsy')) return;
                    var tip = $.data(self, 'active.tipsy');
                    if (opts.fade) {
                        tip.stop().fadeOut(function() {$(this).remove();});
                    } else {
                        tip.remove();
                    }
                }, 100);
            });
        });
    };
    // Overwrite this method to provide options on a per-element basis.
    // For example, you could store the gravity in a 'tipsy-gravity' attribute:
    // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });
    // (remember - do not modify 'options' in place!)
    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };
    $.fn.tipsy.defaults = {
        fade: false,
        fallback: '',
        gravity: 'n',
        html: false,
        title: 'title'
    };
    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };
    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };
})(jQuery);

$(function() {
   $('.popup-tip').tipsy({fade: false, gravity: 's'});
});