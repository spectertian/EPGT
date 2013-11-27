      <aside id="aside">
        <div class="company-menu">
          <ul>
            <li><a href="<?php echo url_for('news/about');?>" <?php if(sfContext::getInstance()->getActionName()=="about") echo "class='active'";?>>关于我们</a></li>
            <li><a href="<?php echo url_for('news/disclaimer');?>" <?php if(sfContext::getInstance()->getActionName()=="disclaimer") echo "class='active'";?>>免责声明</a></li>
            <li><a href="<?php echo url_for('news/privacy');?>" <?php if(sfContext::getInstance()->getActionName()=="privacy") echo "class='active'";?>>隐私声明</a></li>
            <li><a href="<?php echo url_for('news/partner');?>" <?php if(sfContext::getInstance()->getActionName()=="partner") echo "class='active'";?>>合作伙伴</a></li>
            <li><a href="<?php echo url_for('news/employ');?>" <?php if(sfContext::getInstance()->getActionName()=="employ") echo "class='active'";?>>诚聘英才</a></li>
            <li><a href="<?php echo url_for('news/contact');?>" <?php if(sfContext::getInstance()->getActionName()=="contact") echo "class='active'";?>>联系我们</a></li>
            <li><a href="<?php echo url_for('news/help');?>" <?php if(sfContext::getInstance()->getActionName()=="help") echo "class='active'";?>>帮助中心</a></li>
            <li><a href="<?php echo url_for('news/agreement');?>" <?php if(sfContext::getInstance()->getActionName()=="agreement") echo "class='active'";?>>使用协议</a></li>
          </ul>
          <div class="backhome"><a href="/">返回首页</a></div>
        </div>
      </aside>