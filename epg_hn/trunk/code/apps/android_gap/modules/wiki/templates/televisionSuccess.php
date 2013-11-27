<h2 class="tit wt"><a href="<?php echo $previous?>" class="plist btnh">返回</a><?php echo $wiki->getTitle()?></h2>
	<!-- 喜欢/不喜欢/看过/加入片单/分享  -->
	<?php include_partial('nav_tool');?>
		<article>
			<section class="movieintro clear">
				<img src="<?php echo thumb_url($wiki->getCover(), 172, 255)?>" alt="<?php echo $wiki->getTitle() ?> 海报"/>
				<ul>
				<?php if($tags = $wiki->getTags()): $i = 0 ?>
		            <li>
		                <span>类型：</span>
		                <?php foreach($tags as $tag) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $tag;?>
		                <?php endforeach;?>
		            </li>					
	            <?php endif; ?>
				<?php if($hosts = $wiki->getHost()): $i = 0 ?>
		            <li>
		                <span>主持人：</span>
		                <?php foreach($hosts as $host) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $host;?>
		                <?php endforeach;?>
		            </li>					
				<?php endif; ?>
				<?php if($guests = $wiki->getGuests()): $i = 0 ?>
		            <li>
		                <span>嘉宾：</span>
		                <?php foreach($guests as $guest) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $guest;?>
		                <?php endforeach;?>
		            </li>
				<?php endif; ?>
				<?php if($wiki->getChannel()): ?>	
		            <li>
		                <span>播出频道：</span>
						<?php echo $wiki->getChannel()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getPlayTime()): ?>	
		            <li>
		                <span>播出时间：</span>
						<?php echo $wiki->getPlayTime()?>
		            </li>					
	            <?php endif; ?>
				<?php if($wiki->getRuntime()): ?>	
		            <li>
		                <span>播出时长：</span>
						<?php echo $wiki->getRuntime()?>
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
				<?php if($wiki->getHtmlCache()): ?>	
		            <li>
		                <span>栏目简介：</span>
			            <?php echo $wiki->getHtmlCache(200, ESC_RAW); ?>... <a href="#detail">(展开)&raquo;</a>					             	            	            	            
		            </li>					
	            <?php endif; ?>	            
				</ul>
			</section>
			
			<section class="play_sty">
				<h2>观看方式</h2>
				<ul class="clear">
				<?php if ($videos = $wiki->getVideos(20110707)) :?>
				<?php $status=false?>
				<?php foreach($videos as $video) :?>
					<?php $tvconfig = $video->getConfig();?>
					<?php if ($video->getReferer() == 'qiyi'):?>
						<li>
						<a href="<?php echo "http://proxy.kkttww.net:8080/urlproxy/qiyi/?redirect=1&tv_id=".$tvconfig['tvId']?>" class="" title="播放<?php echo $video->getRefererZhcn()?>片源" target="_blank"><?php echo $video->getRefererZhcn()?>视频</a>
						</li>
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
			<?php include_partial('program_guide', array('programs' => $related_programs))?>
					
		</article>