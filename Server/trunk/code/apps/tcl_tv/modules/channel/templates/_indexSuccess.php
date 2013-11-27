<script type="text/javascript">
    // $.epg 对象
    $.epg = {};
    // 全局属性
    $.epg.app = {
        channel_id : <?php echo $channel_id; ?>,
        week : <?php echo $week; ?>,
        date : '<?php echo $date; ?>',
        baseUrl : '<?php echo url_for('@homepage'); ?>',
        tag_name : '',
        wiki_id : 0,
        menu_1 : '',
        menu_2 : '',
        menu_3 : ''
    };
    // $.epg.AJ ajax 调用函数
    $.epg.AJ = function(module, action, params, callback){
        $.ajax({
            url: $.epg.app.baseUrl + module + '/' + action,
            type: 'post',
            data: params,
            success: function(data){
                callback.call(this, data)
            }
        });
    };

    $(function(){
        // 左侧 －－ 第一层菜单
        var nav_1 = new $.Remote({
            elements : {
                block : $('#side-navi'),
                nodes : $('.channel-menu1 .action'),
                container : $('.channel-menu1')
            },
            pager : {
                pageSize : 10
            },
            viewer : {
                rowHeight   : 50
            },
            //            isDefault : true,
            customRemote : {
                ok : function(){
                    var status = 1;
                    var hover = this.getElem(this.viewer.hoverIndex);
                    var rel = hover.attr('rel');
                    this._ok();
                    rel = $.parseJSON(rel);
                    if($.isPlainObject(rel)){
                        if(rel.target == 'all_live'){
                            $('#search').addClass('hidden');
                            $('#program-recommended').addClass('hidden');
                            $('#cats').removeClass('hidden');
                            $('#cats>h2').html('<span>正在直播</span>');
                            $('#weeks').addClass('hidden');
                            $('#tv-listings').removeClass('hidden');
                            $('.tv-listings').removeClass('search-result-lists');
                            $.epg.AJ('program', 'ajax_all_live', {}, function(data){
                                $('.tv-listings ul').replaceWith(data);
                                content.rebuild({
                                    elements : {
                                        nodes : $('.tv-listings>ul .action')
                                    },
                                    pager : {
                                        pageSize : 11
                                    },
                                    viewer : {
                                        scrollRows : [5, 5]
                                    }
                                });
                            });
                        }else if(rel.target == 'search'){
                            //                            console.log(rel.target);
                            $('#search').removeClass('hidden');
                            $('#program-recommended').removeClass('hidden');
                            $('#cats').addClass('hidden');
                            $('#cats>h2').html('<span>搜索结果</span>');
                            $('#weeks').addClass('hidden');
                            $('#tv-listings').addClass('hidden');
                            //                            this.elements.container.hide();
                            this.changeBlock(search);
                            //                            nav_2.elements.container.show();
                            channel_left = nav_1;
                            status = 0;
                        }else if(rel.target == 'tags'){
                            //                            console.log(rel.target)
                            this.elements.container.hide();
                            this.changeBlock(nav_4);
                            nav_4.elements.container.show();
                            channel_left = nav_4;
                            return;
                        }
                        if(status && rel.sub == 1){
                            this.elements.container.hide();
                            this.changeBlock(nav_2);
                            nav_2.elements.container.show();
                            channel_left = nav_2;
                        }
                    }
                },
                right : function(){
                    this.changeBlock(nav_right);
                }
            }
        });

        // 左侧－－第二层菜单
        var nav_2 = new $.Remote({
            elements : {
                block : $('#side-navi'),
                nodes : $('.channel-menu2 .action'),
                container : $('.channel-menu2')
            },
            viewer : {
                rowHeight   : 50
            },
            customRemote : {
                ok : function(){
                    var hover = this.getElem(this.viewer.hoverIndex);
                    var rel = hover.attr('rel');
                    rel = $.parseJSON(rel);
                    var scope = this;
                    this._ok();
                    if($.isPlainObject(rel)){
                        if(rel.sub == 2){
                            $.epg.AJ(
                            'channel', 'ajax_get_channels',{tv_station_id: rel.tv_station_id}, function(data){
                                $('.channel-menu3>ul').replaceWith(data);
                                scope.elements.container.hide();
                                scope.changeBlock(nav_3);
                                nav_3.elements.container.show();
                                channel_left = nav_3;
                                nav_3.rebuild({
                                    elements : {
                                        container : $('.channel-menu3'),
                                        nodes : $('.channel-menu3 .action')
                                    }
                                });
                            }
                        );
                        //本地栏目
                        }else if(rel.sub == 3){
                            $.epg.AJ(
                            'tv_station', 'show_local_channel',{type: rel.action}, function(data){
                                $('.channel-menu_local>ul').replaceWith(data);
                                scope.elements.container.hide();
                                scope.changeBlock(nav_local);
                                nav_local.elements.container.show();
                                channel_left = nav_local;
                                nav_local.rebuild({
                                    elements : {
                                        container : $('.channel-menu_local'),
                                        nodes : $('.channel-menu_local .action')
                                    }
                                });
                            }
                        );
                        }else if(rel.sub == 4){
                            $.epg.AJ(
                            'tv_station', 'show_tv',{type: rel.action}, function(data){
                                $('.channel-menu_tv>ul').replaceWith(data);
                                scope.elements.container.hide();
                                scope.changeBlock(nav_tv);
                                nav_tv.elements.container.show();
                                channel_left = nav_tv;
                                nav_tv.rebuild({
                                    elements : {
                                        container : $('.channel-menu_tv'),
                                        nodes : $('.channel-menu_tv .action')
                                    }
                                });
                            }
                        );
                        //教育
                        }else if(rel.sub == 5){
                            $.epg.AJ(
                            'tv_station', 'show_tv',{type: rel.action}, function(data){
                                $('.channel-menu_edu>ul').replaceWith(data);
                                scope.elements.container.hide();
                                scope.changeBlock(nav_edu);
                                nav_edu.elements.container.show();
                                channel_left = nav_edu;
                                nav_edu.rebuild({
                                    elements : {
                                        container : $('.channel-menu_edu'),
                                        nodes : $('.channel-menu_edu .action')
                                    }
                                });
                            }
                        );
                        }
                    }
                },
                right : function(){
                    this.changeBlock(content);
                },
                left : function(){
                    this.elements.container.hide();
                    this.changeBlock(nav_1);
                    nav_1.elements.container.show();
                    channel_left = nav_1;
                }
            }
        });

        // 左侧－－第三层菜单
        var nav_3 = new $.Remote({
            elements : {
                block : $('#side-navi'),
                nodes : $('.channel-menu3 .action'),
                container : $('.channel-menu3')
            },
            viewer : {
                rowHeight   : 50
            },
            customRemote : {
                ok : function(){
                    this._ok();
                    var scope = this;
                    $('#search').addClass('hidden');
                    $('#program-recommended').addClass('hidden');
                    $('#cats').addClass('hidden');
                    $('#weeks').removeClass('hidden');
                    //                    $('#tv-listings').addClass('hidden');
                    var id = this.elements.active.attr('id').match(/(\d+)$/)[0];
                    var name = this.elements.active.text();
                    $('#weeks>h2').html('<span>'+name+'</span>');
                    $.epg.app.channel_id = id;
                    $.epg.AJ('program', 'ajax_weekday', {
                        week : $.epg.app.week,
                        channel_id : $.epg.app.channel_id
                    }, function(data){
                        $('.tv-listings').removeClass('search-result-lists');
                        $('.tv-listings ul').replaceWith(data);
                        content.rebuild({
                            elements : {
                                //                        block : $('.content-the-loop'),
                                nodes : $('.tv-listings>ul .action')
                            },
                            pager : {
                                pageSize : 11
                            },
                            viewer : {
                                scrollRows : [5, 5]
                            }
                        });
                        scope.changeBlock(content);
                    });
                },
                right : function(){
                    this.changeBlock(content);
                },
                left : function(){
                    this.elements.container.hide();
                    this.changeBlock(nav_2);
                    nav_2.elements.container.show();
                    channel_left = nav_2;
                }
            }
        });
        // 左侧－－本地菜单
        var nav_local = new $.Remote({
            elements : {
                block : $('#side-navi'),
                nodes : $('.channel-menu_local .action'),
                container : $('.channel-menu_local')
            },
            viewer : {
                rowHeight   : 50
            },
            customRemote : {
                ok : function(){
                    this._ok();
                    var scope = this;
                    $('#search').addClass('hidden');
                    $('#program-recommended').addClass('hidden');
                    $('#cats').addClass('hidden');
                    $('#weeks').removeClass('hidden');
                    //                    $('#tv-listings').addClass('hidden');
                    var id = this.elements.active.attr('id').match(/(\d+)$/)[0];
                    var name = this.elements.active.text();
                    $('#weeks>h2').html('<span>'+name+'</span>');
                    $.epg.app.channel_id = id;
                    $.epg.AJ('program', 'ajax_weekday', {
                        week : $.epg.app.week,
                        channel_id : $.epg.app.channel_id
                    }, function(data){
                        $('.tv-listings').removeClass('search-result-lists');
                        $('.tv-listings ul').replaceWith(data);
                        content.rebuild({
                            elements : {
                                //                        block : $('.content-the-loop'),
                                nodes : $('.tv-listings>ul .action')
                            },
                            pager : {
                                pageSize : 11
                            },
                            viewer : {
                                scrollRows : [5, 5]
                            }
                        });
                        scope.changeBlock(content);
                    });
                },
                right : function(){
                    this.changeBlock(content);
                },
                left : function(){
                    this.elements.container.hide();
                    this.changeBlock(nav_2);
                    nav_2.elements.container.show();
                    channel_left = nav_2;
                }
            }
        });
        //卫视
        var nav_tv = new $.Remote({
            elements : {
                block : $('#side-navi'),
                nodes : $('.channel-menu_tv .action'),
                container : $('.channel-menu_tv')
            },
            viewer : {
                rowHeight   : 50
            },
            customRemote : {
                ok : function(){
                    this._ok();
                    var scope = this;
                    $('#search').addClass('hidden');
                    $('#program-recommended').addClass('hidden');
                    $('#cats').addClass('hidden');
                    $('#weeks').removeClass('hidden');
                    //                    $('#tv-listings').addClass('hidden');
                    var id = this.elements.active.attr('id').match(/(\d+)$/)[0];
                    var name = this.elements.active.text();
                    $('#weeks>h2').html('<span>'+name+'</span>');
                    $.epg.app.channel_id = id;
                    $.epg.AJ('program', 'ajax_weekday', {
                        week : $.epg.app.week,
                        channel_id : $.epg.app.channel_id
                    }, function(data){
                        $('.tv-listings').removeClass('search-result-lists');
                        $('.tv-listings ul').replaceWith(data);
                        content.rebuild({
                            elements : {
                                //                        block : $('.content-the-loop'),
                                nodes : $('.tv-listings>ul .action')
                            },
                            pager : {
                                pageSize : 11
                            },
                            viewer : {
                                scrollRows : [5, 5]
                            }
                        });
                        scope.changeBlock(content);
                    });
                },
                right : function(){
                    this.changeBlock(content);
                },
                left : function(){
                    this.elements.container.hide();
                    this.changeBlock(nav_2);
                    nav_2.elements.container.show();
                    channel_left = nav_2;
                }
            }
        });
        //教育
        var nav_edu = new $.Remote({
            elements : {
                block : $('#side-navi'),
                nodes : $('.channel-menu_edu .action'),
                container : $('.channel-menu_edu')
            },
            viewer : {
                rowHeight   : 50
            },
            customRemote : {
                ok : function(){
                    this._ok();
                    var scope = this;
                    $('#search').addClass('hidden');
                    $('#program-recommended').addClass('hidden');
                    $('#cats').addClass('hidden');
                    $('#weeks').removeClass('hidden');
                    //                    $('#tv-listings').addClass('hidden');
                    var id = this.elements.active.attr('id').match(/(\d+)$/)[0];
                    var name = this.elements.active.text();
                    $('#weeks>h2').html('<span>'+name+'</span>');
                    $.epg.app.channel_id = id;
                    $.epg.AJ('program', 'ajax_weekday', {
                        week : $.epg.app.week,
                        channel_id : $.epg.app.channel_id
                    }, function(data){
                        $('.tv-listings').removeClass('search-result-lists');
                        $('.tv-listings ul').replaceWith(data);
                        content.rebuild({
                            elements : {
                                //                        block : $('.content-the-loop'),
                                nodes : $('.tv-listings>ul .action')
                            },
                            pager : {
                                pageSize : 11
                            },
                            viewer : {
                                scrollRows : [5, 5]
                            }
                        });
                        scope.changeBlock(content);
                    });
                },
                right : function(){
                    this.changeBlock(content);
                },
                left : function(){
                    this.elements.container.hide();
                    this.changeBlock(nav_2);
                    nav_2.elements.container.show();
                    channel_left = nav_2;
                }
            }
        });

        // 左侧－－第二层菜单[当第一层选中 “栏目”]
        var nav_4 = new $.Remote({
            elements : {
                block : $('#side-navi'),
                nodes : $('.channel-menu4 .action'),
                container : $('.channel-menu4')
            },
            viewer : {
                rowHeight   : 50
            },
            customRemote : {
                ok : function(){
                    this._ok();
                    var scope = this;
                    $('#search').addClass('hidden');
                    $('#program-recommended').addClass('hidden');
                    $('#cats').addClass('hidden');
                    $('#weeks').removeClass('hidden');
                    //                    //                    $('#tv-listings').addClass('hidden');
                    var tag_name = this.elements.active.text();
                    $.epg.app.tag_name = tag_name;
                    $('#weeks>h2').html('<span>'+tag_name+'</span>');
//                    console.log(tag_name);
                    $.epg.AJ('tags', 'ajax_get_tag_programs', {week: $.epg.app.week, tag: tag_name}, function(data){
//                        console.log(data)
                        $('.tv-listings').removeClass('search-result-lists');
                        $('.tv-listings ul').replaceWith(data);
                        content.rebuild({
                            elements : {
                                block : $('.content-the-loop'),
                                nodes : $('.tv-listings>ul .action')
                            },
                            pager : {
                                pageSize : 11
                            },
                            viewer : {
                                scrollRows : [5, 5]
                            }
                        });
                        scope.changeBlock(content);
                    });
                },
                right : function(){
                    this.changeBlock(content);
                },
                left : function(){
                    this.elements.container.hide();
                    this.changeBlock(nav_1);
                    nav_1.elements.container.show();
                    channel_left = nav_1;
                }
            }
        });

        // 右侧－－节目单列表
        var content = new $.Remote({
            elements : {
                block : $('.content-the-loop'),
                nodes : $('.tv-listings>ul .action')
            },
            pager : {
                pageSize : 11
            },
            viewer  : {
                hoverIndex : 5,
                rowHeight   : 47,
                scrollRows : [5, 5],
                pagerTips   : {
                    up: 'arrow-up',
                    down: 'arrow-down',
                    up_active : 'arrow-up-active',
                    down_active : 'arrow-down-active'
                }
                //                tipsClass   : 'light'
            },
            customRemote : {
                left : function(){
                    this.changeBlock(channel_left);
                    nav_right = this;
                },
                bind : function(){
                    this._bind();
                    var scope = this;
                    this.elements.container.bind(this.uuid + '.keydown', function(event, docEvent){
                        var evt = scope.keyCode = docEvent;
                        if(evt.keyCode>=49 && evt.keyCode <=55){
                            if($('#weeks').is(':hidden')) return;
                            changeWeek(evt.keyCode - 49);
                        }
                    });
                    return true;
                },
                ok : function(){
                    if(this.elements.active.length){
                        var oheight = $('.tv-listings').height();
                        var pager = this.pager;
                        var viewer = this.viewer;
                        var h = viewer.rowIndex * viewer.rowHeight;
                        var dh = $('.dropmenus').outerHeight();
                        var rh = h + viewer.rowHeight;
                        if(rh + dh > oheight){
                            rh = h - dh;
                            $('.dropmenus').addClass('up1');
                        }else{
                            $('.dropmenus').removeClass('up1');
                        }

                        $('.dropmenus').css('top', rh).show();
                        var active = this.elements.active;
                        var rel = $.parseJSON(active.attr('rel'));
                        if(rel){
                            $.epg.app.wiki_id = rel.wiki_id || 0;
                        }
                        //                    console.log(dropmenus)
                        this.changeBlock(dropmenus, 'no');
                    }
                },
                up : function(){
                    if(this.viewer.hoverIndex == this.viewer.rowIndex && this.viewer.hoverIndex ==0){
                        if(!$('#cats').is(':hidden')) this.changeBlock(content_navi);
                        return true;
                    }
                },
                down : function(){
                },
                scroll: function(i){
                    var scope = this;
                    var rel = $.parseJSON(this.elements.scroll.attr('rel'));
                    if(rel){
                        //                        console.log('i : ', i, ' rowIndex : ', scope.viewer.rowIndex, ' hoverIndex : ', scope.viewer.hoverIndex);
                        var rows = scope.viewer.scrollRows;
                        var rowIndex = scope.viewer.rowIndex + i;
                        var hoverIndex = scope.viewer.hoverIndex + i;
                        //                        console.log(rowIndex, hoverIndex)
                        var scroll = scope.elements.scroll;
                        if((rowIndex == rows[0] || rowIndex == rows[0] + 1 ) && hoverIndex == (rel.page - 1)*10 + rows[0] && rel.page < Math.ceil(rel.total/10)){
                            if(!scope.ajaxStatus){
                                //                                console.log('ajax search ... ', rowIndex, hoverIndex, (rel.page - 1)*10 + rows[0])
                                $.ajax({
                                    url : '<?php echo url_for('@search'); ?>',
                                    type: 'post',
                                    data: {
                                        q: $.trim($('#search-input').val()), page: rel.page + 1
                                    },
                                    beforeSend : function(data){
                                        //                                        console.log(data);
                                        scope.ajaxStatus = true;
                                    },
                                    success : function(data){
                                        //                                        console.log(data);
                                        var $ul = $(data);
                                        scope.elements.scroll.attr('rel', $ul.attr('rel'));
                                        var $lis = $ul.find('li');
                                        scope.elements.scroll.append($lis);
                                        $.each($lis, function(i, v){
                                            scope.elements.nodes.push(v);
                                        })
                                        scope.elements.nodes.add($lis);
                                        scope.elements.length += $lis.length;
                                        scope.pager.totalRows = scope.elements.length;
                                        scope.ajaxStatus = false;
                                    }
                                });
                            }
                            //                            $.epg.AJ('search', 'index', {q: $.trim($('#search-input').val()), page: rel.page}, function(data){
                            //                               console.log(data)
                            //                            });
                        }else{
                            //                            scope.viewer.rowIndex = rowIndex;
                            //                            scope.viewer.hoverIndex = hoverIndex;
                            //                            this.changeHover(i);
                        }

                        if(!scope.ajaxStatus){
                            //                        console.log('...............')
                            scope.viewer.rowIndex = rowIndex;
                            var viewer = this.viewer;
                            var pager = this.pager;
                            this.changeHover(i);
                            if((viewer.rowIndex == rows[0] - 1 && viewer.hoverIndex > rows[0] - 1) ||
                                (viewer.rowIndex == rows[1] + 1 && viewer.hoverIndex < pager.totalRows - (pager.pageSize - (rows[1] + 1)))){
                                this._scrollCss(i);
                                viewer.rowIndex = rows[(i + 1) / 2];
                            }
                            this.changePagerTips(i);
                        }

                        return true;
                    }
                }
            },
            isDefault : true
        });

        // 右侧－－弹出层菜单
        var dropmenus = new $.R({
            elements : {
                block : $('#content'),
                nodes : $('.dropmenus .action')
            },
            viewer :{
                hoverIndex : 3
            },
            customRemote : {
                ok : function(){
                    this._ok();
                    var scope = this;
                    $('.dropmenus').hide();
                    scope.changeBlock(content);
                    if(this.elements.activeIndex == 0){

                        var wiki_id = $.epg.app.wiki_id || 0;;
                        //                        console.log(wiki_id);
                        $.epg.AJ('wiki', 'show', {id:5/*$.epg.app.wiki_id*/}, function(data){
                            if(data == 0){
                                return;
                            }
                            $('#inner').hide();
                            $.R.prevBlock = content;
                            $('#wrapper').append(data);
                        });
                    }


                    //                    $('.dropmenus').hide();
                    //                    this.changeBlock(content);
                }
            }
        });

        // 右侧－－顶部分类导航
        var content_navi = new $.R({
            elements : {
                block : $('.content-navi'),
                nodes : $('.content-cats .action')
            },
            viewer : {
                scrollRows : [3, 3],
                xy  : 'x',
                pagerTips   : {
                    up: 'arrow-left',
                    down: 'arrow-right',
                    up_active : 'arrow-left-active',
                    down_active : 'arrow-right-active'
                }
            },
            pager : {
                pageSize : 8
            },
            customRemote : {
                left : function(){
                    this._up();
                },
                right : function(){
                    this._down();
                },
                scroll : function(i){
                    var n = i[0] + this.viewer.hoverIndex;
                    var hover = this.getElem(n);
                    if(hover){
                        var w = hover.outerWidth();
                        this.viewer.rowSize = w;
                    }

                },
                down : function(){
                    this.changeBlock(content);
                    return true;
                },
                up : function(){
                    if(!$('#search').hasClass('hidden')){
                        this.changeBlock(search);
                    }
                    return true;
                },
                ok : function(){
                    this._ok();
                    var tag_id = this.elements.active.attr('id').match(/(\d+)$/)[0];
                    $.epg.AJ('program', 'ajax_live_tagid', {tag_id : tag_id}, function(data){
                        $('.tv-listings ul').replaceWith(data);
                        if($('#search').hasClass('hidden')){
                            content.rebuild({
                                elements : {
                                    nodes : $('.tv-listings>ul .action')
                                },
                                pager : {
                                    pageSize : 11
                                },
                                viewer : {
                                    scrollRows : [5, 5]
                                }
                            });
                        }else{
                            content.rebuild({
                                elements : {
                                    nodes : $('.tv-listings>ul .action')
                                },
                                viewer: {
                                    scrollRows: [4, 4]
                                },
                                pager : {
                                    pageSize : 8
                                }
                            });
                        }

                    });
                }
            }
        });

        // 右侧－－搜索栏
        var search = new $.R({
            elements : {
                block : $('.search-box'),
                nodes : $('.search-box .action')
            },
            customRemote : {
                ok : function(){
                    var scope = this;
                    //                    console.log('search ... ');
                    //                    console.log(this.elements.active.get(0).nodeName);
                    this.elements.active.blur();
                    //                    console.log($.trim(this.elements.active.val()));

                    var value = $.trim(this.elements.active.val());

                    $.epg.AJ('search', 'index', {q: value, page: 1}, function(data){
                        //                        console.log(data)
                        $('#program-recommended').addClass('hidden');
                        $('#tv-listings').removeClass('hidden');
                        $('.tv-listings').addClass('search-result-lists');
                        $('.tv-listings ul').replaceWith(data);
                        $('#cats').removeClass('hidden');
                        content.rebuild({
                            elements : {
                                nodes : $('.tv-listings>ul .action')
                            },
                            viewer: {
                                scrollRows: [4, 4]
                            },
                            pager : {
                                pageSize : 8
                            }
                        });

                        scope.changeBlock(content);
                    });

                    return true;
                },
                onenter : function(){
                    this.elements.active.focus();
                },
                down : function(){
                    if(!$('#cats').hasClass('hidden')){
                        this.changeBlock(content_navi);
                    }

                    return true;
                },
                left : function(){
                    //                    this.elements.container.hide();
                    this.changeBlock(nav_1);
                    //                    nav_1.elements.container.show();
                    channel_left = nav_1;
                    nav_right = this;
                }
            }
        });

        $('#search-form').submit(function(){
            return false;
        });

        //              console.log(content_navi)

        // 取 周一~周日一天的数据
        function changeWeek(i){
            var $i = $('#weekday li').eq(i);
            $.epg.app.week = i+1;
            if($i.hasClass('active')) return;
            var active = $('#weekday li').filter('.active');
            active.removeClass('active');
            $i.addClass('active');
//                console.log($('.channel-menu4').is(':hidden'))
            if($('.channel-menu4').is(':hidden')){

                $.epg.AJ('program', 'ajax_weekday', {week: i+1, channel_id: $.epg.app.channel_id}, function(data){
                    $('.tv-listings ul').replaceWith(data);
                    $('.tv-listings').removeClass('search-result-lists');
                    content.rebuild({
                        elements : {
                            //                        block : $('.content-the-loop'),
                            nodes : $('.tv-listings>ul .action')
                        },
                        pager : {
                            pageSize : 11
                        },
                        viewer : {
                            scrollRows : [5, 5]
                        }
                    });
                });
            }else{
                $.epg.AJ('tags', 'ajax_get_tag_programs', {week: i+1, tag: $.epg.app.tag_name}, function(data){
                    $('.tv-listings ul').replaceWith(data);
                    $('.tv-listings').removeClass('search-result-lists');
                    content.rebuild({
                        elements : {
                            //                        block : $('.content-the-loop'),
                            nodes : $('.tv-listings>ul .action')
                        },
                        pager : {
                            pageSize : 11
                        },
                        viewer : {
                            scrollRows : [5, 5]
                        }
                    });
                });
            }
        };

        // 取出所有 分类
        $.epg.AJ('tags', 'ajax_get_all_tags', {}, function(data){
            $(data).appendTo('.content-cats');
            content_navi.rebuild({
                elements : {
                    nodes : $('.content-navi .action')
                }
            });
        });

        // 取出所有正在直播的节目单
        $.epg.AJ('program', 'ajax_all_live', {}, function(data){
            $('.tv-listings ul').replaceWith(data);
            $('.tv-listings').removeClass('search-result-lists');
            content.rebuild({
                elements : {
                    //                    block : $('.content-the-loop'),
                    nodes : $('.tv-listings>ul .action')
                },
                pager : {
                    pageSize : 11
                },
                viewer : {
                    scrollRows : [5, 5]
                }
            });
        });

        // 取出周一~周日类表
        $.epg.AJ('channel', 'ajax_get_weekdays', {}, function(data){
            $(data).appendTo($('.content-weeks'));
        });
        //
        //        $.epg.AJ('program', 'ajax_weekday', {
        //            week : $.epg.app.week,
        //            channel_id : $.epg.app.channel_id
        //        }, function(data){
        //            $('.tv-listings ul').replaceWith(data);
        //            content.rebuild({elements : {
        //                    nodes : $('.tv-listings>ul .action')
        //                }});
        //        });


        // 设置左边操作块
        var channel_left = nav_1;
        // 设置右边操作块
        var nav_right = content;
    });
