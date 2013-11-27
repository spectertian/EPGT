<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<h2 class="tit"><a href="/dtv/index" class="plist zb">分类</a>频道列表</h2>
		
		<div class="clear jmlb">
			<aside class="channellist">
				<ul>
				<?php foreach($channels as $channel):?>
					<li><a  onclick="changeChannel(this)" href="javascript:void(0)" id="<?php echo $channel->getCode()?>"><img src="<?php echo thumb_url($channel->getLogo(),90,64)?>" alt=""/><?php echo $channel->getName()?></a></li>
				<?php endforeach;?>
				</ul>
			</aside>
			<article class="daylist">
				<h2><a href="#" id="">电视播放</a><img src="/img/7.jpg" alt=""/><span>北京卫视</span></h2>
			<?php use_helper('WeekDays') ?>			
			<?php foreach(weekdays_nav() as $day): ?>
				<h3 style="<?php echo ($day['date'] == date('m-d')) ? 'display:' : 'display:none;'?>"><a href="#" class="dayback" ></a><span><?php echo $day['week'] ?>（<?php echo $day['week_cn'] ?>）</span><a href="#" class="daynext"></a></h3>
			<?php endforeach;?>
				<ul>

				</ul>
			</article>
			
			<aside class="relevant_movie">
				<section id="wiki_info">
					<ul class="movie_intro">
						<li><h3><a href="#" class="pic"><img src="/img/8.jpg" alt=""/>哈利波特与死亡圣器</a></h3></li>
						<li>导演：大卫耶茨</li>
						<li>主演：丹尼尔雷德克里夫 / 艾玛沃森 / 鲁伯特格林</li>
						<li>简介：哈利进入与伏地魔意识连通的环境，找到了一些魂器，但也让伏地魔发觉了他们的行动</li>
						<li style="display:;"><a href="#" class="btnd">收藏</a><a href="#" class="btnd">分享</a><a href="#" class="btnd">详情</a></li>
					</ul>
				</section>
				
				<section id='related_movie'>
					<h2 class="aboutmoviet">相关影片</h2>
					<ul class="aboutmovies">
						<li><a href="#"><img src="/img/8.jpg" alt=""/>源代码<span>(2011)</span></a></li>
						<li><a href="#"><img src="/img/8.jpg" alt=""/>源代码<span>(2011)</span></a></li>
						<li><a href="#"><img src="/img/8.jpg" alt=""/><span>源代码</span><span>(2011)</span></a></li>
					</ul>
				</section>
			</aside>
		</div>
<script type="text/javascript">
function getWiki(object){
	var slug = object.id;
	if(slug!='null')
	{
    	$('#wiki_info').css("display","");
    	$('#related_movie').css("display","");
		$.ajax({
		    url: '/wiki/show',
		    type: 'post',
		    dataType: 'json',
		    data: { 'slug': slug },
		    success: function(data)
		    {
	        	$('.movie_intro li:eq(0) h3 a').html('<img src="" alt=""/>'+data.title);
	        	$('.movie_intro li:eq(0) h3 a').attr('href','/wiki/show?slug='+data.slug);
	        	$('.movie_intro li:eq(0) h3 a img').attr('src',data.cover);
	        	$('.movie_intro li:eq(1)').text("导演："+data.directors);
	        	$('.movie_intro li:eq(2)').text("主演："+data.stars);
	        	$('.movie_intro li:eq(3)').text("简介："+data.htmlcache);
	        	$('.movie_intro li:eq(4)').css("display",'');
		    }
		});	
    	$.ajax({
    	    url: '/wiki/related',
    	    type: 'post',
    	    dataType: 'json',
    	    data: { 'slug': slug },
    	    success: function(res)
    	    {
    		    $('.aboutmoviet').text(res[0].modeltext);
    	    	$('.aboutmovies li:eq(0)').html("<a href='#'><img src='' alt=''/>"+res[1].title+"<span>("+res[1].released+")</span></a>");
            	$('.aboutmovies li:eq(0) a').attr('href','/wiki/show?slug='+res[1].slug).find('img').attr('src',res[1].cover);
            	
    	    	$('.aboutmovies li:eq(1)').html("<a href='#'><img src='' alt=''/>"+res[2].title+"<span>("+res[2].released+")</span></a>");
            	$('.aboutmovies li:eq(1) a').attr('href','/wiki/show?slug='+res[2].slug).find('img').attr('src',res[2].cover);
            	
    	    	$('.aboutmovies li:eq(2)').html("<a href='#'><img src='' alt=''/>"+res[3].title+"<span>("+res[3].released+")</span></a>");
            	$('.aboutmovies li:eq(2) a').attr('href','/wiki/show?slug='+res[3].slug).find('img').attr('src',res[3].cover);
            	
            	
    	    }
    	});		    		   	        	
	}
	else{
    /*	$('.movie_intro li:eq(0) h3 a').html('<img src="" alt=""/>');
    	$('.movie_intro li:eq(0) h3 a').attr('href','#');
    	$('.movie_intro li:eq(0) h3 a img').attr('src','');
    	$('.movie_intro li:eq(1)').text("");
    	$('.movie_intro li:eq(2)').text("");
    	$('.movie_intro li:eq(3)').text("");
    	$('.movie_intro li:eq(4)').css("display",'none');*/
    	$('#wiki_info').css("display","none");
    	$('#related_movie').css("display","none");
	}
}
$(document).ready(function(){
	/*初始化开始*/
	var doc = $('.channellist ul li:eq(0) a');
	var code = doc.attr('id');
	$('.daylist h2 a').attr('id',code);
	$('.daylist h2 span').text(doc.text());
	$('.daylist h2 img').attr('src',doc.find('img').attr('src'));
	$.ajax({
	    url: '/dtv/program',
	    type: 'get',
	    dataType: 'json',
	    data: { 'code': code },
	    success: function(data)
	    {
        	$('.daylist ul').html('');
        	if(data==null)
        		$('.daylist ul').append("<li>该频道暂无播放信息</li>");
        	else
        	{
	        	$(data).each(function(i){
		        	$('.daylist ul').append("<li><a name='program' href='javascript:void(0)' onClick='getWiki(this)' id='"+this.wiki_slug+"'>"+this.time+" "+this.name+"</a></li>");
	        	});
	        	getWiki(document.getElementsByName("program")[0]);
        	}
        	
	    }
	});
	
	/*初始化结束*/

	//点击频道台标变换节目列表
	$('.channellist li a').click(function(){
		var code = $(this).attr('id');
		$('.daylist h2 a').attr('id',code);
		$('.daylist h2 span').text($(this).text());
		$('.daylist h2 img').attr('src',$(this).find('img').attr('src'));
		$.ajax({
		    url: '/dtv/program',
		    type: 'get',
		    dataType: 'json',
		    data: { 'code': code },
		    success: function(data)
		    {
	        	$('.daylist ul').html('');
	        	if(data==null)
	        		$('.daylist ul').append("<li>该频道暂无播放信息</li>");
	        	else
	        	{
		        	$(data).each(function(i){
		        		$('.daylist ul').append("<li><a name='program' href='javascript:void(0)' onClick='getWiki(this)' id='"+this.wiki_slug+"'>"+this.time+" "+this.name+"</a></li>");
		        	});
		        	getWiki(document.getElementsByName("program")[0]);
	        	}
		    }
		});
	});

	//为android
	$('.daylist h2 a').click(function(){
		var code=$('.daylist h2 a').attr('id');
		var jsondata={'channelcode':code};
		changeChannelToTV(jsondata);

	});
});

</script>
