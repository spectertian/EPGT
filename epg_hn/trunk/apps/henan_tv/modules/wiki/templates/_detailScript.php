<script type="text/javascript">
$(document).ready(function() {
    var wiki_id = '';
        var back_target = '';
        $('#dinfo').list({
            focus: function(event){
                $(this).addClass('hover');
            },
            blur: function(event) {
                $(this).removeClass('hover');
            },
            right: function(event) {
                $('#prelist').data('ui').focus();
            },
            up: function(event) {
                $('#prelist').data('ui').focus();
            },
            left: function(event) {
                $('#tab').data('ui').focus();
            },
            down: function(event) {
                $('#tab').data('ui').focus();
            },
            enter: function(event, item) {
                var wiki_id = item.attr('rel');
                var back_target = '#dinfo';
                var wiki_url = '<?php echo url_for('wiki/show?id='); ?>' + wiki_id;
                $("#wiki-info-sizer").load(wiki_url, function() {
                    $("#wiki-sizer").addClass("display-none");
                    $("#wiki-info-sizer").attr('return_target1', back_target);
                    $("#wiki-info-sizer").removeClass("display-none");
                    $('#footer1_back').list('focus');
                });
            }
        });
        $('#tab').list({
            direction: 'H',
            coords: [3 , 1],
            blur: function(event) {
                $(this).removeClass('hover');
            },
            focus: function(event){
                $(this).addClass('hover');
                $(this).addClass('actived');
            },
            over: function(event, pos) {
                if (pos == 'end') {
                    var search_widget = $("#prelist")
                    search_widget.data("ui").focus();
                }
            },
            up: function (event , pos ){
                $('#dinfo').list('focus');
            },
            down: function (event , pos){
                var contentId = $('#tab').find('.actived:first').attr('contentid');
                //showTip(typeof(contentId));
                if(contentId == 'stills'){
                	$('#footer_back').list('focus');
                }else{
                	$('#'+contentId).data("ui").focus();
                }
                
            },
            enter: function(event, item) {
				var cid = item.attr('contentid');
				
                $('#tab').find('.actived').removeClass('actived');
				$('#tab').find('.hover').addClass('actived');
				$('#content').find('ul').addClass("display-none");
				//showTip(typeof(cid));
				$('#'+cid).removeClass("display-none");
                if(cid == 'playlist'){
					$('#dup').addClass("display-none");
					$('#ddown').addClass("display-none");
                }else if(cid == 'nextlist'){
                	$('#dup').addClass("display-none");
					$('#ddown').removeClass("display-none");
                }else if(cid == 'stills'){
					$('#dup').addClass("display-none");
					$('#ddown').addClass("display-none");
                }
            }
        });
       
		$('#nextlist').list({
        	direction: 'V',
        	viewRows: 4,
            enabledScroll: true,
            scrollIndexs: [0, 3],
        	focus: function(event){
                $(this).addClass('hover');
                $(this).addClass('actived');
            },
            right: function(event,ui){
            	$("#prelist").data("ui").focus();
            },
            change: function(event, ui) {     
                var item = ui.to;
                var from = ui.from;
                if($('#nextlist li:first').hasClass('hover')){
    				$('#dup').addClass('display-none');
    			}else{
    				$('#dup').removeClass('display-none');
    			}
    			if($('#nextlist li:last').hasClass('hover')){
    				$('#ddown').addClass('display-none');
    			}else{
    				$('#ddown').removeClass('display-none');
    			}
             },
            enter: function(event, item) {
				var sid = item.attr('sid');
				var date = item.attr('date');
				var time = item.attr('time');
				if(date&&time){
					funSetBooking(sid, date, time);
				}else{
					funSetPlay(sid);
				}
            }
            
        });
        $('#playlist').list({
        	direction: 'V',
        	coords: [1 , 4],
        	focus: function(event){
                $(this).addClass('hover');
                $(this).addClass('actived');
            },
            change: function(event, ui) {     
               var item = ui.to;
               
            },
            
            right: function(event,ui){
            	$("#prelist").data("ui").focus();
            },
            enter: function(event, item) {
				var sid = item.attr('sid');
				var date = item.attr('date');
				var time = item.attr('time');
				if(date&&time){
					funSetBooking(sid, date, time);
				}else{
					funSetPlay(sid);
				}
            },
            over: function(event, pos) {
                if (pos == 'end') {
                    $('#footer_back').list('focus');
                }else{
                    $('#tab').data('ui').focus();
                }
            }
        });   
        
        $('#prelist').list({
            direction: 'V',
            coords: [1 , 13],
            blur: function(event) {
                $(this).removeClass('hover');
            },
            focus: function(event){
                $(this).addClass('hover');
                $(this).addClass('actived');
            },
            left: function(event, ui) {
                $('#tab').data('ui').focus();
            },
            change: function(event, ui) {
                //var title = ui.to.find('div.title').text();
                //$('#footer_info_title').text(title);
                //$('.info').show();
            },
            enter: function(event, item) {
                var wiki_id = item.attr('rel');
                if(wiki_id){
                var wiki_url = '<?php echo url_for('wiki/detail?id='); ?>' + wiki_id;
                $("#wiki-sizer").load(wiki_url, function() {
                    $('#tab').data('ui').focus();
                });
                }
            }
        });
        $('#footer_back').list({
            focus: function(event){
                $(this).addClass('hover');
            },
            blur: function(event) {
                $(this).removeClass('hover');
            },
            right: function(event) {
                $('#prelist').data('ui').focus();
            },
            up: function(event) {
            	var contentId = $('#tab').find('.actived:first').attr('contentid');
                if(contentId == 'stills'){
                	$('#tab').list('focus');
                }else{
                	$('#'+contentId).data("ui").focus();
                }
            },
            enter: function(event, item) {
                $("#main-nav-sizer").removeClass("display-none");
                var return_target = $('#wiki-sizer').attr('return_target');
                $(return_target).data("ui").focus();
                $("#wiki-sizer").html("").addClass("display-none");
                
            }
        });
});

var con = $('#tab li:first').attr('contentid');
if(con == 'playlist'){
	$('#dup').addClass("display-none");
	$('#ddown').addClass("display-none");
}else if(con == 'nextlist'){
	$('#dup').addClass("display-none");
	$('#ddown').removeClass("display-none");
}else if(con == 'stills'){
	$('#dup').addClass("display-none");
	$('#ddown').addClass("display-none");
}

//跳台
function funSetPlay(serviceId){
	var gvarServiceId = new Global('superServiceId');
	gvarServiceId.value = parseInt(serviceId,10);
	window.location = 'file://htmldata/mod/fullscreen/fullscreen.htm' ;
}
//预约
function funSetBooking(serviceId,date,time){
	//date format 2012-12-12
	//time format 23:55
	var gvarServiceId = new Global('superServiceId');
	gvarServiceId.value = parseInt(serviceId,10) ;
	var gvarScheduleInfo = new Global('superScheduleInfo');
	gvarScheduleInfo.value = date + "*" + time ;
	window.location = 'file://htmldata/mod/schedule/schedule.htm' ;
}

var DialogFav ;            

function showTip(content){
DialogFav = new Dialog();
DialogFav.title = "温馨提示";
DialogFav.content =content;
DialogFav.okcancel = false;
DialogFav.left = 174;
DialogFav.top = 195;
DialogFav.width = 372;
DialogFav.height = 186;
DialogFav.timeout = 60;
DialogFav.buttonnum = 1;
DialogFav.open();
}
</script>