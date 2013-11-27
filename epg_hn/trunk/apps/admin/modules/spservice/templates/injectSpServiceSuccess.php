<div>
	<div id="show"></div>
	
	<script type="text/javascript">
	if(nodeInfo){
		for(var i in nodeInfo)
		{
			if(nodeInfo[i].nodeUserLevel == 0 && nodeInfo[i].nodeId < 7){
				var channels = eval(nodeInfo[i].nodeChildVar);
				if(channels.length > 0){
					for(var a in channels){
						$.ajax({
					        url: '<?php echo url_for('spservice/SaveInject')?>',
					        type: 'post',
					        dataType: 'json',
					        data: {
								'channelType' : channels[a].nodeChannelType,
								'name' : channels[a].nodeChannelName,
								'serviceId' : channels[a].nodeChannelServiceId,
								'channelNetworkId' : channels[a].nodeChannelNetworkId,
								'logicNumber' : channels[a].nodeChannelNum,
								'tags' : nodeInfo[i].nodeChannelName
						    },
					        success: function(data){
					            $('#show').append(data.name+'---以导入<br>');
					        }
					    });
					}
				}
			}else{
				continue;
			}
		}
	}
	</script>
</div>