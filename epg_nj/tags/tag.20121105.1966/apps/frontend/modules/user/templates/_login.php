<?php if($sf_user->isAuthenticated()): ?>
<div class="module login" >
    <div class="mini-dash">
      <div class="uesr-info">
        <div class="avatar"><img src="<?php echo image_path('icon_128.jpg') ?>" width="64" height="64" alt="" /></div>
        <div class="username"><a href="#"><?php echo $sf_user->getAttribute("username"); ?></a></div>
        <div class="clear"></div>
      </div>
      <div class="user-utilities-meta"> <span class="message"><a href="#">短消息（1）</a></span> | <span class="set"><a href="<?php echo url_for('user/dashboard')?>">设置</a></span> | <span class="logout"><a href="<?php echo url_for('user/logout') ?>">登出</a></span> </div>
    </div>
</div>
<?php else: ?>
<div class="module login">
    <form name="form1" method="post" action="<?php echo url_for('user/login') ?>">
      <ul>
        <li>
          <label for="uid">用户名：</label>
          <input type="text" name="username" id="uid">
          <div class="clear"></div>
        </li>
        <li>
          <label for="pwd">密&nbsp;&nbsp;&nbsp;码：</label>
          <input type="password" name="password" id="pwd">
          <div class="clear"></div>
        </li>
        <li>
          <input type="submit" name="" id="submit" value="登 录">
          <label for="checkbox" class="remember-pwd">
            <input type="checkbox" name="remember" id="checkbox">记住我</label>
          <div class="clear"></div>
        </li>
      </ul>
      <div class="clear"></div>
    </form>
</div>
<?php endif; ?>