</script>
<div id="header">
    <div class="utility">
        <h1><img src="<?php echo image_path('epg_logo.png') ?>" width="230" height="54" alt=""></h1>
        <ul>
            <li class="location">上海</li>
            <li class="weather">35℃</li>
            <li class="time">PM 3:05</li>
            <li class="signal-strong">&nbsp;</li>
        </ul>
    </div>
</div>
<div id="inner">
    <div id="side-navi">
        <div class="cats channel-menu1">
            <ul>
                <li class="action"><span>首页</span></li>
                <li class="action active" rel='{"sub":0, "type": "ajax", "target": "all_live"}'><span>直播</span></li>
                <li class="action" rel='{"sub":1, "type": "ajax", "target": "all_channels"}'><span>频道</span></li>
                <li class="action" rel='{"sub":1, "type": "ajax", "target": "tags"}'><span>栏目</span></li>
                <li class="action"><span>热播</span></li>
                <li class="action"><span>赛事</span></li>
                <li class="action"><span>院线</span></li>
                <li class="action"><span>订阅</span></li>
                <li class="action" rel='{"sub":1, "type": "noajax", "target": "search"}'><span>搜索</span></li>
            </ul>
        </div>
        <div class="cats channel-menu2 hidden">
            <ul>
                <li class="action" rel='{"sub":3, "type": "ajax"}'><span>本地</span></li>
                <li class="action" rel='{"sub":2, "type": "ajax", "tv_station_id": 1}'><span>央视</span></li>
                <li class="action" rel='{"sub":4, "type": "ajax","action": "tv"}'><span>卫视</span></li>
                <li class="action" rel='{"sub":5, "type": "ajax","action": "edu"}'><span>教育</span></li>
                <li class="action"><span>数字</span></li>
                <li class="action"><span>高清</span></li>
                <li class="action"><span>境外</span></li>
            </ul>
        </div>
        <div class="cats channel-menu3 hidden">
            <ul>
                <li class="action"><span>CCTV1</span></li>
                <li class="action"><span>CCTV2</span></li>
                <li class="action"><span>CCTV3</span></li>
                <li class="action"><span>CCTV4</span></li>
                <li class="action"><span>CCTV5</span></li>
                <li class="action"><span>CCTV6</span></li>
                <li class="action"><span>CCTV7</span></li>
                <li class="action"><span>CCTV8</span></li>
                <li class="action"><span>CCTV9</span></li>
                <li class="action"><span>CCTV10</span></li>
                <li class="action"><span>CCTV11</span></li>
                <li class="action"><span>CCTV12</span></li>
                <li class="action"><span>CCTV13</span></li>
                <li class="action"><span>CCTV14</span></li>
                <li class="action"><span>CCTV15</span></li>
            </ul>
        </div>
        <div class="cats channel-menu_local hidden">
            <ul id="show_channel">
                <li class="action"><span>CCTV1</span></li>
            </ul>
        </div>
        <div class="cats channel-menu_tv hidden">
            <ul>
                <li class="action"><span>CCTV1</span></li>
            </ul>
        </div>
        <div class="cats channel-menu_edu hidden">
            <ul>
                <li class="action"><span>CCTV1</span></li>
            </ul>
        </div>
        <div class="cats channel-menu4 hidden">
            <ul>
                <li class="action"><span>新闻类</span></li>
                <li class="action"><span>娱乐类</span></li>
                <li class="action"><span>体育类</span></li>
                <li class="action"><span>生活类</span></li>
                <li class="action"><span>连续剧</span></li>
                <li class="action"><span>电影</span></li>
                <li class="action"><span>动画片</span></li>
                <li class="action"><span>纪录片</span></li>
                <li class="action"><span>外语</span></li>
            </ul>
        </div>

        <div class="arrow hidden">
            <div class="arrow-up"></div>
            <div class="arrow-down-active"></div>
        </div>
    </div>
    <div id="content">
        <div class="search-box hidden" id="search">
            <form id="search-form" action="<?php echo url_for('@search'); ?>" >
                <input type="text" class="textfield action" id="search-input" />
                <input class="button" type="button" value="搜索" />
            </form>
        </div>

        <div class="program-recommended hidden" id="program-recommended">
            <h3>热门推荐</h3>
            <div class="program-recommended-lists">
                <ul>
                    <li><img src="pub/s1429766.jpg" width="100" height="150" alt="" /></li>
