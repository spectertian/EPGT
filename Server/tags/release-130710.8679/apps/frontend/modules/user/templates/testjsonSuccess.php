<div class="container">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <div class="common-form">
        <form action="<?php echo url_for('user/Testjson') ?>" method="post" name="form1" id="formjson">
        <h2>输入json</h2>  
        <p><textarea name="json" cols="60" rows="5">{"action":"UserLoginTC","device":{"devinfo":"123"},"user":{"huanid":"123","pwd":"123","loginstatus":1}}</textarea></p>
        <p><br><input type="submit" name="button" id="button" value="测试" /></p>
        </form>
      </div>
    </div>
  </div>
</div>