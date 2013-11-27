<script>
function submit(){
   var fileval = $("#value").val();
   var files = fileval.split(",");
   for(var i=0; i<files.length; i++){
     postFile(files[i]);
   }
   return false;
}
function postFile(file){   
   $.ajax({
    url:'<?php echo url_for('tool/postSynFile') ?>',
    type:'get',
    data:'file='+file,
    success:function(data){        
        $("#result").html(data+"<br>"+$("#result").html());
    },
    error: function(){
        alert("ERROR");
    }
  });
}
</script>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content"><?php echo $PageTitle?></h2>
			</header>
			<?php include_partial('global/flashes')?>
            <form method="POST" id="settingForm" name="settingForm">
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3><?php echo $PageTitle?></h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li><label>文件名：</label><textarea name="value" id="value" cols="90" rows="5" style="width: 95%;"><?php echo $value?></textarea></li>
				     <li>说明：多个文件名之间用英文状态下的逗号隔开（例：1355388091829.jpg,1355292709929.jpg）</li>
                     <li><a href="#" onclick="return submit();">保存</a></li>
                     <li id="result"></li>
                  </ul>
				</div>                
              </div>
            </div> 
			</form>
            <div style="width:33%; float:right;">
              <div class="widget">
                <h3>辅助函数</h3>                
              </div>
            </div>   
          </form>
        </div>
      </div>