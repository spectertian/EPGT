<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="content">
<center>
    <?php if(!empty($wikicomments)){?>
    <div id="comments">
        <ul>
           <?php foreach ($wikicomments as $comment){?>
            <li><?php echo $comment['text']->getText() ?></li>
            <li><?php echo $comment['text']->getUserId() ?></li>
            <li id="comment_like_num_<?php echo $comment['text']->getUserId() ?>">
            <?php if(!empty ($comment['son'])){ ?>
                <ul id="parent_id_ul">
                    <?php foreach ($comment['son'] as $com){?>
                    <li id=""parent_id_li><?php echo $com->getText();?></li>
                    <?php }?>
                    
                </ul>             
            <?php }?>
            <?php }?>
            </li>
        </ul>
    </div>
    <?php }?>
    <hr />
    <div id="form1">
        <?php if ($sf_user->hasFlash('error')): ?>
                  <div class="msg-error"><?php echo $sf_user->getFlash('error') ?></div>
          <?php endif; ?>
          <?php if ($sf_user->hasFlash('success')): ?>
                  <div class="msg-error"><?php echo $sf_user->getFlash('success') ?></div>
          <?php endif; ?>
        <form action="<?php echo url_for('user/Comment') ?>" id="comment" method="post" name="form1">
            <input type="txt" name="text" size="20" />
            <input type="hidden" name="parent_id" value="" />
            <input type="submit" name="submit" value="留言"/>
            <ul>
            <?php if($share!=false){?>
                <?php foreach($share as $item){ ?>
                <li><?php $item->getStype() ?></li>
                <?php }?>
            <?php } ?>
            </ul>
        </form>
    </div>
</center>
</div>