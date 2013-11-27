<script type="text/javascript">
function getChannels(sptype){
    $.ajax({
        url:'<?php echo url_for('program/getSpNames')?>',
        type:'post',
        dataType:'json',
        data: { 'type': sptype },
        success:function(channels){
            channels = eval(channels);
            html = '<option value="">请选择</option>';
            for(i=0;i<=channels.length-1;i++){
                if(channels[i].channelcode=='<?php echo $channelCode;?>'){
                    html += "<option value='"+ channels[i].channelcode +"' selected='selected'>"+ channels[i].name +"</option>";
                }else{
                    html += "<option value='"+ channels[i].channelcode +"'>"+ channels[i].name +"</option>";
                }
            }
            $("#code").html(html);
        },
        error: function(){
            alert('error');
        }
    });
}
$(document).ready(function(){
    //先加载cctv频道
    getChannels('<?php echo $type?>');
    //类型改变时加载相应频道
    $("#type").change(function(){
        var typevalue = $(this).attr('value');
        if(typevalue == ''){
            alert("请选择类型");
            return false;
        }
        getChannels(typevalue);
    });
    $("#code").change(function(){
        var channelCode = $(this).attr('value');
        var typevalue = $('#type').attr('value');
        if(channelCode == ''){
            alert("请选择频道");
            return false;
        }
    });
});
</script>

<select name="type" id="type">
    <option value="">请选择</option>
    <?php foreach( $types as $key => $value ): ?>
    <option value="<?php echo $key ?>" <?php echo $key == $type?'selected=selected':''?>><?php echo $value ?></option>
    <?php endforeach; ?>
</select>
频道:
<select name="code" id="code">
</select>