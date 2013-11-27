<script>
$(document).ready(function(){
    $("#program_channel_id").change(function(){
        var channel_id = $(this).attr('value');
        if(channel_id == 0)
        {
            alert("请选择频道");
            return false;
        }
        $("#channel_id").attr('value',channel_id);
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

    <select name="tv_station_id" id="tv_station_id">
        <option value="0" >请选择</option>
        <?php foreach( $topTvStations as $tvStationId => $tvStationName ): ?>
        <option value="<?php echo $tvStationId ?>" 
            <?php 
                if($tvStationId == $tvStation_id)
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
                        //if( $sf_user->getAttribute('channel_id')!='' ) $channel_id=$sf_user->getAttribute('channel_id');
                        if( $channel['id'] == $channel_id )
                        {
                            echo "selected=selected";
                        }
                ?>
                ><?php echo $channel['name'] ?></option>
        <?php endforeach; ?>
    </select>
</form>
