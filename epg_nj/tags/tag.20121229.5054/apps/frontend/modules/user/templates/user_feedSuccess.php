<div class="container profile">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <section id="section">
        <div class="feed-mod">
          <h1><?php if(isset($myself)):?>最近动态<?php else:?><?php echo $user->getNickname();?>的动态 <?php endif;?></h1>
          <div class="feed-bd">
            <ul>
            <?php if(count($commentList) > 0) :?>
            	<?php include_partial("comments_list",array("comments"=>$commentList,'page'=>$page));?>
            <?php else: ?>
                <div class="no-data">暂无动态</div>
            <?php endif;?>
            </ul>
            <div class="loading-feed" style="display:none"><span>载入中...</span></div>
            <div class="more-feed" style="display:none"><a href="javascript:void(0)"><span>查看更多动态</span></a></div>

          </div>
        </div>
      </section>
      <aside id="aside">
        <?php include_component('user', 'user_borad')?>
        <?php include_component('user', 'user_summ')?>
      </aside>
    </div>
  </div>
</div>
<script type="text/javascript">//提交评论
<?php $uid = $sf_request->getParameter('uid', $sf_user->getAttribute('user_id'));?>
function submitReply(form, wiki_id) {
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
            data: {'type': 'reply', 'id': wiki_id, 'pid' : pid, 'comment': text },
            success: function(html){
                list.append(html);
                form.comment.value = '';
                reply.text(' '+ reply_num + ' 回应'); //评论数加1
            }
        });
    }
}

//回复评论
function reply(id){
    $(id).children('.reply-list, .reply-form').slideToggle('fast');
};

$("document").ready(function(){
    var page = 1 ,
        nextpage = 2 ,
        more = $(".more-feed"),
        loading = $('.loading-feed');
    checkMore(nextpage);
    
    more.click(function(){
        page = page ? page + 1 : 1;
        nextpage = nextpage ? nextpage + 1 : 2;    
        $.ajax({
            url:'<?php echo url_for("@user_load_comment"); ?>',
            type:'get',
            dataType: 'html',
            data:{'page':page ,'uid': '<?php echo $uid?>'},
            beforeSend: function() {
                loading.show();
                more.hide();
            },
            success:function(data){
                $(".feed-bd > ul").append(data);
                loading.hide();
                checkMore(nextpage);
            }
        });  
    });
});

var checkMore = function(page){
    $.get('<?php echo url_for('user/check_more')?>',
        {'page' : page, 'uid': '<?php echo $uid?>'},
        function(m){
            if(m == 0){
                $(".more-feed").hide();
            } else {
                 $(".more-feed").show();
            }          
        }
    )
}
</script>