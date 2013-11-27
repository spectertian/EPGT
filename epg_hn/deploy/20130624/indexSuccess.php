<script type="text/javascript">
$(document).ready(function() {
    function closeThis() {
        $("#main-nav-sizer").removeClass("display-none");
        $("#menu-bar").data("ui").focus();

        var search_sizer = $("#search-sizer");
        search_sizer.addClass("display-none");
        var widgets = search_sizer.find('.tvui');
        try {
            widgets.each(function(i, widget) {
                widget.data('ui').destroy();
            });
            search_sizer.html("");
        } catch(e){
        }
    }
    $("#search-results-list").list({
        direction: 'V',
        enter: function(event) {
            
        },
        over: function(event, pos) {
            if (pos == "end") {
                $("#search-results-pages").data("ui").focus();
            }
        }
    });
    $("#search-close-back").list({
        enter: closeThis,
        up: function(event) {
            $("#search-results-list").data("ui").focus();
        },
        over: function(event, pos) {
            if (pos == "end") {
                $("#search-results-pages").data('ui').focus();
            }
        }
    });
    $("#search-results-pages").list({
        focus: function(event, ui) {
            ui.$item = $(this).find(".pgd");
        },
        enter: function(event, item) {
            if (item.hasClass("pgu")) {
                load_search_page(<?php echo $page - 1;?>)
            } else if (item.hasClass("pgd")) {
                load_search_page(<?php echo $page + 1;?>)
            }
        },
        up: function(event) {
            $("#search-results-list").data("ui").focus();
        },
        over: function(event, pos) {
            if (pos == "end") {
                var search_widget = $("#search-sizer #search-form-widget");
                search_widget.data("left", $(this));
                search_widget.data("up", $("#search-results-list"));
                $("#search-sizer #search-form-widget").data("ui").focus();
            } else if (pos == "start") {
                $("#search-close-back").data("ui").focus();
            }
        }
    });
    function load_search_page(page) {
        var total_page = <?php echo $total_page; ?>;
        if (page > total_page || page <= 0) return;

        $("#search-sizer").load("<?php echo url_for('search/index')?>", {"q": "<?php echo $query?>", "page": page}, function() {
            $("#search-results-list").data("ui").focus();
        });
    }
    //$("#search-results-form-action").load("<?php //echo url_for('tcl/search'); ?>", {"sizer": "search-sizer"});
});
</script>
<div class="search-results" rel='{"total":<?php echo $total; ?>,"page": <?php echo $page; ?>}'>
    <div class="left-col">
        <div class="results-title">搜索"<?php echo strip_tags($query); ?>" 共有<?php echo $total; ?>条结果</div>
        <ul id="search-results-list">
            <?php if (!empty($programs)): ?>
            <?php foreach ($programs as $k => $program): ?>
            <li class="action">
                <span class="date"><?php echo date("m月d日", strtotime($program->getDate())); ?></span>
                <span class="week">(<?php echo $program->getWeekChineseName(); ?>)</span>
                <span class="time"><?php echo substr($program->getTime(), 0, 5); ?></span>
                <span class="title"><?php echo htmlspecialchars_decode(mb_substr($program->getName(), 0, 20, 'utf-8')); ?></span>
                <span class="channel"><?php echo $program->getChannel()->getName(); ?></span>
            </li>
            <?php endforeach; ?>
            <?php else: ?>
            <li class="action">
                无结果，请重新输入
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="footer">
    <div class="back" id="search-close-back">
        <div class="action button">返回</div>
    </div>
    <div class="pages" id="search-results-pages">
        <div class="action pgu">上页</div>
        <div class="action pgd">下页</div>
    </div>
</div>
