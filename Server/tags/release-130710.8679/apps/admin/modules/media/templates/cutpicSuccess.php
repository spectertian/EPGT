<?php use_javascript('jquery.imgareaselect.pack.js') ?>
<?php use_stylesheet('imgareaselect-default.css')?>
<?php
list($srcW, $srcH, $type, $attr) = GetImageSize($url);
?>
<script type="text/javascript">
function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;
    var scaleX = 200 / selection.width;
    var scaleY = 300 / selection.height;

    $('#preview img').css({
        width: Math.round(scaleX * 400),
        height: Math.round(scaleY * 600),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    });

    $('#x1').val(selection.x1);
    $('#y1').val(selection.y1);
    $('#w').val(selection.width);
    $('#h').val(selection.height); 

}

$(function () {
    $('#photo').imgAreaSelect({ aspectRatio: '2:3', handles: true,onSelectChange: preview });
});



function savePic(){
    $("#message-box").empty();
    //$("#watch").empty();
    $("#back").empty();
    x1 = $('#x1').val();
    y1 = $('#y1').val();
    width = $('#w').val();
    height = $('#h').val();
    category_id = <?php echo $category_id?>;
    url = "<?php echo $url?>";
    $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?php echo url_for('media/SaveCutPic') ?>",
        data: 'url=' + url + '&category_id='+ category_id + '&width=' + width + '&height=' + height + "&x1="+ x1 + "&y1=" + y1,
        beforeSend: function(){
               $("input[value=保存]").val("正在保存裁剪图片...").attr("disabled","disabled");
        },
        success: function(data){
                $("input[value=正在保存裁剪图片...]").val("保存").attr("disabled","");
                back = "<a href=\"#\" onclick=\"javascript:window.location.href = document.referrer;\">返回<\/a><\/p>";
                $("#back").append(back);
        },

        error: function() {
             html = "<div class=\"status error\"><p>保存失败<\/p></div>";
            $("#message-box").append(html);
        }


    })
}


</script>
<input type="hidden" name="x1" id="x1">
<input type="hidden" id="w" name="w">
<input type="hidden" name="y1" id="y1">
<input type="hidden" name="h" id="h">
<div id="file-wrap">
    <header>
       <div class="default" id="message-box"></div>
      <h2 class="content">文件管理</h2>
      <nav class="utility">
      </nav>
    </header>
    <div>
      <div id="file-content">
        <div class="content_inner">
          <div class="widget">
            <h3>封面裁切</h3>
            <div class="widget-body">
              <div style="float:left; width:35%;">
                <div class="frame">
                  <img src="<?php echo $url?>" id="photo" width="200" height="300">
                </div>
              </div>

                <div style="float:left; width:65%;">
                <h4>预览</h4>
                    <div class="frame" style="margin: 0 1em; width: 400px; height: 600px;">
                      <div id="preview" style="width: 400px; height: 600px; overflow: hidden;">
                        <img src="<?php echo $url?>" style="width: 400px; height: 600px;" />
                      </div>
                    </div>
                        <input name="" type="submit" value="保存" onclick="savePic()">
                        <span id="watch"></span>
                        <span id="back"></span>
                </div>
              <div class="clear"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>