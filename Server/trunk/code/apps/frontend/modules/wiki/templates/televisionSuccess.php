<style type='text/css'>
.movie-id {background:#b91b12 url('public/topic_show.jpg') no-repeat center 48px;}
.topic .container { padding:200px 0 25px;}
</style>
<div class="container">
  <div class="container-inner">
    <?php include_partial('nav_tool', array('wiki' => $wiki, 'wikiMeta' => $wikiMeta, 'related_programs' => $related_programs,'pinglun'=>$pinglun))?>
        <div class="overview drama clearfix">
          <h2><?php echo $wikiMeta->getTitle()?></h2>
          <div class="poster"><img width="172" height="255" src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报" itemprop="photo"></div>
          <div class="info">
            <?php if($tags = $wiki->getTags()): $i= 0 ?>
            <div class="text-block">
                <span class="label">类型：</span>
                <?php foreach($tags as $tag) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="<?php echo url_for('search/index?q=tag:'. $tag)?>" title="<?php echo $tag?>" property="v:genre"><?php echo $tag?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <?php if($hosts = $wiki->getHost()): $i= 0 ?>
            <div class="text-block">
                <span class="label">主持人：</span>
                <?php foreach($hosts as $host) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($host)) ?>" title="<?php echo $host?>" property="v:directedBy"><?php echo $host?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif;?>
            <?php if($guests = $wikiMeta->getGuests()): $i= 0 ?>
            <div class="text-block">
                <span class="label">嘉宾：</span>
                <?php foreach($guests as $guest) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''?>
                <span class="param"><a href="<?php echo url_for('search/index?q=' . urlencode($guest)) ?>" title="<?php echo $guest?>" property="v:starring"><?php echo $guest?></a></span>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
            <?php if ($wiki->getChannel()) :?>
            <div class="text-block"><span class="label">播出频道：</span><span class="param"><?php echo $wiki->getChannel()?></span></div>
            <?php endif?>
            <?php if ($wiki->getPlayTime()) :?>
            <div class="text-block"><span class="label">播出时间：</span><span class="param"><?php echo $wiki->getPlayTime()?></span></div>
            <?php endif?>
            <?php if ($wiki->getRuntime()) :?>
            <div class="text-block"><span class="label">播出时长：</span><span class="param"><?php echo $wiki->getRuntime()?></span></div>
            <?php endif?>
            <?php if($wiki->getCountry()): ?>
            <div class="text-block"><span class="label">国家/地区：</span><span class="param"><?php echo $wiki->getCountry()?></span></div>
            <?php endif;?>
            <?php if($wiki->getLanguage()): ?>
            <div class="text-block"><span class="label">语言：</span><span class="param"><?php echo $wiki->getLanguage()?></span></div>
            <?php endif;?>
            <?php if($wikiMeta->getHtmlCache()): ?>
            <div class="text-block summary">
              <p><span class="label">本期看点：</span><span class="param" property="v:summary"><?php echo $wikiMeta->getHtmlCache(150, ESC_RAW)?>... <a href="#detail">详细&raquo;</a></span></p>
            </div>
            <?php endif;?>
          </div>
        </div>
        <div class="mod" id="vedio-resources">
          <div class="hd">
            <h3>版权片源</h3>
            <!--<div class="r"><a href="#">我要提供片源？</a></div>-->
          </div>
          <?php if ($videos = $wiki->getVideos($wikiMeta->getMark())) :?>
          <div class="bd clearfix">
            <ul>
              <?php foreach($videos as $video) :?>
              <li class="play-btn">
                <div class="on-demand play-<?php echo $video->getReferer()?>">
                    <a href="<?php echo $video->getUrl()?>" class="popup-tip" title="播放<?php echo $video->getRefererZhcn()?>片源" target="_blank"><?php echo $video->getRefererZhcn()?>视频</a>
                </div>
              </li>
              <?php endforeach;?>
            </ul>
          </div>
          <?php else: ?>
          <div class="no-resource">很抱歉，该节目还没有片源！</div>
          <?php endif;?>
        </div>
        <?php include_partial('program_guide', array('programs' => $related_programs))?>
	<div class="mod" id="detail">
          <div class="hd">
            <h3>本期看点</h3>
          </div>
          <div class="bd">
            <div class="storyline"><?php echo $wikiMeta->getHtmlCache(ESC_RAW); ?></div>
            <?php if($wikiMeta->getScreenshots()): ?>
            <div class="stills">
              <ul>
                <?php $i = 0;?>
                <?php foreach($wikiMeta->getScreenshots() as $screenshot) : $i++?>
                <li>
                    <a href="<?php echo file_url($screenshot) ?>" rel="stills" title="<?php printf('%s%d', $wiki->getTitle(), $i)?>">
                       <img src="<?php echo thumb_url($screenshot, 150, 150) ?>" alt="<?php printf('%s%d', $wiki->getTitle(), $i)?>">
                    </a>
                </li>
                <?php endforeach;?>
              </ul>
            <?php if(4 < $wikiMeta->getScreenshotsCount()) :?>
            <div class="more">(全部 <?php echo $wiki->getScreenshotsCount()?> 张剧照) <a href="javascript:void(0)">展开</a></div>
            <?php endif;?>
            </div>
            <?php endif;?>
          </div>
        </div>
        <?php include_partial('comments', array('wiki' => $wiki, 'weibo_sina'=>$weibo_sina, 'weibo_qqt'=>$weibo_qqt))?>
      </section>
      <aside id="aside">
        <?php include_partial('global/ad')?>
        <?php include_component('wiki', 'related_movies')?>
        <?php include_partial('comment_tags', array('commentTags' => $wiki->getCommentTags()))?>
        <!-- </div> -->
      </aside>
    </div>
  </div>
