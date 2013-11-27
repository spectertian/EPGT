<h2 class="tit wt"><a href="<?php echo $previous?>" class="plist btnh">返回</a><?php echo $wiki->getTitle()?></h2>
	<!-- 喜欢/不喜欢/看过/加入片单/分享  -->
	<?php include_partial('nav_tool');?>
		<article>
			<section class="movieintro clear">
				<img src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报"/>
				<ul>
				<?php if($wiki->getReleased()): ?>
		            <li>
		                <span>上映时间：</span>
						<?php echo $wiki->getReleased()?>
		            </li>					
	            <?php endif; ?>	
				<?php if($wiki->getEpisodes()): ?>	
		            <li>
		                <span>集数：</span>
						<?php echo $wiki->getEpisodes()?>
		            </li>					
	            <?php endif; ?>            			
				<?php if($tags = $wiki->getTags()): $i = 0 ?>
		            <li>
		                <span>类型：</span>
		                <?php foreach($tags as $tag) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $tag;?>
		                <?php endforeach;?>
		            </li>					
	            <?php endif; ?>
				<?php if($Directors = $wiki->getDirector()): $i = 0 ?>
		            <li>
		                <span>导演：</span>
		                <?php foreach($Directors as $Director) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Director;?>
		                <?php endforeach;?>
		            </li>					
				<?php endif; ?>
				<?php if($Writers = $wiki->getWriter()): $i = 0 ?>
		            <li>
		                <span>编剧：</span>
		                <?php foreach($Writers as $Writer) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Writer;?>
		                <?php endforeach;?>
		            </li>
				<?php endif; ?>
				<?php if($Stars = $wiki->getStarring()): $i = 0 ?>
		            <li>
		                <span>主演：</span>
		                <?php foreach($Stars as $Star) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
		                <?php endforeach;?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getCountry()): ?>
		            <li>
		                <span>国家：</span>
						<?php echo $wiki->getCountry()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getLanguage()): ?>
		            <li>
		                <span>语言：</span>
						<?php echo $wiki->getLanguage()?>
		            </li>					
	            <?php endif; ?>	
				<?php if($Distributors = $wiki->getDistributor()): $i=0 ?>
		            <li>
		                <span>出品公司：</span>
		                <?php foreach($Distributors as $Distributor) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Distributor;?>
		                <?php endforeach;?>
		            </li>	            
				<?php endif;?>	            
				<?php if($wiki->getProduced()): ?>	
		            <li>
		                <span>制作日期：</span>
						<?php echo $wiki->getProduced()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getHtmlCache()): ?>	
		            <li>
		                <span>剧情简介：</span>
			            <?php echo $wiki->getHtmlCache(200, ESC_RAW); ?>... <a href="#detail">(展开)&raquo;</a>					             	            	            	            
		            </li>					
	            <?php endif; ?>	            
				</ul>
			</section>
			
			<section class="play_sty">
				<h2>观看方式</h2>
				<ul class="clear">
				<?php if ($PlayList = $wiki->getPlayList()) :?>
				<?php $status=false?>
				<?php foreach($PlayList as $playlist) :?>
				<?php $referer = $playlist->getReferer()?>
					<?php if ($referer == 'qiyi'):?>
						<li class="timeline">
						<a href="javascript:void(0)" class="" title="播放<?php echo $playlist->getRefererZhcn()?>片源" target="_blank"><?php echo $playlist->getRefererZhcn()?>视频</a>
						</li>
						<?php $videos = $playlist->getVideos()?>
						<?php foreach($videos as $video) :  ?>
							<?php  $tvconfig = $video->getConfig()?>
							<a href="<?php echo "http://proxy.kkttww.net:8080/urlproxy/qiyi/?redirect=1&tv_id=".$tvconfig['tvId']?>" target="_blank" title="<?php echo $video->getTitle()?>"><?php echo $video->getMark()?></a>
						<?php endforeach;?>
						<?php $status=true?>
					<?php endif;?>
				<?php endforeach;?>
				<?php if($status==false):?>
					<li>很抱歉，该节目还没有奇异片源！</li>
				<?php endif;?>	
				<?php else: ?>
				<li>很抱歉，该节目还没有片源！</li>
				<?php endif;?>
	
				</ul>
			</section>
			<?php if(count($related_programs) > 0): ?>
			<?php use_helper('WeekDays') ?>			
			<section class="tvyg">
				<h2>电视预告</h2>
				<ul class="day clear">
				<?php foreach(weekdays_nav() as $day): ?>
					<li><a href="javascript:void(0)" class="<?php echo ($day['date'] == date('m-d')) ? 'there' : ''?>"><?php echo $day['week_cn'] ?>(<?php echo $day['date'] ?>)</a></li>
				<?php endforeach;?>
				</ul>
				
				<?php $weekday = (0 == date('N')) ? 7 : date('N'); $today = time();?>
				<?php for($d = 1; $d <= 7; $d++) :?>
					<?php $n = $d - $weekday; $date = date('Y-m-d', $today + $n * 86400);?>
					<ul class="day_list" id="date<?php echo $date?>" <?php echo ($date == date('Y-m-d') ? 'style="display:block;"' : 'style="display:none;"')?>>
					<?php if (isset($related_programs[$date])) :?>
	              		<?php foreach($related_programs[$date] as $program): ?>
	              			<?php $play_status = $program->getPlayStatus();?>
	              			<li <?php echo ($play_status == 'playing') ? 'class="playing"' : ''?>>
	              			<?php if($program->getChannelLogo()):?>
								<a href="<?php echo lurl_for("channel/show?id=".$program->getChannel()->getId())?>">
									<img src="<?php echo thumb_url($program->getChannelLogo(), 120, 120) ?>" alt="<?php echo $program->getChannelName() ?>">
								</a>
	              			<?php endif;?>
	              			<?php echo $program->getChannelName() ?>
	              			<?php echo $program->getStartTime()->format('m月d日 ') . $program->getWeekChineseName('星期'); ?>
	              			<?php echo $program->getStartTime()->format('H:i'); ?>
	              			<?php echo $program->getName() ?>
	              			<span class="tx">提醒</span>
	              			</li>
	              		<?php endforeach;?>			
					<?php else :?>
					<li><?php echo $date?> 暂无播出数据...</li>
					<?php endif;?>
					</ul>
				<?php endfor;?>
			</section>
			<?php endif; ?>
					
		</article>
<script type="text/javascript">
$('.day li').click(function(){
	
    $(this).find('a').addClass('there');
    $(this).siblings().find('a').removeClass();
    $(".day_list").eq($('.day li').index(this)).show().siblings('.day_list').hide();
});
</script>