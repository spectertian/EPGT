<?php use_javascript('jquery.imgareaselect.pack.js') ?>
<?php use_stylesheet('imgareaselect-default.css')?>
<?php 
list($srcW, $srcH, $type, $attr) = GetImageSize('http://p.mozitek.com/5itv/web_static/28/053/9787/1313978705328.jpg');
//var_dump($srcW);
//var_dump($srcH);
//var_dump($type);
//var_dump($attr);
?>
<script type="text/javascript">
function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;
    var scaleX = 96 / selection.width;
    var scaleY = 96 / selection.height;

    $('#preview img').css({
        width: Math.round(scaleX * 160),
        height: Math.round(scaleY * 160),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    });

    $('#x1').val(selection.x1);
    $('#y1').val(selection.y1);
    $('#w').val(selection.width);
    $('#h').val(selection.height); 

}

function previewsmall(img, selection) {
   if (!selection.width || !selection.height)
        return;
    var scaleX = 48 / selection.width;
    var scaleY = 48 / selection.height;

    $('#preview img').css({
        width: Math.round(scaleX * 160),
        height: Math.round(scaleY * 160),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    });

    $('#x1').val(selection.x1);
    $('#y1').val(selection.y1);
    $('#w').val(selection.width);
    $('#h').val(selection.height); 
}

$(function () {
    $('#photo').imgAreaSelect({ aspectRatio: '1:1', handles: true,onSelectChange: preview });
});

function savePic(){
    $("#message-box").empty();
    //$("#watch").empty();
    $("#back").empty();
    x1 = $('#x1').val();
    y1 = $('#y1').val();
    width = $('#w').val();
    height = $('#h').val();
    url = "<?php echo thumb_url($user->getAvatar(), 160, 160)?>";
    $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?php echo url_for('user/update_avatar') ?>",
        data: 'url='+url+ '&pic=save'+'&width=' + width + '&height=' + height + "&x1="+ x1 + "&y1=" + y1,
        beforeSend: function(){
               $("input[value=保存头像设置]").val("正在保存裁剪图片...").attr("disabled","disabled");
        },
        success: function(data){
                $("input[value=正在保存裁剪图片...]").val("保存头像设置").attr("disabled","");
               // back = "<a href=\"#\" onclick=\"javascript:window.location.href = document.referrer;\">返回<\/a><\/p>";
               // $("#back").append(back);
                window.location.reload();
        },

        error: function() {
             html = "<div class=\"status error\"><p>保存失败<\/p></div>";
            $("#message-box").append(html);
        }


    })
}
</script>
<input type="hidden" name="x1" id="x1" />
<input type="hidden" id="w" name="w" />
<input type="hidden" name="y1" id="y1" />
<input type="hidden" name="h" id="h" />
<div class="container settings">
  <div class="container-inner">
      <?php include_partial('setmenu')?>
    <div class="main-bd">
      <div class="common-form">
        <form method="post" action="" enctype="multipart/form-data">
          <h4>第1步：从电脑中选择你喜欢的照片</h4>
          <div class="reupload-avatar">
            <input type="file" name="picfile" id="picfile" style="width:300px">
            <br>
            你可以上传不大于2M的JPG、JPEG、GIF、PNG或BMP文件。<br>
            <br>
            <input type="hidden" name="pic" value="upload" />
            <input name="icon_submit" type="submit" value="重新上传照片" style="width:108px;">
          </div>
        </form>
          <h4>第2步：框选小头像</h4>
          <div class="make-avatar clearfix">
            <div class="bigimg">
                <img src="<?php echo thumb_url($user->getAvatar(), 160, 160)?>" id="photo" width="160" height="160">
            </div>
            <div class="preimg-m" id="preview" style="float: left; margin-right: 10px; height: 96px; width: 96px; overflow: hidden;">
                <img src="<?php echo thumb_url($user->getAvatar(),160, 160)?>" style="width: 96px; height: 96px; border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden;">
                <p>96*96px</p>
            </div>
          </div>
          <div class="make-avatar-tip">随意拖拽或缩放大图中的虚线方格，预览的小图即为保存后的头像图标。</div>
          <div class="submit-button">
            <input name="choose_submit" type="submit" value="保存头像设置" style="margin-right:10px;" onclick="savePic()">
          </div>
          <div id="message-box"></div>
          <div id="back"></div>
      </div>
    </div>
  </div>
</div>