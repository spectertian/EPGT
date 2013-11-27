<div class="main-hd">
  <h1 property="v:itemreviewed">
      <span class="title"><?php echo $wiki->getTitle()?></span>
      <?php if($Alias = $wiki->getAlias()):?>
      <?php foreach($Alias as $Alia) :?>
      <span class="alt-title"><?php echo $Alia ?></span>
      <?php endforeach;?>
      <?php endif;?>
  </h1>
  <div id="tool">
    <div class="add">
      <div class="queue"><a href="javascript:void(0)" class="popup-tip <?php echo ($sf_user->getStatusByType((string) $wiki->getId(), 'queue')) ? 'active' : ''?>" title="加入收藏，稍后观看">加入片单</a></div>
      <div class="add-hd"><a href="javascript:void(0)" class="popup-tip" title="加入到精选集，分类归档收藏">加入到...</a></div>
      <div class="add-bd">
        <!-- <h4>添加到：</h4> -->
        <ul>
          <li class="cancel-queue"><a href="javascript:void(0)">取消片单</a></li>
          <li class="add-queue"><a href="javascript:void(0)">加入片单</a></li>
          <li class="add-selectedset"><a href="#">加入专辑</a></li>
        </ul>
      </div>
    </div>
    <div class="share"><a href="javascript:void(0)" class="popup-tip" title="分享到社交网站">分享</a></div>
    <div class="like-area" rel="v:rating">
      <div class="like-tip popup-tip <?php echo $wiki->getRatingColor()?>" title="<?php echo $wiki->getRatingTotal()?> 评价，<?php echo ($wiki->getRating() * 10)?>% 喜欢" typeof="v:Rating">
          <span class="rating-num" property="v:average"><strong><?php echo $wiki->getRatingInt()?></strong>.<?php echo $wiki->getRatingFloat()?></span>
          <span class="hidden">（满分为 <span property="v:best" content="10.0">10</span>分），共 <span property="v:votes"><?php echo $wiki->getRatingTotal()?></span> 人评价。<span property="v:count"><?php echo $wiki->getCommentCount()?></span> 条用户评论。</span>
      </div>
      <div class="like"><a href="javascript:void(0)" class="popup-tip <?php echo ($sf_user->getStatusByType((string) $wiki->getId(), 'like')) ? 'active': ''?>" title="<?php echo $wiki->getLikeNum()?> 喜欢">喜欢</a></div>
      <div class="dislike"><a href="javascript:void(0)" class="popup-tip <?php echo ($sf_user->getStatusByType((string) $wiki->getId(), 'dislike')) ? 'active': ''?>" title="<?php echo $wiki->getDislikeNum() ?> 不喜欢">不喜欢</a></div>
    </div>
    <div class="watched"><a href="javascript:void(0)" class="popup-tip <?php echo ($sf_user->getStatusByType((string) $wiki->getId(), 'watched')) ? 'active': ''?>" title="<?php echo $wiki->getWatchedNum() ?> 看过">看过</a></div>
  </div>
</div>
<?php if($wiki->getModel() == 'television') :?>
<div class="main-tab clearfix">
  <ul>
    <li><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug())?>" <?php echo (!isset($wikiMeta) && ('show' == $sf_request->getParameter('action'))) ? 'class="active"' : ''?>>栏目主页</a></li>
    <li><a href="<?php echo url_for('@archive?slug='.$wiki->getSlug())?>" <?php echo ('archive' == $sf_request->getParameter('action')) ? 'class="active"' : ''?>>节目归档</a></li>
    <?php if (isset($wikiMeta)) :?>
    <li><a href="<?php echo url_for('@wiki_show?slug='.$wiki->getSlug().'&time='. $wikiMeta->getMark())?>" class="active"><?php echo $wikiMeta->getMark()?> 期</a></li>
    <?php endif;?>
  </ul>
