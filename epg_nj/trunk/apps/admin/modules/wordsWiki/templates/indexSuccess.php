<div id="content">
    <div class="content_inner">
    	<header>
    		<h2 class="content">检查所有wiki中的关键词</h2>
    	</header>
        <div style="float:left; width:100%">
          <div class="widget">
            <h3>检查wiki</h3>
    		<div class="widget-body">
    		  <ul class="wiki-meta">
                 <li style="color: #ff0000">提示：wiki共有<?php echo $count?>个，检查时间比较漫长，请耐心等待！直到最后显示"完成"才结束</li>
                 <li><a href="javascript:void(0);" onclick="if(confirm('确定开始检查吗？')){check();return false;}">开始检查</a>    |    <a href="<?php echo url_for('wordsLog/index')?>">停止检查</a> </li>
                 <li>------单击开始检查后，以下为检查结果------</li>
                 <li id="wiki"></li>
              </ul>
    		  <ul id="right">
              </ul>
    		</div>
          </div>
        </div> 
    </div>
</div>
<script type="text/javascript">
var k=0;
var timer;
function check(){
    /*
    for(var i = 0; i < <?php echo $count?>; i=i+50) {
        checkWiki(i);
        setTimeout("",5000);
    }
    */
    timer=setInterval("checkWiki()", 3000);
}

//检查wiki
function checkWiki() {
    if(k < <?php echo $count?>){
     	$.ajax({
            url: '<?php echo url_for('wordsWiki/checkAjax');?>',
            type: 'post',
            dataType: 'text',
            data: { 'i': k },
            success: function(data)
            {
                if(data.length>0){
                    $('#wiki').append(data);
                }
            }
        });
    }else{
        $('#wiki').append("完成");
        clearInterval(timer);
    }
    k=k+50;
}
</script>
