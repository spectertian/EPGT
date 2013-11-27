    <div class="main-hd">
      <h1><img width="48" height="48" src="<?php echo thumb_url($sf_user->getAttribute("avatar"), 48, 48)?>" alt="<?php echo $sf_user->getAttribute("nickname"); ?>"><?php echo $sf_user->getAttribute("nickname"); ?> 的设置</h1>
      <div class="settings-menu">
        <ul>
          <li><a href="<?php echo url_for('user/updateInfo') ?>" <?php if(sfContext::getInstance()->getActionName()=="updateInfo") echo "class='active'";?>>帐号信息</a></li>
          <li><a href="<?php echo url_for('user/update_avatar');?>" <?php if(sfContext::getInstance()->getActionName()=="update_avatar") echo "class='active'";?>>更新头像</a></li>
          <li><a href="<?php echo url_for('user/updatePassword') ?>" <?php if(sfContext::getInstance()->getActionName()=="updatePassword") echo "class='active'";?>>修改密码</a></li>
          <li><a href="<?php echo url_for('user/share') ?>" <?php if(sfContext::getInstance()->getActionName()=="share") echo "class='active'";?>>分享设置</a></li>
        </ul>
      </div>
    </div>