<script type="text/javascript">
$(document).ready(function() {
    function closeThis() {
        $("#wiki-sizer").removeClass("display-none");
        var return_target1 = $('#wiki-info-sizer').attr('return_target1');
        $(return_target1).data("ui").focus();

        var widgets = $("#wiki-info-sizer").find('.tvui');
        $("#wiki-info-sizer").html("").addClass("display-none");
        try {
            widgets.each(function(widget) {
                widget.data('ui').destroy();
            });
        } catch(e) {
            
        }
        
    }

    $('.back').list({
        direction: 'H',
        focus: function(item,ui){
            var pageUp = $("div[rel=pageUp]");
            var pageDown = $("div[rel=pageDown]");
            var countCurrentPage = $(".pagenator").find("SPAN:eq(0)");
            var countMaxPage = $(".pagenator").find("SPAN:eq(1)");
            var currentFocusStatus = parseInt(countMaxPage.attr('rel'));
            var maxHeight = $(".content-slide").height();
            var screenHeight = 256;
            var currentPage = '01';
            var maxPage = Math.ceil(maxHeight/screenHeight);

            if(currentFocusStatus == 1)
            {
                countMaxPage.attr('rel','0');
                countCurrentPage.text(currentPage);

                if(maxPage <= 1)
                {
                    pageUp.addClass('disabled').removeClass("action");
                    pageDown.addClass('disabled').removeClass("action");
                    countMaxPage.text('01');
                }else{
                    pageUp.addClass('disabled').removeClass("action");
                    if(maxPage < 10)
                    {
                        countMaxPage.text( '0' + maxPage);
                    }else{
                        countMaxPage.text(maxPage);
                    }
                }
            }
        },
        over: function(event, pos) {
            if(pos == 'end') {
                if($('.pgu').hasClass('disabled') && $('.pgd').hasClass('disabled')) {
                } else {
                    $('.pages').list('focus');
                }
            }
        },
        enter: closeThis
    });

    $('.pages').list({
        direction: 'H',
        over: function(event, pos) {
            if(pos == 'end') {
            } else {
                $('.back').list('focus');
            }
        },
        enter: function(event, item) {
            rel = item.attr('rel');

            var opt = {};
            var _this= $(".content-slide");
            var lineH= 256;                                            //获取行高
//                line=opt.line?parseInt(opt.line,10):parseInt($(this).height()/lineH,10),       //每次滚动的行数，默认为一屏，即父容器高度
//                speed=opt.speed?parseInt(opt.speed,10):500;                                 //卷动速度，数值越大，速度越慢（毫秒）
            var line=1;
            var upHeight=0-line*lineH;
            var mtop = parseInt(_this.css('marginTop'));
            //页签
            var currentPage = $(".pagenator").find("SPAN:eq(0)");
            var currentMaxPage = Number($(".pagenator").find("SPAN:eq(1)").text());
            var countCurrentPage = Number(currentPage.text());

            if(rel == 'pageUp') {
                 var ULHeight = mtop-upHeight ;
                 if(mtop < 0 )
                 {
                    _this.css('marginTop',ULHeight+'px');
                 }
                    //分页页签
                    countCurrentPage-=1;
                    if(countCurrentPage > 0)
                    {
                         if(countCurrentPage < 10)
                        {
                            currentPage.text('0'+countCurrentPage);
                        }else{
                            currentPage.text(countCurrentPage);
                        }
                    }

                    if(countCurrentPage < currentMaxPage )
                    {
                        $("DIV[rel=pageDown]").removeClass('disabled').addClass("action");
                    }

                    if(countCurrentPage == 1)
                    {
                        $("DIV[rel=pageUp]").addClass('disabled').removeClass("action");
                        $("DIV[rel=pageDown]").removeClass('disabled').addClass("action");
                    }

            } else {
                var ULHeight =  upHeight+mtop ;                 //获取已滚动的高度： MARGINTOP+目前显示的高度
                var currentMarginTop= Math.abs(mtop);           //获取已经滚动的MARGINTOP
                var currentListHeight = _this.height() - currentMarginTop ; //计算剩余UL高度是否满足显示序列数【5】

                if( currentListHeight  >=  lineH )
                {
//                    _this.animate({
//                            marginTop:ULHeight
//                    },speed,function(){
                          _this.css({marginTop:ULHeight});
                          $("DIV[rel=pageUp]").removeClass('disabled').addClass("action");
//                    });
                }

                //分页页签
                countCurrentPage +=1;
                if(countCurrentPage <= currentMaxPage)
                {
                    if(countCurrentPage < 10)
                    {
                        currentPage.text('0'+countCurrentPage);
                    }else{
                        currentPage.text(countCurrentPage);
                    }
                }

                     if(countCurrentPage == currentMaxPage)
                    {
                        $("DIV[rel=pageDown]").addClass('disabled').removeClass("action");
                        $("DIV[rel=pageUp]").removeClass('disabled').addClass("action");
                    }

                    if(countCurrentPage > 1)
                    {
                        $("DIV[rel=pageUp]").removeClass('disabled').addClass("action");
                    }

            }
        }
    });
//
//
//    $('#footer_source').list({
//        direction: 'H',
//        over: function(event, pos) {
//            if(pos == 'end') {
//            } else {
//                $('#footer_back').list('focus');
//            }
//            if(pos == 'start')
//            {
//                if($('.pgu').hasClass('disabled') && $('.pgd').hasClass('disabled')) {
//                    $('#footer_back').list('focus');
//                } else {
//                    $('.pages').list('focus');
//                }
//            }
//        }
//    })

});

</script>