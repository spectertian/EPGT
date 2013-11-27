<div class="container settings">
  <div class="container-inner">
    <div class="main-hd">
      <h1><a href="#"><img width="48" height="48" src="<?php echo thumb_url($sf_user->getAttribute("avatar"), 48, 48)?>" alt="<?php echo $sf_user->getAttribute("username"); ?>"></a><?php echo $sf_user->getAttribute("username"); ?> 的设置</h1>
      <div class="settings-menu">
        <ul>
          <li><a href="<?php echo url_for('user/updateInfo') ?>">帐号信息</a></li>
          <li><a href="<?php echo url_for('user/updatePassword') ?>">修改密码</a></li>
          <li><a href="<?php echo url_for('user/share') ?>">分享设置</a></li>
        </ul>
      </div>
    </div>
    <div class="main-bd">
      <div class="notifications">
        <form method="post" action="">
          <p class="settings-tip">我们会通过Email提醒与你有关的事情，你可以选择只接受哪些类别，你的邮箱地址：shiney.wu@gmail.com（<a href="#">更改</a>）。</p>
          <fieldset>
            <legend>消息</legend>
            <div class="clearfix">
              <label>Email通知我</label>
              <div class="input">
                <ul class="options">
                  <li>
                    <input type="checkbox" value="1" name="user[send_new_direct_text_email]" id="user_send_new_direct_text_email" checked="checked">
                    <label for="user_send_new_direct_text_email"> I'm sent a direct message </label>
                  </li>
                  <li>
                    <input type="checkbox" value="2" name="user[send_mention_email]" id="user_send_mention_email" checked="checked">
                    <label for="user_send_mention_email"> I'm sent a reply or mentioned </label>
                  </li>
                </ul>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>动态</legend>
            <div class="clearfix">
              <label>Email通知我</label>
              <div class="input">
                <ul class="options">
                  <li>
                    <input type="checkbox" value="1" name="user[send_new_friend_email]" id="user_send_new_friend_email" checked="checked">
                    <label for="user_send_new_friend_email"> I'm followed by someone new </label>
                  </li>
                  <li>
                    <input type="checkbox" value="2" name="user[send_favorited_email]" id="user_send_favorited_email" checked="checked">
                    <label for="user_send_favorited_email"> My Sets are marked as favorites </label>
                  </li>
                  <li>
                    <input type="checkbox" value="2" name="user[send_retweeted_email]" id="user_send_retweeted_email" checked="checked">
                    <label for="user_send_retweeted_email"> My Sets are retweeted </label>
                  </li>
                </ul>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>更新</legend>
            <div class="clearfix">
              <label>Email通知我</label>
              <div class="input">
                <ul class="options">
                  <li>
                    <input type="checkbox" value="1" name="user[send_email_newsletter]" id="user_send_email_newsletter" checked="checked">
                    <label for="user_send_email_newsletter"> Occasional updates about new products, features, and tips </label>
                  </li>
                  <li>
                    <input type="checkbox" value="1" name="user[send_account_updates_email]" id="user_send_account_updates_email" checked="checked">
                    <label for="user_send_account_updates_email"> Product or service updates related to my account </label>
                  </li>
                </ul>
              </div>
            </div>
          </fieldset>
          <div class="actions">
            <input type="submit" value="确认" name="commit">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>