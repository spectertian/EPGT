<script type="text/javascript">
    document.title='用户登陆';
    $(document).ready(function(){
        $("#remember").click(function(){
            $(this).val($(this).attr("checked"));
        });
    });
</script>
<div class="container">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <div class="common-form">
        <h2>登录</h2>       
        <form action="<?php echo url_for('user/login') ?>" method="post" name="form1" id="from_url">
          <ul>
            <li class="text-field clearfix">
              <div class="input">
                <label for="uid">欢网ID</label>
                <input type="text" tabindex="1" name="username" id="uid" value="">
              </div>
            </li>
            <li class="text-field clearfix">
              <div class="input">
                <label for="pwd">密码</label>
                <input type="password" tabindex="2" name="password" id="pwd" value="">
              </div>
              <div class="f-password">
                <p><a href="<?php echo url_for('user/lostPassword') ?>">忘记密码了？</a></p>
              </div>
            </li>
            <li class="checkbox-field clearfix">
              <input type="checkbox" tabindex="3" name="checkbox" id="remember" value="">
              <label for="remember" class="remember">下次自动登陆</label>
            </li>
            <li class="submit-field">
              <input type="hidden" name="gourl" value="<?php echo $gourl;?>" />
              <input type="submit" tabindex="4" name="" value="登录">
            </li>
          </ul>
        </form>
        <dl class="other-account clearfix">
        <dt>支持第三方帐号登录：</dt>
        <dd class="account-sina"><a href="<?php echo $Sina;?>">用微博帐号登录</a></dd>
        <dd class="account-qq"><a href="<?php echo $Qqt;?>">用QQ帐号登录</a></dd>
        </dl>
        <div class="tips-field">
          <p>还没有帐号？<a href="<?php echo url_for('user/reg') ?>">立即注册</a></p>
        </div>
      </div>
    </div>
  </div>
</div>