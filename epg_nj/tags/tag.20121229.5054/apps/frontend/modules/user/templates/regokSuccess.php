<script type="text/javascript">
    document.title='注册成功';
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
        <h2>注册成功</h2>  
        您的欢网ID是：<font color="#ff0000"><?php echo $huanid?></font>(该ID可用于登录),请牢记！<br />
        <p>
        <a href="<?php echo url_for('user/user_feed')?>">点此进入用户中心</a><br />
        </p>
        <div class="tips-field">
          <p>还没有帐号？<a href="<?php echo url_for('user/reg') ?>">立即注册</a></p>
        </div>
      </div>
    </div>
  </div>
</div>