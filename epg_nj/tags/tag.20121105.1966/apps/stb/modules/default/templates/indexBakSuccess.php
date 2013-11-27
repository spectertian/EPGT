<div id="wapper" class="wapper">
<script type="text/javascript">
var indexHtml = null;
var hotHtml = null;
function initPage() {	
	publicInit();	
    playVideo();
	//goChannelByName('CCTV-1');
	//$("#wapper").style("visibility","hidden");
	//预加载
	setTimeout("getIndexPageContent()",500);
	setTimeout("getHotPageContent('<?php echo $tag?>')",1000);    
}


function getIndexPageContent() {
	$.get("default/live", function(data){
		indexHtml = data;
	});
}
function getHotPageContent(tag) {
	$.get("default/hot?type="+tag, function(data){
		hotHtml = data;
	});
}

function ShowIndexPage() {
	if(indexHtml == null) {
		$.get("default/live", function(data){
			$("#wapper").html(data);
			$("#wapper").style("visibility","visible");
		});
	}else{
		$("#wapper").html(indexHtml);
		$("#wapper").style("visibility","visible");
	}
}

function ShowHotLivePage() {
	if(hotHtml == null) {
		$.get("default/hot", function(data){
			$("#wapper").html(data);
			$("#wapper").style("visibility","visible");
		});
	}else{
		$("#wapper").html(hotHtml);
		$("#wapper").style("visibility","visible");
	}
}
//hosSuccess.php函数
function showPlay(channel,name,start,end,width){
	var play = document.getElementById("tvplay");
	play.getElementsByTagName("i")[0].innerHTML=channel+"："+name;
    play.getElementsByTagName("span")[0].innerText=start;
	play.getElementsByTagName("b")[0].style.width=width+'%';
	play.style.visibility='visible';
}
function hidPlay() {
	document.getElementById("tvplay").style.visibility='hidden';
}
</script>
</div>