</div>
<?php use_stylesheet('colorbox.css')?>
<?php use_javascript('jquery.colorbox-min.js')?>
<!-- jiathis start -->
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- jiathis end -->
<script type="text/javascript">
$(function(){
    // colorbox
    $("a[rel='stills']").colorbox();
    // colorbox zoom in
    $("#cboxContent").prepend("<div class='zoomin' STYLE='BACKGROUND:#F00; POSITION:ABSOLUTE; BOTTOM:40px; RIGHT:10px;'><a href='#' target='_blank'>查看原图</a></div>");
    // more or less stills
    $('.stills .more a').toggle(function (){
        $(this).addClass('active').empty().append('收缩');
        $(this).parents('.stills').children('.stills ul').css({ 'height': 'auto' });
    },function (){
        $(this).removeClass('active').empty().append('展开');
        $(this).parents('.stills').children('.stills ul').css({ 'height': '110px' });
    });
})

$('#tool .queue a').click(function (event){
    if (loginDialogStatus()){
        $('#tool .queue a').toggleClass('active');
        if ( $(this).hasClass('active') ){
            wikiAjaxAction('queue');
            $('#tip-popup .queue-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
        } else {
            wikiAjaxAction('queue_cancel');
            $('#tip-popup .cancel-queue-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
        }
    }
});

$('#tool .cancel-queue a').click(function (event){
    if (loginDialogStatus()){
        wikiAjaxAction('queue_cancel');
        $('#tool .queue a').toggleClass('active');
        $('#tip-popup .cancel-queue-tip').show().siblings().hide();
        $('#tip-popup').slideDown('fast');
        setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
    }
});

$('#tool .add-queue a').click(function (event){
    if (loginDialogStatus()){
        wikiAjaxAction('queue');
        $('#tool .queue a').toggleClass('active');
        $('#tip-popup .queue-tip').show().siblings().hide();
        $('#tip-popup').slideDown('fast');
        setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
    }
});

$(document).click(function() {
    $('#tool .add .add-bd').hide();
});

$('#tool .add .add-hd a').click(function (event){
    if (loginDialogStatus()){
        $('#tool .add .add-bd').toggle();
        event.stopPropagation();
        if ( $('#tool .queue a').hasClass('active') ){
            $('.add-bd .cancel-queue').show();
            $('.add-bd .add-queue').hide();
        }
        else {
            $('.add-bd .cancel-queue').hide();
            $('.add-bd .add-queue').show();
        }
    }
});

$('.share a').click(function (){
    $('#share-popup').slideToggle('fast').siblings('.tool-popup').fadeOut('fast');
});
<?php if(!$sf_user->getStatusByType((string) $wiki->getId(),'dislike')):?>
$('.like-area .like a').click(function (){
    if (loginDialogStatus()){
        $(this).toggleClass('active').parent().siblings().children('a').removeClass('active');
        if ( $(this).hasClass('active') ){
            wikiAjaxAction('like');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .like-tip').show().siblings().hide();
            $('input[name=type]').val('like');
        } else {
            /*lfc
            wikiAjaxAction('like_cancel');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tip-popup .like-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
            */
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .like-tip').show().siblings().hide();
            $('input[name=type]').val('like');            
        }
    }
});
<?php endif;?>
<?php if(!$sf_user->getStatusByType((string) $wiki->getId(),'like')):?>
$('.like-area .dislike a').click(function (){
    if (loginDialogStatus()){
        $(this).toggleClass('active').parent().siblings().children('a').removeClass('active');
        if ( $(this).hasClass('active') ){
            wikiAjaxAction('dislike');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .dislike-tip').show().siblings().hide();
            $('input[name=type]').val('dislike');
        } else {
            /*lfc
            wikiAjaxAction('dislike_cancel');
            $('.notice .tool-popup').hide();
            $('#tip-popup .dislike-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
            */
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .dislike-tip').show().siblings().hide();
            $('input[name=type]').val('dislike');            
        }
    }
});
<?php endif;?>
$('.watched a').click(function (){
    if (loginDialogStatus()){
        $(this).toggleClass('active');
        if ( $(this).hasClass('active') ){
            wikiAjaxAction('watche');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .watched-tip').show().siblings().hide();
            $('input[name=type]').val('watched');
        } else {
            /*原有程序
            wikiAjaxAction('watche_cancel');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tip-popup .watched-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
            */
            //修改:lfc
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .watched-tip').show().siblings().hide();
            $('input[name=type]').val('watched');                   
        }
    }
});

$('.tool-popup .close a').click(function (){
    $(this).parents('.tool-popup').hide();
});

// 点击选择输入标签
$('.tags-tip > dl > dd').each(function(){
    $(this).click(function(){
        var input_tag = $('input[name=tags]');
        var a = $(this).find('a');
        var tags = input_tag.val().split(/[ ]+/g);
        if (a.hasClass('active')) {
            var new_tags = [];
            for(var i = 0; i < tags.length; i++) {
                if(tags[i] == a.text()) {
                        a.removeClass('active');
                } else {
                        new_tags.push(tags[i]);
                }
                input_tag.val(new_tags.join(' '));
            }
        } else {
            tags.push(a.text());
            input_tag.val(tags.join(' '));
            a.addClass('active');
        }
    });
});

// 手动输入标签
$('input[name=tags]').bind('keyup', function(){
    var tags = $(this).val().split(/[ ]+/g);
    $('.tags-tip > dl > dd').each(function(){
        var a = $(this).find('a');
        if(inArray(a.text(), tags)){
            a.addClass('active');
        } else {
            a.removeClass('active');
        }
    });
});
//ajax 评分请求维基页面
function wikiAjaxAction(act) {
    $.ajax({
        url: '<?php echo url_for('@wiki_do')?>',
        type: 'get',
        dataType: 'json',
        data: {'act': act, 'id': '<?php echo $wiki->getId()?>' },
        success: function(data){
            if (data == 0) {
                alert('网络繁忙！请稍候再试..');
            } else if (data == 1) {
                $('.cancel-queue').show();
            } else if (data == 2) {
                $('.cancel-queue').hide();
            } else {
                $('.like-tip').attr('title', data.rating_total + ' 评价，'+ data.rating * 10 + '% 喜欢');
                if ( $.cookie('rating_color') != data.rating_color) {
                    $('.like-tip').removeClass($.cookie('rating_color')).addClass(data.rating_color);
                    $.cookie('rating_color', data.rating_color);
                }
                $('.like-area .like a').attr('title', data.like_num + ' 喜欢');
                $('.like-area .dislike a').attr('title', data.dislike_num + ' 不喜欢');
                $('.watched a').attr('title', data.watched_num + ' 看过');
                $('.rating-num').html('<strong>' + data.rating_int + '</strong>.' + data.rating_float);
            }
        }
    });
}
//记录比例图标颜色
$.cookie('rating_color', '<?php echo $ratingInfo['rating_color']?>');
</script>