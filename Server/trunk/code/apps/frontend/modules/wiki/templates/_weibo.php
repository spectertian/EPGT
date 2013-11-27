<div class="feed-bd">
<ul id="comment-list">
    <?php if (0 < count($weiboData) && (!isset($weiboData['error_code']))) :?>
    <?php foreach($weiboData as $weibo) :?>
    <li>
      <div>
          <div class="avatar">
              <a href="<?php printf('http://weibo.com/%d', $weibo['user']['id'])?>">
                  <img width="48" height="48" alt="" src="<?php echo $weibo['user']['profile_image_url']?>" class="popup-tip" title="<?php echo $weibo['user']['name']?>">
              </a>
          </div>
          <div class="act-hd">
              <span class="username"><a href="<?php printf('http://weibo.com/%d', $weibo['user']['id'])?>" target="_blank"><?php echo $weibo['user']['name']?></a></span>
          </div>
          <div class="act-bd">
            <div class="act-quote"><?php echo $weibo['text']?></div>
            <div class="act-time"><?php echo date('Y-m-d H:i:s', strtotime($weibo['created_at']))?></div>
          </div>
      </div>
    </li>
    <?php endforeach?>
    <?php else: ?>
    <li>暂时没有数据..</li>
    <?php endif;?>
</ul>
</div>