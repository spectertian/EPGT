<script src="<?php echo javascript_path("city.js"); ?>" type="text/javascript"></script>
<div class="container settings">
  <div class="container-inner">
<?php include_partial('setmenu')?>
    <div class="main-bd">
      <div class="common-form">
        <form id="regform" name="regform" method="post" action="" enctype="multipart/form-data">
          <ul>
            <li class="text-field clearfix">
               <?php if($type==0 || $type==NULL):?>
              <div class="input">
                <label for="user-name">登录邮箱</label>
                <input type="text" tabindex="1" name="user-email" id="user-email" value="<?php echo $email;?>" readonly="readonly">
              </div>
              <div class="extra-tips">
                <p>注册成功不能修改</p>
                <p class="validate-option">中、英文均可，最长14个英文或7个汉字</p>
                <p class="validate-error">用户名不能为空 / 用户名长度不能超过14个英文或7个汉字 / 该用户名已被注册</p>
              </div>
                <?php else:?>
                您是分享用户！
                <?php endif;?>
            </li>
            <li class="text-field clearfix">
              <div class="input">
                <label for="nick-name">昵称</label>
                <input type="text" tabindex="2" name="nickname" id="nick-name" value="<?php echo $nickname;?>">
              </div>
              <div class="extra-tips">
                <p class="validate-option">中、英文均可，最长14个英文或7个汉字</p>
                <p class="validate-error">昵称长度不能超过14个英文或7个汉字</p>
              </div>
            </li>
            <li class="file-field clearfix">
                <label for="profile_image_uploaded_data">显示头像</label>
                <div class="make-avatar clearfix" style="float:left; margin:5px;">
                    <div class="preimg-m">
                        <img src="<?php echo thumb_url($sf_user->getAttribute("avatar"), 96, 96)?>" alt="<?php echo $sf_user->getAttribute('nickname');?>">
                        <p>96*96px</p>
                    </div>
                   <div class="preimg-s">
                       <img src="<?php echo thumb_url($sf_user->getAttribute("avatar"), 48, 48)?>" alt="<?php echo $sf_user->getAttribute('nickname');?>">
                        <p>48*48px</p>
                   </div>
                <div style="float:left;"><a href="<?php echo url_for('user/update_avatar');?>">更新头像</a></div>

                </div>
            </li>
            <li class="select-field clearfix">
              <label for="province">所在城市</label>
              <select tabindex="5" name="province" id="province" onchange="changelocation(this.options[this.selectedIndex].value)">
                <option value="" selected>--请选择省份--</option>
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
                <option value='67'>北京市</option>
                <option value='68'>天津市</option>
                <option value='69'>上海</option>                                        
                <option value='70'>重庆市</option>
              </select>
              <select tabindex="6" name="city" id="city">
                <option value=''>选择城市</option>
              </select>
              <input type="hidden" name="user_province" value="<?php echo $province ?>" />
              <input type="hidden" name="user_city" value="<?php echo $city ?>" />
            </li>
            <li class="textarea-field">
              <div class="input clearfix">
                <label for="user-email">个人简介</label>
                <textarea tabindex="7" name="desc"><?php echo $desc;?></textarea>
              </div>
            </li>
            <li class="submit-field">
              <input type="submit" tabindex="8" name="" value="确认">
            </li>
          </ul>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function cleanavatar(avatar) {
    if(avatar=="") {
        alert("您没有头像了");
        return false;
    }
    $.ajax({
     type: "POST",
     url: "<?php echo url_for('user/clean_avatar')?>",
     data:   '',
     success: function(m)
     { 
         if(m===1){
            alert( "您删除您的头像失败"); 
         }else{
            alert("您成功删除您的头像")
            window.location.reload();
         }
     } 
    }); 
}

var onecount=532;
function changelocation(locationid)
    {

    document.regform.city.length = 0;
    var locationid=locationid;
    var i;
   // document.frm.xl_name.options[0] = new Option('none','');
    for (i=0;i < onecount; i++)
    {
        if (subcat[i][2] == locationid)
        { 
            document.regform.city.options[document.regform.city.length] = new Option(subcat[i][0],subcat[i][1]);
        }    
    }
}
$("document").ready(function(){
	initSelect("province","user_province");
	var province = $("input[name=user_province]").val();
	if(province){
		
		changelocation(province);
	}
	initSelect("city","user_city");
});
function initSelect(objid,name){
	$("#"+objid).val($("input[name=" + name+"]").val());
}
</script>