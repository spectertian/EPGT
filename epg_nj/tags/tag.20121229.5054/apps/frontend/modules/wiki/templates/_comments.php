<div class="tab-mod" id="discussion">
  <div class="tab-hd">
    <ul>
      <li><a href="javascript:void(0)" class="active">相关讨论</a></li>
      <li><a href="javascript:void(0)">新浪微博</a></li>
    </ul>
  </div>
  <div id="tab1" class="tab-bd" style="display:block;">
    <div class="shuo clearfix">
       <?php if ($sf_user->isAuthenticated()): ?>
      <div class="avatar"><a href="<?php echo url_for('user/user_feed?uid='.$sf_user->getAttribute("user_id"))?>"><img width="32" height="32" src="<?php echo thumb_url($sf_user->getAttribute('avatar'), 32, 32)?>" class="popup-tip" title="<?php echo $sf_user->getAttribute('username')?>"></a></div>
      <?php endif;?>
      <form action="<?php echo url_for('wiki/comment')?>" method="post"onsubmit="return loginDialogStatus()">
        <textarea id="TextareaComment" name="comment" class="textarea" placeholder="说点什么 .."></textarea>
        <input type="hidden" name="id" value="<?php echo $wiki->getId()?>"/>
        <input type="hidden" name="title" value="<?php echo $wiki->getTitle()?>" />
        <input type="hidden" name="type" value="comment"/>
        <input id="ButtonPostComment" class="submit-comment" type="submit" value="发表评论">
        <div id="PostCommentCount" class="text-limit">140</div>
        <input type="checkbox" name="weibo[]"  value="Sina" <?php if($weibo_sina !=false) echo "checked";?> /> 同步新浪
        <input type="checkbox" name="weibo[]" value="Qqt" <?php if($weibo_qqt !=false) echo "checked";?> /> 同步腾讯
      </form>
    </div>
    <div class="feed-hd" id="comment-btn">
        <a href="javascript:load('all')" class="active">全部动态</a>
        <a href="javascript:load('comment')">只看评论</a>
    </div>
    <div class="feed-bd">
      <ul id="comment-list">
      </ul>
      <div class="loading-feed" style="display:none"><span>载入中...</span></div>
      <div class="more-feed"><a href="javascript:void(0)"><span>查看更多动态</span></a></div>
    </div>
  </div>
  <div id="tab2" class="tab-bd" style="display:none;">
    <div style="padding:15px;">
        数据加载中 ...
    </div>
   </div>
</div>
<script type="text/javascript">
//初始化 cookie 数据
$.cookie('type', 'all');
$.cookie('page', 2);
$(function(){
    checkMore('all', 1);
    commentLoad('all', 1, false);
    // comment text-limit
    $("#ButtonPostComment").attr("disabled", "disabled").addClass('disabled');
    $("#TextareaComment").bind('keyup', function(){
        var ButtonPostComment = $("#ButtonPostComment");
        var PostCommentCount = $("#PostCommentCount");
        var Textarea =  $.trim($("#TextareaComment").val());
        var inputCount = 140 - Textarea.length;
        PostCommentCount.text(inputCount);
        if (141 > Textarea.length > 0) {
            ButtonPostComment.removeAttr("disabled").removeClass('disabled');
        } else {
            ButtonPostComment.attr("disabled", "disabled").addClass('disabled');
        }
        if (Textarea.length == 0) {
            ButtonPostComment.attr("disabled", "disabled").addClass('disabled');
        }
        
        if(inputCount < 0) {
            PostCommentCount.css({"color": "#f00"});
        } else {
            PostCommentCount.css({"color": "#999"});
        }
    })
})

// 加载更多
$('.more-feed a').bind('click', function(){
    var page = $.cookie('page');
    var type = $.cookie('type');
    checkMore(type, page);
    commentLoad(type, page, true);
})

//根据类型加载数据
function load(type) {
    var page = $.cookie('page');
    var cookie_type = $.cookie('type');
    if (!page || (cookie_type != type)) {
        page = 1;
        $.cookie('type', type);
        $.cookie('page', 2);
    }
    checkMore(type, page);
    commentLoad(type, page, false);
}

//检查是否有更多
function checkMore(type, page) {
    $.get('<?php echo url_for('@wiki_more')?>',
        {'type': type, 'id': '<?php echo $wiki->getId()?>', 'page': page},
        function(rs){
            if (rs == 1) {
                $('.more-feed').show();
            } else {
                $('.more-feed').hide();
            }
    });
}

//加载数据评论
function commentLoad(type, page, more){
    $.ajax({
        url: '<?php echo url_for('@wiki_load_comment')?>',
        type: 'get',
        dataType: 'html',
        data: {'type': type, 'id': '<?php echo $wiki->getId()?>', 'page': page},
        beforeSend: function() {
            $('.loading-feed').show();
            $('.more-feed').hide();
        },
        success: function(html){
            if(type == 'all') {
                $('#comment-btn a').eq(0).addClass('active');
                $('#comment-btn a').eq(1).removeClass('active');
            } else if (type == 'comment'){
                $('#comment-btn a').eq(0).removeClass('active');
                $('#comment-btn a').eq(1).addClass('active');
            }
            if (more) {
                $("#comment-list").append(html);
                $.cookie('page', (parseInt(page) + 1));
            } else {
                $("#comment-list").html(html);
            }
 
            $('.loading-feed').hide();
        }
    });
}

//回复评论
function reply(id){
    $(id).children('.reply-list, .reply-form').slideToggle('fast');
};
//提交评论
function submitReply(form) {
    if (loginDialogStatus()) {
        var text = $.trim(form.comment.value);
        var pid = form.pid.value;
        var replay_div = $(form).parent();
        var list = replay_div.prev();
        var reply_id = form.reply.value;
        var reply = $("#reply-"+reply_id);
        var reply_num = parseInt(reply.text());
        reply_num = (!isNaN(reply_num)) ? reply_num + 1 : 1;

        if (text.length == 0) {
            return false;
        }

        $.ajax({
            url: '<?php echo url_for('@wiki_comment')?>',
            type: 'post',
            dataType: 'html',
            data: {'type': 'reply', 'id': '<?php echo $wiki->getId()?>', 'pid' : pid, 'comment': text },
            success: function(html){
                list.append(html);
                form.comment.value = '';
                reply.text(' '+ reply_num + ' 回应'); //评论数加1
                replay_div.slideUp('slow');
                list.slideUp('slow');
            }
        });
    }
}

// tab-mod
$('.tab-mod .tab-hd li a').click(function(){
    $(this).addClass('active').parent('.tab-mod .tab-hd li').siblings().children('a').removeClass();
    $(".tab-mod .tab-bd").eq($('.tab-mod .tab-hd li a').index(this)).show().siblings('.tab-bd').hide();
    if ($(this).text() == '新浪微博') weibo();
});
//加载微薄数据
function weibo(){
    $.ajax({
        url: '<?php echo url_for('@wiki_weibo')?>',
        type: 'get',
        dataType: 'html',
        data: {'title': '<?php echo $wiki->getTitle()?>'},
        success: function(html){
            $('#tab2 div').html(html);
        }
    });
}

function delComment(id) {
    if(id.length=="") return false;
    $.ajax({
    type: "POST",
    url: '<?php echo url_for('user/delcomment');?>',
    data: "id="+id,
    success: function(msg){
        if(msg==1) {
            //alert("删除评论成功!");
            //window.location.reload();
        }else{
            //alert("删除失败");
        }
    }
    });
}
</script>