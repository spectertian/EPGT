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
         * @public
         */
        this.attributes = {};

        /**
         * 数据初始化 调用函数的顺序
         * @public
         */
        this._attrs = ['viewer', 'elements', 'pager', 'customRemote'];
        
        /**
         * 要操作 dom/jquery 视图部分
         * @public
         */
        this.viewer = {
            activeClass : 'active',                                             // 激活样式
            liveClass   : 'playing',                                            // 当前正在播放的列表
            livedClass  : 'played',                                             // 已经播过的节目
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
            rowHeight   : 0,                                                    // 视图 一行高度
            rowWidth    : 0,                                                    // 视图 一行宽带
            rowSize     : 0,                                                    // 视图 一行的高度/宽度
            pagerTips  : {                                                      // pagerTips
                up      : 'up',                                                 // 分页时，上面有条目没有出现在视图中时提示信息
                down    : 'down',                                               // 分页时，下面有条目没有出现在视图中的提示信息
                tips    : 'location-tips',                                      // 分页时，提示的div
                up_active : 'up-active',                                        // 分页时，上面高亮的提示信息
                down_active : 'down-active',                                    // 分页时，下面高亮的提示信息
                arrow   : 'arrow'                                               // 
            }
        };

        /**
         * 该实例对象要操作的 dom/jquery 元素
         * @public
         */
        this.elements = {
            block   : false,                                                    // 必需；操作块
            active  : false,                                                    // 当前列表中激活的列
            scroll  : false,                                                    // 当有分页时，滚动的元素
            nodes   : false,                                                    // 必需；需要操作的元素
            container   : false,                                                // 
            playing : false,                                                    // 当前正在播放的节目
            played  : false                                                     // 已经播过的节目
        };

        /**
         * 分页效果设置
         * @public
         */
        this.pager = {
            pageSize    : 10,                                                   // 默认分页大小
            totalRows   : 0,                                                    // 总共几行
            currentPage : 1,                                                    // 当前第几页
            totalPages  : 0,                                                    // 总页数
            hasPager    : false                                                 // 是否有分页
        };

        /**
         * 自定义事件
         * @public
         */
        this.customRemote = {                                                   // 自定义事件 ： 对应 上、下、左、右键
            left    : false,
            right   : false,
            up      : false,
            down    : false,
            ok      : false,
            scroll  : false,                                                    // 滚动事件
            bind    : false,                                                    // 绑定其他按键/事件
            onenter   : false,                                                  // 当进入当前操作块时，触发事件
            onleave   : false                                                   // 当离开当前操作块时，触发事件
        };
        
        this.isDefault = false;                                                 // 设置为默认操作块
        this.bindStatus = false;                                                // 事件是否已经绑定
        this.ajaxStatus = false;                                                // ajax调用状态

        this.initializer(options);                                              // 初始化设置
        
        return this;
    };

    /**
     * @static Remote class static functions / properties
     * Remote 类 的 静态方法/属性
     */
    $.extend(Remote, {
        NAME : 'Remote',
        VERSION : '0.0.1',
        ATTRS : {},
        stroage : {},
        // 添加进内部 stroage
        addStroage : function(uuid, remoteObject){
            if(!(uuid in Remote.stroage)){
                Remote.stroage['remote-' + uuid] = remoteObject;
            }
        },
        instance : false,
        downOrclick : 0,
        // 给 document 绑定快捷键操作事件
        bindDoc  : function(){
            var doc = document;
            $(doc).bind('keydown', function(event){
                /////////////////////////////////////
                if($.Remote.downOrclick == 0){
                    if(event.which == 13){
                        $.Remote.downOrclick = 13;
                    }
                }
                /////////////////////////////////////
                var remote = Remote.instance;
                if(remote){
                    var $current_container = remote.elements.container;
                    $current_container.triggerHandler(remote.uuid + '.keydown', [event]);
                }
            });

            $(doc).bind('click', function(event){
                //////////////////////////////////////
                if($.Remote.downOrclick == 0){
                    if(event.which == 1){
                        $.Remote.downOrclick = 1;
                    }
                }
                //////////////////////////////////////
                var remote = Remote.instance;
                if(remote){
                    var $current_container = remote.elements.container;
                    $current_container.triggerHandler(remote.uuid + '.click', [event]);
                }
            });

//            $(doc).bind('keypress', function(event){});
//            $(doc).bind('keyup', function(event){});
            Remote.bindStatus = true;
        },
        // 取得相应的操作块
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
         * 初始化设置
         * @constructor
         */
        initializer : function(options){
            this.uuid = +new Date();
            this.setOptions(options || {});
            Remote.addStroage(this.uuid, this);

            this.bind();

            if(this.isDefault){
                var instance = Remote.instance;
                if(instance){
                    var hover = Remote.instance.getElem(instance.viewer.hoverIndex);
                    if(hover) hover.removeClass(instance.viewer.hoverClass);
                }
                this.elements.block.addClass(this.viewer.focusClass);
                Remote.instance = Remote.stroage['remote-' + this.uuid];
                var hover = Remote.instance.getElem(this.viewer.hoverIndex);
                if(hover) hover.addClass(this.viewer.hoverClass);
            }
        },

//        destructor  : function(){return this;},

        // 切换操作块
        changeBlock : function(toRemote, fb, tb){
            fb = fb || 'yes', tb = tb || 'yes';
            try{
                if(typeof toRemote == 'number') toRemote = Remote.getItem(toRemote);
                if(fb=='yes') this.onleave();
                Remote.instance = toRemote;
                if(tb=='yes') toRemote.onenter();
            }catch(e){}
        },

        // 设置需要操作的元素
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
                viewer.activeIndex = elems.nodes.index(elems.active);
                if(viewer.activeIndex == -1){
                    elems.active = elems.nodes.eq(0);
                    viewer.activeIndex = 0;
                }
            }

            if(!elems.playing){
                elems.playing = elems.container.find('.' + viewer.liveClass);
            }

            if(!elems.played){
                elems.played = elems.container.find('.' + viewer.livedClass);
            }

            if(!elems.scroll){
                elems.scroll = elems.nodes.parent();
            }

            if(viewer.hoverIndex == -1){
                viewer.hoverIndex = viewer.activeIndex;
            }

            elems.previousIndex = viewer.hoverIndex - 1;
            elems.nextIndex = viewer.hoverIndex + 1;
            elems.length = elems.nodes.length;
            viewer.rowIndex = viewer.hoverIndex;
        },

        // 重新初始化设置
        rebuild : function(options){
            this.rebuildStatus = true;
            if(this.rebuildStatus){
                this.elements.nodes = null;
                this.elements.container = null;
                this.elements.active = null;
                this.elements.playing = null;
                this.elements.played = null;
                this.elements.scroll = null;
                this.elements.tips = null;
                this.viewer.hoverIndex = -1;
            }
            this.setOptions(options);
            this._onenter();
        },

        // 设置视图
        setViewer : function(value){
            var viewer = this.viewer;
            $.extend(viewer, value || {});
        },

        // 设置分页
        setPager     : function(value){
            var pager = this.pager;
            var elems = this.elements;
            var viewer = this.viewer;
            $.extend(pager, value || {});
            pager.hasPager = true;
            if(pager.hasPager){
                pager.totalRows = this.elements.length;
                pager.totalPages = Math.ceil(pager.totalRows / pager.pageSize);
            }

            if(viewer.scrollRows[0] < 1) viewer.scrollRows[0] = 0;
            if(viewer.scrollRows[1] > pager.pageSize) viewer.scrollRows[1] = pager.pageSize - 1;

            // 自动定位到直播节目
//            if(elems.playing.size() == 1){
            if(elems.played && elems.played.length && elems.played.size() < elems.length){
                var rows = viewer.scrollRows;
                var playingIndex = elems.nodes.index(elems.playing);
                playingIndex = playingIndex > -1 ? playingIndex : elems.played.size();

                viewer.hoverIndex = playingIndex;
                if(elems.length > pager.pageSize){
                    if(playingIndex < rows[0]){
                        viewer.rowIndex = viewer.hoverIndex;
                    }else if(playingIndex >= rows[0] && playingIndex <= elems.length - rows[1] - 1){
                        this._scrollCss(playingIndex - rows[0]);
                        viewer.rowIndex = rows[0];
                    }else{
                        this._scrollCss(pager.totalRows - pager.pageSize);
                        viewer.rowIndex = pager.pageSize - (pager.totalRows - playingIndex);
                    }
                }else{
                    viewer.rowIndex = viewer.hoverIndex;
                }
            }
            this.changePagerTips();

            return this;
        },

        // 设置分页的提示信息
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

                if(pager.totalRows > pager.pageSize) elems.tips[1].addClass(viewer.pagerTips.down_active);
            }

            elems.tips[2].html((viewer.hoverIndex + 1) + ' | ' + pager.totalRows);

            if(typeof i == 'undefined') i = 1;
            var rows = viewer.scrollRows;

            if(pager.totalRows > pager.pageSize){
//                elems.tips[3].show();
            }else{
                return;
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

        // 设置自定义事件
        setCustomRemote : function(value){
            var customRemote = this.customRemote;
            $.extend(customRemote, value || {});
            this.setDirection();
        },

        // 设置初始化
        setOptions  : function(options){
            var scope = this, options = options || {};
            $.each(scope._attrs, function(key, value){
                if(value in scope){
                    scope['set' + value.replace(/^([\w])/i, function(v){
                        return v.toUpperCase();
                    })](options[value] || {});

                    delete options[value];
                }
            });
            $.extend(true, this, options);
        },

        // 设置当前的 hoverIndex 
        changeHoverIndex : function(i){
            this.viewer.hoverIndex += i;
        },
        
        // 设置当前的 hover
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

        // 设置当前的 active
        changeActive    : function(){
            var hoverIndex = this.viewer.hoverIndex,
            hover = this.getElem(hoverIndex),
            active = this.elements.active,
            hoverClass = this.viewer.hoverClass,
            activeClass = this.viewer.activeClass;
            if(hover.length && hover.hasClass(hoverClass) && !hover.hasClass(activeClass)){
                active.removeClass(activeClass);
                hover.addClass(activeClass);
                this.viewer.activeIndex = hoverIndex;
                this.elements.active = hover;
            }
        },

        // 取得 nodes 中的元素
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
            elems.container.bind(this.uuid + '.keydown', function(event, docEvent){
                var keyCode = $.keyCode;
                var evt = scope.keyCode = docEvent;
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
                        if($.Remote.downOrclick == 1) return;
                        scope.ok();
                        break;
                    default:
                        break;
                };
                event.stopPropagation();
                event.preventDefault();
            });

            elems.container.bind(this.uuid + '.click', function(event, docEvent){
                if($.Remote.downOrclick == 13) return;
                scope.ok();
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

        // 默认滚动事件
        _scroll     : function(i){
            var pager = this.pager;
            if(pager.hasPager){
                this._normalScroll(i);
            }
        },
        
        // 默认滚动方式
        _normalScroll : function(i){
            if(Remote.instance.uuid != this.uuid) return;
            this.changeHover(i);
            var pager = this.pager;
            var viewer = this.viewer;
            var rows = viewer.scrollRows;
            viewer.rowIndex += i;
            if((viewer.rowIndex == rows[0] - 1 && viewer.hoverIndex > rows[0] - 1) ||
                (viewer.rowIndex == rows[1] + 1 && viewer.hoverIndex < pager.totalRows - (pager.pageSize - (rows[1] + 1)))){
                this._scrollCss(i);
                viewer.rowIndex = rows[(i + 1) / 2];
            }
            this.changePagerTips(i);
        },

        // 默认滚动改变的 css 属性
        _scrollCss : function(i){
            var elems = this.elements;
            var viewer = this.viewer;
            var size = parseInt(elems.scroll.css(viewer.scrollType)) || 0;
            elems.scroll.css(viewer.scrollType, size + (-1)*i*viewer.rowSize);
        },

        // 默认向上滚动事件
        _up     : function(){
            if(this.viewer.hoverIndex > 0){
                this.scroll(-1);
            }
        },

        // 默认向下滚动事件
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

        // 离开操作块默认触发事件
        _onleave  : function(){
            if(Remote.instance && Remote.instance.uuid == this.uuid){
                this.elements.block.removeClass(this.viewer.focusClass);
                var hover = this.getElem(this.viewer.hoverIndex);
                if(hover) hover.removeClass(this.viewer.hoverClass);
            }
        },

        // 进入操作块默认触发事件
        _onenter  : function(){
            if(Remote.instance && Remote.instance.uuid == this.uuid){
                this.elements.block.addClass(this.viewer.focusClass);
                var hover = this.getElem(this.viewer.hoverIndex);
                if(hover) hover.addClass(this.viewer.hoverClass);
            }
        }
    });

    /**
     * @private Remote class private functions / properties
     * Remote 类 的 私有方法/属性
     */
    $.extend(Remote.prototype._private = function(){}, {});

    /**
     * 扩展 jQuery 对象
     * @static
     */
    $.R = $.Remote = Remote;

    // 初始化绑定事件
    Remote.bindDoc();

})(jQuery);
/**
 * change log : 'ok' 键改成 document.onclick 触发事件
 */