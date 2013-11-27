<!--
<div id="content">
    <div class="module login">
        <center>
        <div><?php echo $sf_user->getAttribute("username"); ?> 修改密码</div>
         <?php if ($sf_user->hasFlash('error')): ?>
                  <div class="msg-error"><?php echo $sf_user->getFlash('error') ?></div>
          <?php endif; ?>
          <?php if ($sf_user->hasFlash('success')): ?>
                  <div class="msg-error"><?php echo $sf_user->getFlash('success') ?></div>
          <?php endif; ?>
        <form name="form1" method="post" action="<?php echo url_for('user/UpdatePassword') ?>">
          <ul>
              <li>
                  <label for="oldpassword">旧密码:</label>
                  <input type="text" name="oldpassword" id="oldpassword" />
                  <div class="clear"></div>
              </li>
            <li>
              <label for="newpassword">密&nbsp;&nbsp;&nbsp;码：</label>
              <input type="text" name="newpassword" id="newpassword" />
              <div class="clear"></div>
            </li>
            <li>
              <label for="renewpassword">密&nbsp;&nbsp;&nbsp;码：</label>
              <input type="text" name="renewpassword" id="renewpassword" />
              <div class="clear"></div>
            </li>
            <li>
              <input type="submit" name="" id="submit" value="修改密码">
              <div class="clear"></div>
            </li>
          </ul>
          <div class="clear"></div>
        </form>
        </center>
    </div>
</div>
-->
<div id="content">
    <form name="form1" method="post" action="<?php echo url_for('user/update') ?>" enctype="multipart/form-data">
        <input type="file" name="avatar" id="avatar" />
        <input type="submit" name="submit" id="submit" value="update" />
    </form>
</div>