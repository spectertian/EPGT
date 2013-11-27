<script type="text/javascript">
$(document).ready(function() {
    /*$(document).keydown(function(event){
        if(event.which == $.tvui.keyCode.MENU) {
            Huan.system.closeBrowser();
        }
    });*/
    $("#menu-bar").list({
        direction: 'H',
        down: function(event) {
            var c = $("#content-panel").find('.tvui:first');
            if (c.length) {
                c.data('ui').focus();
            }
        },
        enter: function(event, item) {
            var href = item.attr('href');
            if (href) {
                $(this).find('.actived').removeClass('actived');
                item.addClass('actived');
                var c = $("#content-panel").find('.tvui:first');
                $("#content-panel").load(href);
                if (c.length) {
                    c.data('ui').destroy();
                }
            }
        },
        over: function(event, pos) {
            if (pos == 'end') {
                var search_widget = $("#search-form-widget")
                search_widget.data("ui").focus();
                search_widget.data("left", $(this));
                search_widget.data('down', $("#content-panel").find('.tvui:first'));
            }
        },
        menu: function(event) {
            Huan.system.closeBrowser();
        }
    });
});
</script>
<ul id="menu-bar">
    <li class="action font2 first actived" href="<?php echo url_for('tcl/homepage'); ?>"><span class="aactived"><span class="ahover">首页</span></span></li>
    <li class="action font3" href="<?php echo url_for('tcl/tvplays'); ?>"><span class="aactived"><span class="ahover">电视剧</span></span></li>
    <li class="action font2" href="<?php echo url_for('tcl/movie'); ?>"><span class="aactived"><span class="ahover">电影</span></span></li>
    <li class="action font2" href="<?php echo url_for('tcl/sports'); ?>"><span class="aactived"><span class="ahover">体育</span></span></li>
    <li class="action font2" href="<?php echo url_for('tcl/ent'); ?>"><span class="aactived"><span class="ahover">娱乐</span></span></li>
    <li class="action font2" href="<?php echo url_for('tcl/children'); ?>"><span class="aactived"><span class="ahover">少儿</span></span></li>
    <li class="action font2" href="<?php echo url_for('tcl/edu'); ?>"><span class="aactived"><span class="ahover">科教</span></span></li>
    <li class="action font2" href="<?php echo url_for('tcl/finance'); ?>"><span class="aactived"><span class="ahover">财经</span></span></li>
    <li class="action font2" href="<?php echo url_for('tcl/general'); ?>"><span class="aactived"><span class="ahover">综合</span></span></li>
    <li class="action font3 last" href="<?php echo url_for('channel/index'); ?>"><span class="aactived"><span class="ahover">节目表</span></span></li>
</ul>