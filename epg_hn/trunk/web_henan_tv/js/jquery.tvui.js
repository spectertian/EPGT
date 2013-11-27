(function($, undefined){

    $.tvui = $.tvui || {};
    if ( $.tvui.version ) {
        return;
    }  
    
    if(navigator.userAgent.toLowerCase().indexOf("chrome") >= 0){
        $.extend( $.tvui, {
            version: "0.1",
            keyCode: {
                ENTER: 13,
                OK: 13,
                BACKSPACE: 8,
                DELETE: 8,
                DOWN: 40,
                UP: 38,
                RIGHT: 39,
                LEFT: 37,
                QUICK: 9,
                ZOOM: 27,
                VOLUME_UP: 114,
                VOLUME_DOWN: 115,
                IME: 17,
                NUMPAD_ONE: 49,
                NUMPAD_TWO: 50,
                NUMPAD_THERE: 51,
                NUMPAD_FOUR: 52,
                NUMPAD_FIVE: 53,
                NUMPAD_SIX: 54,
                NUMPAD_SEVEN: 55,
                NUMPAD_EIGHT: 56,
                NUMPAD_NINE: 57,
                NUMPAD_ZERO: 48,
                MENU: 113,
                F2: 113
            },            
            current: null
        });
    }else {
        $.extend( $.tvui, {  //河南moto机顶盒键值
            version: "0.1",
            keyCode: {
                ENTER: 65293,
                OK: 65293,
                BACKSPACE: 65367,
                DELETE: 8,
                DOWN: 65364,
                UP: 65362,
                RIGHT: 65363,
                LEFT: 65361,
                QUICK: 9,
                ZOOM: 27,
                VOLUME_UP: 63561,
                VOLUME_DOWN: 63562,
                IME: 17,
                NUMPAD_ONE: 49,
                NUMPAD_TWO: 50,
                NUMPAD_THERE: 51,
                NUMPAD_FOUR: 52,
                NUMPAD_FIVE: 53,
                NUMPAD_SIX: 54,
                NUMPAD_SEVEN: 55,
                NUMPAD_EIGHT: 56,
                NUMPAD_NINE: 57,
                NUMPAD_ZERO: 48,
                MENU: 65360,
                F2: 33
            },
            current: null
        });
    }

    $.widget( "tvui.base", {
        $cursor: null,
        options: {
            hoverCss: 'hover',
            activedCss: 'actived',
            actionCss: 'action',
            disabled: 'disabled',
            htmlTag: ''
        },
        _create: function() {
            this.init();
            
            this.element.data('ui', this);
         },
        init: function() {
            var $el = this.element, opts = this.options;
            /*$hover = $el.find( '.' + opts.hoverCss );
            if ( $hover.length ) {
                this.$cursor = $hover;
            } else {
                this.$cursor = $el.find( '.' + opts.actionCss ).eq( 0 );
            }*/
            /*$el.find( opts.htmlTag + '.' + opts.actionCss ).bind( 'over.tvui out.tvui', function( event ) {
                console.log(1, event.type)
                $( this ).toggleClass( opts.hoverCss );
            });*/
            $el.find( opts.htmlTag + '.' + opts.actionCss ).bind( 'out.tvui', function( event ) {
                $( this ).removeClass( opts.hoverCss );
            });
            $el.find( opts.htmlTag + '.' + opts.actionCss ).bind( 'over.tvui', function( event ) {
                $( this ).addClass( opts.hoverCss );
            });
            $el.addClass('tvui');

            this._trigger('init', null, this.options);
        },
        /**
         * 控件聚焦方法
         */
        focus: function() {
            var $el = this.element, opts = this.options;
            
            var current = $.tvui.current;
            if ( current ) {
                if (false === current.blur()) {
                    return false;
                }
            }
            $.tvui.current = this;
            var ui = {$item: null};
            this._trigger('focus', null, ui);
            // 使用前台回传焦点，或默认第一个
            if (ui.$item || !this.$cursor) {
                var $av = $el.find( opts.htmlTag + '.' + opts.activedCss );
                var $h = $el.find( opts.htmlTag + '.' + opts.hoverCss );
                var c_item = ui.$item || ( $av.length ? $av : ( $h.length ? $h : $el.find( opts.htmlTag + '.' + opts.actionCss ).eq( 0 ) ) );
            }
            if (!c_item) {
                c_item = this.$cursor;
                this.$cursor = null;
            }

            this.setCursor(null, c_item);
            //this.$cursor = $cursor;
            //this.$cursor.trigger( 'over.tvui' );
            return true;
        },
        blur: function() {
            if (false === this._trigger('blur')) {
                return false;
            }
            this.$cursor.trigger( 'out.tvui' );     
            $.tvui.current = null;
            return true;
        },
        _call: function( type, event ) {
            var ui = {
                $item: null,
                type: type,
                instance: this
            };

            if (type == 'enter') {
                this._enter(event);
            } else {
                /*var tmp_ui = this._trigger( type, event, ui );
                if (false === tmp_ui) {
                    return false;
                }*/

                if ( '_' + type in this && $.isFunction( this[ '_' + type ] ) ) {
                    this[ '_' + type ]( event, ui );
                }
                this._trigger( type, event, ui );
            }
        },
        goTo: function( event, ui ) {
            if ( ui.$item && ui.$item.length ) {
                var $to = ui.$item;       
                this.setCursor( event, $to );
            }
        },
        setCursor: function( event, $item ) {
            var ui = {to: $item};
            if (this.$cursor) {
                ui.from = this.$cursor;
                this.$cursor.trigger( 'out.tvui' );
            }
            this.$cursor = $item;
            this.$cursor.trigger( 'over.tvui' );
            this._trigger('change', event, ui);
        },
        _next: function( event, ui ) {
            var o = this.options;
            var $next = ui.$item || this.$cursor.nextAll( o.htmlTag + '.' + o.actionCss )
                .not( o.htmlTag + '.' + o.disabled ).first();
            if ( $next.length ) {
                this.setCursor( event, $next );
            } else {
                this._trigger('over', event, 'end');
            }
        },
        _prev: function( event, ui ) {
            var o = this.options;
            var $prev = ui.$item || this.$cursor.prevAll( o.htmlTag + '.' + o.actionCss )
                .not( o.htmlTag + '.' + o.disabled ).first();
            if ( $prev.length ) {
                this.setCursor( event, $prev );
            } else {
                this._trigger('over', event, 'start');
            }
        },
        _left: function( event, ui ) {
            this._prev( event, ui );
        },
        _right: function( event, ui ) {
            this._next( event, ui );
        },
        _up: function( event, ui ) {
            this._prev( event, ui );
        },
        _down: function( event, ui ){
            this._next( event, ui );
        },
        _enter: function( event ) {
            //event.target = this.$cursor;
            var item = this.$cursor;
            this._trigger('enter', event, item);
            //console.log('_enter')
        },
        _getKeyName: function( keyCode ){
            for ( var key in $.tvui.keyCode ) {
                if ( $.tvui.keyCode[ key ] == keyCode ) {
                    return key.toLowerCase();
                }
            }
        },
        keyDown: function( event ){
            var keyCode = event.keyCode, key = this._getKeyName( keyCode );
            //console.profile('profile');
            //console.time('time')
            this._call( key, event );
            //console.timeEnd('time')
            //console.profileEnd('profile');
        },
        destroy: function() {
            var options = this.options;
            this.element.removeData('ui');
            this.element.removeClass('ui');
            this.element.find( options.htmlTag + '.' + options.actionCss ).unbind('over.tvui out.tvui');
            return $.Widget.prototype.destroy.call( this );
        }
    });

    // list plugin
    $.widget( 'tvui.list', $.tvui.base, {
        options: {
            // H 水平, V 垂直
            direction: 'H',
            // 是否开启滚动
            enabledScroll: false,
            // 视图中显示几条数据
            viewRows: 1,
            // 下标从 0 开始
            scrollIndexs: [ 0, 0 ],
            // 每次滚动几条
            scrollNum: 1,
            // 在视图列表中第几条, 下标从 0 开始
            viewRowIndex: 0,
            // item 高/宽
            itemPX: 0,
            // 滚动方式
            scrollType: 'left',
            $view: null,
            scrolling: true
        },
        _create: function() {
            $.tvui.base.prototype._create.call( this );
            var opts = this.options;
            if ( opts.direction == 'H' ) {
                this._up = this._down = null;
            } else if ( opts.direction == 'V' ) {
                this._left = this._right = null;
            }
        },
        _init: function() {
            var enabledScroll = this.options.enabledScroll;
            if ( enabledScroll ) {
                this._setScroll();
            }
        },
        _setScroll: function() {
            this._setScrollOptions();
        },
        _setScrollOptions: function() {
            var $el = this.element, opts = this.options;
            this.$items = $el.find( opts.htmlTag + '.' + opts.actionCss );
            opts.$view = this.$items.parent();
            this._setNext();
            this._setPrev();
            this._getItemPX();
            var $f = this.$items.eq(0);
            var $av = $el.find( opts.htmlTag + '.' + opts.activedCss );
            var $h = $el.find( opts.htmlTag + '.' + opts.hoverCss );
            this.$cursor = $av.length ? $av : ($h.length ? $h : $f);
            opts.viewRowIndex = this.$items.index(this.$cursor);
            opts.scrollType = opts.direction == 'V' ? 'top' : 'left';
        },
        _setNext: function() {
            this._next = function( event, ui ) {
                var $next = ui.$item || this.$cursor.next();
                if ( $next.length ) {
                    this._scroll( 1 );
                    this.setCursor( event, $next );
                } else if ($next.length==0 && (!$('#wiki-sizer').hasClass('display-none'))) {
                    $('#footer_back').data('ui').focus();
                }
            }
        },
        _setPrev: function() {
            this._prev = function( event, ui ) {
                var $prev = ui.$item || this.$cursor.prev();
                if ( $prev.length ) {
                    this._scroll( -1 );
                    this.setCursor( event, $prev );
                } else if ($prev.length==0 && (!$('#channel-programs-sizer').hasClass('display-none'))) {
                    $('#dates').data('ui').focus();
                }else if($prev.length==0 && (!$('#wiki-sizer').hasClass('display-none'))){
                    $('#tab').data('ui').focus();
                }
            }
        },
        _getItemPX: function() {
            var o = this.options, hv = o.direction, $f = this.$items.first();
            return o.itemPX = ( hv == 'V' ? $f.outerHeight( true ) : $f.outerWidth( true ) );
        },
        _scroll: function(i) {
            var o = this.options, si = o.scrollIndexs, i = i, l = o.viewRows,
            n = o.scrollNum, h = o.itemPX || this._getItemPX(), $hover = this.$cursor,
            $scroll = o.$view, $items = this.$items, _index = $items.index( $hover ),
            row = o.viewRowIndex += i;
            if(o.scrolling && (( row == si[ 0 ] - 1 && _index > si[ 0 ] ) ||
                    ( _index < $items.length - ( l - si[ 1 ] ) && row == si[ 1 ] + 1 ) )) {
                $scroll.css( o.scrollType,
                        ( parseInt( $scroll.css( o.scrollType ) ) || 0 ) + -i*h*n );
                o.viewRowIndex = si[ ( i + 1 ) / 2 ];
            }
        }
    });

    // grid plugin
    $.widget( 'tvui.grid', $.tvui.list, {
        $items: null,
        options: {
            // H 水平, V 垂直
            direction: 'HV',
            // 几行几列
            coords: [ 0, 0 ],
            // 内部有边界
            leftPass: false,
            rightPass: false,
            downPass: false,
            upPass: false,
            currentPage: 0,
            pages: -1
        },
        _create: function() {
            var $el = this.element, opts = this.options;
            this.$items = this.element.find( opts.htmlTag + '.' + opts.actionCss );
            opts.pages = Math.ceil( this.$items.length / ( opts.coords[0] * opts.coords[1] ) ) - 1;
            $.tvui.list.prototype._create.call( this );
        },
        _right: function( event, ui ) {
            var o = this.options, x = o.coords[ 0 ], y = o.coords[ 1 ];
            //cursorIndex
            if ( x > 0 && y > 0 ) {
                var ci = this.$items.index( this.$cursor );
                if ( !o.rightPass && ( ci % y  + 1 == y || ci + 1 == this.$items.length ) ) {
                    this._borde( event, ui );
                    return;
                } 
            }
            $.tvui.list.prototype._right.call( this, event, ui );
        },
        _left: function( event, ui ) {
            var o = this.options, x = o.coords[ 0 ], y = o.coords[ 1 ];
            //cursorIndex
            if ( x > 0 && y > 0 ) {
                var ci = this.$items.index( this.$cursor );
                var i = ci % ( x * y );
                if ( !o.leftPass && ( ci == 0 || i % y == 0 ) ) {
                    this._borde( event, ui );
                    return;
                }
            }
            $.tvui.list.prototype._left.call( this, event, ui );
        },
        _up: function( event, ui ) {
            var o = this.options, x = o.coords[ 0 ], y = o.coords[ 1 ];
            //cursorIndex
            if ( x > 0 && y > 0 ) {
                var ci = this.$items.index( this.$cursor );
                var i = ci % ( x * y );
                if ( !o.upPass && ( 0 <= i && i < y ) ) {
                    this._borde( event, ui );
                    return;
                } else {
                    ui.$item = this.$items.eq( ci - y );
                }
            }
            $.tvui.list.prototype._left.call( this, event, ui );
         },
        _down: function( event, ui ) {
            var o = this.options, x = o.coords[ 0 ], y = o.coords[ 1 ];
            //cursorIndex
            if ( x > 0 && y > 0 ) {
                var ci = this.$items.index( this.$cursor );
                var i = ci % ( x * y );
                var start = x * y - y;
                var end = x * y;
                if ( !o.downPass && ( start <= i && i < end ) ) {
                    this._borde( event, ui );
                    return;
                } else {
                    ui.$item = this.$items.eq( ci + y );
                }
            }
            $.tvui.list.prototype._down.call( this, event, ui );
        },
        _borde: function( event, ui ) {
            //console.log(ui)
            this._trigger( ui.type + 'Borde', event, ui );
        }
    });

    $(function() {
        var doc = $( document );
        /*doc.bind( 'click.tvui', function( event ) {
            event.keyCode = $.tvui.keyCode.ENTER;
            var  current = $.tvui.current;
            if ( current ) {
                current.keyDown( event );
                event.stopPropagation();
            }
        });*/
        doc.bind( 'keydown.tvui', function( event ) {
            var  current = $.tvui.current;
            if ( current ) {
                current.keyDown( event );
                event.stopPropagation();
            }
        });
        $("input").blur();
    });

})(jQuery);


$(document).keydown(function(e){
	keyEvent(e);
});
//document.onkeydown = keyEvent ;
//按menu键退出浏览器
function keyEvent(e)
{
	keyword=e.which;
	switch(keyword)
	{
		/**获取返回键**/
		case 72:
			history.go(0);
			return false;
			break ;
		default:
			break ;
	}
}

//从机顶盒获取当前运营商id
var channelNetWordId = System.GetNetworkID();

$(document).ready(function(){
	$.ajax({
        url: 'user/saveNetWorkId',
        type: 'post',
        dataType: 'json',
        data: {
			'channelNetWordId' : channelNetWordId
	    },
        success: function(data){
            
        }
    });
});

//十进制转换二进制
function toBin(intNum) {
    var answer = "";
    if(/\d+/.test(intNum)) {
      while(intNum != 0) {
        answer = Math.abs(intNum%2)+answer;
        intNum = parseInt(intNum/2);
      }
      if(answer.length == 0)
        answer = "0";
      return answer;
    } else {
      return 0;
    }
}


