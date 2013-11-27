<script type="text/javascript">
document.title = "用户找回密码";
$(document).ready(function(){

}
</script>
<div class="container">
  <div class="container-inner">
    <div class="main-bd">
      <div class="common-form">
        <h2>找回密码</h2>
        <?php if($sf_user->hasFlash('success')): ?>
        <div class="notice done"><span class="textInfo"><?php echo $sf_user->getFlash('success') ?></span></div>
        <?php endif ?>
        <form id="fpsw-form" name="fpsw-form" method="POST" action="">
          <ul>
            <li class="text-field clearfix">
              <div class="input">
                <label for="user-email">邮箱</label>
                <input type="text" tabindex="1" name="useremail" id="useremail" value="">
              </div>
              <div class="extra-tips option-tip">
                <p class="validate-option">输入您注册的Email找回密码</p>
                <p class="validate-error">Email不能为空 / Email格式不正确 / 该邮箱已经注册过</p>
              </div>
            </li>
            <li class="submit-field">
              <input type="submit" tabindex="2" name="" value="找回密码">
            </li>
          </ul>
        </form>
        <div class="tips-field">
          <p><a href="<?php echo url_for('user/login') ?>">返回登录页面</a></p>
        </div>
      </div>
    </div>
  </div>
</div>