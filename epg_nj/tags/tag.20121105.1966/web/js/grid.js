// JavaScript Document
(function($){
    $.fn.grid = function(){
        var that = this;
         that.GAPX = 12,
         that.GAPY = 15,
         that.columns = 4,
         that.rows = Math.ceil(that.size() / that.columns);
         that.tooltips = [];

         $.extend(that,{
            toGrid: function(){                
                var index = 0,
                    wapperH = 0,
                    wapperW = 0,
                    eachHArray = [],
                    eachWArray = [],
                    mapArray = [];
                for (var n =0; n < that.columns; n++) {
                    mapArray[n] = 0;
                }
               
                if (that.size() <= that.columns) {
                    that.each(function(i){
                        $(this).css({'top': "0px", 'left' : wapperW + "px"});
                        wapperW += $(this).outerWidth()+ that.GAPX;
                        wapperH = Math.max(wapperH, $(this).outerHeight() + that.GAPX);
                        that.tooltips[i] = {};
                        that.tooltips[i].top = 0;
                        that.tooltips[i].left = 0;
                    });        
                } else {
                    that.each(function(i){
                        eachWArray[i] = $(this).outerWidth() + that.GAPX;
                        eachHArray[i] = $(this).outerHeight() + that.GAPY;
                    });
                    for (var i = 0; i < that.rows; i++ ) {
                        var left = 0;
                        for (var j = 0; j < that.columns; j++ ) {
                             left = j * eachWArray[j];
                             that.eq(index).css({'top':  mapArray[j] + "px", 'left' : left+ "px"});
                             that.tooltips[index] = {};
                             that.tooltips[index].top = mapArray[j];
                             that.tooltips[index].left = left;
                             mapArray[j] += eachHArray[index];
                             index++;

                             if (!isNaN(mapArray[j])) {
                                wapperH = Math.max(wapperH, mapArray[j]);
                             }
                        }
                    }
                }

                that.parent().height(wapperH-that.GAPY);
            },

            toHover: function() {
                var toolTip = $('#tooltip');
                var delayTime = [];
                $('.rec-list li .stills a').each(function(index) {           
                    $(this).hover(function() {
                        var top = $('.rec-list').offset().top + that.tooltips[index].top;
                        var left = $('.rec-list').offset().left + that.tooltips[index].left;
                        var slug = $(this).attr("slug");
                        delayTime[index] = setTimeout(function() {
                            if (left + $('#tooltip').width() + 200 < $(document).width()) {
                                toolTip.css({'top': top+32, 'left': left + 217});
                                $('.arrow-img').css({'background-position': '0 0', 'left': '8px'});
                            } else {
                                toolTip.css({'top':top+32, 'left':left-462});
                                $('.arrow-img').css({'background-position': '-8px 0', 'left': '457px'});
                            }
                            
                            toolTip.show();
                            loadWiki(slug);
                        }, 400)
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
        });
        
        that.find('img').load(function() {
            that.toGrid();
        });
        return that;
    }
	
})(jQuery);

$(document).ready(function() {
    $('.eachrec').grid().toHover();
});
