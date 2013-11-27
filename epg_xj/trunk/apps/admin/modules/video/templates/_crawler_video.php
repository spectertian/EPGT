<!DOCTYPE HTML>
<html>
<head>
<title>Mozitek Internal System</title>
<link href="<?php echo stylesheet_path('internal.css')?>" rel="stylesheet" type="text/css" media="all">
<script type="text/javascript" src="<?php echo javascript_path ('jquery-1.4.2.min.js')?>"></script>
<script type="text/javascript">
var submitCrawler  = function(form){
    if (form.crawler.value == '完成') {
        window.top.location.reload();
        return false;
    }
    
    var url = $.trim(form.url.value);
    if (url.length == 0) {
        alert('请输入需要采集的视频地址..');
        return false;
    }
    
    $.ajax({
        url: '<?php echo url_for('video/crawler')?>',
        type: 'get',
        dataType: 'json',
        data: {url: url, id: form.id.value },
        beforeSend: function() {
            form.crawler.value = "后台采集中.可执行其他操作";
            form.crawler.disabled = true;
        },
        success: function(m) {
            if (m == 0) {
                alert('采集视频地址错误！');
                window.top.location.reload();
            } else {
                form.crawler.value = "完成";
                form.crawler.disabled = false;                
            }
        },
        error: function() {
            if(argument[1] == 'timeout')
            {
                form.crawler.value = "完成";
                form.crawler.disabled = false;  
            }
            else                          
            	alert('网络错误，请稍候再试');
            window.top.location.reload();
        }
    });
    return false;
}
</script>
</head>
<body>
  <div id="file-wrap">
    <div id="file-content">
        <div class="content_inner">
          <div class="widget">
            <h3>文件上传</h3>
            <div class="widget-body">
              <form onSubmit="return submitCrawler(this)">
              <p><label>视频列表地址：</label> 
                  <input type="text" style="width:400px" name="url" />
                  <input type="hidden" name="id" value="<?php echo $wiki->getId()?>" />
                  <input name="crawler" type="submit" value="采集" />
              </p>
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
</body>
</html>