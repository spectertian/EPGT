<style>
.hide_{display:none;}
.show_{font-weight:bold;cursor:pointer;color:green;padding:4px}
.hx{border-top:2px solid #000;width:80%;}
.black_{color:#444;}
.span_{width:30px}
.input_{width:90%}
.wiki-meta li{float:none}
#first_type, #notice_error ,#hx ,#son_type{width:80%;margin-left:120px}
</style>
	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">修改运营商</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#spForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("spservice/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
            <form method="POST" id="spForm" name="spForm" action="<?php echo url_for("spservice/edit");?>">
            <div style="float:left; width:100%;">
              <div class="widget">
                <h3>运营商修改</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
				      <li><span class='span_'>&nbsp;</span><input Disabled style='background:#eee;width:50%;float:left;padding-right:20px'  type='text' name='createdAt' value='<?php echo '创建时间:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$createdAt; ?>'></li>
					    <li><span class='span_'>&nbsp;</span><input Disabled style='background:#eee;width:50%;float:left;padding-right:20px'  type='text' name='updatedAt' value='<?php echo '更新时间:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$updatedAt; ?>'></li>
					    <li><span class='span_'>sp_code</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='sp_code' value='<?php echo $sp_code; ?>'></li>
					    <li><span class='span_'>名称</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='name' value='<?php echo $name; ?>'></li>
					    <li><span class='span_'>频率值</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='frequency' value='<?php echo $frequency;  ?>'></li>
					    <li><span class='span_'>符号率</span><input  style='width:90%;float:right;padding-right:20px' type='text' name='symbolRate' value='<?php echo $symbolRate; ?>'></li>
					    <li><span class='span_'>调制方式</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='modulation' value='<?php echo $modulation; ?>'></li>
					    <li><span class='span_'>原始网络Id</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='onId' value='<?php echo $onId; ?>'></li>
					    <li><span class='span_'>频点Id</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='tsId' value='<?php echo $tsId; ?>'></li>
					    <li><span class='span_'>逻辑频道号</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='logicNumber' value='<?php echo $logicNumber; ?>'></li>
					    <li><span class='span_'>视频PID</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='videoPID' value='<?php echo $videoPID; ?>'></li>
					    <li><span class='span_'>音频PID</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='audioPID' value='<?php echo $audioPID; ?>'></li>
					    <li><span class='span_'>PCR PID</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='PCRPID' value='<?php echo $PCRPID; ?>'></li>
					    <li><span class='span_'>免费或干扰</span>
					      <select name='isfree'>
					        <option value='1'<?php if($isFree)echo 'selected'; ?>>免费</option>
					        <option value='0'<?php if(!$isFree)echo 'selected'; ?>>干扰</option>
					      </select>
					    <li><span class='span_'>location</span><input style='width:90%;float:right;padding-right:20px'  type='text' name='location' value='<?php echo $location; ?>'></li>
					    <li><span class='span_'>分类tags</span><input style='width:90%;float:right;padding-right:20px'   onclick="getTags();" id='tags'  type='text' name='tags' value='<?php echo $tags; ?>'></li>
					    <li id='first_type' style='widht:80%;'></li>
					    <li><span class='span_'>频道code</span><input id='mylogo_' style='width:90%;float:right;padding-right:20px'  type='text' name='channel_code' value='<?php echo $channel_code; ?>'></li>
					    <li id='logo_' class='hide_' style='cursor:pointer;color:orange;background:#ccc'></li>
					    <li><input type='hidden' name='id' value="<?php echo $id; ?>"></li>
				  </ul>

<script>
 /*$(function(){
  	var channelCode = $('#mylogo_').val();
  	if(channelCode!=''){
  		$.ajax({
  		  url: '<?php echo url_for('spservice/getChannelLogo') ?>',
  		  type: 'post',
  		  dataType: 'text',
  		  data: {channelCode:channelCode},
  		  success: function(s){
  	  		var data = s.split('*');
  			  $('#logo_').html('<a href="/channel/'+data[1]+'/edit"><img src="'+data[0]+'"></a>');
    			$('#logo_').removeClass('hide_');
  			}
  		});
 })*/
</script>
				  
<script>
function getTags(){
	var tags = ['cctv','tv','hd','pay','local','other'];
	var html = ''
	for (i=0;i<tags.length;i++){
		  html += '<a onclick="appendvalue(this);" style="padding-left:5px;font-weight:bold;font-size:14px" href="javascript:void(0)">'+tags[i]+'</a>';
	}
	$('#first_type').html(html);
}

function appendvalue(param){
	var newTagsValue = '';
	var tagsValue    = $('#tags').val();
	var statu = true;
	//alert(tagsValue.length);
	if(tagsValue.length!=0){
		var suffix = ',';
		var tvArr = tagsValue.split(',');
		var tvLen = tvArr.length;
		if(tvLen>0){
			for(var i = 0;i <= tvLen ; i++){
			  if(tvArr[i] == $(param).text()){
				  statu = false;
				}
			}
		}
	}else{
		suffix = '';
	}
	  
	if(statu){
		newTagsValue = tagsValue + suffix + $(param).text();
		$('#tags').val(newTagsValue);
	}else{
	}
}

function Obj2str(o) {
    if (o == undefined) {
        return "";
    }
    var r = [];
    if (typeof o == "string") return "\"" + o.replace(/([\"\\])/g, "\\$1").replace(/(\n)/g, "\\n").replace(/(\r)/g, "\\r").replace(/(\t)/g, "\\t") + "\"";
    if (typeof o == "object") {
        if (!o.sort) {
            for (var i in o)
                r.push("\"" + i + "\":" + Obj2str(o[i]));
            if (!!document.all && !/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)) {
                r.push("toString:" + o.toString.toString());
            }
            r = "{" + r.join() + "}"
        } else {
            for (var i = 0; i < o.length; i++)
                r.push(Obj2str(o[i]))
            r = "[" + r.join() + "]";
        }
        return r;
    }
    return o.toString().replace(/\"\:/g, '":""');
}

</script>         
				</div>
              </div>
            </div> 
			</form>
        </div>
      </div>
