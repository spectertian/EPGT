<?php if(count($programs)): ?>
<script type="text/javascript">
     $('#tv-listings ul').list({
        direction: 'V',
        viewRows: 9,
        enabledScroll: true,
        scrollIndexs: [0, 8],
        enter: function(event, item){
        	var sid = item.attr('sid');
			var date = item.attr('date');
			var time = item.attr('time');
			if(item.hasClass('on-air')){
				funSetPlay(sid);
			}else if((!item.hasClass('played')) && (!item.hasClass('on-air'))){
				funSetBooking(sid, date, time);
			}
        },
        change: function(event, ui) {
			if($('#tv-listings li:first').hasClass('hover')){
				$('#listup').addClass('display-none');
			}else{
				$('#listup').removeClass('display-none');
			}
			if($('#tv-listings li:last').hasClass('hover')){
				$('#listdown').addClass('display-none');
			}else{
				$('#listdown').removeClass('display-none');
			}
       	},
        menu: function(event, ui){
             $('#channel-slider').data('ui').focus();
        }
      });
     $('#listup').addClass('display-none');
     //跳台
     function funSetPlay(serviceId){
     	var gvarServiceId = new Global('superServiceId');
     	gvarServiceId.value = serviceId ;
     	window.location = 'file://htmldata/mod/fullscreen/fullscreen.htm' ;
     }
     //预约
     function funSetBooking(serviceId,date,time){
     	//date format 2012-12-12
     	//time format 23:55
     	var gvarServiceId = new Global('superServiceId');
     	gvarServiceId.value = serviceId ;
     	var gvarScheduleInfo = new Global('superScheduleInfo');
     	gvarScheduleInfo.value = date + "*" + time ;
     	window.location = 'file://htmldata/mod/schedule/schedule.htm' ;
     }
</script>
<?php else: ?>
<script type="text/javascript">
$('#listup').addClass('display-none');
$('#listdown').addClass('display-none');
</script>
<?php endif; ?>
<ul>
<?php if($programs): ?>
    <?php foreach ($programs as $program): ?>
        <li class="action
        <?php
            if($program->getPlayStatus() == 'playing') {
                echo 'on-air';
            } elseif($program->getPlayStatus() == 'played') {
                echo 'played';
            }
        ?>
        " sid="<?php echo $serviceId;?>" date="<?php echo $date;?>" time="<?php echo $program->getTime();?>">
            <span class="time"><?php echo $program->getTime() ?></span>
            <span class="program-title">
                <?php echo $program->getName(); ?>
                <?php if($program->getPlayStatus() == 'playing') echo '(正在播放)'; ?>
            </span>
            <span class="column"><?php echo $program->getFirstTag() ?></span>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    暂无节目
<?php endif; ?>
</ul>