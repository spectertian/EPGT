<!DOCTYPE HTML>
<html>
<head>
<title>Mozitek Internal System</title>
<link href="<?php echo stylesheet_path('internal_old.css')?>" rel="stylesheet" type="text/css" media="all">
<script type="text/javascript" src="<?php echo javascript_path ('jquery-1.4.2.min.js')?>"></script>
<script type="text/javascript">
var submitCrawler  = function(form){
    if (form.crawler.value == '完成') {
        window.top.location.reload();
        return false;
    }
    
    var url = $.trim(form.url.value);
    var title = $.trim(form.title.value);
    if (title.length == 0) {
        alert('请输入分期或分集名称..');
        return false;
    }       
    if (url.length == 0) {
        alert('请输入分期或分集视频地址..');
        return false;
    }
 
    $.ajax({
        url: '<?php echo url_for('video/AddVideo')?>',
        type: 'post',
        dataType: 'json',
        data: {url: url, title:title, id: form.id.value },
        beforeSend: function() {
            form.crawler.value = "进行中..";
            form.crawler.disabled = true;
        },
        success: function(m) {
            if (m == 0) {
                //alert('视频地址错误！');
                window.top.location.reload();
            } else {
                //alert('完成！');
                form.crawler.value = "完成";
                form.crawler.disabled = false;  
                window.top.location.reload();              
            }
        },
        error: function() {
            //alert('网络错误，请稍候再试');
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
            <h3>分期/分集视频地址</h3>
            <div class="widget-body">
              <form onSubmit="return submitCrawler(this)">
              <p>
              <label>分期/分集名称：</label> 
                  <input type="text" style="width:300px" name="title" value="<?php echo $title ?>"/>
              </p> <br />
              <p>
              <label>视频地址：</label> 
                  <input type="text" style="width:500px" name="url" />
                  <input type="hidden" name="id" value="<?php echo $wiki->getId()?>" />
                  <input name="crawler" type="submit" value="添加" />
              </p>
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
</body>
</html>