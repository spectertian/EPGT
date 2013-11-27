<script src="<?php echo javascript_path("city.js"); ?>" type="text/javascript"></script>
<script type="text/javascript">
document.title = "用户注册";
$(document).ready(function(){
    var validators = {
        'globals' : {
            'errorClass' : 'failure',
            'noticeClass': 'transfer',
            'successClass': 'success',
            'inputErrorClass' : 'error',
            'tipClass':'status',
            'name' : /user\[([\d\w\-\_]+)\]/ ,
            'searchTip':'正在检测中...'
        },
        'email' : {
            reg: /^[0-9a-zA-Z][_.0-9a-zA-Z-]{0,31}@([0-9a-zA-Z][0-9a-zA-Z-]{0,30}\.){1,4}[a-zA-Z]{2,4}$/,
            flag: false,
            notice: ' 用来接收到激活邮件才能完成注册，找回密码',
            errors: {
                'required' : '请输入邮箱',
                'invalid': ' 该邮箱已经注册过',
                'format' : ' 邮箱格式错误'
            },
            url:'<?php echo url_for('user/check_value') ?>',
            success: ''
        },
        'password': {
            reg: /^[0-9a-zA-Z><,\[\]\{\}\?\/\+=\\'\\\":;\~\!\@\#\*\$\%\^\&\(\)\-\—\.`\|]{4,20}$/,
            flag: false,
            notice: ' 6-20个字符，请使用字母加数字或符号的组合密码，不能单独使用字母、数字或符号',
            errors: {
                'required': '密码不能为空',
                'invalid' : '请使用英文字母、符号或数字',
                'format' : '密码格式错误'
            },
            success: ''
        }
//        're_password':{
//            flag: false,
//            notice: '请再次输入密码',
//            errors: {
//                'invalid' : '登录密码与再次输入密码不一致',
//                'required' : '请再次输入密码'
//            },
//            success: ''
//        }
    };

    var globals = validators.globals;
    $("#regForm").find("INPUT[type=text],INPUT[type=password]").each(function(i,e){
        var type = $(e).attr("type");
        var name = null;
        var rule = null;
        var msg = null;
        var successTip = null;

        $(e).focus(function(){
            name = $(this).removeClass().attr('name').match(globals.name)[1];
            rule = validators[name];
            msg = $(this).parents("LI").find("P");
            msg.removeClass().addClass(globals.tipClass).html(rule.notice);
        }).blur(function(){
            var value = $(this).val();           
            if( value.length != 0 ) {  
//                if(name == 're_password') {  
//                    var password = $.trim($(this).parents("#regForm").find("#user_password").val());
//                    if( password != $.trim($(this).val()) ) {
//                        rule.flag = false;
//                        $(this).addClass(globals.inputErrorClass);
//                        msg.addClass(globals.errorClass).html(rule.errors.invalid);
//                    }else{
//                        rule.flag = true;
//                        msg.addClass(globals.successClass).html('');
//                    }
//                }else{
                    if(rule.reg.exec(value)){
                        if(name == 'password'){
                            rule.flag = true;
                            msg.addClass(globals.successClass).html('');
                        }else{
                            msg.addClass(globals.noticeClass).html(globals.searchTip);
                            var data = {'field':name,'value':value};
                            $.post(rule.url, data, function(data){
                                data = eval(data.user);
                                if(data){
                                    rule.flag = true;
                                    msg.removeClass(globals.searchTip).addClass(globals.successClass).html('');
                                }else{
                                    rule.flag = false;
                                    $(this).addClass(globals.inputErrorClass);
                                    msg.removeClass(globals.searchTip).addClass(globals.errorClass).html(rule.errors.invalid);
                                }
                            },'json');
                        }
                    }else{
                        rule.flag = false;
                        $(this).addClass(globals.inputErrorClass);
                        msg.addClass(globals.errorClass).html(rule.errors.format);
                    }
               // }
            }else{
                rule.flag = false;
                $(this).addClass(globals.inputErrorClass).parents("LI").find("SPAN").addClass(globals.errorClass).html(rule.errors.required);
            }
        });
    });

    $("#regForm").submit(function(){
        var i;
        var assent = $('.user-agreement > INPUT[type=checkbox]').attr('checked');
        for( i in validators ) {
            if(i == 'globals'){
                continue;
            }
            if(!validators[i].flag){
                //alert(i);
                return false;
            }
        }
        if(!assent){
            return false;
        }
        return true;
    });
});

var onecount=532;
function changelocation(locationid) {
    document.regForm.city.length = 0;
    var locationid=locationid;
    var i;
    for (i=0;i < onecount; i++)
    {
        if (subcat[i][2] == locationid)
        { 
            document.regForm.city.options[document.regForm.city.length] = new Option(subcat[i][0],subcat[i][1]);
        }    
    }
}
</script>
<div class="container">
  <div class="container-inner">
    <div class="main-bd">
      <div class="common-form">
      <form action="<?php echo url_for('user/reg') ?>" id="regForm" method="post" name="regForm">
        <h2>快速注册</h2>
          <?php echo $form->renderHiddenFields() ?>
          <ul>
            <li class="text-field clearfix">
              <div class="input">
                <label for="user-email">邮箱</label>
                <?php echo $form['email']->render(); ?> 
              </div>
                <div class="extra-tips">
                <p class="status <?php if($form['email']->hasError()): ?> failure <?php endif ?> "><?php echo $form['email']->getError() ?></p>
                </div>
            </li>
            <li class="text-field clearfix">
              <div class="input">
                <label for="user-password">密码</label>
                <?php echo $form['password']->render(); ?>
              </div>
                <div class="extra-tips">
              <P class="status <?php if($form['password']->hasError()): ?> failure <?php endif ?>"> <?php echo $form['password']->getError() ?></p>
                </div>
             </li>
            <li class="text-field clearfix">
                <div type="input">
                    <label for="user-password">昵称</label>
                    <?php echo $form['nickname']->render();?>
                </div>
                <div class="extra-tips">
                <P class="status <?php if($form['nickname']->hasError()): ?> failure <?php endif ?>"> <?php echo $form['nickname']->getError() ?></p>
                </div>
            </li>
            <li class="text-field clearfix">
                <div class="input">
                  <label for="province">所在城市</label>
                 <select tabindex="5" name="province" id="province" onchange="changelocation(this.options[this.selectedIndex].value)">
                <option value="" selected>--请选择省份--</option>
                <option value='67'>北京市</option>
                <option value='68'>天津市</option>
                <option value='69'>上海市</option>                                        
                <option value='70'>重庆市</option>
                <option value='37'>湖北</option>
                <option value='38'>广东</option>
                <option value='39'>江西</option>
                <option value='40'>安徽</option>
                <option value='41'>福建</option>
                <option value='42'>广西</option>
                <option value='43'>云南</option>
                <option value='44'>四川</option>
                <option value='45'>贵州</option>
                <option value='46'>湖南</option>
                <option value='47'>浙江</option>
                <option value='48'>江苏</option>
                <option value='49'>河南</option>
                <option value='50'>河北</option>
                <option value='51'>山东</option>
                <option value='52'>山西</option>
                <option value='53'>陕西</option>
                <option value='54'>甘肃</option>
                <option value='55'>青海</option>
                <option value='56'>宁夏</option>
                <option value='57'>内蒙古自治区</option>
                <option value='58'>辽宁</option>
                <option value='59'>吉林</option>
                <option value='60'>黑龙江</option>
                <option value='61'>新疆自治区</option>
                <option value='62'>西藏自治区</option>
                <option value='63'>海南</option>
                <option value='64'>澳门</option>
                <option value='65'>香港</option>
                <option value='66'>台湾</option>
              </select>
              <select tabindex="6" name="city" id="city">
                <option value=''>选择城市</option>
              </select>
                </div>
            </li>
            <li class="checkbox-field clearfix">   
              <label for="CheckBoxTerms" class="user-agreement">
              <input type="checkbox" tabindex="5" name="user-agreement" id="CheckBoxTerms" />
              我已经认真阅读并同意我爱电视的《<a href="<?php echo url_for('news/agreement');?>" target="_blank">使用协议</a>》。</label>
            </li>
            <li class="submit-field clearfix">
              <input type="submit" tabindex="6" name="" id="ButtonAcceptTerms" value="注册" title="阅读并同意我爱电视的《使用协议》方可注册。">
            </li>
          </ul>
        </form>
        <dl class="other-account clearfix">
          <dt>支持第三方帐号登录：</dt>
          <dd class="account-sina"><a href="<?php echo $Sina;?>">用微博帐号登录</a></dd>
          <dd class="account-qq"><a href="<?php echo $Qqt;?>">用QQ帐号登录</a></dd>
        </dl>
        <div class="tips-field">
          <p>已经拥有帐号？<a href="<?php echo url_for('user/login') ?>">立即登录</a></p>
        </div>
      </div>
    </div>
  </div>
</div>