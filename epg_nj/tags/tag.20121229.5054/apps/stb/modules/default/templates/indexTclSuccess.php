<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<div class="wapper" id="wapper">
	<div class="bg">
		<div class="nav">
			<h1>江苏有线云媒体电视</h1>
            <div class="navh">
                <ul id="navul">
                    <li><a href="/default/index" class="there">智能门户</a></li>
                    <li><a href="/vod/index">影片库</a></li>
                    <li><a href="/channel/index">一周节目</a></li>
                    <li><a href="/user/cliplist">我的片单</a></li>
                    <li><a href="/search">搜索</a></li>
                    <!--<li><a href="http://hditv.jsamtv.com/epg/show.do?app=vpg&hd=y&content=forsearch&movieassetid=10813087&inquiry=y&clientid=">搜索</a></li>-->
                </ul>
            </div>
		</div>		
		<div class="liststy playnow">
			<div class="hlist">
				<ul class="clr" id="livelist">
                    <?php include_component('default','liveList');?>
				</ul>
			</div>
		</div>		
		<div class="liststy youlike">
			<div class="hlist">
				<ul class="clr" id="recommend">
                    <?php foreach($program_list as $program):?>  
                    <?php if($program->getWikiCover()=='1353052878834.jpg') continue;?>           
					<li>
						<a href="#" onclick="return goChannelByName('<?php echo $program->getSpName();?>');">
							<img src="<?php echo thumb_url($program->getWikiCover(),114,152);?>" />
							<span><b><?php echo $program->getWikiTitle();?></b></span>
						</a>
					</li>
            		<?php endforeach;?>
                    <?php foreach($wikis as $wiki):?>     
                    <?php if($wiki->getCover()=='1313030694207.png') continue;?>             
					<li>
						<a href="<?php echo url_for('wiki/show?slug='.$wiki->getSlug()) ?>">
							<img src="<?php echo  thumb_url($wiki->getCover(), 114, 152);?>" />
							<span><b><?php echo $wiki->getTitle();?></b></span>
						</a>
					</li>
            		<?php endforeach;?>
				</ul>
			</div>
		</div>		
		<div class="liststy playnow commant">
			<div class="hlist">
				<ul id="theme">
                    <?php 
                          foreach($themes as $theme):
                    ?>                  
					<li>
						<a href="<?php echo url_for('theme/show?tid='.$theme->getId()) ?>">
							<img src="<?php echo  thumb_url($theme->getImg(), 220, 125);?>" alt="" />
							<span><b><?php echo $theme->getTitle();?></b></span>
						</a>
					</li>
            		<?php
                          endforeach;
                    ?>
				</ul>
			</div>
		</div>		
		<div class="help">
			<ul>
				<li><img src="/img/fx.jpg" alt="选择"/>选择</li>
				<li><img src="/img/ok.jpg" alt="选择"/>进入</li>
				<li><img src="/img/cd.jpg" alt="选择"/>云媒体首页</li>
				<li><img src="/img/pd.jpg" alt="选择"/>帮助</li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
    function showIndexPage() {
        showPlayPage(); 
    }  
    function goChannel(name) {
        showTip('正在跳转到该频道');
        setTimeout(function(){goChannelByName(name);},2000); //隔2秒再跳转
        return true;
    }
    function initPage() {	
    	publicInit();	
        //playVideo();   //用这种方法从其他应用返回来不播放  
        //------播放当前视频
        var curSer=getCurrentService();
        //playVideoByLocation(curSer.getLocation());  //用这种方法从其他应用返回来不播放 
        var modulation = 16 * Math.pow(2, curSer.modulation - 1);
		deliver = "deliver://"+curSer.frequency+"000."+curSer.symbolRate+"."+modulation+"."+curSer.serviceId;
        playVideoByLocation(deliver);
        //------播放当前视频  
        //导航滚动    
        $("#navul").animateNav({speed: 10, step: 20, width: 150});
        $('#navul').find('a')[0].focus();
        //图层滚动
        $("#livelist").animateNav({speed:10, step:50, width:244});
        $("#recommend").animateNav({speed:10, step:40, width:165}); 
        $("#theme").animateNav({speed:10, step:50, width:244}); 
        //文字滚动 
        $("#livelist").scroll("big",8); 
        $("#recommend").scroll("b",4);
        $("#theme").scroll("b",8); 
        
        Utility.ioctlWrite("JsAddKeyState","Y");  //重新定义默认键值
    }
</script>