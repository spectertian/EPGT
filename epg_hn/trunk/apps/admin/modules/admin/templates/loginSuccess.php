<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
<!--[if IE 7]>
<link href="<?php echo javascript_path('ie7.css');?>" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lte IE 6]>
<link href="<?php echo javascript_path('ie6.css');?>" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript">
    function setFocus() {
        document.login.username.select();
        document.login.username.focus();
    }
</script>
</head>
<body onload="javascript:setFocus()">
<div id="border-top" class="h_green">
    <div>
        <div><span class="title">管理系统</span></div>
    </div>
</div>

<div id="content-box">
    <div class="padding">
        <div id="element-box" class="login">
            <div class="t">
                <div class="t">
                    <div class="t"></div>
                </div>
            </div>
            <div class="m">
                <h1>管理员登录</h1>
                <?php if ($sf_user->hasFlash('error')): ?>
                <dl id="system-message">
                    <dd class="error message fade">
                        <ul>
                            <li style="text-align: center;"><?php echo $sf_user->getFlash('error');?></li>
                        </ul>
                    </dd>
                </dl>
                <?php endif; ?>
                <div id="section-box">
                    <div class="t">
                        <div class="t">
                            <div class="t"></div>
                        </div>
                    </div>
                    <div class="m">
                        <form action="<?php echo url_for('admin/login') ?>" method="post" name="login" id="form-login" style="clear: both;">
                            <p id="form-login-username">
                                <label for="modlgn_username">用户名</label>
                                <input name="username" id="modlgn_username" type="text" class="inputbox" size="15" />
                            </p>
                            <p id="form-login-password">
                                <label for="modlgn_passwd">密码</label>
                                <input name="password" id="modlgn_passwd" type="password" class="inputbox" size="15" />
                            </p>
                            <p id="form-login-username">
                                <label for="modlgn_username2">验证码</label>
                                <input name="validatorCode" id="modlgn_username1" type="text" class="inputbox" size="15" value="请输入验证码" onfocus="this.value='';" />
                            </p>
                            <p id="form-login-username">
                                <label for="modlgn_username3"></label>
                                <img id="siimage" align="left" alt="captcha" style="border: 0" src="<?php echo url_for('admin/captcha') .'?sid='.md5(time()) ?>" />
                                <a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="$('#siimage').attr('src','<?php echo url_for('admin/captcha') ?>?sid=' + Math.random());return false;">
                                    换一个验证码
                                </a>
                            </p>
                            <div class="button_holder">
                                <div class="button1">
                                    <div class="next">
                                        <a onclick="login.submit();">登录</a>
                                    </div>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <input type="submit" style="border: 0; padding: 0; margin: 0; width: 0px; height: 0px;" value="Login" />
                        </form>
                        <div class="clr"></div>
                    </div>
                    <div class="b">
                        <div class="b">
                            <div class="b"></div>
                        </div>
                    </div>
                </div>
                <!--
                    <p>Use a valid username and password to gain access to the Administrator Back-end.</p>
                    <p>
                        <a href="http://localhost:8888/joomla/">Return to site Home Page</a>
                    </p> -->
                <div id="lock"></div>
                <div class="clr"></div>
            </div>
            <div class="b">
                <div class="b">
                    <div class="b"></div>
                </div>
            </div>
        </div>
        <noscript> Warning! JavaScript must be enabled for proper operation of the Administrator Back-end </noscript>
        <div class="clr"></div>
    </div>
</div>
<div id="border-bottom"><div><div></div></div>
</div>
<div id="footer">
    <p class="copyright">
        <a href="http://www.mozitek.com" target="_blank">mozitek</a>
    </p>
</div>
</body>
</html>
