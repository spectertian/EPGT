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
        <div class="action spotlight"  rel="52851">
            <div class="frame">
                <div class="title">《桥隆飙》</div>
                <div class="description">桥隆飙率领的"飙字军"，骁勇善战、劫富济贫，后受到共产党的感化，从自发的革命斗争到接受党领导的曲折复杂过程，整编入伍打击日寇的传奇故事。</div>
                <div class="button">查看详情</div>
            </div>
            <img src="public/home_spotlight.jpg" width="895" height="566" alt="">
        </div>
    </div>
    <div id="home-right">
        <div class="featured">
            <ul>
                <li class="action"  rel="52830">
                    <div class="frame">
                        <div class="title">《一一向前冲》</div>
                        <div class="button">查看详情</div>
                    </div>
                    <img src="public/home_featured1.jpg" width="430" height="182" alt="">
                </li>
                <li class="action" rel="45470">
                    <div class="frame">
                        <div class="title">《你是我的生命》</div>
                        <div class="button">查看详情</div>
                    </div>
                    <img src="public/home_featured2.jpg" width="430" height="182" alt="">
                </li>
                <li class="action" rel="51138">
                    <div class="frame">
                        <div class="title">《虾球传》</div>
                        <div class="button">查看详情</div>
                    </div>
                    <img src="public/home_featured3.jpg" width="430" height="182" alt="">
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