<!--                    <li class="hover"><img src="pub/s1801295.jpg" width="100" height="150" alt="" /></li>-->
                    <li><img src="pub/s1986504.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s2765052.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s3400809.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s4207957.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s3139131.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s3169603.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s3866706.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s3959616.jpg" width="100" height="150" alt="" /></li>
                    <li><img src="pub/s4226652.jpg" width="100" height="150" alt="" /></li>

                    <li><img src="pub/s3592077.jpg" width="100" height="150" alt="" /></li>
                </ul>
            </div>
        </div>

        <div class="content-navi" id="cats">
<!--            <h2><img src="<?php echo image_path('channel/cctv1.png') ?>" width="140" height="60" alt=""></h2>-->
            <h2><span>正在直播</span></h2>
            <div class="content-cats"></div>
            <div class="arrow hidden">
                <div class="clear"></div>
                <div class="arrow-left"></div>
                <div class="arrow-right"></div>
            </div>
        </div>
        <div class="content-navi hidden" id="weeks">
            <h2><img src="<?php echo image_path('channel/cctv1.png') ?>" width="140" height="60" alt=""></h2>
            <div class="content-weeks"></div>
        </div>
        <div class="content-the-loop" id="tv-listings">
            <div class="content-dir arrow hidden">
                <div class="arrow-up"></div>
                <div class="arrow-down"></div>
            </div>
            <div class="tv-listings">
                <ul></ul>
                <div class="clear"></div>
                <div class="dropmenus down1 hidden" id="dropmenus">
                    <!--                <div class="dropmenus down1">-->
                    <div class="action">详情</div>
                    <div class="action">关注</div>
                    <div class="action">推荐</div>
                    <div class="action last">取消</div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
