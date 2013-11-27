<div class="container settings">
  <div class="container-inner">
<?php include_partial('setmenu')?>
    <div class="main-bd">
      <div class="common-form">
        <?php if($user->getType()==0 || $user->getType()==NULL):?>
        <form id="reg-form" name="reg-form" method="post" action="">
          <ul>
            <li class="text-field clearfix">
              <div class="input">
                <label for="password">旧密码</label>
                <input type="password" tabindex="1" name="oldpassword" id="password">
              </div>
              <div class="f-password">
                <p><a href="<?php echo url_for('user/lostPassword') ?>">忘记密码了？</a></p>
              </div>
              <!-- <div class="extra-tips error-tip">
                <p class="validate-option">字母、数字或符号，最短6个字符，区分大小写</p>
                <p class="validate-error">密码不能为空 / 密码长度不足6个字符 / 请使用英文字母、符号或数字</p>
              </div> -->
            </li>
            <li class="text-field clearfix">
              <div class="input">
                <label for="new-password">新密码</label>
                <input type="password" tabindex="2" name="newpassword" id="new-password">
              </div>
              <!-- <div class="extra-tips option-tip">
                <p class="validate-option">字母、数字或符号，最短6个字符，区分大小写</p>
                <p class="validate-error">密码不能为空 / 密码长度不足6个字符 / 请使用英文字母、符号或数字</p>
              </div> -->
            </li>
            <li class="text-field clearfix">
              <div class="input">
                <label for="verify-password">确认密码</label>
                <input type="password" tabindex="3" name="renewpassword" id="verify-password">
              </div>
              <!-- <div class="extra-tips error-tip">
                <p class="validate-option">请再次输入密码</p>
                <p class="validate-error">两次密码输入不一致，请重新输入</p>
              </div> -->
            </li>
            <li class="submit-field">
              <input type="submit" tabindex="4" name="" value="确认">
            </li>
          </ul>
        </form>
          <?php else:?>
          您是分享用户，无法操作此项!
          <?php endif;?>
      </div>
    </div>
  </div>
</div>