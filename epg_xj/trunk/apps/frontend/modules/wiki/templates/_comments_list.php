<?php foreach($comments as $i => $comment) :?>
<li id="list-<?php echo $page.'-'.$i?>">
  <div class="act-<?php echo $comment->getType()?>">
      <div class="avatar">
          <a href="<?php echo url_for('user/user_feed?uid='.$comment->getUser()->getId())?>"><img width="48" height="48" src="<?php echo thumb_url($comment->getUser()->getAvatar(), 48, 48)?>" class="popup-tip" title="<?php echo $comment->getUser()->getNickname()?>"></a>
      </div>
      <div class="act-icon"></div>
      <div class="act-hd">
          <span class="username <?php echo $sf_user->getAttribute('user_id') == ((string)$comment->getUser()->getId()) ? 'yourself' : '' ;?>">
              <a href="<?php echo url_for('user/user_feed?uid='.$comment->getUser()->getId())?>"><?php echo $comment->getUser()->getNickname()?></a>
          </span>
          <?php echo $comment->getZhcnType()?>
          <span class="act-reply"><a href="javascript:reply('#list-<?php echo $page.'-'.$i?>')" id="reply-<?php echo $page.'-'.$i?>"><?php echo $comment->getSonCommentsCount()?> 回应</a></span>
         <?php if($comment->getUser()->getId()==$sf_user->getAttribute("user_id")):?>
          <span class="act-del"><a href="javascript:delComment('<?php echo $comment->getId();?>')" class="popup-tip" title="删除">x</a></span>
         <?php endif;?>
      </div>
      <div class="act-bd">
        <div class="act-quote"><?php echo $comment->getText()?></div>
        <div class="act-time"><?php echo $comment->getCreatedAt()->format("Y-m-d H:i:s")?></div>
      </div>
  </div>
  <div class="reply-list" style="display:none">
  <ul>
    <?php if ($SonComments = $comment->getSonComments()) :?>
    <?php foreach($SonComments as $SonComment) :?>
    <li>
      <div class="avatar">
          <a href="<?php echo url_for('user/user_feed?uid='.$SonComment->getUser()->getId())?>">
              <img width="32" height="32" src="<?php echo thumb_url($SonComment->getUser()->getAvatar(), 32, 32)?>" class="popup-tip" title="<?php echo $SonComment->getUser()->getNickname()?>">
          </a>
      </div>
      <div class="reply-bd">
        <div class="reply-quote">
            <span class="username <?php echo $sf_user->getAttribute('user_id') == ((string)$SonComment->getUser()->getId()) ? 'yourself' : '' ?>">
                <a href=""><?php echo $SonComment->getUser()->getNickname()?></a>
            </span>
            <?php echo $SonComment->getText()?>
        </div>
        <div class="reply-time"><?php echo $SonComment->getCreatedAt()->format("Y-m-d H:i:s") ?></div>
        <?php if($SonComment->getUser()->getId()==$sf_user->getAttribute("user_id")):?>
          <div class="act-del"><a href="javascript:delComment('<?php echo $SonComment->getId();?>')" class="popup-tip" title="删除">x</a></div>
        <?php endif;?>
      </div>
    </li>
    <?php endforeach;?>
    <?php endif;?>
  </ul>
  </div>
  <div class="reply-form" style="display:none">
    <div class="avatar">
        <?php if ($sf_user->isAuthenticated()): ?>
        <a href="<?php echo url_for('user/user_feed?uid='.$sf_user->getAttribute("user_id"))?>">
            <img width="32" height="32" alt="" src="<?php echo thumb_url($sf_user->getAttribute('avatar'), 32, 32)?>" class="popup-tip" title="<?php echo $sf_user->getAttribute('nickname'); ?>">
        </a>
        <?php else:?>
            <img width="32" height="32" src="<?php echo thumb_url('1313572883180.png', 32, 32)?>" />
        <?php endif;?>
    </div>
    <form onsubmit="submitReply(this); return false">
      <input type="text" style="width:330px;" name="comment" maxlength="140">
      <input type="hidden" name="pid" value="<?php echo $comment->getId()?>"/>
      <input type="hidden" name="reply" value="<?php echo $page.'-'.$i?>"/>
      <input type="submit" value="回应">
    </form>
  </div>
</li>
<?php endforeach?>
