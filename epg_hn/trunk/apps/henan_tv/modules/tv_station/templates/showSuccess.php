<?php $counts = count($channels); ?>
<div class="epg-list-new">
	<span id="listup" class="up"></span>
    <span id="listdown" class="down"></span>
    <div class="channel-list">
        <div class="channel-slider" id="channel-slider">
        </div>
        <script type="text/javascript">
            var m=new mainnav();
            <?php foreach ($channels as $k => $channel): ?>
            <?php if ($channel->getChannelCode() == $channel_id): ?>
            m.cops = <?php echo $k; ?>;
            <?php endif; ?>
            m.addNav("<?php echo $channel->getChannelCode(); ?>",<?php echo json_encode($channel->getName()); ?>);
            <?php endforeach; ?>
            m.element = $("#channel-slider");
            m.writeNav();
        </script>
        <div class="larr">&lt;</div>
        <div class="rarr">&gt;</div>
    </div>
    <div class="dates" id="dates">
        <?php echo tcl_week(); ?>
    </div>
    <div class="tv-listings" id="tv-listings">
        
    </div>
</div>
<div class="footer">
    <div class="help"><span>按</span> <span class="arrows_vertical">&lt; &gt;</span> <span>键滚动节目列表</span> <span>，按回看<!-- </span><span class="button_menu">menu</span><span>-->键返回上一级</span></div>
</div>

<script type="text/javascript">
var storeDatas = {
    date: '<?php echo date('Y-m-d');?>',
    channel_id: '<?php echo $channel_id ? $channel_id : $channels[0]->getChannelCode(); ?>'
};
$(function(){
    $('#channel-slider').list({
        direction: 'H',
        //viewRows: 9,
        //enabledScroll: true,
        //scrolling: false,
        //scrollIndexs: [4, 4],
        down: function(event, ui){
            $('#dates').data('ui').focus();
            // 按下自动加载选中之数据
            var _this = ui.instance;
            item = _this.$cursor;
            var rel = item.attr('rel');
            if (rel != storeDatas.channel_id) {
                $(this).find('.actived').removeClass('actived');
                item.addClass('actived');
                storeDatas.channel_id = rel;
                $('#tv-listings').load('<?php echo url_for("channel/show"); ?>', {channel_id:storeDatas.channel_id, date:storeDatas.date});
            }
        },
        enter: function(event, item){
            var rel = item.attr('rel');
            $(this).find('.actived').removeClass('actived');
            item.addClass('actived');
            storeDatas.channel_id = rel;
            $('#tv-listings').load('<?php echo url_for("channel/show"); ?>', {channel_id:storeDatas.channel_id, date:storeDatas.date});
        },
        menu: function(event, ui){
            $('#main-nav-sizer').removeClass('display-none');
            $('#channel-programs-sizer').addClass('display-none');
            $("#channel-cat-contentbb").data('ui').focus();
        },
        left: function(event, ui){
            /*var _this = ui.instance;
            var $items = _this.$items;
            var l = $items.length;
            $items.eq(l-1).insertBefore($items.eq(0));
            _this.$items = $(this).find('.action');*/
            m.prvNav();
        },
        right: function(event, ui){
            /*var _this = ui.instance;
            var $items = _this.$items;
            var l = $items.length;
            $items.eq(0).insertAfter($items.eq(l-1));
            _this.$items = $(this).find('.action');*/
            m.nextNav();
        },
        focus: function(event, ui){
            var item = $(this).find('.actived');
            if (item.length) {
                ui.$item = item;
            }
        }
    });
    $('#channel-slider').data('ui').focus();
    $('#dates').list({
        direction: 'H',
        up: function(){
            $('#channel-slider').data('ui').focus();
        },
        enter: function(event, item){
            var rel = item.attr('rel');
            $(this).find('.actived').removeClass('actived');
            item.addClass('actived');
            storeDatas.date = rel;
            $('#tv-listings').load('<?php echo url_for("channel/show"); ?>', {channel_id:storeDatas.channel_id, date:storeDatas.date});
        },
        down: function(){
            var ul = $('#tv-listings ul').data('ui');
            if(ul){
                ul.focus();
            }
        },
        menu: function() {
            $('#channel-slider').data('ui').focus();
        }
    });

    if(storeDatas.channel_id){
        $('#tv-listings').load('<?php echo url_for("channel/show"); ?>', {channel_id:storeDatas.channel_id, date:storeDatas.date});
    }
});
</script>