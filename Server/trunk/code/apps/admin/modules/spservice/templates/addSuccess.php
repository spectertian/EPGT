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
				<h2 class="content">添加运营商</h2>
				<nav class="utility">
				  <li class="save"><a href="#" onclick="$('#spForm').submit()">保存</a></li>
				  <li class="back"><a href="<?php echo url_for("spservice/index")?>">返回列表</a></li>
				</nav>
			</header>
			<?php include_partial('global/flashes')?>
            <form method="POST" id="spForm" name="spForm" action="<?php echo url_for("spservice/add");?>">
            <div style="float:left; width:100%;">
              <div class="widget">
                <h3>运营商添加</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
		          <li><span class='span_'>sp_code</span><input style='width:90%;float:right;padding-right:20px' type='text' name='sp_code' value=''></li>
		          <li><span class='span_'>名称</span><input style='width:90%;float:right;padding-right:20px' type='text' name='name' value=''></li>
					    <li><span class='span_'>频率值</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='frequency' value=''></li>
					    <li><span class='span_'>符号率</span><input  style='width:90%;float:right;padding-right:20px'  type='text' name='symbolRate' value=''></li>
					    <li><span class='span_'>调制方式</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='modulation' value=''></li>
					    <li><span class='span_'>原始网络Id</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='onId' value=''></li>
					    <li><span class='span_'>频点Id(tsId)</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='tsId' value=''></li>
					    <li><span class='span_'>serviceId</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='serviceId' value=''></li>
                        <li><span class='span_'>逻辑频道号</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='logicNumber' value=''></li>
					    <li><span class='span_'>视频PID</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='videoPID' value=''></li>
					    <li><span class='span_'>音频PID</span><input  style='width:90%;float:right;padding-right:20px'  type='text' name='audioPID' value=''></li>
					    <li><span class='span_'>PCR PID</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='PCRPID' value=''></li>
					    <li><span class='span_'>免费或干扰</span>
					      <select name='isfree'>
					        <option value='1'<?php if($isFree)echo 'selected'; ?>>免费</option>
					        <option value='0'<?php if(!$isFree)echo 'selected'; ?>>干扰</option>
					      </select>
					    </li>
					    <li><span class='span_'>location</span><input  style='width:90%;float:right;padding-right:20px'   type='text' name='location' value=''></li>
					    <li><span class='span_'>分类tags:</span><input  style='width:90%;float:right;padding-right:20px'   onclick="getTags();" id='tags' type='text' name='tags' value=''></li>
					    <li id='first_type' style='widht:80%;'></li>
					    <li><span class='span_'>频道code</span><input id='mylogo_' style='width:90%;float:right;padding-right:20px'  type='text' name='channel_code' value=''></li>
                        <li><span class='span_'>抓取code</span><input id='mylogoa_' style='width:90%;float:right;padding-right:20px'  type='text' name='channel_codea' value=''></li>
                        <li><span class='span_'>频道ID</span><input id='channelID' style='width:90%;float:right;padding-right:20px'  type='text' name='channel_id' value=''></li>
		          <li style='cursor:pointer;color:orange;background:#ccc'>输入频道code获取台标！</li>
					    <li id='logo_'  style='cursor:pointer;color:orange;background:#ccc'></li>
		        </ul>
		        
<script>
function getTags(){
	var tags = ['cctv','tv','hd','pay','local','other'];
	var html = ''
	for (i=0;i<tags.length;i++){
		  html += '<a onclick="appendvalue(this);" style="padding-left:5px;font-weight:bold;font-size:14px" href="javascript:void(0)">'+tags[i]+'</a>';
	}
	$('#first_type').html(html);
}
/*function getTags(){
	$.ajax({
        url: '<?php echo url_for('spservice/tagsarray')?>',
        type: 'post',
        dataType: 'json',
        success: function(data){
            var index  = 0;
            var html   = '';
            var class_1 = '';
            var class_2 = '';
            var htmlson = '';
            
            $.each(data,function(i,o){
                if(index == 0){
                  class_1 = 'class = "show_"';
                  class_2 = '';
                }else{
                	class_1 = 'class = "black_"';
                	class_2 = 'class = "hide_"';
                }
                html += '<a href="javascript:void(0);" onclick="showson(this);" '+class_1+' value = "'+index+'">'+i+'</a>';
                var temp    = Obj2str(o);
                var temparr = temp.split(',');
                var templen = temparr.length;
                htmlson += '<span id="'+index+'" '+class_2+'>';
                for(a in temparr){
                  var sonname  = temparr[a].replace(/\[/g,'').replace(/\]/g,'').replace(/\"/g,'');
                  if(index==0){style = ' style="font-weight:bold;padding:4px" ';}else{style = ' style="font-weight:bold;padding:4px" ';}
              	  htmlson += '<a '+style+' href="javascript:void(0);" onclick="appendvalue(this);" value="'+index+'">'+sonname+'</a>';
                }
                htmlson += '</span>';
                $('#first_type').html(html);
                $('#hx').removeClass('hide_');
                $('#hx').addClass('hx');
                $('#son_type').html(htmlson);
            	  index++;
            });
        }
    });
}*/

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
				  //alert(tvArr[i]);
				  //alert($(param).text());
				  //alert('chonglai');
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
            </div> </form>
        </div>
      </div>
