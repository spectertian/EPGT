<script type="text/javascript">
    $(function(){
        var wiki_id = '';
        var back_target = '';
        $('#home-left').list({
            direction: 'V',
            focus: function(event) {
                $(this).addClass('hover');
                //var title = $(this).find('div.title').text();
                //$('#footer_info_title').text(title);
                //$('.info').removeClass('display-none');
            },
            blur: function(event) {
                $(this).removeClass('hover');
            },
            right: function(event) {
                $('#home-right').data('ui').focus();
            },
            enter: function(event, item) {
                wiki_id = item.attr('rel');
                back_target = '#home-left';
                var wiki_url = '<?php echo url_for('wiki/show?id='); ?>' + wiki_id;
                $("#wiki-sizer").load(wiki_url, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#wiki-sizer").attr('return_target', back_target);
                    $("#wiki-sizer").removeClass("display-none");

                    $('#footer_back').list('focus');
                });
                /*
                back_target = '#home-left';
                var popup = $('#popup');
                popup.removeClass('display-none');
                popup.list('focus');
                popup.data('back', $(this));
                */
            },
            change: function(event, ui) {
            },
            up: function( event, ui ) {
                $('#menu-bar').data('ui').focus();
            }
        });
        $('#home-right').grid({
            coords: [3 , 1],
            change: function(event, ui) {
                //var title = ui.to.find('div.title').text();
                //$('#footer_info_title').text(title);
                //$('.info').show();
            },
            enter: function(event, item) {
                wiki_id = item.attr('rel');
                back_target = '#home-right';
                var wiki_url = '<?php echo url_for('wiki/show?id='); ?>' + wiki_id;
                $("#wiki-sizer").load(wiki_url, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#wiki-sizer").attr('return_target', back_target);
                    $("#wiki-sizer").removeClass("display-none");

                    $('#footer_back').list('focus');
                });
                /* 2010-11-11
                back_target = '#home-right';
                var popup = $('#popup');
                popup.removeClass('display-none');
                popup.list('focus');
                popup.data('back', $(this));
                */
            },
            leftBorde: function(event, ui) {
                $('#home-left').data('ui').focus();
            },
            rightBorde: function(event, ui) {
                $('#home-left').data('ui').focus();
            },
            upBorde: function (event , pos ){
                $('#menu-bar').data('ui').focus();
            }

        });
        /* 2010-11-11
        $('#popup').list({
            direction: 'V',
            enter: function(event, item) {
                var href = item.attr('href');
                if(href == 'closePoup') {
                    var back = $(this).data('back');
                    $('#popup').addClass('display-none');
                    back.data('ui').focus();
                } else if (href == 'showDetail') {
                    $('#popup').addClass('display-none');
                    var wiki_url = '<?php echo url_for('wiki/show?id='); ?>' + wiki_id;
                    $("#wiki-sizer").load(wiki_url, function() {
                        $("#main-nav-sizer").addClass("display-none");
                        $("#wiki-sizer").attr('return_target', back_target);
                        $("#wiki-sizer").removeClass("display-none");

                        $('#footer_back').list('focus');
                    });
                }
            }
        });
        */
    });
</script>
<div class="epg-home">
    <div id="home-left">
        <div class="action spotlight" rel="4d020b4eedcd88f63c000a1b">
            <div class="frame">
                <div class="title">《江湖绝恋》</div>
                <div class="description">三个家族，两代人的爱恨纠葛，一场围绕“十六年前爱情”展开的家族恩怨，当一切事情终归平淡后，有情人终成眷属。</div>
                <div class="button">查看详情</div>
            </div>
            <img src="public/home_spotlight.jpg?v=7" width="895" height="566" alt="">
        </div>
    </div>
    <div id="home-right">
        <div class="featured">
            <ul>
                <li class="action" rel="4da2b196edcd883a5e000207">
                    <div class="frame">
                        <div class="title">《抗日奇侠》</div>
                        <div class="button">查看详情</div>
                    </div>
                    <img src="public/home_featured1.jpg?v=7" width="430" height="182" alt="">
                </li>
                <li class="action" rel="4d9e91deedcd88f65d0000bb">
                    <div class="frame">
                        <div class="title">《能人冯天贵》</div>
                        <div class="button">查看详情</div>
                    </div>
                    <img src="public/home_featured2.jpg?v=7" width="430" height="182" alt="">
                </li>
                <li class="action" rel="4da2b08dedcd8883510002eb">
                    <div class="frame">
                        <div class="title">《新拿什么拯救你,我的爱人》</div>
                        <div class="button">查看详情</div>
                    </div>
                    <img src="public/home_featured3.jpg?v=7" width="430" height="182" alt="">
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="footer">
    <div class="info display-none">
        <span id="footer_info_title"></span>
        <span class="progress">
            <span class="track" style="width:20%"></span>
        </span>
        <span>00:30/01:30</span>
    </div>
    <div class="help">
        <span>按</span>
        <span class="arrows">&lt; &gt;</span>
        <span>键选择，按</span>
        <span class="button">OK</span>
        <span>键确认</span>
    </div>
</div>

<?php   include_partial('tcl/popup'); ?>