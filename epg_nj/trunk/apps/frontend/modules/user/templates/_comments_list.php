<?php foreach($comments as $key => $comment) { //var_dump($comment['user']->getAvatar());?>
  <li id="list-<?php echo $page.'-'.$key?>">
    <div class="act-<?php echo $comment['type'];?>">
      <div class="avatar"><a href="<?php echo url_for('user/user_feed?uid='.$comment['user']->getId())?>"><img width="48" height="48" alt="" src="<?php echo thumb_url($comment['user']->getAvatar(), 48, 48)?>" class="popup-tip" title="<?php echo $comment['user']->getNickname(); ?>"></a></div>
      <div class="act-icon"></div>
      <div class="act-hd"><span class="<?php echo $comment['user']->getNickName();?>"><a href="<?php echo url_for('user/user_feed?uid='.$comment['user']->getId())?>"><?php echo $comment['user']->getNickName();?></a></span> 评论 <?php if($comment['wiki']!=NULL):?><a href="<?php echo url_for('wiki/'.$comment['wiki']->getSlug());?>"><?php echo $comment['wiki']->getTitle();?></a><?php endif;?> <span class="act-reply"><a id="reply-<?php echo $page.'-'.$key?>" href="javascript:reply('#list-<?php echo $page.'-'.$key?>')"><?php echo $comment['sontotal'];?>回应</a></span>
         <?php if($comment['user']->getId()==$sf_user->getAttribute("user_id")):?>
          <span class="act-del"><a href="javascript:delComment('<?php echo $comment['id'];?>')" class="popup-tip" title="删除">x</a></span>
         <?php endif;?>
      </div>
      <div class="act-bd">
        <div class="act-quote"><?php echo $comment['text'];?></div>
        <div class="act-time"><?php $date = $comment['time'];echo  $date->format('Y-m-d H:i:s');?></div>
      </div>
    </div>
    <div class="reply-list">
      <ul>
      <?php if($comment['son']!="") {?>
      <?php foreach($comment['son'] as $sonkey => $soncomment ) { ?>
        <li>
          <div class="avatar"><a href="<?php echo url_for('user/user_feed?uid='.$soncomment->getUser()->getId())?>"><img width="32" height="32" alt="" src="<?php echo thumb_url($soncomment->getUser()->getAvatar(), 32, 32)?>" class="popup-tip" title="<?php echo $soncomment->getUser()->getNickname()?>"></a></div>
          <div class="reply-bd">
            <div class="reply-quote"><span class="<?php echo $soncomment->getUser()->getNickname();?>"><a href="<?php echo url_for('user/user_feed?uid='.$soncomment->getUser()->getId())?>"><?php echo $soncomment->getUser()->getNickname()?></a></span><?php echo $soncomment->getText();?></div>
            <div class="reply-time"><?php echo  $comment['time']->format('Y-m-d H:i:s');?></div>
          </div>
          <?php if($soncomment->getUser()->getId()==$sf_user->getAttribute("user_id")):?>
          <div class="act-del"><a href="javascript:delComment('<?php echo $soncomment->getId();?>')" class="popup-tip" title="删除">x</a></div>
          <?php endif;?>
        </li>
      <?php } ?>
      <?php } ?>
      </ul>
    </div>
    <div class="reply-form">
      <div class="avatar">
          <?php if ($sf_user->isAuthenticated()): ?>
          <a href="#">
              <img width="32" height="32" alt="" src="<?php echo thumb_url($sf_user->getAttribute("avatar"), 32, 32)?>" class="popup-tip" title="<?php echo $sf_user->getAttribute("nickname"); ?>">
          </a>
          <?php else:?>
            <img width="32" height="32" src="<?php echo thumb_url('1313572883180.png', 32, 32)?>" />
          <?php endif;?>
      </div>
      <form onsubmit="submitReply(this, '<?php echo $comment["wiki"]->getId()?>'); return false">
        <input type="text" name="comment" maxlength="140">
        <input type="hidden" name="pid" value="<?php echo $comment['id']; ?>"/>
        <input type="hidden" name="reply" value="<?php echo $page.'-'.$key?>"/>
        <input type="submit" value="回应">
      </form>
    </div>
    <div class="related-wiki clearfix">
      <div class="poster"><a href="<?php echo url_for('@wiki_show?slug='.$comment['wiki']->getSlug())?>" target="_blank"><img alt="" src="<?php echo $comment['wiki']->getCoverUrl();?>" width="60" height="90"></a></div>
      <h3><span class="title"><a href="<?php echo url_for('wiki/'.$comment['wiki']->getSlug())?>"><?php echo $comment['wiki']->getTitle();?></a></span> <small>( <span class="release-date"><?php echo $comment['wiki']->getReleased();?></span> )</small></h3>
      <div class="genre"><span class="label">类型：</span>
      <?php $tags = $comment['wiki']->getTags(); if($tags!="") {?>
      <?php foreach($tags as $key => $tag)?>
      <span class="param"><a href="<?php echo url_for('search/index?q=tag:'.$tag);?>"><?php echo $tag;?></a></span>
      <?php } ?>
      </div>
      <div class="text-block"> <span class="param"><?php echo $comment['wiki']->getHtmlCache(150);?><a href="<?php echo url_for('wiki/'.$comment['wiki']->getSlug())?>">更多资讯&raquo;</a></span></div>
      <div class="rating"><span class="rating-num"><strong><?php echo $comment['wiki']->getRatingInt();?></strong>.<?php echo $comment['wiki']->getRatingFloat();?></span> 分 &#47; <?php echo $comment['wiki']->getRatingTotal();?>评价</div>
    </div>
  </li>
<?php } ?>
<script type="text/javascript">
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
              