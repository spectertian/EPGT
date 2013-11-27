(function($, undefined){

    // 快捷键
    $.extend($, {
        keyCode : {
            ENTER : 13,
            OK : 13,
            BACKSPACE : 8,
            DELETE : 8,
            DOWN : 40,
            UP : 38,
            RIGHT : 39,
            LEFT : 37,
            QUICK : 9,
            ZOOM : 27,
            VOLUME_UP : 114,
            VOLUME_DOWN : 115,
            IME : 17,
            ONE : 49,
            TWO : 50,
            THERE : 51,
            FOUR : 52,
            FIVE : 53,
            SIX : 54,
            SEVEN : 55,
            EIGHT : 56,
            NINE : 57,
            ZERO : 48
        }
    });

    // epg 操作插件
    $.epg = {};

    $.extend($.epg, {
        // $.egp 存储
        stroage : {
            remoteTmps : {
                objects   : {},                                                 // 存放实例化对象集
                selectors : {}                                                  // 存放生成实例的 css selector
            }
        }
    });

    // Remote 遥控类
    function Remote(selector){
        var tmpTimes = new Date().getTime();                                    // 生成时间
        this.options = {                                                        // 默认设置
            activeClass :   'active',                                           // 当前激活元素 active className
            hoverClass  :   'hover',                                            // 当前光标 hover className
            blockClass  :   'block',                                            // 当前光标所在的 block 块级元素
            focusClass  :   'focus',                                            // 当前光标所在的 block 块级元素 focus className
            isDefault   :   false,                                              // true 设置为默认操作块
            hasPager    :   false,                                              // true 操作列表需要分页
            hoverIndex  :   -1,
            pager       :   {                                                   // 分页
                pageSize        : 10,                                           // 默认显示 10 条
                totalRows       : 0,                                            // 最大记录数
                currentPage     : 1,                                            // 当前页 id
                totalPages      : 0,                                            // 总页数
                viewRowIndex    : 1                                             // 在当前视图中，第几行
            },
            uuid        :   tmpTimes,                                           // 实例化，唯一标记
            alias       :   'remote_' + tmpTimes,                               // 别名
            isHorizontal:   true,                                               // 默认为 up/down 上下键操作，反之为上下 操作
            isCustomRemote  : false,                                            // 开启自动义事件
            customRemote    : {                                                 // 自定义事件 ： 对应 上、下、左、右键
                left    : false,
                right   : false,
                up      : false,
                down    : false,
                ok      : false
            },
            container   : '',// 用来动画操作的容器
            activeElem  : '',
            blockElem   : '',
            scrollElem  : ''
        };

        this.selector = selector || '';

        this.bindStatus = false;
        this.isRebuilded = false;
        this.elems = {};                                                        // 存放当前实例要操作的 Dom 元素/ jQuery 对象
        
    };

    // 扩展 Remote 类的 public 方法
    $.extend(Remote.prototype, {
        initialize : function(){
            this.initDatas();

            this.setPager();

            if(this.bindStatus) this.unbind();
            this.bind();

            if(this.options.isDefault){
                Remote.instance = $.epg.stroage.remoteTmps.objects[this.options.alias];
                Remote.instance.getElem(this.elems.hoverIndex).addClass('hover');
            }
        },
        initDatas    : function(){
            var opts = this.options, elems = this.elems;
            
            this.setElems();
            
            elems.block = opts.blockElem ? $(opts.blockElem) : elems.activeElem.parents('.' + opts.blockClass);
            elems.container = opts.container ? (opts.blockElem == opts.container ? elems.block : $(opts.container)) : elems.activeElem.parent();
            
        },
        setElems    : function(){
            var opts = this.options, elems = this.elems;

            elems._elements = $(this.selector);
            
            elems.activeElem = elems._elements.filter('.' + opts.activeClass);

            elems.scroll = opts.scrollElem ? $(opts.scrollElem) : elems.activeElem.parent();

            if(!elems.activeElem.length){
                elems.activeElem = elems._elements.eq(0);
                elems.activeIndex = 0;
            }else{
                elems.activeIndex = elems._elements.index(elems.activeElem);
            }
            
            elems.parent = elems.activeElem.parent();

            if(opts.hoverIndex != -1){
                elems.hoverIndex = elems.currentIndex = opts.hoverIndex;
            }else{
                elems.hoverIndex = elems.currentIndex = elems.activeIndex;
            }

            elems.previousIndex = elems.hoverIndex - 1;
            elems.nextIndex = elems.hoverIndex + 1;
            elems.length = elems._elements.length;
            
            if(this.options.pager.totalRows == 0 || this.isRebuilded){
                this.options.pager.totalRows = elems.length;
            }

            if(elems.hoverIndex > this.options.pager.pageSize - 1){
//                console.log(elems.hoverIndex)
//                console.log(this.options.pager)
                elems.scroll.css('top', -(elems.hoverIndex - this.options.pager.pageSize + 1) * this.options.pager.viewHeight);
            }

            if(this.isRebuilded){
                if(!elems.container.length)  elems.container = opts.container ? (opts.blockElem == opts.container ? elems.block : $(opts.container)) : elems.activeElem.parent();

                var h = this.getElem(elems.hoverIndex);
                if(h) h.addClass(opts.hoverClass);
            }

        },
        rebuild : function(){
            this.isRebuilded = true;
            this.setElems();
            this.setPager();
//            this.rebind();
        },
        // 设置 pager 分页
        setPager : function(){
            if(this.options.hasPager){
                var pager = this.options.pager;
                pager.totalPages = Math.ceil(pager.totalRows / pager.pageSize);
                pager.viewRowIndex = this.elems.hoverIndex > 9 ? 10 : this.elems.hoverIndex + 1;
            }
        },
        // 绑定自定义 jQuery 事件
        bind         : function(){
            var elems = this.elems;
            var scope = this;
            this.bindStatus = true;
            this.setDirection();
            elems.block.bind(this.options.alias + '.keypress', function(event, docEvent){
                var keyCode = $.keyCode;
                var evt = docEvent;
//                console.log(evt.keyCode)
                switch(evt.keyCode){
                    case keyCode.UP:
                        scope.up();
                        break;
                    case keyCode.DOWN:
                        scope.down();
                        break;
                    case keyCode.LEFT:
                        scope.left();
                        break;
                    case keyCode.RIGHT:
                        scope.right();
                        break;
                    case keyCode.OK:
                        scope.ok();
                        break;
                    default:
                        break;
                };
                event.stopPropagation();
                event.preventDefault();
                return false;
            });
        },
        // 取消绑定 jQuery 事件
        unbind      : function(){
            this.elems.block.unbind(this.options.alias + '.keypress');
        },
        rebind      : function(){
            this.unbind();
            this.bind();
        },
        // 设置快捷键
        setDirection : function(){
            var scope = this;
            var opts = this.options;
            $.each(opts.customRemote, function(i, v){
                scope[i] = function(){
                    if($.isFunction(v)){
                        var custom = v.call(scope);
                        if(custom) return;
                    }
                    scope['_' + i].call(scope);
                };
            });
        },
        // 设置 当前光标的 hover 效果
        setHoverIndex    : function(i){
            this.elems.hoverIndex += i;
        },
        // 设置 当前光标的 hover 效果
        setHover    : function(i){
            var hoverClass = this.options.hoverClass;
            var h1 = this.getElem(this.elems.hoverIndex);
            if(h1) h1.removeClass(hoverClass);
//            this.getElem(this.elems.hoverIndex).removeClass(hoverClass);
            this.setHoverIndex(i);
            var h2 = this.getElem(this.elems.hoverIndex);
            if(h2) h2.addClass(hoverClass);
//            this.getElem(this.elems.hoverIndex).addClass(hoverClass);
        },
        getElem : function(index){
            var elem;
            return (elem = this.elems._elements[index]) ? $(elem) : false;
        },
        // 设置激活 active 效果
        setActive : function(){
            var hoverIndex = this.elems.hoverIndex;
            var activeIndex = this.elems.activeIndex;
            var hover = this.getElem(hoverIndex);
            var active = this.elems.activeElem;
            var hoverClass = this.options.hoverClass;
            var activeClass = this.options.activeClass;
//            if(hover.hasClass(hoverClass) && hoverIndex != activeIndex){
            if(hover.hasClass(hoverClass) && !hover.hasClass(activeClass)){
                active.removeClass(this.options.activeClass);
                hover.addClass(this.options.activeClass);
                this.elems.activeIndex = hoverIndex;
                this.elems.activeElem = hover;
            }
        },
        // 设置默认 up 操作
        _up    : function(){
            if(this.elems.hoverIndex > 0){
                this.setHover(-1);
                this._scrollUp();
            }
        },
        // 设置默认 scrollUp 操作
        _scrollUp : function(){
            if(this.options.hasPager){
                var pager = this.options.pager;
                var elems = this.elems;
                pager.viewRowIndex--;
                if(pager.viewRowIndex == 0){
                    var top = parseInt(elems.parent.css('top')) || 0;
                    elems.scroll.css('top', top + pager.viewHeight);
                    if(pager.viewRowIndex <= 0) pager.viewRowIndex = 1;
                }
            }
        },
        _down : function(){
            if(this.elems.hoverIndex < this.elems.length - 1){
                this.setHover(1);
                this._scrollDown();
            }
        },
        _scrollDown : function(){
            if(this.options.hasPager){
                var pager = this.options.pager;
                var elems = this.elems;
                if(pager.viewRowIndex < (pager.pageSize + 1)) pager.viewRowIndex++;
                if(pager.viewRowIndex > pager.pageSize && elems.hoverIndex < pager.totalRows){
                    var top = parseInt(elems.parent.css('top')) || 0;
                    elems.scroll.css('top', top - pager.viewHeight);
                    if(pager.viewRowIndex > pager.pageSize) pager.viewRowIndex = pager.pageSize;
                }
            }
        },
        _left : function(){
        },
        _right : function(){
        },
        _ok : function(){
            this.setActive();
        }
    });

    // jQuery remote 插件
    $.fn.remote = function(options){
        var tmps = $.epg.stroage.remoteTmps;
        var objects = tmps.objects;
        var selectors = tmps.selectors;
        var selector = this.selector;

        var remote = objects[selectors[selector]] || new Remote(selector);
        var opts = $.extend(true, remote.options, options);                     // 深度拷贝

        if(!(selector in selectors)){
            var alias = selectors[selector] = remote.options.alias;
            objects[alias] = remote;
            remote.initialize();
        }else{
            if('customRemote' in options){
                remote.rebind();
            }
        }

        // 检测 Remote.bindDoc 状态
        if(!Remote.bindStatus) Remote.bindDoc();

    };

    // Remote 静态属性：实例化对象
    Remote.instance = false;
    // Remote 静态方法，bind document events
    Remote.bindStatus = false;                                                  // bind status
    Remote.bindDoc  = function(){
        var doc = document;
        $(doc).bind('keydown', function(event){
            var remote = Remote.instance;
            var $current_block = remote.elems.block;
            $current_block.triggerHandler(remote.options.alias + '.keypress', [event]);
        });

        $(doc).bind('keypress', function(event){});
        $(doc).bind('keyup', function(event){});
        Remote.bindStatus = true;
    };
    // block 之间切换
    Remote.changeBlock = function(fromRemote, toRemote){
        if(typeof fromRemote == 'string') fromRemote = Remote.getItem(fromRemote);
        if(typeof toRemote == 'string') toRemote = Remote.getItem(toRemote);
        var f = fromRemote, t = toRemote, fopts = f.options, topts = t.options;
        f.elems.block.removeClass(fopts.focusClass);
        var h1 = f.getElem(f.elems.hoverIndex);
//        console.log(h1)
        if(h1) h1.removeClass(fopts.hoverClass);
        Remote.instance = t;
        t.elems.block.addClass(topts.focusClass);
        var h2 = t.getElem(t.elems.hoverIndex);
//        console.log(h2)
        if(h2) h2.addClass(topts.hoverClass);
    };

    // 取得 Remote 实例对象
    Remote.getItem = function(selector){
        var remoteTmps = $.epg.stroage.remoteTmps, alias;
        return (alias = remoteTmps.selectors[selector])
        ? remoteTmps.objects[alias] : false;
    };

    // 扩展 jQuery 静态方法
    $.Remote = Remote;

})(jQuery);

$(function(){
   $(window).unload(function(){
        $.Remote.instance = null;
        $.epg.stroage.remoteTmps.objects = null;
        $.epg.stroage.remoteTmps.selectors = null;
//        console.log('bye!');
   });
});
