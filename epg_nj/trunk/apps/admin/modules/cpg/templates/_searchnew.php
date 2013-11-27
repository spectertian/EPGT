<script>
function ajaxpost(){
	var type = $('#type_').val();
	$.ajax({
    url:'<?php echo url_for('cpg/getSpNames') ?>',
    type:'post',
    dataType:'json',
    data:'type='+type,
    success:function(channels){
        channels = eval(channels);
        html = '<option value="0">请选择</option>';
        for(i=0;i<=channels.length-1;i++)
        {
            var channelcode="<?php echo $channel_code; ?>"
            if(channelcode==channels[i].channelcode){
          	  var selected = 'selected';
            }else{
            	var selected = '';
            }
            html += "<option value='"+ channels[i].channelcode +"' "+selected+">"+ channels[i].name +"</option>";
        }
        $("#spchannel").html(html);
    },
    error: function(){
        alert(ERROR);
    }
});
}
$(function(){
	$("#spchannel").change(function(){
    var channel_code = $(this).attr('value');
    if(channel_code=='null'){
      alert('暂无channel_code,请选择其它频道');
      window.location.reload();
    }else{
      var day = $(".week-action").find("LI > A[class=active]").attr('link');
      var date = eval("("+day+")").date;
      var type = $('#type_').val();
      var GoUrl="<?php echo url_for('cpg/index') ?>?type="+type+"&channel_code="+channel_code+"&date="+date;
      window.location.href = GoUrl;
    }
  });
	$('#type_').change(function(){
		ajaxpost();
	});
	if($('#type_').val()!='1'){
		ajaxpost();
	}else{
		  alert('请选择频道');
		  return false;
	}
})
</script>
<select id='type_' name='type_'>
<option value='cctv' <?php if($type=='cctv') echo 'selected'; ?>>cctv</option>
<option value='tv' <?php if($type=='tv') echo 'selected'; ?>>tv</option>
<option value='hd' <?php if($type=='hd') echo 'selected'; ?>>hd</option>
<option value='pay' <?php if($type=='pay') echo 'selected'; ?>>pay</option>
<option value='local' <?php if($type=='local') echo 'selected'; ?>>local</option>
</select>
<select id='spchannel' name='spchannel'>
</select>