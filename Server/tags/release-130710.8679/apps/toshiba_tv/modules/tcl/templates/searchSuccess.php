<script type="text/javascript">
$(document).ready(function() {
    $("#<?php echo $sizer;?> #search-form-widget").list({
        focus: function(event) {
            $(this).find("#fake-search-input").addClass("display-none");
            $(this).find("#true-search-input").removeClass("display-none");
            $(this).find("#searchq").focus();
        },
        blur: function(event) {
            $(this).find("#fake-search-input").removeClass("display-none");
            $(this).find("#true-search-input").addClass("display-none");
            $(this).find("input#searchq").blur();
        },
        up: function(event) {
            var up = $(this).data('up');
            if (up) {
                up.data('ui').focus();
            }
        },
        down: function(event) {
            var down = $(this).data('down');
            if (down) {
                down.data('ui').focus();
            }
        },
        left: function(event) {
            var left = $(this).data('left');
            if (left) {
                left.data('ui').focus();
            }
            //console.log($(this).data('back'));
            //$('#menu-bar').data('ui').focus();
        }
    });
    $("#<?php echo $sizer;?> #searchq").click(function(event) {
        $(this).attr('value', '');
    });
    $("#<?php echo $sizer;?> #search-do-it").click(function(event) {
        q = $("#<?php echo $sizer;?> #searchq").attr('value'); //item.attr('rel');
        if (q) {
            $("#search-sizer").load("<?php echo url_for('search/index'); ?>", {"q": q}, function() {
                $("#main-nav-sizer").addClass("display-none");
                $("#search-sizer").removeClass("display-none");
                $("#search-results-list").data("ui").focus();
                //$(".channel-item:first").data("ui").focus();
                //$("#channel_programs_back").list('focus');
            });
        }
        return false;
    });
});
</script>
<div class="search-form" id="search-form-widget">
    <div>
        <div class="fake" id="fake-search-input">
            <div class="textfield-focus">
                <div class="textfield">输入节目名称...</div>
            </div>
            <span class="submit-focus">搜索</span>
        </div>
        <div class="real display-none" id="true-search-input">
            <div class="textfield-focus">
                <input type="text" id="searchq" name="q" class="textfield" value="按OK键输入搜索">
            </div>
            <a id="search-do-it" href="#"><span class="submit-focus">搜索</span></a>
        </div>
    </div>
</div>
