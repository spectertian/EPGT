// JavaScript Document

$(function(){
	$('.playnow li:odd').addClass('odd');
	$('.playnow li:even').addClass('even');
	$('.tvchoice li:odd').addClass('odd');
	$('.jjlists .t').eq(0).find('h3').hide().end().find('ol').show();
		
	$('.jjlists h3 a').each(function(){
		$(this).click(function(){
			$(this).parent().hide('slow').end().parent().siblings().show('slow');
		});
	});
		
	$('nav h1 a').click(function(){
		$('.choicecity').slideDown('slow');	
	});
	$('.choicecity a').each(function(){
		$(this).click(function(){
			$('nav h1 a').text($(this).text());
			$('.choicecity').slideUp('slow');	
		});	
	});
	
	$('.mvjj').hide().eq(0).show();
	var $tabsa=$('.tabs a');
	$tabsa.click(function(){
		$(this).addClass('there').siblings().removeClass("there");
		var $tag=$tabsa.index(this);
		$('.mvjj').eq($tag).show().siblings().hide();
        scroll_introduction.refresh();
	});
	
	$('.menu li').each(function(){
		var $a=$(this);
		tabs($a);
	});
	
	$('.choicecover li').eq(0).addClass('there');
	$('.choicecover li').each(function(){
		var $a=$(this);
		$a.click(function(){
			var $src=$a.find('img').attr('src');
			$('.covers').attr('src',$src);
		});
		tabs($a);
	});
	
	function tabs($a){
		$a.click(function(){
			$a.addClass('there').siblings().removeClass('there');
		});	
	}
	
	showpic($left,$a);
});


var $left=parseInt($('.showlist ul').css('left'));
var $a=0;
function showpic(){
	$a=$a+1;
	var $width=$('.showlist a').width();
	if($a<6){
		$('.showlist ul').css('left',$a*-$width);
		$('.showlist p b').removeClass('there').eq($a).addClass('there');
	}else{
		$('.showlist ul').css('left',0);
		$a=0;
		$('.showlist p b').removeClass('there').eq($a).addClass('there');
	}
	$('.showlist a').mouseover(function(){
		clearTimeout($go);
	});
	$('.showlist a').mouseout(function(){
		$go();
	});
	$go=setTimeout("showpic()", 2000);
}


//滚动代码
var scroll1, scroll2;
function loaded() {
	scroll1 = new iScroll('standard',{ hScroll:false });
	scroll2 = new iScroll('transition', { useTransition:true, vScrollbar: false});
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 500); }, false);