</div>
<?php endif;?>
<div class="main-bd clearfix">
  <section id="section">
    <div class="notice">
      <div class="tool-popup" id="share-popup">
        <div class="close"><a href="javascript:void(0)">x</a></div>
        <div id="ckepop"> <span class="jiathis_txt">分享到：</span> <a class="jiathis_button_qzone"></a> <a class="jiathis_button_tsina"></a> <a class="jiathis_button_tqq"></a> <a class="jiathis_button_renren"></a> <a class="jiathis_button_kaixin001"></a> <a class="jiathis_button_tsohu"></a> <a class="jiathis_button_douban"></a> </div>
      </div>
      <div class="tool-popup" id="tip-popup">
        <h4 class="like-tip red">您取消了喜欢该片！</h4>
        <h4 class="dislike-tip red">您取消了不喜欢该片！</h4>
        <h4 class="watched-tip red">您取消了看过该片！</h4>
        <h4 class="queue-tip">成功加入片单 / 已在片单，<a href="#">去我的片单查看</a>！</h4>
        <h4 class="cancel-queue-tip red">已从片单删除！</h4>
      </div>
      <div class="tool-popup clearfix" id="tool-popup">
        <div class="close"><a href="javascript:void(0)">x</a></div>
        <form action="<?php echo url_for('wiki/comment')?>" method="post">
          <div class="module">
            <div class="hd">
              <h4 class="like-tip">您喜欢该片，评论一下<small>(选填)</small></h4>
              <h4 class="dislike-tip">您不喜欢该片，评论一下<small>(选填)</small></h4>
              <h4 class="watched-tip">您看过该片，评论一下<small>(选填)</small></h4>
            </div>
            <div class="bd"><textarea name="comment" id='pinglun'><?php if($pinglun){echo $pinglun->getText();}?></textarea></div>
          </div>
          <div class="module add-tags">
            <div class="hd">
              <h4>添加印象<small>(选填)</small></h4>
              <div class="sample">例如：美国 电影 'on the road'（多印象之间用空格分隔，英文词组用单引号分隔）</div>
            </div>
            <div class="bd">
              <input type="text" name="tags" value="" />
            </div>
            <div class="tags-tip">
              <?php if($UserTags = $sf_user->getUserTags()) :?>
              <dl>
                <dt>我的标签：</dt>
                <?php foreach($UserTags as $utags => $count) :?>
                <dd><a href="javascript:void(0)"><?php echo $utags?></a></dd>
                <?php endforeach;?>
              </dl>
              <?php endif;?>
              <?php if($CommentTags = $wiki->getCommentTags()) :?>
              <dl>
                <dt>常用标签：</dt>
                <?php foreach($CommentTags as $ctags => $count) :?>
                <dd><a href="javascript:void(0)"><?php echo $ctags?></a></dd>
                <?php endforeach;?>
              </dl>
              <?php endif;?>
            </div>
          </div>
          <div class="save">
            <input type="hidden" name="id" value="<?php echo $wiki->getId()?>"/>
            <input type="hidden" name="type"/>
            <input type="submit" value="保存">
          </div>
        </form>
      </div>
      <?php if( 0 < count($related_programs)) : ?>
      <div class="epg-popup">
        <div class="close"><a href="javascript:void(0)">x</a></div>
        <span class="tip-info">正在播放 / 本周有电视播放该节目</span> <span class="tip-act"><a href="#tv-listing">参与讨论 / 查看节目表</a></span>
      </div>
      <?php endif;?>
    </div>
<?php use_stylesheet('colorbox.css')?>
<?php use_javascript('jquery.colorbox-min.js')?>
<!-- jiathis start -->
<script type="text/javascript" src="http://v2.jiathis.com/code/jia.js" charset="utf-8"></script>
<!-- jiathis end -->
<script type="text/javascript">
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
        $(this).toggleClass('active',true).parent().siblings().children('a').removeClass('active');
        if ( $(this).hasClass('active') ){
            wikiAjaxAction('like');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .like-tip').show().siblings().hide();
            $('input[name=type]').val('like');
        } else {
            //该段未执行
            /*lfc
            wikiAjaxAction('like_cancel');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tip-popup .like-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
            */
            wikiAjaxAction('like');
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
        $(this).toggleClass('active',true).parent().siblings().children('a').removeClass('active');
        if ( $(this).hasClass('active') ){
            wikiAjaxAction('dislike');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .dislike-tip').show().siblings().hide();
            $('input[name=type]').val('dislike');
        } else {
            //该段未执行
            /*lfc
            wikiAjaxAction('dislike_cancel');
            $('.notice .tool-popup').hide();
            $('#tip-popup .dislike-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
            */
            wikiAjaxAction('dislike');
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
        $(this).toggleClass('active',true);
        if ( $(this).hasClass('active') ){
            wikiAjaxAction('watche');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tool-popup').slideDown('fast');
            $('#tool-popup .watched-tip').show().siblings().hide();
            $('input[name=type]').val('watched');
        } else {
            /*lfc
            wikiAjaxAction('watche_cancel');
            $('.notice .tool-popup').fadeOut('fast');
            $('#tip-popup .watched-tip').show().siblings().hide();
            $('#tip-popup').slideDown('fast');
            setTimeout(function(){$('#tip-popup').fadeOut('slow')},3000);
            */
            //修改:lfc
            wikiAjaxAction('watche');
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
                //alert('执行到ajax');
                $('#pinglun').val(data.neirong);
                $('.like-area .like a').attr('title', data.like_num + ' 喜欢');
                $('.like-area .dislike a').attr('title', data.dislike_num + ' 不喜欢');
                $('.watched a').attr('title', data.watched_num + ' 看过');
                $('.rating-num').html('<strong>' + data.rating_int + '</strong>.' + data.rating_float);                
                $('.like-tip').attr('title', data.rating_total + ' 评价，'+ data.rating * 10 + '% 喜欢');
                if ( $.cookie('rating_color') != data.rating_color) {
                    $('.like-tip').removeClass($.cookie('rating_color')).addClass(data.rating_color);
                    $.cookie('rating_color', data.rating_color);
                }
            }
        }
    });
}
//记录比例图标颜色
$.cookie('rating_color', '<?php echo $wiki->getRatingColor()?>');
</script>