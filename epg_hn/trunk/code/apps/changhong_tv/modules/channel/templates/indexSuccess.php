<script type="text/javascript">
    var hour_str = '<?php echo date('H'); ?>';
    var min_str = '<?php echo date('i'); ?>';
    var sce_str = '<?php echo date('s'); ?>';

    // $.epg 对象
    $.epg = {};
    // 全局属性
    $.epg.attrs = {
        channel_id : <?php echo $channel_id; ?>,
        week : <?php echo $week ? $week : 7; ?>,
        today_week : <?php echo $week ? $week : 7; ?>,
        today   : '<?php echo $date; ?>',
        date : '<?php echo $date; ?>',
        baseUrl : '<?php echo url_for('@homepage', true); ?>',
        program_id : '',
        tag_name : '',
        wiki_id : 0,
        left : false,
        right : false,
        login : <?php echo ($sf_user->getAttribute('user_key') != 'guest') ? 0 : -1; ?>
    };
    // $.epg.AJ ajax 调用函数
    $.epg.AJ = function(module, action, params, callback){
        $.ajax({
            url: $.epg.attrs.baseUrl + module + '/' + action,
            type: 'post',
            data: params,
            success: function(data){
                callback.call(this, data)
            }
        });
    };

    $(function(){
        $(".loading").ajaxStart(function(){
            $('.no-data').addClass('display-none');
            $(this).show();
        }).ajaxStop(function(){
            $(this).hide();
        });

        var dropmenu = $('.drop-menu1').length ? $('.drop-menu1') : $('.drop-menu3');

        /**
         * 左侧--第一层菜单
         */
        var menu1 = new $.R({
            elements : {
                block : $('.channel-menu1'),
                nodes : $('.channel-menu1 .action'),
                container : $('.channel-menu1')
            },
            viewer : {
//                hoverIndex : 2,
                rowHeight   : 45,
                scrollRows  : [2, 9],
                pagerTips   : {
                    up: 'side-navi-uarr',
                    down: 'side-navi-darr',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 12
            },
            isDefault : true,
            customRemote : {
                onenter : function(){
                    $('.side-navi-title').text(this.elements.active.text());
                },
                ok : function(){
                    this._ok();
                    var name = this.elements.active.text();
                    $('.side-navi-title').text(name);
                    var hover = this.getElem(this.viewer.hoverIndex);
                    var rel = hover.attr('rel');
                    var cblock = false, left = false;
                    $('.content-title').text(name);
                    rel = $.parseJSON(rel);
                    if($.isPlainObject(rel)){
                        $('#content-lists').removeClass('search');
                        if(rel.target == 'home'){
                            $('#default-channels').addClass('display-none');
                            $('#search-hots').addClass('display-none');
                            $('#cats').addClass('display-none');
                            $('#weeks').addClass('display-none');
                            $('#follows').addClass('display-none');
                            $('#search').addClass('display-none');
                            $('#content-lists').addClass('display-none');
                            $('#default-sports').addClass('display-none');
                            $('#default-home').removeClass('display-none');
                            $.epg.attrs.right = home;
                        }else if(rel.target == 'follow'){
                            left = this;
//                            cblock = follow_navi;
                            $.epg.attrs.right = content;
                            $('#default-channels').addClass('display-none');
                            $('#default-home').addClass('display-none');
                            $('#search-hots').addClass('display-none');
                            $('#cats').addClass('display-none');
                            $('#weeks').addClass('display-none');
                            $('#search').addClass('display-none');
                            $('#follows').removeClass('display-none');
                            $('#content-lists').removeClass('display-none');
                            $('#default-sports').addClass('display-none');
                            $.epg.AJ('favorites', 'ajax_get_channel_program', {}, function(data){
                                $('#content-lists .content-loop-body>ul').replaceWith(data);
                                content.rebuild({
                                    elements : {
                                        nodes : $('#content-lists .content-loop-body>ul .action')
                                    },
                                    pager : {
                                        pageSize : 11
                                    },
                                    viewer : {
                                        scrollRows : [1,9]
                                    }
                                });
                            });
                            return;
                        }else if(rel.target == 'sports'){
                            $('#default-channels').addClass('display-none');
                            $('#default-home').addClass('display-none');
                            $('#search-hots').addClass('display-none');
                            $('#cats').addClass('display-none');
                            $('#weeks').addClass('display-none');
                            $('#search').addClass('display-none');
                            $('#follows').addClass('display-none');
                            $('#content-lists').addClass('display-none');
                            $('#default-sports').removeClass('display-none');
                            $.epg.attrs.right = game;
                        }else if(rel.target == 'default-static'){
                            cblock = left = this;
                            $('#default-channels').removeClass('display-none');
                            $('#search-hots').addClass('display-none');
                            $('#cats').addClass('display-none');
                            $('#follows').addClass('display-none');
                            $('#weeks').addClass('display-none');
                            $('#search').addClass('display-none');
                            $('#content-lists').addClass('display-none');
                            $('#default-sports').addClass('display-none');
                            $('#default-home').addClass('display-none');
                            updateAjax({data_name:rel.param});
                            $.epg.attrs.right = default_channels;
                            $('.no-data').addClass('display-none');
                        }else if(rel.target == 'all_live'){
                            left = this;
                            cblock = content;
                            $.epg.attrs.right = content;
                            $('#cats').removeClass('display-none');
                            $('#weeks').addClass('display-none');
                            $('#follows').addClass('display-none');
                            $('#search').addClass('display-none');
                            $('#default-channels').addClass('display-none');
                            $('#default-sports').addClass('display-none');
                            $('#default-home').addClass('display-none');
                            $('#search-hots').addClass('display-none');
                            $('#content-lists').removeClass('display-none');
                             /**
                             * 取出所有分类
                             */
//                            $.epg.AJ('tags', 'ajax_get_all_tags', {}, function(data){
//                                $(data).appendTo('#cats>.content-top-navi-body');
//                                content_navi.rebuild({
//                                    elements : {
//                                        nodes : $('ul#all-tags .action')
//                                    }
//                                });
//                            });

                        content_navi.viewer.activeIndex =
                            content_navi.viewer.hoverIndex =
                                content_navi.viewer.rowIndex = 0;
                            content_navi.elements.scroll.css('left', 0);

                            var active = $('#all-tags li').filter('.active');
            active.removeClass('active');
            $('#all-tags li').eq(0).addClass('active');
                    content_navi.elements.tips[0].removeClass(content_navi.viewer.pagerTips.up_active);
                        content_navi.elements.tips[1].addClass(content_navi.viewer.pagerTips.down_active);
                            $.epg.AJ('program', 'ajax_all_live', {}, function(data){
                                if(data == 0){
                                    $('.no-data').removeClass('display-none');
                                    $('#content-lists .content-loop-body>ul').empty();
                                    return;
                                }

                                $('#content-lists .content-loop-body>ul').replaceWith(data);
                                content.rebuild({
                                    elements : {
                                        nodes : $('#content-lists .content-loop-body>ul .action')
                                    },
                                    pager : {
                                        pageSize : 11
                                    },
                                    viewer : {
                                        scrollRows : [1,9]
                                    }
                                });
                            });
                        }else if(rel.target == 'tags'){
                            cblock = left = menu4;
                        }else if(rel.target == 'search'){
                            $('#search').removeClass('display-none');
                            $('#search-hots').removeClass('display-none');
                            $('#cats').addClass('display-none');
                            $('#weeks').addClass('display-none');
                            $('#follows').addClass('display-none');
                            $('#default-channels').addClass('display-none');
                            $('#default-home').addClass('display-none');
                            $('#default-sports').addClass('display-none');
                            $('#content-lists').addClass('display-none');
                            $('#content-lists').addClass('search');
                            left = this;
                            cblock = $.epg.attrs.right = search;
                            $('#content-lists>.content-loop-body>ul').empty();
                        }else if(rel.target == 'all_channels'){
                            cblock = left = menu2;
                        }

                        if(left){
                            if(left.elements.block.hasClass('side-navi')){
                                this.elements.block.addClass('display-none');
                                left.elements.block.removeClass('display-none');
                            }
                            if(cblock !== content) this.changeBlock(cblock);
                            $.epg.attrs.left = left;
                        }
                    }
                    return true;
                },
                right : function(){
                    if($('#search').hasClass('display-none') && $('#content-lists').hasClass('display-none') && $('#default-home').hasClass('display-none') && $('#default-sports').hasClass('display-none') && $('default-channels').hasClass('display-none')) return;
                    this.changeBlock($.epg.attrs.right);
                    $.epg.attrs.left = menu1;
                }
            }
        });

        $.epg.attrs.left = menu1;

        /**
         * 左侧--第二层菜单
         */
        var menu2 = new $.R({
            elements : {
                block : $('.channel-menu2'),
                nodes : $('.channel-menu2 .action'),
                container : $('.channel-menu2')
           },
           viewer : {
                rowHeight   : 45,
                scrollRows  : [2, 9],
                pagerTips   : {
                    up: 'side-navi-uarr',
                    down: 'side-navi-darr',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 12
            },
            customRemote : {
                onenter : function(){
                    $('.side-navi-title').text(this.elements.active.text());
                },
                left : function(){
                    this.elements.container.addClass('display-none');
                    this.changeBlock(menu1);
                    menu1.elements.container.removeClass('display-none');
                    $.epg.attrs.left = menu1;
                },
                ok : function(){
                    this._ok();
                    var name = this.elements.active.text();
                    $('.side-navi-title').text(name);
                    var hover = this.getElem(this.viewer.hoverIndex);
                    var rel = hover.attr('rel'), rel = $.parseJSON(rel);
                    var scope = this, module, action, params;
                    if($.isPlainObject(rel)){
                        $('#content-lists').removeClass('search');
                        $('default-channels').addClass('display-none');
                        $('#default-sports').addClass('display-none');
                        $('#default-home').addClass('display-none');
                        if(rel.sub == 1){
                            module = 'favorites';
                            action = 'ajax_get_channel';
                            params = {};
                        }else if(rel.sub == 2){
                            module = 'channel';
                            action = 'ajax_get_channels';
                            params = {tv_station_id: rel.tv_station_id};
                        }else if(rel.sub == 3){
                            module = 'tv_station';
                            action = 'show_local_channel';
                            params = {type: rel.action};
                        }else if(rel.sub == 4){
                            module = 'tv_station';
                            action = 'show_tv';
                            params = {type: rel.action};
                        }else if(rel.sub == 5){
                            module = 'tv_station';
                            action = 'show_tv';
                            params = {type: rel.action};
                        }

                        $.epg.attrs.left = menu3;
                        $.epg.AJ(module, action, params, function(data){
                            $('.channel-menu3>.side-navi-body>ul').replaceWith(data);
                            scope.elements.block.addClass('display-none');
                            scope.changeBlock(menu3);
                            menu3.elements.block.removeClass('display-none');
                            menu3.rebuild({
                                elements : {
                                    container : $('.channel-menu3'),
                                    nodes : $('.channel-menu3 .action')
                                }
                            });
                        });
                    }

                    return true;
                },
                right : function(){
                    if($('#content-lists').hasClass('display-none')) return false;
                    this.changeBlock($.epg.attrs.right);
                    $.epg.attrs.left = this;
                }
            }
        });

        /**
         * 左侧--第三层菜单
         */
        var menu3 = new $.R({
            elements : {
                block : $('.channel-menu3'),
                nodes : $('.channel-menu3 .action'),
                container : $('.channel-menu3')
            },
            viewer : {
                rowHeight   : 45,
                scrollRows  : [2, 9],
                pagerTips   : {
                    up: 'side-navi-uarr',
                    down: 'side-navi-darr',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 12
            },
            customRemote : {
                left : function(){
                    this.elements.block.addClass('display-none');
                    this.changeBlock(menu2);
                    menu2.elements.block.removeClass('display-none');
                    $.epg.attrs.left = menu2;
                },
                ok : function(){
                    this._ok();
                    var scope = this;
                    var id = this.elements.active.attr('id').match(/(\d+)$/)[0];
                    var name = this.elements.active.text();
                    var ssss = '<?php echo ($sf_user->getAttribute('user_key') != 'guest') ? '<span class="on-air">按[0]键收藏该频道</span>':''; ?>';
                    $('.content-title').html(name+ssss);
                    $('#cats').addClass('display-none');
                    $('#follows').addClass('display-none');
                    $('#default-channels').addClass('display-none');
                    $('#default-sports').addClass('display-none');
                    $('#default-home').addClass('display-none');
                    $('#search').addClass('display-none');
                    $('#search-hots').addClass('display-none');
                    $('#weeks').removeClass('display-none');
                    $('#content-lists').removeClass('search');
                    $('#content-lists').removeClass('display-none');

                    $.epg.attrs.channel_id = id;
                    $.epg.attrs.left = this;
                    $.epg.attrs.right = content;
                    $.epg.attrs.week = $.epg.attrs.today_week;
                    $.epg.attrs.date = $.epg.attrs.today;
                    var i;
                    i = weeks_navi.viewer.activeIndex =
                            weeks_navi.viewer.hoverIndex =
                                weeks_navi.viewer.rowIndex = $.epg.attrs.today_week - 1;

                    changeWeek(i);
                    weeks_navi.elements.scroll.css('left', 0);
                    weeks_navi.elements.tips[0].removeClass(weeks_navi.viewer.pagerTips.up_active);
                        weeks_navi.elements.tips[1].addClass(weeks_navi.viewer.pagerTips.down_active);
                    return true;
                },
                right : function(){
                    if($('#content-lists').hasClass('display-none')) return false;
                    this.changeBlock($.epg.attrs.right);
                } ,
                //频道收藏
                bind : function(){
                    this._bind();
                    var scope = this;
                    this.elements.container.bind(this.uuid + '.keydown', function(event, docEvent){
                        var evt = scope.keyCode = docEvent;
                        var hoverIndex = scope.viewer.hoverIndex;
                        var hover = scope.getElem(hoverIndex);
                        if(hover.attr('rel') == 'no') return;
                        var id = hover.attr('id').match(/(\d+)$/)[0];
                        <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>
                        if(evt.keyCode == 48){
                            $.epg.AJ('favorites', 'ajax_create', {
                                content : id,
                                type: 'channel'
                            }, function(data) {
                                if(data.result == true) {
                                    $('.reminder').html('频道收藏成功!').clone().appendTo($('#content')).removeClass('display-none').hide('slow',function(){
                                        $(this).remove();
                                    });
                                }
                            });
                        }
                        <?php endif; ?>
                    });
                    return true;
                }
            }
        });

        /**
         * 左侧--菜单[当第一层选中 “栏目”]
         */
        var menu4 = new $.R({
            elements : {
                block : $('.channel-menu4'),
                nodes : $('.channel-menu4 .action'),
                container : $('.channel-menu4')
            },
            viewer : {
                rowHeight   : 45,
                scrollRows  : [2, 9],
                pagerTips   : {
                    up: 'side-navi-uarr',
                    down: 'side-navi-darr',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 12
            },
            customRemote : {
                left : function(){
                    this.elements.block.addClass('display-none');
                    this.changeBlock(menu1);
                    menu1.elements.block.removeClass('display-none');
                    $.epg.attrs.left = menu1;
                },
                right : function(){
                    this.changeBlock($.epg.attrs.right);
                },
                ok : function(){
                    this._ok();
                    var scope = this;
                    if(this.elements.active.attr('rel') == 'no'){
                        $('.side-navi-title').text(this.elements.active.text());
                        $.epg.AJ('favorites', 'ajax_get_tag', {}, function(data){
                            $('.channel-menu5>.side-navi-body>ul').replaceWith(data);
                            scope.elements.block.addClass('display-none');
                            scope.changeBlock(menu5);
                            $.epg.attrs.left = menu5;
                            menu5.elements.block.removeClass('display-none');
                            menu5.rebuild({
                                elements : {
                                    container : $('.channel-menu5'),
                                    nodes : $('.channel-menu5 .action')
                                }
                            });
                        });
                    }else{
                        $.epg.attrs.left = this;
                        $.epg.attrs.right = content;
                        $('#default-channels').addClass('display-none');
                        $('#default-sports').addClass('display-none');
                        $('#default-home').addClass('display-none');
                        $('#cats').addClass('display-none');
                        $('#follows').addClass('display-none');
                        $('#search').addClass('display-none');
                        $('#search-hots').addClass('display-none');
                        $('#weeks').removeClass('display-none');
                        $('#content-lists').removeClass('search');
                        $('#content-lists').removeClass('display-none');
//                        var name = $.trim(this.elements.active.text());
                        var name = $.trim(this.elements.active.attr('rel'));
                        $.epg.attrs.tag_name = name;
                        $('.content-title').text(name);
                        $.epg.attrs.week = $.epg.attrs.today_week;
                        $.epg.attrs.date = $.epg.attrs.today;
                        var i;
                        i = weeks_navi.viewer.activeIndex =
                                weeks_navi.viewer.hoverIndex =
                                    weeks_navi.viewer.rowIndex = $.epg.attrs.today_week - 1;
                        changeWeek(i);
                        weeks_navi.elements.scroll.css('left', 0);
                        weeks_navi.elements.tips[0].removeClass(weeks_navi.viewer.pagerTips.up_active);
                        weeks_navi.elements.tips[1].addClass(weeks_navi.viewer.pagerTips.down_active);
                    }
                    return true;
                },
                //栏目收藏
                bind : function(){
                    this._bind();
                    var scope = this;
                    this.elements.container.bind(this.uuid + '.keydown', function(event, docEvent){
                        var evt = scope.keyCode = docEvent;
                         var hoverIndex = scope.viewer.hoverIndex;
                        var hover = scope.getElem(hoverIndex);
                        if(hover.attr('rel') == 'no') return;
                        var name = $.trim(hover.text());
                        <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>
                        if(evt.keyCode == 48){
                            $.epg.AJ('favorites', 'ajax_create', {
                                content : name,
                                type: 'tag'
                            }, function(data) {
                                if(data.result == true) {
                                    $('.reminder').html('栏目收藏成功!').clone().appendTo($('#content')).removeClass('display-none').hide('slow',function(){
                                        $(this).remove();
                                    });
                                }
                            });
                        }
                        <?php endif; ?>
                    });
                    return true;
                }
            }
        });


        var menu5 = new $.R({
            elements : {
                block : $('.channel-menu5'),
                nodes : $('.channel-menu5 .action'),
                container : $('.channel-menu5')
            },
            viewer : {
                rowHeight   : 45,
                scrollRows  : [2, 9],
                pagerTips   : {
                    up: 'side-navi-uarr',
                    down: 'side-navi-darr',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 12
            },
            customRemote : {
                left : function(){
                    this.elements.block.addClass('display-none');
                    this.changeBlock(menu4);
                    menu4.elements.block.removeClass('display-none');
                    $.epg.attrs.left = menu4;
                },
                right : function(){
                    if($('#content-lists').hasClass('display-none')) return false;
                    this.changeBlock($.epg.attrs.right);
                },
                ok : function(){
                    this._ok();
                    var scope = this;
                    $.epg.attrs.left = this;
                        $('#default-channels').addClass('display-none');
                        $('#default-home').addClass('display-none');
                        $('#default-sports').addClass('display-none');
                        $('#cats').addClass('display-none');
                        $('#follows').addClass('display-none');
                        $('#search').addClass('display-none');
                        $('#search-hots').addClass('display-none');
                        $('#weeks').removeClass('display-none');
                        $('#content-lists').removeClass('search');
                        $('#content-lists').removeClass('display-none');
                        var name = $.trim(this.elements.active.text());
                        $.epg.attrs.tag_name = name;
                        $('.content-title').text(name);
                        $.epg.AJ('tags', 'ajax_get_tag_programs', {date: $.epg.attrs.date, tag: name}, function(data){
                            if(data == 0){
                                $('.no-data').removeClass('display-none');
                                $('#content-lists .content-loop-body>ul').empty();
                                return;
                            }
                            $('#content-lists .content-loop-body>ul').replaceWith(data);
                            content.rebuild({
                                elements : {
                                    nodes : $('#content-lists .content-loop-body>ul .action')
                                },
                                pager : {
                                    pageSize : 11
                                },
                                viewer : {
                                    scrollRows : [1,9]
                                }
                            });
                            scope.changeBlock(content);
                        });
                        return true;
                }
            }
        });

        $('#search>.search-form').submit(function(){
            return false;
        });

        /**
        * 右侧--搜索栏
        */
        var search = new $.R({
            elements : {
                block : $('#search'),
                nodes : $('#search>.search-form .action'),
                container : $('.search-form')
            },
            customRemote : {
                onenter : function(){
                    this.elements.container.addClass('hover');
                    this.elements.active.focus();
                    
                },
                onleave : function(){
                    this.elements.container.removeClass('hover');
                    this.elements.active.blur();
                },
                ok : function(){
                    $('#search').removeClass('display-none');
                    $('#search-hots').removeClass('display-none');
                    $('#cats').removeClass('display-none');
                    $('#follows').addClass('display-none');
                    $('#weeks').addClass('display-none');
                    $('#content-lists').removeClass('display-none');
                    $('#content-lists').addClass('search');
                    $("#cats").addClass('display-none');
                    var scope = this;
                    this.elements.active.focus();
                    var value = $.trim(this.elements.active.val());
                    $.epg.AJ('search', 'index', {q: value, page: 1}, function(data){
                        if(data == 0){
                            $('#content-lists .content-loop-body>ul').empty();
                            $('.no-data').removeClass('display-none');
                            return;
                        }

                        $('#content-lists .content-loop-body>ul').replaceWith(data);
                        content.rebuild({
                            elements : {
                                nodes : $('#content-lists .content-loop-body>ul .action')
                            },
                            viewer: {
                                scrollRows: [4, 4]
                            },
                            pager : {
                                pageSize : 10
                            }
                        });
//                        scope.changeBlock(content);
//                        scope.elements.active.blur();
                    });
                },
                down : function(){
                    if(!$('#content-lists').hasClass('display-none')){
                        this.changeBlock(content);
                    }
                    return true;
                },
                left : function(){
                    this.changeBlock($.epg.attrs.left);
                    $.epg.attrs.right = this;
                }
            }
        });

        /**
         * home 导视首页
         */
        var home = new $.R({
            elements : {
                block : $('#default-home .home'),
                container : $('#default-home .home'),
                nodes : $('#default-home .home .action')
            },
            customRemote : {
                left : function(){
                    if(this.viewer.hoverIndex%4==0){
                        this.changeBlock($.epg.attrs.left);
                        return true;
                    }
                    this._up();
                },
                right : function(){
                    if(this.viewer.hoverIndex%4==3){
                        return true;
                    }
                    this._down();
                },
                up : function(){
                    var i = this.viewer.hoverIndex - 4;
                    if(i>=0){
                        this.changeHover(-4);
                    }
                    return true;
                },
                down : function(){
                    var i = this.viewer.hoverIndex - 4;
                    if(i<0){
                        this.changeHover(4);
                    }
                    return true;
                },
                ok : function(){
                    this._ok();
                    var active = this.elements.active, rel = Number(active.attr('rel'));
                    var mi = rel + $.epg.attrs.login;
                    var l = $.epg.attrs.left;
                    this.changeBlock(l);
                    l.changeHover(mi-l.viewer.hoverIndex);
                    l.viewer.rowIndex = l.viewer.hoverIndex;
                    l.changeActive();
                    l.ok();
                    l.elements.scroll.css('top', 0);
                    l.elements.tips[0].removeClass(l.viewer.pagerTips.up_active);
                    l.elements.tips[1].addClass(l.viewer.pagerTips.down_active);
                    if(rel == 5) $.epg.attrs.right = game;
                    else $.epg.attrs.right = default_channels;
                    return true;
                }
            }
        });

        $.epg.attrs.right = home;

        /**
         * default_channels;
         */
        var default_channels = new $.R({
            elements : {
                block : $('#default-channels .spotlight .cover'),
                container : $('#default-channels .spotlight .cover'),
                nodes : $('#default-channels .spotlight .cover')
            },
            customRemote : {
                left : function(){
                    this.changeBlock($.epg.attrs.left);
                },
                right : function(){
                    this.changeBlock(onair);
                    return true;
                },
                down : function(){
                    this.changeBlock(items);
                    return true;
                },
                ok : function(){
                    this._ok();
                    var wiki_id = Number(this.elements.active.attr('wiki_id'));
                    if(wiki_id){
                        $.epg.attrs.right = this;
                        $.epg.AJ('wiki', 'show', {id: wiki_id}, function(data){
                            $("#page").hide();
                            $("#page").parent().append(data);
                        });
                    }
                    return true;
                }
            }
        });

        var onair = new $.R({
            elements : {
                block : $('.onair-list'),
                container : $('.onair-list'),
                nodes : $('.onair-list .action')
            },
            customRemote : {
                left : function(){
                    this.changeBlock(default_channels);
                },
                right : function(){
                    this.changeBlock(channel_ranking);
                },
                down : function(){
                    if(this.viewer.hoverIndex == this.elements.length - 1){
                        this.changeBlock(items);
                        return true;
                    }
                },
                ok : function(){
                    this._ok();
                    var wiki_id = Number(this.elements.active.attr('wiki_id'));
                    if(wiki_id){
                        $.epg.attrs.right = this;
                        $.epg.AJ('wiki', 'show', {id: wiki_id}, function(data){
                            $("#page").hide();
                            $("#page").parent().append(data);
                        });
                    }
                    return true;
                }
            }
        });

        var channel_ranking = new $.R({
            elements : {
                block : $('#default-channels .ranking'),
                container : $('#default-channels .ranking'),
                nodes : $('#default-channels .ranking .action')
            },
            customRemote : {
                left : function(){
                    this.changeBlock(onair);
                },
                ok : function(){
                    this._ok();
                    var wiki_id = Number(this.elements.active.attr('wiki_id'));
                    if(wiki_id){
                        $.epg.attrs.right = this;
                        $.epg.AJ('wiki', 'show', {id: wiki_id}, function(data){
                            $("#page").hide();
                            $("#page").parent().append(data);
                        });
                    }
                    return true;

                }
            }
        });

        var items = new $.R({
            elements : {
                block : $('#default-channels .column'),
                container : $('#default-channels .column'),
                nodes : $('#default-channels .column>.item')
            },
            customRemote : {
                left : function(){
                    if(this.viewer.hoverIndex == this.viewer.rowIndex && this.viewer.hoverIndex ==0){
                        this.changeBlock($.epg.attrs.left);
                        return true;
                    }
                    this._up();
                },
                right : function(){
                    if(this.viewer.hoverIndex == this.elements.length - 1){
                        this.changeBlock(channel_ranking);
                        return true;
                    }
                    this._down();
                },
                up : function(){
                    this.changeBlock(onair);
                    return true;
                },
                down : function(){
                    return true;
                },
                ok : function(){
                    this._ok();
                    var wiki_id = Number(this.elements.active.attr('wiki_id'));
                    if(wiki_id){
                        $.epg.attrs.right = this;
                        $.epg.AJ('wiki', 'show', {id: wiki_id}, function(data){
                            $("#page").hide();
                            $("#page").parent().append(data);
                        });
                    }
                    return true;

                }
            }
        });

        /**
         * game 体育
         */
        var game = new $.R({
            elements : {
                block : $('#default-sports .game'),
                container : $('#default-sports .game'),
                nodes : $('#default-sports .game .action')
            },
            customRemote : {
                left : function(){
                    if(this.viewer.hoverIndex%5==0){
                        this.changeBlock($.epg.attrs.left);
                        return true;
                    }
                    this._up();
                },
                right : function(){
                    if(this.viewer.hoverIndex%5==4){
                        this.changeBlock(gameRanking);
                        return true;
                    }
                    this._down();
                },
                up : function(){
                    var i = this.viewer.hoverIndex - 5;
                    if(i>=0){
                        this.changeHover(-5);
                    }
                    return true;
                },
                down : function(){
                    var i = this.viewer.hoverIndex - 5;
                    if(i<5){
                        this.changeHover(5);
                    }
                    return true;
                },
                ok : function(){
                    this._ok();
                    if(this.viewer.hoverIndex == 0){
                        //window.location = 'http://222.73.42.91/TVTV/epg_v4/demo_game_forecast_weekly.html';
                    }
                    return true;
                }
            }
        });

        var gameRanking = new $.R({
            elements : {
                block : $('#default-sports .ranking'),
                container : $('#default-sports .ranking'),
                nodes : $('#default-sports .ranking .action')
            },
            customRemote : {
                left : function(){
                    this.changeBlock(game);
                },
                ok : function(){
                }
            }
        });
 
        /**
         * 右侧--节目单列表
         */
        var content = new $.R({
            elements : {
                block : $('#content-lists'),
                container : $('#content-lists'),
                nodes : $('#content-lists .content-loop-body>ul .action')
            },
            pager : {
                pageSize : 11
            },
            viewer  : {
//                hoverIndex : 5,
                rowHeight   : 50,
                scrollRows : [1, 9]
            },
            customRemote : {
                left : function(){
                    this.changeBlock($.epg.attrs.left);
                    $.epg.attrs.right = this;
                },
                bind : function(){
                    this._bind();
                    var scope = this;
                    this.elements.container.bind(this.uuid + '.keydown', function(event, docEvent){
                        var evt = scope.keyCode = docEvent;
                        if(evt.which>=49 && evt.which <=55){
                            if($('#weeks').is(':hidden')) return;
                            var i = evt.which - 49;
                            weeks_navi.viewer.rowIndex = i;
                            var left = parseInt($('#weekday').css('left'));
                            if(left < 0) i += 7;
                            weeks_navi.viewer.hoverIndex = weeks_navi.viewer.activeIndex = i;
                            changeWeek(i);
                        }else if(evt.which == 48){
                            <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>
                                if(evt.keyCode == 48){
                                    $.epg.AJ('favorites', 'ajax_create', {
                                        content : $.epg.attrs.channel_id,
                                        type: 'channel'
                                    }, function(data) {
                                    if(data.result == true) {
                                        $('.reminder').clone().appendTo($('#content')).removeClass('display-none').hide('slow',function(){
                                            $(this).remove();
                                        });
                                    } else {
                                    }
                                });
                            }
                            <?php endif; ?>
                        }
                    });
                    return true;
                },
                up : function(){
                    if(this.viewer.hoverIndex == this.viewer.rowIndex && this.viewer.hoverIndex ==0){
                        if(!$('#cats').is(':hidden')) this.changeBlock(content_navi);
                        else if(!$('#follows').hasClass('display-none')){this.changeBlock(follow_navi);}
                        else if(!$('#search').hasClass('display-none')){this.changeBlock(search);}
                        else{this.changeBlock(weeks_navi);}
                        return true;
                    }
                },
                ok : function(){
                    this._ok();
                    if(this.elements.active.length){
                        var oheight = $('#content-lists>.content-loop-body').height();
                        var viewer = this.viewer;
                        var h = viewer.rowIndex * viewer.rowHeight;
                        var dh = dropmenu.outerHeight();
                        var rh = h + viewer.rowHeight;
                        if(rh + dh > oheight){
                            rh = h - dh;
                            dropmenu.addClass('up1');
                        }else{
                            dropmenu.removeClass('up1');
                        }
                        dropmenu.css('top', rh).removeClass('display-none');
                        var active = this.elements.active;
                        var rel = $.parseJSON(active.attr('rel'));
                        if(rel){
                            $.epg.attrs.program_id = rel.program_id;
                            $.epg.attrs.wiki_id = rel.wiki_id;
                        }
                        this.changeBlock(dropmenus, 'no');
                    }
                    return true;
                },
                scroll : function(i){
                    var scope = this;
                    var rel = $.parseJSON(this.elements.scroll.attr('rel'));
                    if(rel){
                        var rows = scope.viewer.scrollRows;
                        var rowIndex = scope.viewer.rowIndex + i;
                        var hoverIndex = scope.viewer.hoverIndex + i;

                        if((rowIndex == rows[0] || rowIndex == rows[0] + 1 ) && hoverIndex == (rel.page - 1)*10 + rows[0] && rel.page < Math.ceil(rel.total/10)){
                            if(!scope.ajaxStatus){
                                $.ajax({
                                    url : '<?php echo url_for('@search'); ?>',
                                    type: 'post',
                                    data: {
                                        q: $.trim($('#search-input').val()), page: rel.page + 1
                                    },
                                    beforeSend : function(data){
                                        scope.ajaxStatus = true;
                                    },
                                    success : function(data){
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
                        }

                        if(!scope.ajaxStatus){
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
            }
        });

        //$.epg.attrs.right = content;

        /**
        * 右侧--顶部一周导航
        */
        var weeks_navi = new $.R({
            elements : {
                block : $('#weeks'),
                nodes : $('ul#weekday .action'),
                container : $('#weeks')
            },
            viewer : {
                xy  : 'x',
                rowWidth : 127,
                pagerTips   : {
                    up: 'left-arrow',
                    down: 'right-arrow',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 7
            },
            customRemote : {
                up : function(){
                    return true;
                },
                down : function(){
                    this.changeBlock(content);
                    return true;
                },
                left : function(){
                    this._up();
                    if(this.viewer.hoverIndex == 6){
                        this._scrollCss(-this.viewer.hoverIndex);
                        this.elements.tips[0].removeClass(this.viewer.pagerTips.up_active);
                        this.viewer.rowIndex = 6;
                    }
                },
                right : function(){
                    this._down();
                    if(this.viewer.hoverIndex == 7){
                        this._scrollCss(this.viewer.hoverIndex - 1);
                        this.elements.tips[1].removeClass(this.viewer.pagerTips.down_active);
                        this.viewer.rowIndex = 0;
                    }
                },
                ok : function(){
                    this._ok();
                    changeWeek(this.viewer.activeIndex);
                    return true;
                }
            }
        });

        /**
         * 右侧--顶部分类导航
         */
        var content_navi = new $.R({
            elements : {
                block : $('#cats'),
                nodes : $('ul#all-tags .action'),
                container : $('#cats')
            },
            viewer : {
                scrollRows : [3, 3],
                xy  : 'x',
                rowWidth : 127,
                pagerTips   : {
                    up: 'left-arrow',
                    down: 'right-arrow',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 7
            },
            customRemote : {
                up : function(){
                    if(!$('#search').hasClass('display-none')){
                        this.changeBlock(search);
                    }
                    return true;
                },
                down : function(){
                    this.changeBlock(content);
                    return true;
                },
                left : function(){
                    this._up();
                },
                right : function(){
                    this._down();
                },
                ok : function(){
                    this._ok();
//                    var tag_id = this.elements.active.attr('id').match(/(\d+)$/)[0];
                    var tag_name = $.trim(this.elements.active.attr('rel'))
                    $.epg.AJ('program', 'ajax_live_tag', {tag : tag_name}, function(data){
                        if(data == 0){
                            $('.no-data').removeClass('display-none');
                            $('#content-lists .content-loop-body>ul').empty();
                            return;
                        }
                        else $('.no-data').addClass('display-none');
                        $('#content-lists .content-loop-body>ul').replaceWith(data);
                        var pageSize, scrollRows;
                        if($('#search').hasClass('display-none')){
                            pageSize = 11, scrollRows = [1,9];
                        }else{
                            pageSize = 10, scrollRows = [4, 4];
                        }
                        content.rebuild({
                            elements : {
                                nodes : $('#content-lists .content-loop-body>ul .action')
                            },
                            pager : {
                                pageSize : pageSize
                            },
                            viewer : {
                                scrollRows : scrollRows
                            }
                        });
                    });

                    return true;
                }
            }
        });

        var follow_navi = new $.R({
            elements : {
                block : $('#follows'),
                nodes : $('ul#all-fos .action'),
                container : $('#follows')
            },
            viewer : {
                scrollRows : [3, 3],
                xy  : 'x',
                rowWidth : 127,
                pagerTips   : {
                    up: 'left-arrow',
                    down: 'right-arrow',
                    up_active : 'visible',
                    down_active : 'visible'
                }
            },
            pager : {
                pageSize : 7
            },
            customRemote : {
                up : function(){
                    return true;
                },
                down : function(){
                    this.changeBlock(content);
                    return true;
                },
                left : function(){
                    this._up();
                },
                right : function(){
                    this._down();
                },
                ok : function(){
                    this._ok();
                    var rel = $.parseJSON(this.elements.active.attr('rel'));
                    var module, action, params;
                    if(rel.target == 'channels'){
                        module = 'favorites';
                        action = 'ajax_get_channel_program';
                        params = {};
                    }else if(rel.target == 'tags'){
                        module = 'favorites';
                        action = 'ajax_get_tag_program';
                        params = {};
                    }
                    $.epg.AJ(module, action, params, function(data){
                                $('#content-lists .content-loop-body>ul').replaceWith(data);
                                content.rebuild({
                                    elements : {
                                        nodes : $('#content-lists .content-loop-body>ul .action')
                                    },
                                    pager : {
                                        pageSize : 11
                                    },
                                    viewer : {
                                        scrollRows : [1,9]
                                    }
                                });
                            });
                }
            }
        });

        /**
         * 右侧--节目单下拉菜单
         */
        var dropmenus = new $.R({
            elements : {
                block : dropmenu,
                nodes : dropmenu.find('.action')
            },
            viewer : {
//                hoverIndex : 3 //1
            },
            customRemote : {
                ok : function(){
                    this._ok();
                    var scope = this;

                    var active = this.elements.active;
                    var rel = active.attr('rel'), module, action, params;
                    if(rel == 'follow'){
                        module = 'attention';
                        action = 'ajax_create';
                        params = {pid: $.epg.attrs.program_id};
                    }else if(rel =='wiki'){
                        if($.epg.attrs.wiki_id){
                            $.epg.AJ('wiki', 'show', {id: $.epg.attrs.wiki_id}, function(data){
                                $("#page").hide();
                                $("#page").parent().append(data);
                            });
                        }
                    }

                    if(module && action && params){
                        $.epg.AJ(module, action, params, function(data){});
                    }

                    dropmenu.addClass('display-none');
                    scope.changeBlock(content);
                    return true;
                },
                left : function(){
                    this._up();
                },
                right : function(){
                    this._down();
                },
                up : function(){
                    return true;
                },
                down : function(){
                    return true;
                }
            }
        });

        /**
         *  取 周一~周日一天的数据
         */
        function changeWeek(i){
            var $i = $('#weekday li').eq(i);
            $.epg.attrs.week = i+1;
            $.epg.attrs.date = $.trim($i.attr('rel'));
//            if($i.hasClass('active')) return;
            var active = $('#weekday li').filter('.active');
            active.removeClass('active');
            $i.addClass('active');
            var module, action, params;
            if($('.channel-menu4').is(':hidden')){
                module = 'program';
                action = 'ajax_weekday';
//                params = {week: i+1, channel_id: $.epg.attrs.channel_id};
                params = {date: $.epg.attrs.date, channel_id: $.epg.attrs.channel_id};
            }else{
                module = 'tags';
                action = 'ajax_get_tag_programs';
//                params = {week: i+1, tag: $.epg.attrs.tag_name};
                params = {date: $.epg.attrs.date, tag: $.epg.attrs.tag_name};
            }

            $.epg.AJ(module, action, params, function(data){
                if(data == 0){
                    $('.no-data').removeClass('display-none');
                    $('#content-lists .content-loop-body>ul').empty();
                    return;
                }

                $('#content-lists .content-loop-body>ul').replaceWith(data);
                content.rebuild({
                    elements : {
                        nodes : $('#content-lists .content-loop-body>ul .action')
                    },
                    pager : {
                        pageSize : 11
                    },
                    viewer : {
                        scrollRows : [1,9]
                    }
                });
            });
        }
        /**
         * 取出周一~周日类表
         */
        $.epg.AJ('channel', 'ajax_get_weekdays', {}, function(data){
            $(data).appendTo($('#weeks>.content-top-navi-body'));
            weeks_navi.rebuild({
               elements : {
                   nodes : $('ul#weekday .action')
               }
            });
        });
       $.epg.AJ('tags', 'ajax_get_all_tags', {}, function(data){
            $(data).appendTo('#cats>.content-top-navi-body');
            content_navi.rebuild({
                elements : {
                    nodes : $('ul#all-tags .action')
                }
            });
        });
        var str = hour_str + ":" + min_str;
        $(".time").html(str);


        function updateContent(data){
            var dc = $('#default-channels'); 
            var cover = dc.find('.cover');
            var coverImg = dc.find('.cover>img');
            var onair = dc.find('.onair-list li.action');
            var items = dc.find('.today-content-title').nextAll();
            var rankings = dc.find('.ranking .action');
            coverImg.attr('src', data['cover']['src']);
            if(data['cover']['wiki_id']) cover.attr('wiki_id', data['cover']['wiki_id']);
            else cover.attr('wiki_id', 0);
            items.each(function(i, v){
                $(v).find('img').attr('src', data['items'][i]['src']);
                $(v).find('.column-title').text(data['items'][i]['title']);
                if(data['items'][i]['wiki_id']) $(v).attr('wiki_id', data['items'][i]['wiki_id']);
                else $(v).attr('wiki_id', 0);
            });
            onair.each(function(i, v){
                $(v).find('.title').text(data['onair'][i]['title']);
                $(v).find('.channel').text(data['onair'][i]['channel']);
                if(data['onair'][i]['wiki_id']) $(v).attr('wiki_id', data['onair'][i]['wiki_id']);
                else $(v).attr('wiki_id', 0);
            });
            rankings.each(function(i, v){
                if(i==0){
                    $(v).find('img').attr('src', data['ranking'][i]['src']);
                    $(v).find('.title').text(data['ranking'][i]['title']);
                    $(v).find('.producer').text('导演：'+data['ranking'][i]['producer']);
                    $(v).find('.count').text(data['ranking'][i]['count']);
                }else{
                    $(v).find('span').text(data['ranking'][i]['title']);
                }
                if(data['ranking'][i]['wiki_id']) $(v).attr('wiki_id', data['ranking'][i]['wiki_id']);
                else $(v).attr('wiki_id', 0);
            });
            $('.ranking-title').text(data['ranking-title']); 
            $('.drama-tips').text(data['drama-tips']); 
        };

        function updateAjax(param){
            $.epg.AJ('channel', 'ajax_get_datas', param || {}, function(data){
                updateContent(data);
            });
        };
    });
</script>
<div id="side">
    <div class="side-navi-larr visible">←</div>
    <div class="side-navi-title">导视首页</div>
    <div class="side-navi-rarr visible">→</div>
    <div class="clear-both"></div>
    <div class="side-navi channel-menu1">
        <div class="side-navi-uarr">↑</div>
        <div class="side-navi-body">
            <ul>
                <li class="action active hover" rel='{"sub":0, "target": "home"}'><span>导视首页</span></li>
                <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "follow"}'><span>精彩关注</span></li>
                <?php endif; ?>
                <li class="action" rel='{"sub":0, "type": "ajax", "target": "all_live"}'><span>正在播出</span></li>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "default-static", "param":"vod2"}'><span>热播剧集</span></li>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "default-static", "param":"film"}'><span>电影沙龙</span></li>
                <li class="action" rel='{"sub":1, "type": "ajax", "target": "sports"}'><span>体育赛事</span></li>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "default-static", "param":"ent"}'><span>娱乐综艺</span></li>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "default-static", "param":"anime"}'><span>少儿节目</span></li>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "default-static", "param":"kejiao"}'><span>科学教育</span></li>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "default-static", "param":"finance"}'><span>财经理财</span></li>
                <li class="action" rel='{"sub":0, "type": "noajax", "target": "default-static", "param":"news"}'><span>社会新闻</span></li>
                <li class="action" rel='{"sub":1, "type": "ajax", "target": "all_channels"}'><span>所有频道</span></li>
                <li class="action" rel='{"sub":1, "type": "ajax", "target": "tags"}'><span>栏目分类</span></li>
                <li class="action" rel='{"sub":1, "type": "noajax", "target": "search"}'><span>节目搜索</span></li>
            </ul>
        </div>
        <div class="side-navi-darr">↓</div>
    </div>
    <div class="side-navi channel-menu2 display-none">
        <div class="side-navi-uarr">↑</div>
        <div class="side-navi-body">
            <ul>
                <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>
                <li class="action" rel='{"sub":1, "type": "ajax"}'><span>我的频道</span></li>
                <?php endif; ?>
                <li class="action" rel='{"sub":3, "type": "ajax"}'><span>本地</span></li>
                <li class="action" rel='{"sub":2, "type": "ajax", "tv_station_id": 1}'><span>央视</span></li>
                <li class="action" rel='{"sub":4, "type": "ajax","action": "tv"}'><span>卫视</span></li>
                <li class="action" rel='{"sub":5, "type": "ajax","action": "edu"}'><span>教育</span></li>
                <li class="action"><span>数字</span></li>
                <li class="action"><span>高清</span></li>
                <li class="action"><span>境外</span></li>
            </ul>
        </div>
        <div class="side-navi-darr">↓</div>
    </div>
    <div class="side-navi channel-menu3 display-none">
        <div class="side-navi-uarr">↑</div>
        <div class="side-navi-body">
            <ul>
            </ul>
        </div>
        <div class="side-navi-darr">↓</div>
    </div>
    <div class="side-navi channel-menu4 display-none">
        <div class="side-navi-uarr">↑</div>
        <div class="side-navi-body">
            <ul>
                <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>
                <li class="action" rel="no"><span>我的栏目</span></li>
                <?php endif; ?>
                <li class="action" rel='新闻'><span>新闻类</span></li>
                <li class="action" rel="娱乐"><span>娱乐类</span></li>
                <li class="action" rel="体育"><span>体育类</span></li>
                <li class="action" rel="生活"><span>生活类</span></li>
                <li class="action" rel="电视剧"><span>连续剧</span></li>
                <li class="action" rel="电影"><span>电影</span></li>
                <li class="action" rel="动画片"><span>动画片</span></li>
                <li class="action" rel="纪录片"><span>纪录片</span></li>
                <li class="action" rel="外语"><span>外语</span></li>
            </ul>
        </div>
        <div class="side-navi-darr">↓</div>
    </div>
    <div class="side-navi channel-menu5 display-none">
        <div class="side-navi-uarr">↑</div>
        <div class="side-navi-body">
            <ul>
            </ul>
        </div>
        <div class="side-navi-darr">↓</div>
    </div>
</div>
<div id="content">
    <div class="content-utility">
        <img width="93" height="37" class="logo" alt="" src="<?php echo image_path('logo.png') ?>"/>
        <ul>
            <li class="weather"><?php echo $sf_user->getAttribute('user_city'); ?> 35℃</li>
            <li class="time">00:00</li>
        </ul>
    </div>
    <div class="content-title">导视首页</div>
    <div class="search display-none" id="search">
        <form class="search-form" action="<?php echo url_for('@search'); ?>">
            <input type="text" value="" class="action active hover text" name="q" id="search-input" autocomplete="off"/>
            <input type="button" value="搜索" class="submit" name=""/>
        </form>
    </div>
    <div class="content-top-navi display-none" id="cats">
        <div class="left-arrow">←</div>
        <div class="content-top-navi-body">
        </div>
        <div class="right-arrow">→</div>
    </div>
    <div class="content-top-navi display-none" id="weeks">
        <div class="left-arrow">←</div>
        <div class="content-top-navi-body">
        </div>
        <div class="right-arrow">→</div>
    </div>
    <div class="content-top-navi display-none" id="follows">
        <div class="left-arrow">←</div>
        <div class="content-top-navi-body">
            <ul id="all-fos">
                <li class="action active" rel='{"target": "channels"}'><span>频道</span></li>
                <li class="action" rel='{"target": "tags"}'><span>栏目</span></li>
            </ul>
        </div>
        <div class="right-arrow">→</div>
    </div>
    <div class="content-the-loop display-none" id="content-lists">
        <div class="content-loop-body">
            <ul>
            </ul>
            <div class="display-none 
                <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>drop-menu1
                <?php else: ?>
                    drop-menu3
                <?php endif; ?>
                ">
                <ul>
                    <li class="action details" rel="wiki"><span>详情</span></li>
                    <?php if ($sf_user->getAttribute('user_key') != 'guest'): ?>
                    <li class="action remind" rel="follow"><span>提醒</span></li>
                    <li class="action recommend"><span>分享</span></li>
                    <?php endif; ?>
                    <li class="action back"><span>取消</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="search-hots" class="display-none">
        <div class="content-top-navi">
            <div class="content-top-navi-body">
                <ul>
                    <li class="action active"><span>热门推荐</span></li>
                </ul>
            </div>
        </div>
        <div class="content-the-loop search">
            <div class="recommended">
                <div class="left-col">
                    <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/recommended_01.jpg'; ?>"></div>
                    <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/recommended_02.jpg'; ?>"></div>
                    <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/recommended_03.jpg'; ?>"></div>
                </div>
                <div class="center-col">
                    <div class="action feature"><img width="1" height="1" alt="" src="<?php echo$sf_request->getRelativeUrlRoot() . '/public/recommended_04.jpg'; ?>"></div>
                    <div class="action hot"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/recommended_05.jpg'; ?>"></div>
                </div>
                <div class="right-col">
                    <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/recommended_06.jpg'; ?>"></div>
                    <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/recommended_07.jpg'; ?>"></div>
                    <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/recommended_02.jpg'; ?>"></div>
                </div>
                <div class="clear-both"></div>
            </div>
        </div>
    </div>

    <div id="default-home" class="">
        <div class="home">
            <div rel="4" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_01.jpg'; ?>"></div>
            <div rel="3" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_02.jpg'; ?>"></div>
            <div rel="6" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_03.jpg'; ?>"></div>
            <div rel="5" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_04.jpg'; ?>"></div>
            <div rel="7" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_05.jpg'; ?>"></div>
            <div rel="8" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_06.jpg'; ?>"></div>
            <div rel="9" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_07.jpg'; ?>"></div>
            <div rel="10" class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_08.jpg'; ?>"></div>
        </div>
        <div class="sub">
        <div class="sub-title">当前正在热播</div>
        <div class="sub-feature">
          <img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_09.jpg'; ?>">
          <div class="sub-feature-body">
            <div class="title">头师父一体</div>
            <div class="cat">类型：喜剧</div>
            <p>流氓就怕有文化</p>
          </div>
          <div class="clear-both"></div>
        </div>
        <div class="sub-feature">
          <img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/home_10.jpg'; ?>">
          <div class="sub-feature-body">
            <div class="title">新西游记</div>
            <div class="cat">类型：剧情</div>
            <p>最大胆最雷人的经典翻拍</p>
          </div>
          <div class="clear-both"></div>
        </div>
        <div class="action sub-item">
          新闻联播
        </div>
        <div class="action sub-item">
          新闻联播
        </div>
        <div class="action sub-item">
          新闻联播
        </div>
        <div class="action sub-item">
          新闻联播
        </div>
      </div>
    </div>

    <div id="default-channels" class="display-none">
        <div class="column">
            <div class="spotlight">
                <div class="action cover">
                    <img width="1" height="1" alt="" src=""/>
                </div>
                <div class="spotlight-body">
                    <div class="onair-column-title">同时正在播出</div>
                    <div class="onair-list">
                        <ul>
                            <li class="action"><span class="title"></span><span class="channel"></span></li>
                            <li class="action"><span class="title"></span><span class="channel"></span></li>
                            <li class="action"><span class="title"></span><span class="channel"></span></li>
                            <li class="action"><span class="title"></span><span class="channel"></span></li>
                            <li class="action"><span class="title"></span><span class="channel"></span></li>
                            <li class="action"><span class="title"></span><span class="channel"></span></li>
                        </ul>
                    </div>
                </div>
                <div class="clear-both"></div>
      </div>
      <div class="today-content-title">今日即将热播<span class="drama-tips">今日全部连续剧</span></div>
      <div class="item action"> <img width="1" height="1" alt="" src=""/>
        <div class="column-title"></div>
      </div>
      <div class="item action"> <img width="1" height="1" alt="" src=""/>
        <div class="column-title"></div>
      </div>
      <div class="item action"> <img width="1" height="1" alt="" src=""/>
        <div class="column-title"></div>
      </div>
    </div>
        <div class="ranking">
            <div class="ranking-title">电视剧热播榜</div>
            <div class="action ranking-feature">
                <div class="ico-hot">1</div>
                <img width="1" height="1" alt="" src="">
                <div class="ranking-feature-body">
                    <div class="title"></div>
                    <div class="cat">类型：</div>
                    <div class="star">评价：</div>
                    <div class="producer">导演：</div>
                    <div class="score">推荐指数：<span class="count"></span></div>
                </div>
                <div class="clear-both"></div>
            </div>
            <div class="action ranking-item">
                <div class="ico-hot">2</div>
                <span></span>
            </div>
            <div class="action ranking-item">
                <div class="ico-hot">3</div>
                <span></span>
            </div>
            <div class="action ranking-item">
                <div class="ico-count">4</div>
                <span></span>
            </div>
            <div class="action ranking-item">
                <div class="ico-count">5</div>
                <span></span>
            </div>
            <div class="action ranking-item">
                <div class="ico-count">6</div>
                <span></span>
            </div>
            <div class="action ranking-item">
                <div class="ico-count">7</div>
                <span></span>
            </div>
            <div class="action ranking-item">
                <div class="ico-count">8</div>
                <span></span>
            </div>
            <div class="action ranking-item">
                <div class="ico-count">9</div>
                <span></span>
            </div>
        </div>
    </div>

    <!--div id="default-follow" class="display-none">
        <div class="content-top-navi">
            <div class="content-top-navi-body">
                <ul>
                    <li class="action active"><span>频道</span></li>
                    <li class="action"><span>栏目</span></li>
                </ul>
            </div>
        </div>
        <div class="content-the-loop">
            <div class="content-loop-body">
                <ul>
                </ul>
            </div>
        </div>
    </div-->

    <div id="default-sports" class="display-none">
        <div class="game">
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_001.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_002.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_003.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_004.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_005.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_006.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_007.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_008.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_009.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_010.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_011.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_012.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_013.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_014.jpg'; ?>"/></div>
      <div class="action item"><img width="1" height="1" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_015.jpg'; ?>"/></div>
    </div>        <div class="ranking">
        <div class="ranking-title">今日热门赛事</div>
        <div class="action ranking-feature">
          <div class="ico-hot">1</div>
          <img width="1" height="1" class="game" alt="" src="<?php echo $sf_request->getRelativeUrlRoot() . '/public/game_06.jpg'; ?>">
          <!--<div class="ranking-feature-body">
            <div class="title">原来是美男</div>
            <div class="cat">类型：爱情/喜剧</div>
            <div class="star">评价：</div>
            <div class="producer">导演：张根锡</div>
            <div class="score">推荐指数：<span class="count">8.0</span></div>
          </div>-->
          <div class="clear-both"></div>
        </div>
        <div class="action ranking-item">
          <div class="ico-hot">2</div>
          同步热播 国米夺超级杯
        </div>
        <div class="action ranking-item">
          <div class="ico-hot">3</div>
          同步热播 国米夺超级杯
        </div>
        <div class="action ranking-item">
          <div class="ico-count">4</div>
          同步热播 国米夺超级杯
        </div>
        <div class="action ranking-item">
          <div class="ico-count">5</div>
          同步热播 国米夺超级杯
        </div>
        <div class="action ranking-item">
          <div class="ico-count">6</div>
          同步热播 国米夺超级杯
        </div>
        <div class="action ranking-item">
          <div class="ico-count">7</div>
          同步热播 国米夺超级杯
        </div>
        <div class="action ranking-item">
          <div class="ico-count">8</div>
          同步热播 国米夺超级杯
        </div>
        <div class="action ranking-item">
          <div class="ico-count">9</div>
          同步热播 国米夺超级杯
        </div>
      </div>
    </div>
    <div class="loading display-none">数据加载中...</div>
    <div class="no-data display-none">节目尚未录入</div>
    <div class="reminder display-none">预定提醒成功</div>
    <div class="share display-none">分享成功</div>
</div>
<div class="clear-both"></div>
