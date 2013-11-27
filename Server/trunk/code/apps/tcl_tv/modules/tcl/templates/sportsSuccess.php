<script type="text/javascript">
var wiki_id = '';
$(document).ready(function() {
    /* 2010-11-11
    $('#popup').list({
        direction: 'V',

        enter: function(event, item) {
            var href = item.attr('href');
            if(href == 'closePoup') {
                $('#popup').addClass('display-none');
                $('.grid').grid('focus');
            } else if (href == 'showDetail') {
                $('#popup').addClass('display-none');
                var wiki_url = '<?php //echo url_for('wiki/show?id='); ?>' + wiki_id;
                $("#wiki-sizer").load(wiki_url, function() {
                    $("#main-nav-sizer").addClass("display-none");
                    $("#wiki-sizer").attr('return_target', '.grid');
                    $("#wiki-sizer").removeClass("display-none");

                    $('#footer_back').list('focus');
                });
            }
        }
    });
    */

    var scrollHeight = $('#content-panel>.cat-list').height();

    var $laar = $('#content-panel>.cat-list .larr');
    var $raar = $('#content-panel>.cat-list .rarr');

    $('.grid').grid({
        coords: [ 2, 6 ],
        init: function(event, opts) {
            //var opts = instance.options;
            if (opts.currentPage == 0) {
                $laar.addClass('display-none');
            } else {
                $laar.removeClass("display-none");
            }
            if (opts.pages <= 0) {
                $raar.addClass("display-none");
            } else {
                $raar.removeClass("display-none");
            }
        },
        enter: function(event, item) {
            wiki_id = item.attr('rel');
            //program_id = item.attr('program_id')
            var wiki_url = '<?php echo url_for('wiki/show?id='); ?>' + wiki_id ;//+ '/<?php //echo 'program_id/' ?>' + program_id;
            $("#wiki-sizer").load(wiki_url, function() {
                $("#main-nav-sizer").addClass("display-none");
                $("#wiki-sizer").attr('return_target', '.grid');
                $("#wiki-sizer").removeClass("display-none");

                $('#footer_back').list('focus');
            });
            /* 2010-11-11
            $('#popup').removeClass('display-none');
            $('#popup').list('focus');
            */
        },

        upBorde: function( event, ui ) {
            $('#menu-bar').data('ui').focus();
        },
        change: function(event, ui) {
            var item = ui.to;
            var title = item.attr('title');
            var progress = item.attr('progress');
            $('#footer_info_title').text(title);
            $('.track').css('width', progress + '%');
            $('.info').show();
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
            var $i = ui.instance.$items.eq(i);
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
<?php if (!$programs): ?>
<div class="cat-list">
  <div class="grid no-match">
    <p class="action">没有正在播出的节目，请选择其他类别</p>
  </div>
</div>
<?php else: ?>
<div class="cat-list">
    <div class="grid">
        <ul>
        <?php foreach ($programs as $program): ?>
            <?php if(!$program->getWiki()) continue; ?>
            <li
                class="action"
                rel="<?php echo $program->getWikiId() ?>"
                title="[<?php echo $program->getChannelName() ?>] <?php echo $program->getName() ?>"
                progress="<?php echo $program->getProgress() ?>"
            >
                <div class="item">
                    <div class="cover">
                        <img src="<?php echo $program->getWikiCoverUrl() ?>" width="" height="" alt="" />
                    </div>
                    <div class="title"><?php echo $program->getWikiTitle(); ?></div>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="larr display-none">&lt;</div>
    <div class="rarr display-none">&gt;</div>
</div>
<?php endif; ?>

<div class="footer">
    <div class="info" style="display: none;">
      <span id="footer_info_title"></span>
      <span class="progress">
        <span class="track" style="width:20%"></span>
      </span>
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