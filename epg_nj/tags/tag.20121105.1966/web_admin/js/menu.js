/**
* @version		$Id: menu.js 9765 2007-12-30 08:21:02Z ircmaxell $
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * JMenu javascript behavior
 *
 * @author		Johan Janssens <johan.janssens@joomla.org>
 * @package		Joomla
 * @since		1.5
 * @version     1.0
 */
var JMenu = {
	initialize: function(el)
	{
		var elements = $('li', el);
		var nested = null
		for (var i=0; i<elements.length; i++)
		{
			var element = elements[i];

			// element.addEvent('mouseover', function(){ this.addClass('hover'); });
			// element.addEvent('mouseout', function(){ this.removeClass('hover'); });
			$(element).hover(function() {$(this).addClass('hover')}, function() {$(this).removeClass('hover')});

			//find nested UL
			nested = $('ul', element)[0];
			if(!nested) {
				continue;
			}

			//declare width
			var offsetWidth  = 0;

			//find longest child
			for (k=0; k < nested.childNodes.length; k++) {
				var node  = nested.childNodes[k]
				if (node.nodeName == "LI")
					offsetWidth = (offsetWidth >= node.offsetWidth) ? offsetWidth :  node.offsetWidth;
			}

			//match longest child
			for (l=0; l < nested.childNodes.length; l++) {
				var node = nested.childNodes[l]
				if (node.nodeName == "LI") {
					$(node).css('width', offsetWidth+'px');
				}
			}

			$(nested).css('width', offsetWidth+'px');
		}
	}
}
var click   = 1;
function noticeShow(msg)
{
    var html    = ("#system-message");
    if(click == 1)
    {
        click += 1;
        var str = noticeHtml(msg);
        $("#toolbar-box").append(str);
    }
    else
    {
        $("#show_msg").html(msg);
        $("#system-message").show();

    }

    $('#system-message').css({'position' : 'absolute','width':'300px','text-align':'center'});
    var winTop = $(window).scrollTop();
    var winLeft = $(window).scrollLeft();
    var winWidth = $(window).width();
    var winHeight = $(window).height();
    var alertHeight = $("#system-message").height();
    var alertWidth = $("#system-message").width();
    var top = winTop + winHeight/2 - alertHeight/2;
    var left = winLeft + winWidth/2 - alertWidth/2;
    $('#system-message').css( { 'top' : top ,'left' : left } );
    
    window.setTimeout(function()
    {
        noticeHide();
    },1500);
}

function noticeHide()
{
    $("#system-message").hide();
}

function noticeHtml(msg)
{
    var str = '';
    str += '<dl id="system-message">';
    str += '    <dt class="notice">Message</dt>';
    str += '    <dd class="notice fade">';
    str += '        <ul>';
    str += '            <li id="show_msg">' +msg+ '</li>';
    str += '        </ul>';
    str += '    </dd>';
    str += '</dl>';
    return str;
}

$(document).ready(function(){
    $('FORM[name=adminForm]').submit(function(){
        return false;
    });
});

