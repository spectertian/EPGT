<script type="text/javascript">
$(document).ready(function() {
    /*$("#channel-cat-content").list({
        direction: 'H',
        up: function(event) {
            $("#menu-bar").data('ui').focus();
        },
        enter: function(event, item) {
            type = item.attr('rel');
            if (type) {
                $("#channel-programs-sizer").load("<?php echo url_for('tv_station/show'); ?>", {"type": type}, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#channel-programs-sizer").removeClass("display-none");
//                    $(".channel-item:first").data("ui").focus();
                    //$("#channel_programs_back").list('focus');
                });
            }
        }
    });*/
    var scrollHeight = 492;

    var $laar = $('.channel-list-new .larr');
    var $raar = $('.channel-list-new .rarr');

    $('#channel-cat-contentbb').grid({
        coords: [ 3, 5 ],
        init: function(event, opts) {
            //var opts = instance.options;
            if (opts.currentPage == 0) {
                $laar.addClass('display-none');
            } else {
                $laar.removeClass("display-none");
            }
            if (opts.pages == 0) {
                $raar.addClass("display-none");
            } else {
                $raar.removeClass("display-none");
            }
        },
        enter: function(event, item) {
            type = item.attr('rel');
            //if (type) {
            var channel_id = item.attr('channel_id');
            $("#channel-programs-sizer").load("<?php echo url_for('tv_station/show'); ?>", {"type": type, "channel_id": channel_id}, function() {
                $("#main-nav-sizer").addClass("display-none");
                $("#channel-programs-sizer").removeClass("display-none");
//                    $(".channel-item:first").data("ui").focus();
                //$("#channel_programs_back").list('focus');
            });
            //}
        },

        upBorde: function( event, ui ) {
            $('#menu-bar').data('ui').focus();
        },
        rightBorde: function( event, ui ) {
            var opts = ui.instance.options;
            var i = ( opts.currentPage + 1 ) * opts.coords[0] * opts.coords[1];
            var $i = ui.instance.$items.eq(i);
            if($i.length){
                opts.currentPage++;
                ui.$item = $i;
                ui.instance.goTo(event, ui );
                $(this).css('top', function(index, val) {
                    return (parseFloat(val) - scrollHeight) + 'px';
                });
                if (opts.currentPage == opts.pages) {
                    $raar.addClass('display-none');
                } else {
                    $raar.removeClass('display-none');
                }
                if (opts.currentPage == 0) {
                    $laar.addClass('display-none');
                } else {
                    $laar.removeClass('display-none');
                }
            }
        },
        leftBorde: function( event, ui ) {
            var opts = ui.instance.options;
            if ( opts.currentPage - 1 < 0 ) return;
            var i = ( opts.currentPage - 1 ) * opts.coords[0] * opts.coords[1];
            var $i = ui.instance.$items.eq(i + opts.coords[1] - 1);
            if($i.length){
                opts.currentPage--;
                ui.$item = $i;
                ui.instance.goTo(event, ui );
                $(this).css('top', function(index, val) {
                    return (parseFloat(val) + scrollHeight) + 'px';
                });
                if (opts.currentPage == opts.pages) {
                    $raar.addClass('display-none');
                } else {
                    $raar.removeClass('display-none');
                }
                if (opts.currentPage == 0) {
                    $laar.addClass('display-none');
                } else {
                    $laar.removeClass('display-none');
                }
            }
        }
    });
});
</script>
<div class="channel-list-new">
    <div class="item-list" id="channel-cat-contentbb" style="top:0px">
        <ul>
            <?php if ($channels):?>
            <?php foreach ($channels as $channel) :?>
            <?php if (!$channel->getChannelLogo()) continue;?>
            <li class="action" channel_id="<?php echo $channel->getChannelCode();?>" rel="<?php echo $channel->getTags();?>">
                <div class="icon"><img src="<?php echo thumb_url($channel->getChannelLogo(),200,108)?>" width="180" height="90" alt=""></div>
            	<?php echo $channel->getName();?>
            </li>
            <?php endforeach;?>
            <?php endif;?>
        </ul>
    </div>
    <div class="larr">&lt;</div>
    <div class="rarr">&gt;</div>
</div>
