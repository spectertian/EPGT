<script>
$(document).ready(function(){
    $("#program_channel_id").change(function(){
        var channel_id = $(this).attr('value');
        var day = $(".week-action").find("LI > A[class=active]").attr('link');
        var date = eval("("+day+")").date;
        var GoUrl="<?php echo url_for('program/index') ?>?channel_id="+channel_id+"&date="+date;
        window.location.href = GoUrl;
    });
    
    $("#tv_station_id").change(function(){
        var tvStation_id = $(this).attr('value');
        if(tvStation_id == 0)
        {
            alert("请选择电视台");
            return false;
        }
        $.ajax({
            url:'<?php echo url_for('program/TvStationChannel') ?>',
            type:'post',
            dataType:'json',
            data:'id='+tvStation_id,
            success:function(channels){
                channels = eval(channels);
                html = '<option value="0">请选择</option>';
                for(i=0;i<=channels.length-1;i++)
                {
                	var reg=/.*（高清）.*/;
                	var reg2 = /.*高清/;
                	var reg3 = /深圳卫视/;
                	var reg4 = /兵团卫视/;
                    if(channels[i].type=='tv'&& !reg.test(channels[i].name) && !reg2.test(channels[i].name)&& !reg3.test(channels[i].name) && !reg4.test(channels[i].name))
                    {
                        //alert(<?php echo $sf_user->getAttribute('channel_id'); ?>);
                    	html += "<option value='"+ channels[i].id +"' selected='selected'>"+ channels[i].name +"</option>";
	                    var day = $(".week-action").find("LI > A[class=active]").attr('link');
	                    var date = eval("("+day+")").date;
	                    var GoUrl="<?php echo url_for('program/index') ?>?channel_id="+channels[i].id+"&date="+date;
	                    window.location.href = GoUrl;
                    }
                    else
                    	html += "<option value='"+ channels[i].id +"'>"+ channels[i].name +"</option>";
                }
                $("#program_channel_id").html(html);
            },
            error: function(){
                alert(ERROR);
            }
        });
    });
});
</script>
<form action="#" method="post">
    <input name="current_time" value="<?php $sf_request->getParameter('date',date("Y-m-d",time())) ?>" id="program_filters_date_from" type="hidden">
过滤:电视台:
    <select name="tv_station_id" id="tv_station_id">
        <option value="0" >请选择</option>
        <?php foreach( $topTvStations as $tvStationId => $tvStationName ): ?>
        <option value="<?php echo $tvStationId ?>" 
            <?php 
                if($tvStationId == $sf_user->getAttribute('tv_station_id'))
                {
                    echo "selected=selected";
                 }
            ?> >
        <?php echo $tvStationName ?>
        </option>
        <?php endforeach; ?>
    </select>
    频道:
    <select name="program_search[channel_id]" id="program_channel_id">
        <option value="0">请选择</option>
        <?php foreach( $channels as $channel ): ?>
        <option value="<?php echo $channel['id'] ?>"
                <?php
                        if( $channel['id'] == $sf_user->getAttribute('channel_id') )
                        {
                            echo "selected=selected";
                        }
                ?>
                ><?php echo $channel['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <a href="<?php echo url_for('program/tyvs?channel_code='.$channel_code) ?>" target="_blank">昨日今日数据对比</a>
    <a href="<?php echo url_for('program/tvsou?channel_code='.$channel_code) ?>&date=<?php echo ($sf_request->getParameter('date')) ? $sf_request->getParameter('date') : date("Y-m-d");?>" target="_blank">tvsou数据对比</a>
    <?php if($update):?>
    [tvsou有更新[<?php echo $updatetime?>] | <a href="<?php echo url_for('program/tvsouOk?channel_code='.$channel_code) ?>">确认完毕</a>]
    <?php endif?>
     <a href="<?php echo url_for('program/tvsouGet?channel_code='.$channel_code) ?>">重新抓取</a> 
</form>
