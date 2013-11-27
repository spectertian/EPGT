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
//
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
    $("#media_list").load("<?php echo url_for('media/category_files?rand='.rand()) ?>", {"category_id": id}, function() {
        //tb_init('a.thickbox, area.thickbox, input.thickbox');
    });
};
    swfu = new SWFUpload(settings);
});

</script>

<div style="float:left;margin-left:10px;">
    <fieldset>
        <legend>文件上传</legend>
        <form>
            <table cellspacing="0" cellpadding="4" border="0" align="left">
                <tr align="left">
                    <td>
                        <label for="user-upload" >文件分类</label>
                        <select name="category_id" id="category_id">
                        <?php foreach($categorys as $id => $category_name): ?>
                            <option value="<?php echo $id ?>"><?php echo $category_name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <div class="flashUploadWidget" style="float:left;">
                            <label for="user-upload" style="display:none;">文件上传：</label>
                            <span class="fileUpload">
                                <span id="upload_image"></span>
                            </span>
                            <span class="textInfo">
                              <span class="msg width420">暂无文件限制</span>
                            </span>
                            <div class="clear"></div>

                            <div id="uploading" style="display:none;">
                                <span>
                                    文件正在上传中......<span>0%</span>
                                </span>
                            </div>
                            <div class="clear"></div>
                            <div id="filecount" style="display:none;">
                                <span>
                                    共<span>0</span>个文件 ||
                                </span>
                                <span>
                                    上传成功<span>0</span>个文件
                                </span>
                            </div>
                            <div class="clear"></div>
                            <div id="fsUploadProgress"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
</div>