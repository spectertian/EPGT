<script type="text/javascript">
var swfu;


$(document).ready(function(){
     var select = $('#category_id');

     var settings = {
        flash_url : "<?php echo javascript_path('swfupload/swfupload.swf') ?>",
        upload_url: "<?php echo url_for('media/Uploader') ?>",
        post_params: {
                        "5itv_sess" : $.cookie('5itv_sess'),
                        'category_id' : $('#category_id option:selected').val()
                     },
        prevent_swf_caching: false,
        file_size_limit : "10 MB",
        file_types : "*.jpg;*.png;*.gif;*.rar;*.bmp;",
        file_types_description : "图像文件,压缩文件",
        file_upload_limit : -1, //30
        file_queue_limit : -1, //30
        debug: false,
       
        // Button settings
        button_image_url: "<?php echo image_path('button_photo_upload.png') ?>",
        button_width: "129",
        button_height: "25",
        button_placeholder_id: "upload_image",
        button_cursor: SWFUpload.CURSOR.HAND,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,

//        // The event handler functions are defined in handlers.js
        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        upload_start_handler : uploadStart,
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,
        queue_complete_handler : queueComplete   // Queue plugin event
    };

function queueComplete(numFilesUploaded)
{
    var upload_number = parseInt($('#filecount').find('SPAN:first > SPAN').text());
    var complete_number = parseInt($("#filecount").find('SPAN:eq(2) > span').text());

    if(upload_number > 0 || complete_number > 0)
    {
        $('#filecount').find('SPAN:first > SPAN').text(0);
        $("#filecount").find('SPAN:eq(2) > span').text(0);
    }

    $('#uploading').hide();
    $('#filecount').hide();
//    window.location.reload(true);
    id = select.val();
    <?php if(!isset ($show)):?>
        $("#media_list").load("<?php echo url_for('media/category_files') ?>", {"category_id": id, "popup": 0});
    <?php else:?>
        $("#media_list").load("<?php echo url_for('media/category_files') ?>", {"category_id": id, "popup": 1});
    <?php endif;?>
};

    swfu = new SWFUpload(settings);

});

function oneFileInsert(parents)
{
    var key = $('#file_info').find('span:eq(1)').text();
    var link = $('#file_info').find('span:eq(0)').text();
     if( key == 0 || link == 0)
    {
        alert('请选择文件！');
        return false;
    }else{
        function_name = '<?php echo $sf_request->getParameter('function_name',false) ?>';
		
        if(function_name)
        {
            parents.eval(function_name)(key,link);
        }else{
            parents.fileChange(key,link);
        }
    }
}


function insert_file(parents)
{
    mark = "<?php echo $sf_request->getParameter('mark',false) ?>";
    which_recommend = '<?php echo $sf_request->getParameter('num',false) ?>';
    var key = $('#file_info').find('span:eq(1)').text();
    var link = $('#file_info').find('span:eq(0)').text();
    var files = { 'file_info' : []};
    $("#show_file_info[rel=selected]").each(function(x){
        key = $(this).find('span:eq(1)').text();
        link = $(this).find('span:eq(0)').text();
        files.file_info[x] = { 'key' : key ,'link': link ,'mark': mark,'which_recommend':which_recommend} ;
    });
   var files_json = eval(files.file_info);
    if( files_json.length == 0)
    {
        alert('请选择文件！');
        return false;
    }else{
        function_name = '<?php echo $sf_request->getParameter('function_name',false) ?>';
        if(function_name)
        {
            parents.eval(function_name)(files_json);
        }else{
            parents.fileChange(files_json);
        }
    }
}

function CollectionPic(){
    var url = $.trim($("input[name=collectionPic]").val());
    if(url.length == 0){
        alert("请输入图片地址");
        $("input[name=collectionPic]").focus();
        return;
    }
    var category_id = $("#category_id").val();
    $.ajax({
        type: "POST",
        url: "<?php echo url_for('media/CollectionPic') ?>",
        data: "url="+ url + "&category_id="+category_id,
        beforeSend: function(){
            $("input[value=采集图片]").val("正在采集图片...").attr("disabled","disabled");
        },
        success: function(data){
            $("input[value=正在采集图片...]").val("采集图片").attr("disabled","");
            alert("采集成功");
            location.reload();
        },
        error: function() {
            alert("采集失败");
        }


    })
}

</script>
  <div class="widget">
    <h3>文件上传</h3>
    <div class="widget-body">
      <p><label>文件分类：</label>
      <select name="category_id" id="category_id">
        <?php foreach($categorys as $id => $category_name): ?>
            <option value="<?php echo $id ?>"><?php echo $category_name ?></option>
        <?php endforeach; ?>
      </select> &nbsp;
     <label for="user-upload" style="display:none;">文件上传：</label>
                            <span class="fileUpload">
                                <span id="upload_image"></span>
                            </span> &nbsp;
      <span class="tip">暂无文件限制</span></p>
      <br>
      <p><label>采集图片地址：</label> <input type="text" name="collectionPic" size="100"><input onclick="CollectionPic()" type="submit" value="采集图片"></p>
      
      <div class="progress">
        <span id="uploading" style="display:none;">
            <span>文件正在上传中......<span>0%</span></span>
        </span>
        <span id="filecount" style="display:none;"><span>共<span>0</span>个文件 ||</span><span>上传成功<span>0</span>个文件</span></span>
        <div id="fsUploadProgress">
          <ul>
<!--            <li><p class="alert">pic1a.JPG上传失败，返回数据错误！</p></li>-->
          </ul> 
        </div>
      </div>
    </div>
  </div>