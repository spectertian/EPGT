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
            left: function(event) {
                $('#home-right').data('ui').focus();
            },
            right: function(event) {
                $('#home-right').data('ui').focus();
            },
            enter: function(event, item) {
                wiki_id = item.attr('rel');
                back_target = '#home-left';
                var wiki_url = '<?php echo url_for('wiki/detail?id='); ?>' + wiki_id;
               $("#wiki-sizer").load(wiki_url, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#wiki-sizer").attr('return_target', back_target);
                    $("#wiki-sizer").removeClass("display-none");
                    $('#tab').data('ui').focus();
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
                var wiki_url = '<?php echo url_for('wiki/detail?id='); ?>' + wiki_id;
                $("#wiki-sizer").load(wiki_url, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#wiki-sizer").attr('return_target', back_target);
                    $("#wiki-sizer").removeClass("display-none");
                    $('#tab').data('ui').focus();
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
        <?php if ($bigPicWiki):?>
	    <div class="action spotlight" rel="<?php $id = substr($bigPicWiki->getUrl(), strrpos($bigPicWiki->getUrl(), "=")+1); echo $id;?>">
            <div class="frame">
                <div class="title">《<?php echo $bigPicWiki->getTitle();?>》</div>
                <div class="description"><?php echo mb_strcut($bigPicWiki->getDesc(),0,60,'utf-8');?></div>
                <div class="button">查看详情</div>
            </div>
            <img src="<?php echo thumb_url($bigPicWiki->getPic(),895,498);?>" width="895" height="498" alt="">
        </div>
        <?php endif;?>
    </div>
    <div id="home-right">
        <div class="featured">
            <ul>
                <?php if ($recWikis):?>
                <?php foreach($recWikis as $recWikis) :
                	$id = substr($recWikis->getUrl(), strrpos($recWikis->getUrl(), "=")+1);
                ?>
                <li class="action" rel="<?php echo $id;?>">
                    <div class="frame">
                        <div class="title">《<?php echo $recWikis->getTitle();?>》</div>
                        <div class="button">详情</div>
                    </div>
                    <img src="<?php echo thumb_url($recWikis->getSmallpic(),268,156);?>" width="268" height="156" alt="">
                </li>
                <?php endforeach;?>
                <?php endif;?>
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