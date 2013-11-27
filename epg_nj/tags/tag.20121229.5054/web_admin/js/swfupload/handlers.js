//文件选择框关闭时，对每个成功加入队列的文件都将触发此事件
function fileQueued(file)
{
//    var file_count = parseInt($('#filecount').find('SPAN:first > SPAN').text());
//    $('#filecount').find('SPAN:first > SPAN').text( file_count + 1 );
    $('#filecount').find('SPAN:first > SPAN').text(0);
    $('#uploading').show();
    $('#filecount').show();
}

//错误提示
function fileQueueError(file, errorCode, message) {
    try {
        if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
            //alert("上传文件过多，\n" + (message === 0 ? "文件数已到达最大限制" : "您可以上传" + (message > 1 ? message + " 个文件。" : "1个文件。")));
            alert("上传文件过多，\n" + (message == 0 ? "文件数已到达最大限制" : "您还可以上传" + message + " 个文件。" ));
            return;
        }

        switch (errorCode) {
        case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
            setError(file, "文件太大！");
            this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            setError(file, "不可以上传空白文件！");
            this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
            setError(file, "不允许上传的文件类型！");
            this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        default:
            
            if (file !== null) {
                setError(file, "未知错误！");
            }
            this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        }
    } catch (ex) {
        this.debug(ex);
    }
}

//文件选择框关闭时，此函数将提示文件选择数量【不论是否加入队列】与文件成功加入队列的数量
function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
//        $('input[name=submit_upload]').removeClass('button129').addClass('button129Dis');
//        $('input[name=submit_upload]').attr('disabled', true);
        $('#filecount').find('SPAN:first > SPAN').text( numFilesQueued );
        this.startUpload();
    } catch (ex)  {
        this.debug(ex);
    }
}

//文件发送到服务器之前最后一个上传前的验证
function uploadStart(file) {
    swfu.addPostParam('category_id',$('#category_id option:selected').val());
    try {
//        var complete_number = parseInt($('#progressbar_div span#complete_number').text());
//        $('#progressbar_div span#complete_number').html(complete_number + 1);
//        $('#progressbar_div span#uploading_file_name').html(file.name);

    }
    catch (ex) {}

    return true;
}
//上传过程中，返回文件，已上传字节，总字节
function uploadProgress(file, bytesLoaded, bytesTotal) {
    try {
        var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
        $('#uploading SPAN:first > SPAN').text(percent + '%');
    } catch (ex) {
        this.debug(ex);
    }
}
//文件发送到服务端之后返回HTTP200，即执行此函数
function uploadSuccess(file, serverData)
{
    try {
        file_info = eval("(" + serverData + ")");
        if (file_info['error']) {
            setError(file, file_info.message);
            return false;
        }
        //此处累加上传成功计数
        var success_file = parseInt($("#filecount").find('SPAN:eq(2) > span').text());
        $("#filecount").find('SPAN:eq(2) > span').text(success_file+1);
    } catch (ex) {
        setError(file, '上传失败，返回数据错误！');
        this.debug(ex);
    }
}

//无论什么时候，只要上传被终止或者没有成功完成，那么该事件都将被触发。
function uploadError(file, errorCode, message) {
    try {
        switch (errorCode) {
        case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
            setError(file, "上传错误: " + message);
            this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
            setError(file, "上传失败");
            this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.IO_ERROR:
            setError(file, "网络传输错误！");
            this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
            setError(file, "Security Error");
            this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
            setError(file, "Upload limit exceeded.");
            this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
            setError(file, "Failed Validation.  Upload skipped.");
            this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
            setError(file, "已取消");
            // progress.setCancelled();
            break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
            setError(file, "已停止");
            break;
        default:
            setError(file, "Unhandled Error: " + errorCode);
            this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        }
    } catch (ex) {
        this.debug(ex);
    }
}
//当上传队列中的一个文件完成了一个上传周期，
//无论是成功(uoloadSuccess触发)还是失败(uploadError触发)，
//此事件都会被触发，这也标志着一个文件的上传完成，可以进行下一个文件的上传了。
function uploadComplete(file) {
    if (this.getStats().files_queued === 0) {
//        $('input[name=submit_upload]').removeClass('button129Dis').addClass('button129');
//        $('input[name=submit_upload]').attr('disabled', false);
//        $('input[name=submit_upload]').css('display','block');
        swfu.setButtonDisabled(false);
    }
}

//function queueComplete(numFilesUploaded) {
//    var upload_number = parseInt($('#filecount').find('SPAN:first > SPAN').text());
//    var complete_number = parseInt($("#filecount").find('SPAN:eq(2) > span').text());
//
//    if(upload_number > 0 || complete_number > 0)
//    {
//        $('#filecount').find('SPAN:first > SPAN').text(0);
//        $("#filecount").find('SPAN:eq(2) > span').text(0);
//    }
//
//    $('#uploading').hide();
//    $('#filecount').hide();
//    window.location.reload(true);
//
//
//
//
//}

function setError(file, msg) {
    $('#fsUploadProgress ul').append('<li><p class="alert">'+ file.name + msg + '</p></li>');
}