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

    /**
     * @class Remote 遥控类
     */
    function Remote(options){

        /**
         * 该实例话对象最基本的属性
         * 默认值
         */
        this.attributes = {};

        // 数据初始化 调用函数的顺序
        this._attrs = ['viewer', 'elements', 'pager', 'customRemote'];
        
        /**
         * 要操作 dom/jquery 视图部分
         */
        this.viewer = {
            activeClass : 'active',                                             // 激活样式
            hoverClass  : 'hover',                                              // hover 样式
            hoverIndex  : -1,                                                   // 默认hover起始位置
            blockClass  : 'block',                                              // 操作 block 大块
            focusClass  : 'focus',                                              // 操作 block 大块 focus 样式
            actionClass : 'action',                                             // 默认取所有操作元素的样式
            rowIndex    : 1,                                                    // 视图起始位置 （1开始）
            activeIndex : 0,                                                    // 激活位置 （0开始）
            xy          : 'y',                                                  // 滚动方向，默认 纵轴方向
            scrollType  : 'top',                                                // 滚动方向，top
            scrollRows  : [0, 9],                                               // index start 0
            //            isFixedCursor   : false,
            rowHeight   : 0,                                                    // 视图 一行高度
            rowWidth    : 0,                                                    // 视图 一行宽带
            rowSize     : 0,                                                    // 视图 一行的高度/宽度
            pagerTips  : {                                                      // pagerTips
                up      : 'up',
                down    : 'down',
                tips    : 'location-tips',
                up_active : 'up-active',
                down_active : 'down-active',
                arrow   : 'arrow'
            }
        };

        /**
         * 该实例对象要操作的 dom/jquery 元素
         */
        this.elements = {
            block   : false,                                                    // 必需
            active  : false,
            scroll  : false,
            nodes   : false,                                                    // 必需
            container   : false
        };

        
        /**
         * 分页效果
         */
        this.pager = {
            pageSize    : 10,
            totalRows   : 0,
            currentPage : 1,
            totalPages  : 0,
            //            currentRow  : -1,
            hasPager    : false
        };

        this.customRemote = {                                                   // 自定义事件 ： 对应 上、下、左、右键
            left    : false,
            right   : false,
            up      : false,
            down    : false,
            ok      : false,
            scroll  : false,
            bind    : false,
            onenter   : false,
            onleave   : false,
            scrollAjax : false
        };
        
        this.isDefault = false;
        this.bindStatus = false;
        this.ajaxStatus = false;

        this.initializer(options);
        
        return this;
    };

    /**
     * @static Remote class static functions / properties
     * Remote 类 的 静态方法/属性
     */
    $.extend(Remote, {
        NAME : 'Remote',
        VERSION : '0.0.1',
        ATTRS : {
            
        },
        stroage : {},
        addStroage : function(uuid, remoteObject){
            if(!(uuid in Remote.stroage)){
                Remote.stroage['remote-' + uuid] = remoteObject;
            }
        },
        instance : false,
        bindDoc  : function(){
            var doc = document;
            $(doc).bind('keydown', function(event){
                //                console.log(event.keyCode, event.type)
                var remote = Remote.instance;
                if(remote){
                    var $current_container = remote.elements.container;
                    //                console.log($current_block)
                    //                console.log(remote.uuid)
                    $current_container.triggerHandler(remote.uuid + '.keydown', [event]);
                }
            });

            $(doc).bind('keypress', function(event){});
            $(doc).bind('keyup', function(event){});
            Remote.bindStatus = true;
        },
        //        changeBlock : function(fromRemote, toRemote){
        ////            console.log(fromRemote, toRemote)
        //            if(typeof fromRemote == 'number') fromRemote = Remote.getItem(fromRemote);
        //            if(typeof toRemote == 'number') toRemote = Remote.getItem(toRemote);
        //            var f = fromRemote, t = toRemote;
        //            f.elements.block.removeClass(f.viewer.focusClass);
        //            var hover = f.getElem(f.viewer.hoverIndex);
        //            if(hover) hover.removeClass(f.viewer.hoverClass);
        //            Remote.instance = t;
        //            t.elements.block.addClass(t.viewer.focusClass);
        //            hover = t.getElem(t.viewer.hoverIndex);
        //            if(hover) hover.addClass(t.viewer.hoverClass);
        //        },
        getItem : function(remote_uuid){
            return Remote.stroage['remote-' + remote_uuid] || false;
        }
    });

    /**
     * @public Remote class public functions / properties
     * Remote 类 的 公共方法/属性
     */
    $.extend(Remote.prototype, {

        /**
         * @constructor
         */
        initializer : function(options){
            this.uuid = +new Date();
            this.setOptions(options || {});
            Remote.addStroage(this.uuid, this);

            this.bind();

            if(this.isDefault){
                this.elements.block.addClass(this.viewer.focusClass);
                Remote.instance = Remote.stroage['remote-' + this.uuid];
                var hover = Remote.instance.getElem(this.viewer.hoverIndex);
                if(hover) hover.addClass(this.viewer.hoverClass);
            }
        },

        /**
         * 
         */
        destructor  : function(){
            return this;
        },

        changeBlock : function(toRemote, fb, tb){
            fb = fb || 'yes', tb = tb || 'yes';
            try{
                if(typeof toRemote == 'number') toRemote = Remote.getItem(toRemote);
                if(fb=='yes') this.onleave();
                Remote.instance = toRemote;
                if(tb=='yes') toRemote.onenter();
            }catch(e){
                
            }
        },

        setElements   : function(value){
            var elems = this['elements'];
            var viewer = this['viewer'];
            $.extend(elems, value);

            if(!elems.block || !elems.block.length){
                //                console.log('请输入要操作的块级元素！');
                return;
            }

            if(!elems.container){
                elems.container = elems.block;
            }


            if(!elems.nodes){
                elems.nodes = elems.container.find('.' + viewer.actionClass);
            }
            
            if(!elems.active){
                elems.active = elems.container.find('.' + viewer.activeClass);
                elems.activeIndex = elems.nodes.index(elems.active);
                if(elems.activeIndex == -1){
                    elems.active = elems.nodes.eq(0);
                    elems.activeIndex = 0;
                }
            }

            if(!elems.scroll){
                elems.scroll = elems.nodes.parent();
            }

            if(viewer.hoverIndex == -1){
                viewer.hoverIndex = elems.activeIndex;
            }

            viewer.rowIndex = viewer.hoverIndex;
            elems.previousIndex = viewer.hoverIndex - 1;
            elems.nextIndex = viewer.hoverIndex + 1;
            elems.length = elems.nodes.length;

        },

        rebuild : function(options){
            this.rebuildStatus = true;
            if(this.rebuildStatus){
                this.elements.nodes = null;
                this.elements.container = null;
                this.elements.active = null;
                this.elements.scroll = null;
                this.elements.tips = null;
                this.viewer.hoverIndex = -1;
            }
            this.setOptions(options);
            this._onenter();
        },

        setViewer : function(value){
            var viewer = this.viewer;
            $.extend(viewer, value || {});
        },

        setPager     : function(value){
            var pager = this.pager;
            $.extend(pager, value || {});
            pager.hasPager = true;
            if(pager.hasPager){
                pager.totalRows = this.elements.length;
                pager.totalPages = Math.ceil(pager.totalRows / pager.pageSize);
            }
            
            var viewer = this.viewer;
            if(viewer.scrollRows[0] < 1) viewer.scrollRows[0] = 0;
            if(viewer.scrollRows[1] > pager.pageSize) viewer.scrollRows[1] = pager.pageSize - 1;
            
            this.changePagerTips();
            return this;
        },

        changePagerTips : function(i){
            var viewer = this.viewer;
            var pager = this.pager;
            var elems = this.elements;
            if(!elems.tips){
                elems.tips = [
                elems.container.find('.' + viewer.pagerTips.up),
                elems.container.find('.' + viewer.pagerTips.down),
                elems.container.find('.' + viewer.pagerTips.tips)
                ];

                elems.tips[3] = elems.tips[0].parent();

                elems.tips[0].removeClass(viewer.pagerTips.up_active);
                elems.tips[1].removeClass(viewer.pagerTips.down_active);

                elems.tips[1].addClass(viewer.pagerTips.down_active);
            }

            elems.tips[2].html((viewer.hoverIndex + 1) + ' | ' + pager.totalRows);

            if(typeof i == 'undefined') i = 1;
            var rows = viewer.scrollRows;

            if(pager.totalRows > pager.pageSize){
                elems.tips[3].show();
            }

            if(i == 1){
                if(viewer.hoverIndex > rows[1]){
                    elems.tips[0].addClass(viewer.pagerTips.up_active);
                }

                if(viewer.hoverIndex >= pager.totalRows - (pager.pageSize - rows[1])){
                    elems.tips[1].removeClass(viewer.pagerTips.down_active);
                }
            }else{
                if(viewer.hoverIndex <= rows[0]){
                    elems.tips[0].removeClass(viewer.pagerTips.up_active);
                }

                if(viewer.hoverIndex < pager.totalRows - (pager.pageSize - rows[0])){
                    elems.tips[1].addClass(viewer.pagerTips.down_active);
                }
            }
        },

        setCustomRemote : function(value){
            var customRemote = this.customRemote;
            $.extend(customRemote, value || {});
            this.setDirection();
        },

        get     : function(key){},

        setOptions  : function(options){
            var scope = this, options = options || {};
            $.each(scope._attrs, function(key, value){
                if(value in scope){
                    //                    //                    console.log(value)
                    scope['set' + value.replace(/^([\w])/i, function(v){
                        return v.toUpperCase();
                    })](options[value] || {});

                    delete options[value];
                }
            });
            $.extend(true, this, options);
        },
        changeHoverIndex : function(i){
            this.viewer.hoverIndex += i;
        },
        changeHover     : function(i){
            var viewer = this.viewer;
            var hoverClass = viewer.hoverClass;
            var elem;
            if(elem = this.getElem(viewer.hoverIndex)){
                elem.removeClass(hoverClass);
            }
            this.changeHoverIndex(i);
            if(elem = this.getElem(viewer.hoverIndex)){
                elem.addClass(hoverClass);
            }
        },
        changeActive    : function(){
            var hoverIndex = this.viewer.hoverIndex,
            hover = this.getElem(hoverIndex),
            active = this.elements.active,
            hoverClass = this.viewer.hoverClass,
            activeClass = this.viewer.activeClass;
            if(hover.length && hover.hasClass(hoverClass) && !hover.hasClass(activeClass)){
                active.removeClass(activeClass);
                hover.addClass(activeClass);
                this.elements.activeIndex = hoverIndex;
                this.elements.active = hover;
            }
        },
        getElem         : function(index){
            if(!this.elements.nodes || !this.elements.nodes.length) return false;
            var elem;
            return (elem = this.elements.nodes[index]) ? $(elem) : false;
        },
        // 绑定自定义 jQuery 事件
        _bind         : function(){
            if(this.bindStatus){
                this.unbind();
                this.bindStatus = false;
            }
            var elems = this.elements;
            var scope = this;
            //            this.bindStatus = true;
            elems.container.bind(this.uuid + '.keydown', function(event, docEvent){
                var keyCode = $.keyCode;
                var evt = scope.keyCode = docEvent;
                //                console.log(evt.keyCode)
                //                switch(evt.keyCode){
                switch(evt.which){
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
            });

            this.bindStatus = true;
        },
        // 取消绑定 jQuery 事件
        unbind      : function(){
            this.elements.container.unbind(this.uuid + '.keydown');
        },
        // 设置快捷键
        setDirection : function(){
            var scope = this;
            var viewer = this.viewer;
            if(viewer.xy == 'y'){
                if(viewer.scrollType != 'top') viewer.scrollType = 'top';
                viewer.rowSize = viewer.rowHeight;
            }else{
                viewer.scrollType = 'left';
                viewer.rowSize = viewer.rowWidth;
            }
            
            $.each(scope.customRemote, function(i, v){
                scope[i] = function(){
                    if($.isFunction(v)){
                        var custom = v.apply(scope, arguments);
                        if(custom) return;
                    }
                    return scope['_' + i].apply(scope, arguments);
                };
            });
        },
        _scroll     : function(i){
            //            console.log(i)
            var pager = this.pager;
            //            var viewer = this.viewer;
            if(pager.hasPager){
                this._normalScroll(i);
            }
        },
        _normalScroll : function(i){
            if(Remote.instance.uuid != this.uuid) return;
            this.changeHover(i);
            var pager = this.pager;
            var viewer = this.viewer;
            var elems = this.elements;
            var rows = viewer.scrollRows;
            viewer.rowIndex += i;
            if((viewer.rowIndex == rows[0] - 1 && viewer.hoverIndex > rows[0] - 1) ||
                (viewer.rowIndex == rows[1] + 1 && viewer.hoverIndex < pager.totalRows - (pager.pageSize - (rows[1] + 1)))){
                this._scrollCss(i);
                viewer.rowIndex = rows[(i + 1) / 2];
            }
            this.changePagerTips(i);
        },
        _scrollCss : function(i){
            var elems = this.elements;
            var viewer = this.viewer;
            var size = parseInt(elems.scroll.css(viewer.scrollType)) || 0;
            elems.scroll.css(viewer.scrollType, size + (-1)*i*viewer.rowSize);
        },
        _up     : function(){
            if(this.viewer.hoverIndex > 0){
                this.scroll(-1);
            }
        },
        _down   : function(){
            if(this.viewer.hoverIndex < this.elements.length - 1){
                this.scroll(1);
            }
        },
        _left   : function(){},
        _right  : function(){},
        _ok     : function(){
            this.changeActive();
        },
        _onleave  : function(){
            if(Remote.instance && Remote.instance.uuid == this.uuid){
                this.elements.block.removeClass(this.viewer.focusClass);
                var hover = this.getElem(this.viewer.hoverIndex);
                if(hover) hover.removeClass(this.viewer.hoverClass);
            }
        },
        _onenter  : function(){
            if(Remote.instance && Remote.instance.uuid == this.uuid){
                this.elements.block.addClass(this.viewer.focusClass);
                var hover = this.getElem(this.viewer.hoverIndex);
                if(hover) hover.addClass(this.viewer.hoverClass);
            }
        },
        _scrollAjax : function(){
            this.ajaxStatus = true;
        }
    });

    /**
     * @private Remote class private functions / properties
     * Remote 类 的 私有方法/属性
     */
    $.extend(Remote.prototype._private = function(){}, {

        });

    /**
     * 扩展 jQuery 对象
     */
    $.R = $.Remote = Remote;

    Remote.bindDoc();

/**
     * 
     */
//    $.fn.remote = function(options){
//        var $this = $(this);
//        console.log(this.selector, "\n")
//        console.dir(this)

//        return this.each(function(_i, _elem){
//            console.log(_i, _elem)
//        });
//    };

})(jQuery);

/**
 * notes:
 *      1. 改进工作方式，以 block 方式生成该 block 对应的 Remote 实例
 */